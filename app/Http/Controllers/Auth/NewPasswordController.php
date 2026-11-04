<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * GET /reset-password/{token}
     * Affiche le formulaire de nouveau mot de passe.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * POST /reset-password
     * Valide et enregistre le nouveau mot de passe.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token'                 => ['required', 'string'],
            'email'                 => ['required', 'email'],
            'password'              => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'token.required'                => 'Le token de réinitialisation est manquant.',
            'email.required'               => "L'adresse email est obligatoire.",
            'email.email'                  => "L'adresse email n'est pas valide.",
            'password.required'            => 'Le mot de passe est obligatoire.',
            'password.confirmed'           => 'Les mots de passe ne correspondent pas.',
            // Règles de complexité (Password::defaults(), voir AppServiceProvider)
            'password.min'          => 'Le mot de passe doit contenir au moins :min caractères.',
            'password.password.mixed'      => 'Le mot de passe doit contenir au moins une majuscule et une minuscule.',
            'password.password.letters'    => 'Le mot de passe doit contenir au moins une lettre.',
            'password.password.numbers'    => 'Le mot de passe doit contenir au moins un chiffre.',
        ]);

        // Laravel Password Broker : vérifie l'existence du jeton, son
        // expiration (config/auth.php) et qu'il correspond bien à l'email.
        // Après un succès, le jeton est supprimé : il est à usage unique.
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password'       => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            // Succès → redirige vers login avec message
            return redirect()
                ->route('login')
                ->with('status', 'Votre mot de passe a été réinitialisé. Vous pouvez maintenant vous connecter.');
        }

        /*
         * SÉCURITÉ — anti-énumération de comptes :
         * jeton invalide, jeton expiré, jeton déjà utilisé et adresse
         * inconnue donnent exactement le même message.
         */
        throw ValidationException::withMessages([
            'email' => ['Ce lien de réinitialisation est invalide ou a expiré. Veuillez effectuer une nouvelle demande.'],
        ]);
    }
}
