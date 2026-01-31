<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Agregamos la FK. Asegúrate de correr la migración de Roles antes.
            $table->foreignId('role_id')->after('id')->constrained('roles');
            $table->boolean('active')->default(true)->after('password');
            $table->softDeletes(); // Columna deleted_at
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn(['role_id', 'active', 'deleted_at']);
        });
    }
};
