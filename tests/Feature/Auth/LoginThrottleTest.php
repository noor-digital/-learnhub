<?php

namespace Tests\Feature\Auth;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * Limitation des tentatives de connexion.
 *
 * Règles testées :
 *  - 5 échecs par couple e-mail + IP, puis blocage de 15 minutes ;
 *  - 20 échecs par IP (toutes adresses confondues), puis blocage ;
 *  - le compteur e-mail + IP est remis à zéro après une connexion réussie ;
 *  - le message est le même que l'e-mail existe ou non.
 */
class LoginThrottleTest extends TestCase
{
    use RefreshDatabase;

    private const IP_A = '10.10.0.1';

    private const IP_B = '10.10.0.2';

    // ── Outils ──────────────────────────────────────────────────────────

    private function login(string $email, string $password = 'mauvais-mot-de-passe', string $ip = self::IP_A): TestResponse
    {
        return $this->withServerVariables(['REMOTE_ADDR' => $ip])
            ->post('/login', ['email' => $email, 'password' => $password]);
    }

    private function emailError(TestResponse $response): ?string
    {
        return $response->baseResponse->getSession()?->get('errors')?->first('email');
    }

    /** Provoque $count échecs et vérifie que chacun reçoit le message d'échec normal. */
    private function failTimes(int $count, string $email, string $ip = self::IP_A): void
    {
        for ($i = 1; $i <= $count; $i++) {
            $response = $this->login($email, ip: $ip);
            $this->assertSame(
                LoginRequest::FAILED_MESSAGE,
                $this->emailError($response),
                "L'échec n°{$i} aurait dû recevoir le message d'échec normal, pas un blocage."
            );
        }
        $this->assertGuest();
    }

    private function assertThrottled(TestResponse $response): void
    {
        $response->assertSessionHasErrors('email');
        $this->assertMatchesRegularExpression(
            '/^Trop de tentatives de connexion\. Veuillez réessayer dans \d+ (minutes?|secondes?)\.$/u',
            (string) $this->emailError($response)
        );
        $this->assertGuest();
    }

    // ── Politique ───────────────────────────────────────────────────────

    public function test_the_documented_policy_values_are_the_ones_in_force(): void
    {
        // Figer la politique : toute modification doit être volontaire et mise à jour ici.
        $this->assertSame(5, LoginRequest::MAX_ATTEMPTS);
        $this->assertSame(20, LoginRequest::MAX_ATTEMPTS_PER_IP);
        $this->assertSame(900, LoginRequest::DECAY_SECONDS);
    }

    // ── Blocage par e-mail + IP ─────────────────────────────────────────

    public function test_login_is_blocked_after_five_failed_attempts(): void
    {
        $user = User::factory()->create();

        $this->failTimes(LoginRequest::MAX_ATTEMPTS, $user->email);

        $this->assertThrottled($this->login($user->email));
    }

    public function test_the_lockout_message_tells_how_long_to_wait(): void
    {
        $user = User::factory()->create();
        $this->failTimes(LoginRequest::MAX_ATTEMPTS, $user->email);

        $message = $this->emailError($this->login($user->email));

        $this->assertSame('Trop de tentatives de connexion. Veuillez réessayer dans 15 minutes.', $message);
    }

    public function test_the_correct_password_is_refused_while_locked_out(): void
    {
        $user = User::factory()->create();
        $this->failTimes(LoginRequest::MAX_ATTEMPTS, $user->email);

        $this->assertThrottled($this->login($user->email, 'password'));
    }

    public function test_a_lockout_event_is_dispatched_when_the_limit_is_hit(): void
    {
        Event::fake([Lockout::class]);
        $user = User::factory()->create();
        $this->failTimes(LoginRequest::MAX_ATTEMPTS, $user->email);

        Event::assertNotDispatched(Lockout::class);

        $this->login($user->email);

        Event::assertDispatched(Lockout::class);
    }

    public function test_the_limit_is_not_reached_before_the_fifth_failure(): void
    {
        $user = User::factory()->create();

        $this->failTimes(LoginRequest::MAX_ATTEMPTS - 1, $user->email);

        $this->login($user->email, 'password')->assertRedirect(route('dashboard', absolute: false));
        $this->assertAuthenticatedAs($user);
    }

    public function test_the_lockout_expires_after_fifteen_minutes(): void
    {
        $user = User::factory()->create();
        $this->failTimes(LoginRequest::MAX_ATTEMPTS, $user->email);
        $this->assertThrottled($this->login($user->email, 'password'));

        $this->travel(LoginRequest::DECAY_SECONDS + 1)->seconds();

        $this->login($user->email, 'password')->assertRedirect(route('dashboard', absolute: false));
        $this->assertAuthenticatedAs($user);
    }

    public function test_the_remaining_delay_is_shown_in_seconds_when_under_a_minute(): void
    {
        $user = User::factory()->create();
        $this->failTimes(LoginRequest::MAX_ATTEMPTS, $user->email);

        $this->travel(LoginRequest::DECAY_SECONDS - 30)->seconds();

        $message = $this->emailError($this->login($user->email, 'password'));
        $this->assertMatchesRegularExpression('/dans \d+ secondes?\.$/', (string) $message);
    }

    // ── Compteur réinitialisé après succès ──────────────────────────────

    public function test_the_counter_is_reset_after_a_successful_login(): void
    {
        $user = User::factory()->create();

        $this->failTimes(LoginRequest::MAX_ATTEMPTS - 1, $user->email);

        $this->login($user->email, 'password');
        $this->assertAuthenticatedAs($user);
        $this->post('/logout');
        $this->assertGuest();

        // Sans réinitialisation, le premier échec ci-dessous déclencherait déjà le blocage.
        $this->failTimes(LoginRequest::MAX_ATTEMPTS, $user->email);
        $this->assertThrottled($this->login($user->email));
    }

    public function test_failures_before_a_success_do_not_accumulate_across_sessions(): void
    {
        $user = User::factory()->create();

        foreach (range(1, 3) as $round) {
            $this->failTimes(LoginRequest::MAX_ATTEMPTS - 1, $user->email);
            $this->login($user->email, 'password');
            $this->assertAuthenticatedAs($user);
            $this->post('/logout');
        }

        $this->assertGuest();
    }

    // ── Pas de fuite sur l'existence des comptes ────────────────────────

    public function test_unknown_and_existing_emails_get_the_same_failure_message(): void
    {
        $user = User::factory()->create();

        $existing = $this->emailError($this->login($user->email));
        $unknown = $this->emailError($this->login('inconnu@example.com'));

        $this->assertSame(LoginRequest::FAILED_MESSAGE, $existing);
        $this->assertSame($existing, $unknown);
    }

    public function test_unknown_and_existing_emails_get_the_same_lockout_behaviour(): void
    {
        $user = User::factory()->create();

        $this->failTimes(LoginRequest::MAX_ATTEMPTS, $user->email);
        $this->failTimes(LoginRequest::MAX_ATTEMPTS, 'inconnu@example.com');

        $existing = $this->emailError($this->login($user->email));
        $unknown = $this->emailError($this->login('inconnu@example.com'));

        $this->assertThrottled($this->login($user->email));
        $this->assertSame($existing, $unknown);
    }

    // ── Portée du blocage ───────────────────────────────────────────────

    public function test_email_case_and_padding_do_not_bypass_the_limit(): void
    {
        $user = User::factory()->create(['email' => 'etudiante@example.com']);

        $this->failTimes(2, 'etudiante@example.com');
        $this->failTimes(2, 'ETUDIANTE@Example.com');
        $this->failTimes(1, '  etudiante@example.com  ');

        $this->assertThrottled($this->login('Etudiante@EXAMPLE.com', 'password'));
    }

    public function test_a_lockout_on_one_ip_does_not_lock_the_account_out_from_another_ip(): void
    {
        $user = User::factory()->create();

        $this->failTimes(LoginRequest::MAX_ATTEMPTS, $user->email, self::IP_A);
        $this->assertThrottled($this->login($user->email, 'password', self::IP_A));

        $this->login($user->email, 'password', self::IP_B);
        $this->assertAuthenticatedAs($user);
    }

    public function test_a_lockout_on_one_account_does_not_block_another_account_on_the_same_ip(): void
    {
        $victim = User::factory()->create();
        $other = User::factory()->create();

        $this->failTimes(LoginRequest::MAX_ATTEMPTS, $victim->email);

        $this->login($other->email, 'password');
        $this->assertAuthenticatedAs($other);
    }

    // ── Blocage par IP (rotation d'adresses e-mail) ─────────────────────

    public function test_an_ip_trying_many_different_emails_gets_blocked(): void
    {
        $user = User::factory()->create();

        for ($i = 1; $i <= LoginRequest::MAX_ATTEMPTS_PER_IP; $i++) {
            $this->failTimes(1, "inconnu{$i}@example.com");
        }

        $this->assertThrottled($this->login($user->email, 'password', self::IP_A));

        // Une autre adresse IP n'est pas concernée.
        $this->login($user->email, 'password', self::IP_B);
        $this->assertAuthenticatedAs($user);
    }

    public function test_the_ip_counter_is_not_reset_by_a_login_on_another_account(): void
    {
        $own = User::factory()->create();

        for ($i = 1; $i < LoginRequest::MAX_ATTEMPTS_PER_IP; $i++) {
            $this->failTimes(1, "cible{$i}@example.com");
        }

        // L'attaquant se connecte à son propre compte pour tenter de remettre le compteur à zéro.
        $this->login($own->email, 'password');
        $this->assertAuthenticatedAs($own);
        $this->post('/logout');

        $this->failTimes(1, 'derniere-cible@example.com');

        $this->assertThrottled($this->login($own->email, 'password'));
    }

    // ── La validation n'alimente pas les compteurs ──────────────────────

    public function test_malformed_requests_do_not_count_as_failed_attempts(): void
    {
        $user = User::factory()->create();

        for ($i = 0; $i < 10; $i++) {
            $this->withServerVariables(['REMOTE_ADDR' => self::IP_A])
                ->post('/login', ['email' => 'pas-un-email', 'password' => 'x'])
                ->assertSessionHasErrors('email');
            $this->withServerVariables(['REMOTE_ADDR' => self::IP_A])
                ->post('/login', ['email' => $user->email])
                ->assertSessionHasErrors('password');
        }

        $this->login($user->email, 'password');
        $this->assertAuthenticatedAs($user);
    }

    // ── Clés de limitation ──────────────────────────────────────────────

    public function test_throttle_keys_have_a_fixed_length_and_do_not_expose_the_email(): void
    {
        $request = LoginRequest::create('/login', 'POST', [
            'email' => 'Personne@Example.com',
            'password' => 'x',
        ], server: ['REMOTE_ADDR' => self::IP_A]);

        $this->assertStringNotContainsStringIgnoringCase('personne', $request->throttleKey());
        $this->assertStringNotContainsString(self::IP_A, $request->throttleKey());
        $this->assertLessThan(100, strlen($request->throttleKey()));
        $this->assertLessThan(100, strlen($request->ipThrottleKey()));
        $this->assertNotSame($request->throttleKey(), $request->ipThrottleKey());
    }

    public function test_failures_are_recorded_in_the_rate_limiter_and_cleared_on_success(): void
    {
        $user = User::factory()->create();
        $request = LoginRequest::create('/login', 'POST', ['email' => $user->email], server: ['REMOTE_ADDR' => self::IP_A]);

        $this->failTimes(3, $user->email);
        $this->assertSame(3, RateLimiter::attempts($request->throttleKey()));
        $this->assertSame(3, RateLimiter::attempts($request->ipThrottleKey()));

        $this->login($user->email, 'password');

        $this->assertSame(0, RateLimiter::attempts($request->throttleKey()));
        $this->assertSame(3, RateLimiter::attempts($request->ipThrottleKey()));
    }
}
