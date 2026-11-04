<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Log\Events\MessageLogged;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PasswordUpdateTest extends TestCase
{
    use RefreshDatabase;

    /** Mot de passe conforme à la politique (8+ car., majuscule, minuscule, chiffre). */
    private const NEW_PASSWORD = 'NewPassword-2026';

    // ──────────────────────────────────────────────────────────────────
    // Tests d'origine (conservés — seul le mot de passe d'exemple est
    // renforcé pour respecter la nouvelle politique Password::defaults()).
    // ──────────────────────────────────────────────────────────────────

    public function test_password_can_be_updated(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->put('/password', [
                'current_password' => 'password',
                'password' => self::NEW_PASSWORD,
                'password_confirmation' => self::NEW_PASSWORD,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->assertTrue(Hash::check(self::NEW_PASSWORD, $user->refresh()->password));
    }

    public function test_correct_password_must_be_provided_to_update_password(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->put('/password', [
                'current_password' => 'wrong-password',
                'password' => self::NEW_PASSWORD,
                'password_confirmation' => self::NEW_PASSWORD,
            ]);

        $response
            ->assertSessionHasErrorsIn('updatePassword', 'current_password')
            ->assertRedirect('/profile');
    }

    // ──────────────────────────────────────────────────────────────────
    // Route et formulaire
    // ──────────────────────────────────────────────────────────────────

    public function test_password_update_route_is_registered_as_put_password(): void
    {
        $this->assertTrue(Route::has('password.update'));
        $this->assertSame('/password', route('password.update', absolute: false));

        $route = Route::getRoutes()->getByName('password.update');
        $this->assertContains('PUT', $route->methods());
        $this->assertContains('auth', $route->gatherMiddleware());
    }

    public function test_profile_page_renders_password_form_targeting_password_update_route(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/profile');

        $response->assertOk();
        $response->assertSee('action="'.route('password.update').'"', false);
        $response->assertSee('name="_method" value="put"', false);
        $response->assertSee('name="_token"', false); // @csrf présent
        $response->assertSee('name="current_password"', false);
        $response->assertSee('name="password_confirmation"', false);
    }

    // ──────────────────────────────────────────────────────────────────
    // Changement réussi
    // ──────────────────────────────────────────────────────────────────

    public function test_new_password_is_stored_hashed_and_old_password_no_longer_matches(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->from('/profile')->put('/password', [
            'current_password' => 'password',
            'password' => self::NEW_PASSWORD,
            'password_confirmation' => self::NEW_PASSWORD,
        ])->assertSessionHasNoErrors();

        $stored = $user->refresh()->password;

        $this->assertNotSame(self::NEW_PASSWORD, $stored, 'Le mot de passe ne doit jamais être stocké en clair.');
        $this->assertTrue(Hash::isHashed($stored));
        $this->assertTrue(Hash::check(self::NEW_PASSWORD, $stored));
        $this->assertFalse(Hash::check('password', $stored));
    }

    public function test_login_works_with_new_password_and_fails_with_old_one_after_update(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->from('/profile')->put('/password', [
            'current_password' => 'password',
            'password' => self::NEW_PASSWORD,
            'password_confirmation' => self::NEW_PASSWORD,
        ])->assertSessionHasNoErrors();

        $this->post('/logout');
        $this->assertGuest();

        $this->post('/login', ['email' => $user->email, 'password' => 'password']);
        $this->assertGuest();

        $this->post('/login', ['email' => $user->email, 'password' => self::NEW_PASSWORD]);
        $this->assertAuthenticatedAs($user);
    }

    public function test_remember_token_is_rotated_when_password_is_updated(): void
    {
        $user = User::factory()->create();
        $before = $user->remember_token;

        $this->actingAs($user)->from('/profile')->put('/password', [
            'current_password' => 'password',
            'password' => self::NEW_PASSWORD,
            'password_confirmation' => self::NEW_PASSWORD,
        ])->assertSessionHasNoErrors();

        $this->assertNotSame($before, $user->refresh()->remember_token);
    }

    public function test_successful_update_flashes_password_updated_status(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->from('/profile')->put('/password', [
            'current_password' => 'password',
            'password' => self::NEW_PASSWORD,
            'password_confirmation' => self::NEW_PASSWORD,
        ])->assertSessionHas('status', 'password-updated');
    }

    // ──────────────────────────────────────────────────────────────────
    // Refus : ancien mot de passe, confirmation, politique
    // ──────────────────────────────────────────────────────────────────

    public function test_wrong_current_password_is_rejected_and_password_is_unchanged(): void
    {
        $user = User::factory()->create();
        $hashBefore = $user->password;

        $this->actingAs($user)->from('/profile')->put('/password', [
            'current_password' => 'wrong-password',
            'password' => self::NEW_PASSWORD,
            'password_confirmation' => self::NEW_PASSWORD,
        ])
            ->assertSessionHasErrorsIn('updatePassword', ['current_password'])
            ->assertRedirect('/profile');

        $this->assertSame('Le mot de passe actuel est incorrect.', $this->updatePasswordError('current_password'));
        $this->assertSame($hashBefore, $user->refresh()->password);
    }

    public function test_current_password_is_required(): void
    {
        $user = User::factory()->create();
        $hashBefore = $user->password;

        $this->actingAs($user)->from('/profile')->put('/password', [
            'password' => self::NEW_PASSWORD,
            'password_confirmation' => self::NEW_PASSWORD,
        ])->assertSessionHasErrorsIn('updatePassword', ['current_password']);

        $this->assertSame($hashBefore, $user->refresh()->password);
    }

    public function test_incorrect_password_confirmation_is_rejected(): void
    {
        $user = User::factory()->create();
        $hashBefore = $user->password;

        $response = $this->actingAs($user)->from('/profile')->put('/password', [
            'current_password' => 'password',
            'password' => self::NEW_PASSWORD,
            'password_confirmation' => 'Another-Password-2026',
        ]);

        $response
            ->assertSessionHasErrorsIn('updatePassword', ['password'])
            ->assertRedirect('/profile');

        $this->assertSame('La confirmation ne correspond pas au nouveau mot de passe.', $this->updatePasswordError('password'));
        $this->assertSame($hashBefore, $user->refresh()->password);
    }

    public function test_missing_password_confirmation_is_rejected(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->from('/profile')->put('/password', [
            'current_password' => 'password',
            'password' => self::NEW_PASSWORD,
        ])->assertSessionHasErrorsIn('updatePassword', ['password']);

        $this->assertTrue(Hash::check('password', $user->refresh()->password));
    }

    public function test_new_password_must_differ_from_current_password(): void
    {
        // Mot de passe actuel conforme à la politique, pour isoler la règle "different".
        $user = User::factory()->create(['password' => Hash::make(self::NEW_PASSWORD)]);

        $this->actingAs($user)->from('/profile')->put('/password', [
            'current_password' => self::NEW_PASSWORD,
            'password' => self::NEW_PASSWORD,
            'password_confirmation' => self::NEW_PASSWORD,
        ])->assertSessionHasErrorsIn('updatePassword', ['password']);

        $this->assertSame(
            'Le nouveau mot de passe doit être différent du mot de passe actuel.',
            $this->updatePasswordError('password')
        );
    }

    #[DataProvider('weakPasswords')]
    public function test_weak_new_password_is_rejected(string $weak, string $expectedMessage): void
    {
        $user = User::factory()->create();
        $hashBefore = $user->password;

        $this->actingAs($user)->from('/profile')->put('/password', [
            'current_password' => 'password',
            'password' => $weak,
            'password_confirmation' => $weak,
        ])->assertSessionHasErrorsIn('updatePassword', ['password']);

        // Plusieurs règles peuvent échouer en même temps : on vérifie que le message attendu figure parmi eux.
        $this->assertStringContainsString($expectedMessage, implode(' | ', $this->updatePasswordErrors('password')));
        $this->assertSame($hashBefore, $user->refresh()->password);
    }

    public static function weakPasswords(): array
    {
        return [
            'trop court'          => ['Ab1',           'au moins 8 caractères'],
            'sans majuscule'      => ['password-2026', 'majuscule et une minuscule'],
            'sans minuscule'      => ['PASSWORD-2026', 'majuscule et une minuscule'],
            'sans chiffre'        => ['Password-Only', 'au moins un chiffre'],
            'uniquement chiffres' => ['12345678',      'au moins une lettre'],
        ];
    }

    // ──────────────────────────────────────────────────────────────────
    // Authentification
    // ──────────────────────────────────────────────────────────────────

    public function test_guest_cannot_update_password(): void
    {
        $user = User::factory()->create();
        $hashBefore = $user->password;

        $response = $this->put('/password', [
            'current_password' => 'password',
            'password' => self::NEW_PASSWORD,
            'password_confirmation' => self::NEW_PASSWORD,
        ]);

        $response->assertRedirect(route('login'));
        $this->assertGuest();
        $this->assertSame($hashBefore, $user->refresh()->password);
    }

    public function test_guest_cannot_update_password_with_json_request(): void
    {
        $this->putJson('/password', [
            'current_password' => 'password',
            'password' => self::NEW_PASSWORD,
            'password_confirmation' => self::NEW_PASSWORD,
        ])->assertUnauthorized();
    }

    public function test_password_update_cannot_target_another_user(): void
    {
        $attacker = User::factory()->create();
        $victim = User::factory()->create();
        $victimHash = $victim->password;

        // Même en glissant un id/email dans la requête, seul l'utilisateur connecté est modifié.
        $this->actingAs($attacker)->from('/profile')->put('/password', [
            'id' => $victim->id,
            'email' => $victim->email,
            'current_password' => 'password',
            'password' => self::NEW_PASSWORD,
            'password_confirmation' => self::NEW_PASSWORD,
        ])->assertSessionHasNoErrors();

        $this->assertSame($victimHash, $victim->refresh()->password);
        $this->assertTrue(Hash::check(self::NEW_PASSWORD, $attacker->refresh()->password));
    }

    // ──────────────────────────────────────────────────────────────────
    // CSRF
    // ──────────────────────────────────────────────────────────────────

    public function test_password_update_is_rejected_without_csrf_token(): void
    {
        $user = User::factory()->create();
        $hashBefore = $user->password;

        // Laravel désactive la vérification CSRF quand l'env est "testing" : on la réactive.
        $this->app['env'] = 'local';

        $this->actingAs($user)->put('/password', [
            'current_password' => 'password',
            'password' => self::NEW_PASSWORD,
            'password_confirmation' => self::NEW_PASSWORD,
        ])->assertStatus(419);

        $this->assertSame($hashBefore, $user->refresh()->password);
    }

    public function test_password_update_is_accepted_with_valid_csrf_token(): void
    {
        $user = User::factory()->create();

        $this->app['env'] = 'local';

        $this->actingAs($user)
            ->withSession(['_token' => 'csrf-test-token'])
            ->from('/profile')
            ->put('/password', [
                '_token' => 'csrf-test-token',
                'current_password' => 'password',
                'password' => self::NEW_PASSWORD,
                'password_confirmation' => self::NEW_PASSWORD,
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->assertTrue(Hash::check(self::NEW_PASSWORD, $user->refresh()->password));
    }

    // ──────────────────────────────────────────────────────────────────
    // Anti-brute-force
    // ──────────────────────────────────────────────────────────────────

    public function test_password_update_is_rate_limited(): void
    {
        $user = User::factory()->create();

        for ($i = 0; $i < 6; $i++) {
            $this->actingAs($user)->from('/profile')->put('/password', [
                'current_password' => 'wrong-password',
                'password' => self::NEW_PASSWORD,
                'password_confirmation' => self::NEW_PASSWORD,
            ])->assertSessionHasErrorsIn('updatePassword', ['current_password']);
        }

        // 7e tentative dans la minute : bloquée, même avec le bon mot de passe.
        $this->actingAs($user)->from('/profile')->put('/password', [
            'current_password' => 'password',
            'password' => self::NEW_PASSWORD,
            'password_confirmation' => self::NEW_PASSWORD,
        ])->assertStatus(429);

        $this->assertTrue(Hash::check('password', $user->refresh()->password));
    }

    // ──────────────────────────────────────────────────────────────────
    // Confidentialité : journaux et données flashées
    // ──────────────────────────────────────────────────────────────────

    public function test_passwords_are_never_written_to_logs_or_flashed_to_session(): void
    {
        $user = User::factory()->create();
        $logged = [];

        Event::listen(MessageLogged::class, function (MessageLogged $event) use (&$logged) {
            $logged[] = $event->message.' '.json_encode($event->context);
        });

        // 1) échec de validation
        $this->actingAs($user)->from('/profile')->put('/password', [
            'current_password' => 'wrong-current-secret',
            'password' => 'weak-new-secret',
            'password_confirmation' => 'other-new-secret',
        ])->assertSessionHasErrorsIn('updatePassword');

        // Les mots de passe ne sont pas renvoyés dans old('...') après un échec.
        $this->assertNull(session()->getOldInput('current_password'));
        $this->assertNull(session()->getOldInput('password'));
        $this->assertNull(session()->getOldInput('password_confirmation'));

        // 2) succès
        $this->actingAs($user)->from('/profile')->put('/password', [
            'current_password' => 'password',
            'password' => self::NEW_PASSWORD,
            'password_confirmation' => self::NEW_PASSWORD,
        ])->assertSessionHasNoErrors();

        $dump = implode("\n", $logged);
        foreach (['wrong-current-secret', 'weak-new-secret', 'other-new-secret', self::NEW_PASSWORD] as $secret) {
            $this->assertStringNotContainsString($secret, $dump);
        }
    }

    /** Premier message d'erreur du champ dans le bag "updatePassword" (lu par la vue du profil). */
    private function updatePasswordError(string $field): ?string
    {
        return $this->updatePasswordErrors($field)[0] ?? null;
    }

    /** @return list<string> Tous les messages d'erreur du champ dans le bag "updatePassword". */
    private function updatePasswordErrors(string $field): array
    {
        return session('errors')->getBag('updatePassword')->get($field);
    }
}
