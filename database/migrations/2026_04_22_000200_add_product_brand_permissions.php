<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $permissions = [
            ['name' => 'Ver marcas de productos', 'code' => 'ver-marcas-productos', 'module' => 'inventario', 'description' => 'Permite listar marcas del catalogo biomedico.'],
            ['name' => 'Crear marcas de productos', 'code' => 'crear-marcas-productos', 'module' => 'inventario', 'description' => 'Permite registrar marcas y cargar su logo.'],
            ['name' => 'Editar marcas de productos', 'code' => 'editar-marcas-productos', 'module' => 'inventario', 'description' => 'Permite actualizar datos y logo de una marca.'],
            ['name' => 'Eliminar marcas de productos', 'code' => 'eliminar-marcas-productos', 'module' => 'inventario', 'description' => 'Permite eliminar marcas sin productos asociados.'],
        ];

        $roleId = DB::table('roles')
            ->where('code', 'administrador-biomedico')
            ->value('id');

        foreach ($permissions as $permission) {
            DB::table('permissions')->updateOrInsert(
                ['code' => $permission['code']],
                [
                    'name' => $permission['name'],
                    'module' => $permission['module'],
                    'description' => $permission['description'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            );

            $permissionId = DB::table('permissions')
                ->where('code', $permission['code'])
                ->value('id');

            if ($roleId && $permissionId) {
                DB::table('permission_role')->updateOrInsert(
                    [
                        'role_id' => $roleId,
                        'permission_id' => $permissionId,
                    ],
                    [
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                );
            }
        }
    }

    public function down(): void
    {
        $codes = [
            'ver-marcas-productos',
            'crear-marcas-productos',
            'editar-marcas-productos',
            'eliminar-marcas-productos',
        ];

        $permissionIds = DB::table('permissions')
            ->whereIn('code', $codes)
            ->pluck('id');

        if ($permissionIds->isNotEmpty()) {
            DB::table('permission_role')
                ->whereIn('permission_id', $permissionIds)
                ->delete();

            DB::table('permissions')
                ->whereIn('id', $permissionIds)
                ->delete();
        }
    }
};
