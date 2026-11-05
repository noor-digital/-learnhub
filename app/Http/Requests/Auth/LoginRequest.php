<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Échecs de connexion autorisés pour un même couple e-mail + adresse IP
     * avant blocage temporaire.
     */
    public const MAX_ATTEMPTS = 5;

    /**
     * Échecs de connexion autorisés pour une même adresse IP, toutes adresses
     * e-mail confondues (protège contre un essai d'une liste d'e-mails).
     */
    public const MAX_ATTEMPTS_PER_IP = 20;

    /**
     * Durée, en secondes, de la fenêtre de comptage et du blocage (15 min).
     */
    public const DECAY_SECONDS = 900;

    /**
     * Message unique en cas d'échec : identique que l'adresse e-mail existe
     * ou non, pour ne pas permettre de deviner les comptes existants.
     */
    public const FAILED_MESSAGE = 'Adresse e-mail ou mot de passe incorrect.';

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * Seuls les échecs sont comptés. Le compteur du couple e-mail + IP est
     * remis à zéro après une connexion réussie ; celui de l'IP ne l'est pas,
     * afin qu'un attaquant ne puisse pas le réinitialiser en se connectant
     * à son propre compte entre deux essais.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey(), self::DECAY_SECONDS);
            RateLimiter::hit($this->ipThrottleKey(), self::DECAY_SECONDS);

            throw ValidationException::withMessages([
                'email' => self::FAILED_MESSAGE,
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * Le blocage est vérifié avant toute tentative d'authentification : une
     * fois la limite atteinte, même le bon mot de passe est refusé, avec le
     * même message quelle que soit l'existence du compte.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        $blockedByAccount = RateLimiter::tooManyAttempts($this->throttleKey(), self::MAX_ATTEMPTS);
        $blockedByIp = RateLimiter::tooManyAttempts($this->ipThrottleKey(), self::MAX_ATTEMPTS_PER_IP);

        if (! $blockedByAccount && ! $blockedByIp) {
            return;
        }

        event(new Lockout($this));

        $seconds = max(
            $blockedByAccount ? RateLimiter::availableIn($this->throttleKey()) : 0,
            $blockedByIp ? RateLimiter::availableIn($this->ipThrottleKey()) : 0,
        );

        throw ValidationException::withMessages([
            'email' => $this->throttleMessage($seconds),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request (e-mail + IP).
     *
     * L'e-mail est normalisé (minuscules) puis haché : la clé a une longueur
     * fixe (compatible avec tous les stores de cache) et ne contient pas
     * l'adresse e-mail en clair.
     */
    public function throttleKey(): string
    {
        $email = Str::lower(trim((string) $this->input('email')));

        return 'login:'.hash('sha256', $email.'|'.$this->ip());
    }

    /**
     * Rate limiting key shared by every attempt coming from one IP address.
     */
    public function ipThrottleKey(): string
    {
        return 'login-ip:'.hash('sha256', (string) $this->ip());
    }

    /**
     * Message clair indiquant au bout de combien de temps réessayer.
     */
    private function throttleMessage(int $seconds): string
    {
        $seconds = max(1, $seconds);

        $delay = $seconds < 60
            ? $seconds.' seconde'.($seconds > 1 ? 's' : '')
            : ($minutes = (int) ceil($seconds / 60)).' minute'.($minutes > 1 ? 's' : '');

        return 'Trop de tentatives de connexion. Veuillez réessayer dans '.$delay.'.';
    }
}
