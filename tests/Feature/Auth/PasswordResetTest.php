<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Log\Events\MessageLogged;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    /** Mot de passe conforme à la politique (8+ car., majuscule, minuscule, chiffre). */
    private const NEW_PASSWORD = 'NewPassword-2026';

    private const GENERIC_INVALID_LINK_MESSAGE =
        'Ce lien de réinitialisation est invalide ou a expiré. Veuillez effectuer une nouvelle demande.';

    // ──────────────────────────────────────────────────────────────────
    // Tests d'origine (conservés — seul le nouveau mot de passe d'exemple
    // du dernier test est renforcé pour respecter Password::defaults()).
    // ──────────────────────────────────────────────────────────────────

    public function test_reset_password_link_screen_can_be_rendered(): void
    {
        $response = $this->get('/forgot-password');

        $response->assertStatus(200);
    }

    public function test_reset_password_link_can_be_requested(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_reset_password_screen_can_be_rendered(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) {
            $response = $this->get('/reset-password/'.$notification->token);

            $response->assertStatus(200);

            return true;
        });
    }

    public function test_password_can_be_reset_with_valid_token(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
            $response = $this->post('/reset-password', [
                'token' => $notification->token,
                'email' => $user->email,
                'password' => self::NEW_PASSWORD,
                'password_confirmation' => self::NEW_PASSWORD,
            ]);

            $response
                ->assertSessionHasNoErrors()
                ->assertRedirect(route('login'));

            return true;
        });
    }

    // ──────────────────────────────────────────────────────────────────
    // Demande de lien ("mot de passe oublié")
    // ──────────────────────────────────────────────────────────────────

    public function test_valid_reset_request_is_accepted_and_creates_a_token(): void
    {
        Notification::fake();
        $user = User::factory()->create();

        $this->from('/forgot-password')
            ->post('/forgot-password', ['email' => $user->email])
            ->assertSessionHasNoErrors()
            ->assertSessionHas('status')
            ->assertRedirect('/forgot-password');

        Notification::assertSentToTimes($user, ResetPassword::class, 1);
        $this->assertDatabaseHas('password_reset_tokens', ['email' => $user->email]);
    }

    public function test_reset_request_for_unknown_email_gives_the_same_response_as_for_known_email(): void
    {
        Notification::fake();
        $user = User::factory()->create();

        $known = $this->from('/forgot-password')->post('/forgot-password', ['email' => $user->email]);
        $knownStatus = session('status');

        $unknown = $this->from('/forgot-password')->post('/forgot-password', ['email' => 'nobody-here@example.com']);
        $unknownStatus = session('status');

        $known->assertSessionHasNoErrors()->assertRedirect('/forgot-password');
        $unknown->assertSessionHasNoErrors()->assertRedirect('/forgot-password');

        $this->assertNotEmpty($knownStatus);
        $this->assertSame($knownStatus, $unknownStatus, "La réponse ne doit pas révéler si l'adresse possède un compte.");

        // Aucun mail ni jeton pour l'adresse inconnue
        Notification::assertSentTimes(ResetPassword::class, 1);
        $this->assertDatabaseMissing('password_reset_tokens', ['email' => 'nobody-here@example.com']);
    }

    public function test_repeated_reset_request_is_throttled_by_broker_without_revealing_it(): void
    {
        Notification::fake();
        $user = User::factory()->create();

        $first = $this->from('/forgot-password')->post('/forgot-password', ['email' => $user->email]);
        $firstStatus = session('status');

        // 2e demande immédiate : le broker applique son délai anti-spam (config/auth.php)…
        $second = $this->from('/forgot-password')->post('/forgot-password', ['email' => $user->email]);

        // …mais la réponse reste identique et sans erreur.
        $second->assertSessionHasNoErrors();
        $this->assertSame($firstStatus, session('status'));
        Notification::assertSentToTimes($user, ResetPassword::class, 1);
    }

    #[DataProvider('invalidEmails')]
    public function test_reset_request_requires_a_valid_email(?string $email): void
    {
        Notification::fake();

        $this->from('/forgot-password')
            ->post('/forgot-password', ['email' => $email])
            ->assertSessionHasErrors('email')
            ->assertRedirect('/forgot-password');

        Notification::assertNothingSent();
    }

    public static function invalidEmails(): array
    {
        return [
            'vide'            => [null],
            'format invalide' => ['not-an-email'],
        ];
    }

    public function test_reset_request_is_rate_limited(): void
    {
        for ($i = 0; $i < 6; $i++) {
            $this->post('/forgot-password', ['email' => "user{$i}@example.com"])->assertSessionHasNoErrors();
        }

        $this->post('/forgot-password', ['email' => 'user7@example.com'])->assertStatus(429);
    }

    // ──────────────────────────────────────────────────────────────────
    // Jeton : stockage, lien e-mail, formulaire
    // ──────────────────────────────────────────────────────────────────

    public function test_token_is_stored_hashed_in_database(): void
    {
        $user = User::factory()->create();

        $plainToken = Password::createToken($user);

        $stored = DB::table('password_reset_tokens')->where('email', $user->email)->value('token');

        $this->assertNotSame($plainToken, $stored, 'Le jeton ne doit pas être stocké en clair.');
        $this->assertTrue(Hash::check($plainToken, $stored));
    }

    public function test_reset_email_is_really_sent_with_a_working_secure_link(): void
    {
        // Pas de fake : on traverse le vrai système de notification / mailer (transport "array" en test).
        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email])->assertSessionHasNoErrors();

        $messages = app('mailer')->getSymfonyTransport()->messages();
        $this->assertCount(1, $messages, 'Un e-mail de réinitialisation doit être envoyé.');

        $raw = quoted_printable_decode($messages->first()->toString());
        $this->assertStringContainsString($user->email, $raw);
        $this->assertMatchesRegularExpression(
            '#/reset-password/([A-Za-z0-9]{64})\?email=#',
            $raw,
            "Le lien doit pointer vers la route password.reset avec un jeton aléatoire de 64 caractères."
        );

        preg_match('#/reset-password/([A-Za-z0-9]{64})\?email=#', $raw, $m);
        $token = $m[1];

        // Le lien reçu par e-mail fonctionne vraiment…
        $this->get('/reset-password/'.$token.'?email='.urlencode($user->email))->assertOk();

        // …et permet de définir un nouveau mot de passe.
        $this->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => self::NEW_PASSWORD,
            'password_confirmation' => self::NEW_PASSWORD,
        ])->assertSessionHasNoErrors()->assertRedirect(route('login'));

        $this->assertTrue(Hash::check(self::NEW_PASSWORD, $user->refresh()->password));
    }

    public function test_reset_form_contains_hidden_token_and_csrf_field(): void
    {
        $this->get('/reset-password/abc123token?email=someone@example.com')
            ->assertOk()
            ->assertSee('name="_token"', false)
            ->assertSee('name="token"', false)
            ->assertSee('value="abc123token"', false)
            ->assertSee(route('password.store'), false);
    }

    public function test_forgot_password_form_contains_csrf_field_and_posts_to_password_email(): void
    {
        $this->get('/forgot-password')
            ->assertOk()
            ->assertSee('name="_token"', false)
            ->assertSee('action="'.route('password.email').'"', false);
    }

    // ──────────────────────────────────────────────────────────────────
    // Jeton invalide / expiré / déjà utilisé
    // ──────────────────────────────────────────────────────────────────

    public function test_invalid_token_is_rejected(): void
    {
        $user = User::factory()->create();
        Password::createToken($user); // un jeton valide existe, mais ce n'est pas celui-ci
        $hashBefore = $user->password;

        $this->from('/reset-password/bad-token')
            ->post('/reset-password', [
                'token' => 'this-is-not-the-right-token',
                'email' => $user->email,
                'password' => self::NEW_PASSWORD,
                'password_confirmation' => self::NEW_PASSWORD,
            ])
            ->assertSessionHasErrors(['email' => self::GENERIC_INVALID_LINK_MESSAGE])
            ->assertRedirect('/reset-password/bad-token');

        $this->assertSame($hashBefore, $user->refresh()->password);
        $this->assertGuest();
    }

    public function test_token_is_required(): void
    {
        $user = User::factory()->create();

        $this->post('/reset-password', [
            'email' => $user->email,
            'password' => self::NEW_PASSWORD,
            'password_confirmation' => self::NEW_PASSWORD,
        ])->assertSessionHasErrors('token');
    }

    public function test_expired_token_is_rejected(): void
    {
        $user = User::factory()->create();
        $token = Password::createToken($user);
        $hashBefore = $user->password;

        // Expiration lue dans la config (60 min par défaut) : on dépasse d'une minute.
        $expire = (int) config('auth.passwords.users.expire');
        $this->travel($expire + 1)->minutes();

        $this->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => self::NEW_PASSWORD,
            'password_confirmation' => self::NEW_PASSWORD,
        ])->assertSessionHasErrors(['email' => self::GENERIC_INVALID_LINK_MESSAGE]);

        $this->assertSame($hashBefore, $user->refresh()->password);
    }

    public function test_token_is_still_valid_just_before_expiration(): void
    {
        $user = User::factory()->create();
        $token = Password::createToken($user);

        $expire = (int) config('auth.passwords.users.expire');
        $this->travel($expire - 1)->minutes();

        $this->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => self::NEW_PASSWORD,
            'password_confirmation' => self::NEW_PASSWORD,
        ])->assertSessionHasNoErrors()->assertRedirect(route('login'));
    }

    public function test_configured_expiration_delay_is_respected(): void
    {
        // La durée est pilotée par config/auth.php : on la réduit à 5 min pour ce test.
        config(['auth.passwords.users.expire' => 5]);

        $user = User::factory()->create();
        $token = Password::createToken($user);

        $this->travel(6)->minutes();

        $this->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => self::NEW_PASSWORD,
            'password_confirmation' => self::NEW_PASSWORD,
        ])->assertSessionHasErrors('email');

        $this->assertTrue(Hash::check('password', $user->refresh()->password));
    }

    public function test_token_cannot_be_reused_once_consumed(): void
    {
        $user = User::factory()->create();
        $token = Password::createToken($user);

        // 1re utilisation : OK
        $this->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => self::NEW_PASSWORD,
            'password_confirmation' => self::NEW_PASSWORD,
        ])->assertSessionHasNoErrors()->assertRedirect(route('login'));

        $this->assertDatabaseMissing('password_reset_tokens', ['email' => $user->email]);

        // 2e utilisation du même jeton : refusée
        $secondPassword = 'Attacker-Pass-999';
        $this->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => $secondPassword,
            'password_confirmation' => $secondPassword,
        ])->assertSessionHasErrors(['email' => self::GENERIC_INVALID_LINK_MESSAGE]);

        $user->refresh();
        $this->assertTrue(Hash::check(self::NEW_PASSWORD, $user->password));
        $this->assertFalse(Hash::check($secondPassword, $user->password));
    }

    public function test_token_issued_for_one_account_cannot_reset_another_account(): void
    {
        $alice = User::factory()->create();
        $bob = User::factory()->create();
        $aliceToken = Password::createToken($alice);
        $bobHash = $bob->password;

        $this->post('/reset-password', [
            'token' => $aliceToken,
            'email' => $bob->email,
            'password' => self::NEW_PASSWORD,
            'password_confirmation' => self::NEW_PASSWORD,
        ])->assertSessionHasErrors('email');

        $this->assertSame($bobHash, $bob->refresh()->password);
        // Le jeton d'Alice n'a pas été consommé par cette tentative
        $this->assertDatabaseHas('password_reset_tokens', ['email' => $alice->email]);
    }

    public function test_requesting_a_new_link_invalidates_the_previous_token(): void
    {
        $user = User::factory()->create();
        $oldToken = Password::createToken($user);

        // Passé le délai anti-spam du broker, une nouvelle demande remplace le jeton précédent.
        $this->travel(config('auth.passwords.users.throttle') + 5)->seconds();
        $this->post('/forgot-password', ['email' => $user->email])->assertSessionHasNoErrors();

        $this->post('/reset-password', [
            'token' => $oldToken,
            'email' => $user->email,
            'password' => self::NEW_PASSWORD,
            'password_confirmation' => self::NEW_PASSWORD,
        ])->assertSessionHasErrors('email');

        $this->assertTrue(Hash::check('password', $user->refresh()->password));
    }

    public function test_unknown_email_and_invalid_token_give_the_same_error_message(): void
    {
        $user = User::factory()->create();
        Password::createToken($user);

        $this->post('/reset-password', [
            'token' => 'wrong-token',
            'email' => $user->email, // compte existant, mauvais jeton
            'password' => self::NEW_PASSWORD,
            'password_confirmation' => self::NEW_PASSWORD,
        ]);
        $errorKnownAccount = session('errors')->first('email');

        $this->post('/reset-password', [
            'token' => 'wrong-token',
            'email' => 'ghost@example.com', // compte inexistant
            'password' => self::NEW_PASSWORD,
            'password_confirmation' => self::NEW_PASSWORD,
        ]);
        $errorUnknownAccount = session('errors')->first('email');

        $this->assertSame(self::GENERIC_INVALID_LINK_MESSAGE, $errorKnownAccount);
        $this->assertSame($errorKnownAccount, $errorUnknownAccount);
    }

    // ──────────────────────────────────────────────────────────────────
    // Nouveau mot de passe : validation et effets
    // ──────────────────────────────────────────────────────────────────

    public function test_password_confirmation_mismatch_is_rejected_and_token_stays_valid(): void
    {
        $user = User::factory()->create();
        $token = Password::createToken($user);

        $this->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => self::NEW_PASSWORD,
            'password_confirmation' => 'Different-Pass-123',
        ])->assertSessionHasErrors(['password' => 'Les mots de passe ne correspondent pas.']);

        $this->assertTrue(Hash::check('password', $user->refresh()->password));

        // Une erreur de saisie ne "brûle" pas le jeton : une saisie correcte fonctionne ensuite.
        $this->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => self::NEW_PASSWORD,
            'password_confirmation' => self::NEW_PASSWORD,
        ])->assertSessionHasNoErrors()->assertRedirect(route('login'));
    }

    #[DataProvider('weakPasswords')]
    public function test_weak_password_is_rejected_on_reset(string $weak, string $expectedMessage): void
    {
        $user = User::factory()->create();
        $token = Password::createToken($user);

        $this->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => $weak,
            'password_confirmation' => $weak,
        ])->assertSessionHasErrors('password');

        $this->assertStringContainsString($expectedMessage, implode(' | ', session('errors')->get('password')));
        $this->assertTrue(Hash::check('password', $user->refresh()->password));
    }

    public static function weakPasswords(): array
    {
        return [
            'trop court'     => ['Ab1',           'au moins 8 caractères'],
            'sans majuscule' => ['password-2026', 'majuscule et une minuscule'],
            'sans chiffre'   => ['Password-Only', 'au moins un chiffre'],
        ];
    }

    public function test_new_password_works_and_old_password_stops_working_after_reset(): void
    {
        $user = User::factory()->create();
        $token = Password::createToken($user);

        $this->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => self::NEW_PASSWORD,
            'password_confirmation' => self::NEW_PASSWORD,
        ])->assertSessionHasNoErrors()->assertRedirect(route('login'));

        $this->assertNotSame(self::NEW_PASSWORD, $user->refresh()->password);
        $this->assertTrue(Hash::isHashed($user->password));

        // Ancien mot de passe : refusé
        $this->post('/login', ['email' => $user->email, 'password' => 'password']);
        $this->assertGuest();

        // Nouveau mot de passe : accepté
        $this->post('/login', ['email' => $user->email, 'password' => self::NEW_PASSWORD]);
        $this->assertAuthenticatedAs($user);
    }

    public function test_remember_token_is_rotated_on_reset(): void
    {
        $user = User::factory()->create();
        $before = $user->remember_token;
        $token = Password::createToken($user);

        $this->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => self::NEW_PASSWORD,
            'password_confirmation' => self::NEW_PASSWORD,
        ])->assertSessionHasNoErrors();

        $this->assertNotSame($before, $user->refresh()->remember_token);
    }

    public function test_successful_reset_redirects_to_login_with_a_status_message(): void
    {
        $user = User::factory()->create();
        $token = Password::createToken($user);

        $this->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => self::NEW_PASSWORD,
            'password_confirmation' => self::NEW_PASSWORD,
        ])->assertRedirect(route('login'))
            ->assertSessionHas('status', 'Votre mot de passe a été réinitialisé. Vous pouvez maintenant vous connecter.');

        // Le message est bien affiché sur la page de connexion
        $this->get('/login')->assertOk()->assertSee('Votre mot de passe a été réinitialisé.');
    }

    // ──────────────────────────────────────────────────────────────────
    // Accès, CSRF, journaux
    // ──────────────────────────────────────────────────────────────────

    public function test_authenticated_user_is_redirected_away_from_reset_pages(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/forgot-password')->assertRedirect();
        $this->actingAs($user)->get('/reset-password/some-token')->assertRedirect();
    }

    public function test_reset_endpoints_are_rejected_without_csrf_token(): void
    {
        $user = User::factory()->create();
        $token = Password::createToken($user);
        $hashBefore = $user->password;

        // Laravel désactive la vérification CSRF quand l'env est "testing" : on la réactive.
        $this->app['env'] = 'local';

        $this->post('/forgot-password', ['email' => $user->email])->assertStatus(419);

        $this->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => self::NEW_PASSWORD,
            'password_confirmation' => self::NEW_PASSWORD,
        ])->assertStatus(419);

        $this->assertSame($hashBefore, $user->refresh()->password);
    }

    public function test_reset_flow_never_logs_or_flashes_passwords(): void
    {
        $user = User::factory()->create();
        $logged = [];

        Event::listen(MessageLogged::class, function (MessageLogged $event) use (&$logged) {
            $logged[] = $event->message.' '.json_encode($event->context);
        });

        $token = Password::createToken($user);

        // Échec (jeton invalide) puis succès
        $this->post('/reset-password', [
            'token' => 'bad-token',
            'email' => $user->email,
            'password' => 'Secret-Attempt-111',
            'password_confirmation' => 'Secret-Attempt-111',
        ])->assertSessionHasErrors('email');

        $this->assertNull(session()->getOldInput('password'));
        $this->assertNull(session()->getOldInput('password_confirmation'));

        $this->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => self::NEW_PASSWORD,
            'password_confirmation' => self::NEW_PASSWORD,
        ])->assertSessionHasNoErrors();

        $dump = implode("\n", $logged);
        foreach (['Secret-Attempt-111', self::NEW_PASSWORD, $token] as $secret) {
            $this->assertStringNotContainsString($secret, $dump);
        }
    }
}
