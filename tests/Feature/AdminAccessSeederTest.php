<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use Database\Seeders\AdminAccessSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_creates_default_admin_with_all_permissions(): void
    {
        Permission::query()->create([
            'name' => 'Permiso adicional de prueba',
            'code' => 'permiso-adicional-prueba',
            'module' => 'pruebas',
            'description' => 'Permiso agregado para validar el seeder.',
        ]);

        $this->seed(AdminAccessSeeder::class);

        $role = Role::query()
            ->where('code', 'administrador-biomedico')
            ->firstOrFail();

        $this->assertDatabaseHas('users', [
            'email' => AdminAccessSeeder::DEFAULT_ADMIN_EMAIL,
            'role_id' => $role->id,
        ]);

        $this->assertSame(
            Permission::query()->count(),
            $role->permissions()->count(),
        );
    }
}
