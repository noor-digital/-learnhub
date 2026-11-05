<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * Connexion et redirection selon le rôle (admin, formateur, étudiant),
 * et cloisonnement des tableaux de bord.
 *
 * Les rôles ne sont jamais modifiés par la connexion.
 */
class LoginRolesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Le tableau de bord admin utilise DATE_FORMAT() (MySQL). La base de test
        // est SQLite : on déclare une fonction équivalente, côté test uniquement.
        DB::connection()->getPdo()->sqliteCreateFunction(
            'DATE_FORMAT',
            fn ($date, $format) => $date ? date('Y-m', strtotime($date)) : null,
            2
        );
    }

    public static function roles(): array
    {
        return [
            'admin' => ['admin', 'dashboard.admin'],
            'formateur' => ['formateur', 'dashboard.formateur'],
            'étudiant' => ['etudiant', 'dashboard.student'],
        ];
    }

    private function userWithRole(string $role): User
    {
        return User::factory()->create(['role' => $role]);
    }

    #[DataProvider('roles')]
    public function test_each_role_can_log_in_and_lands_on_its_own_dashboard(string $role, string $dashboardRoute): void
    {
        $user = $this->userWithRole($role);

        $this->post('/login', ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticatedAs($user);

        $this->get(route('dashboard'))->assertRedirect(route($dashboardRoute));
        $this->get(route($dashboardRoute))->assertOk();
    }

    #[DataProvider('roles')]
    public function test_a_wrong_password_never_logs_in_any_role(string $role, string $dashboardRoute): void
    {
        $user = $this->userWithRole($role);

        $this->post('/login', ['email' => $user->email, 'password' => 'mauvais'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
        $this->get(route($dashboardRoute))->assertRedirect(route('login'));
    }

    #[DataProvider('roles')]
    public function test_logging_in_does_not_change_the_role(string $role, string $dashboardRoute): void
    {
        $user = $this->userWithRole($role);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
            'role' => 'admin', // champ parasite : ne doit avoir aucun effet
        ]);

        $this->assertAuthenticatedAs($user);
        $this->assertSame($role, $user->fresh()->role);
    }

    #[DataProvider('roles')]
    public function test_a_lockout_applies_whatever_the_role_of_the_account(string $role, string $dashboardRoute): void
    {
        $user = $this->userWithRole($role);

        foreach (range(1, 5) as $_) {
            $this->post('/login', ['email' => $user->email, 'password' => 'mauvais']);
        }

        $this->post('/login', ['email' => $user->email, 'password' => 'password'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
        $this->assertSame($role, $user->fresh()->role);
    }

    // ── Cloisonnement des tableaux de bord ──────────────────────────────

    public function test_guests_are_redirected_to_login_from_every_dashboard(): void
    {
        foreach (['dashboard', 'dashboard.student', 'dashboard.formateur', 'dashboard.admin'] as $route) {
            $this->get(route($route))->assertRedirect(route('login'));
        }
    }

    public function test_a_student_cannot_open_the_admin_or_formateur_dashboards(): void
    {
        $this->actingAs($this->userWithRole('etudiant'));

        $this->get(route('dashboard.admin'))->assertForbidden();
        $this->get(route('dashboard.formateur'))->assertForbidden();
        $this->get(route('dashboard.student'))->assertOk();
    }

    public function test_a_formateur_cannot_open_the_admin_or_student_dashboards(): void
    {
        $this->actingAs($this->userWithRole('formateur'));

        $this->get(route('dashboard.admin'))->assertForbidden();
        $this->get(route('dashboard.student'))->assertForbidden();
        $this->get(route('dashboard.formateur'))->assertOk();
    }

    public function test_an_admin_can_open_every_dashboard(): void
    {
        $this->actingAs($this->userWithRole('admin'));

        $this->get(route('dashboard.admin'))->assertOk();
        $this->get(route('dashboard.formateur'))->assertOk();
        $this->get(route('dashboard.student'))->assertOk();
    }

    public function test_public_registration_cannot_create_a_privileged_account(): void
    {
        foreach (['admin', 'formateur'] as $role) {
            $this->post('/register', [
                'name' => 'Pirate '.$role,
                'email' => "pirate-{$role}@example.com",
                'password' => 'MotDePasse123',
                'password_confirmation' => 'MotDePasse123',
                'role' => $role,
            ]);

            $this->assertSame('etudiant', User::where('email', "pirate-{$role}@example.com")->value('role'));
            $this->post('/logout');
        }
    }
}
