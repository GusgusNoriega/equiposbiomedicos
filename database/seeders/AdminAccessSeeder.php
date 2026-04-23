<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminAccessSeeder extends Seeder
{
    public const DEFAULT_ADMIN_EMAIL = 'admin@biomed.local';
    public const DEFAULT_ADMIN_PASSWORD = 'Admin12345!';

    public function run(): void
    {
        DB::transaction(function (): void {
            $role = Role::query()->updateOrCreate(
                ['code' => 'administrador-biomedico'],
                [
                    'name' => 'Administrador biomedico',
                    'description' => 'Rol base con acceso total al modulo administrativo.',
                    'is_system' => true,
                ],
            );

            $permissionIds = Permission::query()->pluck('id')->all();

            if ($permissionIds !== []) {
                $role->permissions()->sync($permissionIds);
            }

            $admin = User::query()->firstOrCreate(
                ['email' => self::DEFAULT_ADMIN_EMAIL],
                [
                    'name' => 'Administrador principal',
                    'password' => Hash::make(self::DEFAULT_ADMIN_PASSWORD),
                    'role_id' => $role->id,
                    'email_verified_at' => now(),
                ],
            );

            if ($admin->role_id !== $role->id) {
                $admin->forceFill(['role_id' => $role->id])->save();
            }
        });
    }
}
