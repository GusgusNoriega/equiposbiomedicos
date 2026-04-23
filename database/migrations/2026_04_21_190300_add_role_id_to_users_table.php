<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')
                ->nullable()
                ->after('email')
                ->constrained('roles')
                ->restrictOnDelete();
        });

        $administratorRoleId = DB::table('roles')
            ->where('code', 'administrador-biomedico')
            ->value('id');

        if ($administratorRoleId) {
            DB::table('users')
                ->whereNull('role_id')
                ->update(['role_id' => $administratorRoleId]);
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('role_id');
        });
    }
};
