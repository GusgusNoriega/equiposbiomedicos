<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $permissions = [
            ['name' => 'Ver parametros de productos', 'code' => 'ver-parametros-productos', 'module' => 'inventario', 'description' => 'Permite listar parametros filtrables del catalogo de productos.'],
            ['name' => 'Crear parametros de productos', 'code' => 'crear-parametros-productos', 'module' => 'inventario', 'description' => 'Permite registrar parametros filtrables y sus unidades.'],
            ['name' => 'Editar parametros de productos', 'code' => 'editar-parametros-productos', 'module' => 'inventario', 'description' => 'Permite actualizar parametros filtrables y sus unidades.'],
            ['name' => 'Eliminar parametros de productos', 'code' => 'eliminar-parametros-productos', 'module' => 'inventario', 'description' => 'Permite eliminar parametros filtrables sin valores asociados.'],
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
            'ver-parametros-productos',
            'crear-parametros-productos',
            'editar-parametros-productos',
            'eliminar-parametros-productos',
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
