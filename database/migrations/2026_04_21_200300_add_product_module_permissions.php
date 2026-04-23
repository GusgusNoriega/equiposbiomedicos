<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $permissions = [
            ['name' => 'Ver productos biomedicos', 'code' => 'ver-productos-biomedicos', 'module' => 'inventario', 'description' => 'Permite listar productos biomedicos dentro del administrador.'],
            ['name' => 'Crear productos biomedicos', 'code' => 'crear-productos-biomedicos', 'module' => 'inventario', 'description' => 'Permite registrar productos biomedicos y sus especificaciones.'],
            ['name' => 'Editar productos biomedicos', 'code' => 'editar-productos-biomedicos', 'module' => 'inventario', 'description' => 'Permite actualizar productos biomedicos existentes.'],
            ['name' => 'Eliminar productos biomedicos', 'code' => 'eliminar-productos-biomedicos', 'module' => 'inventario', 'description' => 'Permite eliminar productos biomedicos del catalogo.'],
            ['name' => 'Ver categorias de productos', 'code' => 'ver-categorias-productos', 'module' => 'inventario', 'description' => 'Permite listar categorias de productos biomedicos.'],
            ['name' => 'Crear categorias de productos', 'code' => 'crear-categorias-productos', 'module' => 'inventario', 'description' => 'Permite registrar categorias de productos biomedicos.'],
            ['name' => 'Editar categorias de productos', 'code' => 'editar-categorias-productos', 'module' => 'inventario', 'description' => 'Permite actualizar categorias de productos biomedicos.'],
            ['name' => 'Eliminar categorias de productos', 'code' => 'eliminar-categorias-productos', 'module' => 'inventario', 'description' => 'Permite eliminar categorias sin productos asociados.'],
        ];

        $roleId = DB::table('roles')
            ->where('code', 'administrador-biomedico')
            ->value('id');

        foreach ($permissions as $permission) {
            $permissionId = DB::table('permissions')->insertGetId([
                ...$permission,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if ($roleId) {
                DB::table('permission_role')->insert([
                    'role_id' => $roleId,
                    'permission_id' => $permissionId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        $codes = [
            'ver-productos-biomedicos',
            'crear-productos-biomedicos',
            'editar-productos-biomedicos',
            'eliminar-productos-biomedicos',
            'ver-categorias-productos',
            'crear-categorias-productos',
            'editar-categorias-productos',
            'eliminar-categorias-productos',
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
