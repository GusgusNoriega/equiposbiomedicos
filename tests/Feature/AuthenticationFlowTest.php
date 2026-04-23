<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_from_admin_dashboard(): void
    {
        $this->get('/admin')
            ->assertRedirect(route('login'));
    }

    public function test_existing_user_can_log_in_with_current_credentials(): void
    {
        $adminRole = Role::query()
            ->where('code', 'administrador-biomedico')
            ->firstOrFail();

        $user = User::factory()->create([
            'email' => 'usuario@example.com',
            'password' => 'clave-segura-123',
            'role_id' => $adminRole->id,
        ]);

        $this->post(route('login.attempt'), [
            'email' => $user->email,
            'password' => 'clave-segura-123',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($user);
    }
}
