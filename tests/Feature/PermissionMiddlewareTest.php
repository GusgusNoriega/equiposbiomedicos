<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PermissionMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_without_permission_cannot_access_the_users_module(): void
    {
        $role = Role::query()->create([
            'name' => 'Consulta basica',
            'code' => 'consulta-basica',
            'description' => 'Rol de prueba sin permisos de usuarios.',
            'is_system' => false,
        ]);

        $user = User::factory()->create([
            'role_id' => $role->id,
            'password' => 'clave-segura-123',
        ]);

        $this->actingAs($user)
            ->get(route('admin.users.index'))
            ->assertForbidden();
    }

    public function test_user_with_permission_can_access_the_users_module(): void
    {
        $permission = Permission::query()
            ->where('code', 'ver-usuarios')
            ->firstOrFail();

        $role = Role::query()->create([
            'name' => 'Gestor de usuarios',
            'code' => 'gestor-usuarios',
            'description' => 'Rol de prueba con acceso al listado de usuarios.',
            'is_system' => false,
        ]);

        $role->permissions()->sync([$permission->id]);

        $user = User::factory()->create([
            'role_id' => $role->id,
            'password' => 'clave-segura-123',
        ]);

        $this->actingAs($user)
            ->get(route('admin.users.index'))
            ->assertOk();
    }
}
