<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('code')->unique();
            $table->string('module')->nullable()->index();
            $table->string('description', 500)->nullable();
            $table->timestamps();
        });

        $permissions = [
            ['name' => 'Ver usuarios', 'code' => 'ver-usuarios', 'module' => 'usuarios', 'description' => 'Permite listar usuarios dentro del administrador.'],
            ['name' => 'Crear usuarios', 'code' => 'crear-usuarios', 'module' => 'usuarios', 'description' => 'Permite registrar nuevos usuarios y asignarles rol.'],
            ['name' => 'Editar usuarios', 'code' => 'editar-usuarios', 'module' => 'usuarios', 'description' => 'Permite actualizar datos y rol del usuario.'],
            ['name' => 'Eliminar usuarios', 'code' => 'eliminar-usuarios', 'module' => 'usuarios', 'description' => 'Permite remover usuarios del sistema.'],
            ['name' => 'Ver roles', 'code' => 'ver-roles', 'module' => 'usuarios', 'description' => 'Permite consultar el catalogo de roles.'],
            ['name' => 'Crear roles', 'code' => 'crear-roles', 'module' => 'usuarios', 'description' => 'Permite registrar nuevos roles.'],
            ['name' => 'Editar roles', 'code' => 'editar-roles', 'module' => 'usuarios', 'description' => 'Permite actualizar roles y sus permisos.'],
            ['name' => 'Eliminar roles', 'code' => 'eliminar-roles', 'module' => 'usuarios', 'description' => 'Permite eliminar roles sin usuarios asignados.'],
            ['name' => 'Ver permisos', 'code' => 'ver-permisos', 'module' => 'usuarios', 'description' => 'Permite consultar permisos del sistema.'],
            ['name' => 'Crear permisos', 'code' => 'crear-permisos', 'module' => 'usuarios', 'description' => 'Permite registrar nuevos permisos.'],
            ['name' => 'Editar permisos', 'code' => 'editar-permisos', 'module' => 'usuarios', 'description' => 'Permite actualizar permisos existentes.'],
            ['name' => 'Eliminar permisos', 'code' => 'eliminar-permisos', 'module' => 'usuarios', 'description' => 'Permite eliminar permisos no asignados.'],
        ];

        foreach ($permissions as $permission) {
            DB::table('permissions')->insert([
                ...$permission,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
