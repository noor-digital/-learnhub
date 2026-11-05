<?php

namespace Tests\Feature\Auth;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }

    // ── Ajouts : message d'échec, validation, session, déconnexion ──────

    public function test_invalid_password_returns_a_generic_message_on_the_email_field(): void
    {
        $user = User::factory()->create();

        $response = $this->from('/login')->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['email' => LoginRequest::FAILED_MESSAGE]);
        $response->assertSessionHasInput('email', $user->email);
        $this->assertGuest();
    }

    public function test_the_wrong_password_flash_never_contains_the_submitted_password(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertSessionMissing('_old_input.password');
    }

    #[DataProvider('invalidPayloads')]
    public function test_login_validates_the_submitted_credentials(array $payload, string $field): void
    {
        $response = $this->post('/login', $payload);

        $response->assertSessionHasErrors($field);
        $this->assertGuest();
    }

    public static function invalidPayloads(): array
    {
        return [
            'e-mail manquant' => [['password' => 'password'], 'email'],
            'e-mail invalide' => [['email' => 'pas-un-email', 'password' => 'password'], 'email'],
            'e-mail trop long' => [['email' => str_repeat('a', 250).'@example.com', 'password' => 'password'], 'email'],
            'mot de passe manquant' => [['email' => 'user@example.com'], 'password'],
        ];
    }

    public function test_session_id_is_regenerated_after_a_successful_login(): void
    {
        $user = User::factory()->create();

        $this->get('/login');
        $idBeforeLogin = session()->getId();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);
        $this->assertNotSame($idBeforeLogin, session()->getId());
    }

    public function test_user_is_redirected_to_the_intended_url_after_login(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->withSession(['url.intended' => route('courses.index')])
            ->post('/login', [
                'email' => $user->email,
                'password' => 'password',
            ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('courses.index'));
    }

    public function test_remember_me_sets_the_recaller_cookie_only_when_requested(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
            'remember' => 'on',
        ]);

        $response->assertCookie(Auth::guard('web')->getRecallerName());
    }

    public function test_logout_invalidates_the_session_and_regenerates_the_csrf_token(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->withSession(['marqueur' => 'avant-deconnexion']);
        $tokenBefore = csrf_token();

        $response = $this->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
        $this->assertNull(session('marqueur'));
        $this->assertNotSame($tokenBefore, csrf_token());
    }

    public function test_authenticated_users_cannot_access_the_login_form_again(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/login')->assertRedirect();
        $this->actingAs($user)->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect();
    }
}
