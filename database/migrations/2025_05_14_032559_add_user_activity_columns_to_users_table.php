<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Columna para controlar si el usuario está activo
            $table->boolean('active')->default(true)->after('role');
            
            // Columnas para seguimiento de actividad
            $table->timestamp('last_login_at')->nullable()->after('remember_token');
            $table->timestamp('last_active_at')->nullable()->after('last_login_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['active', 'last_login_at', 'last_active_at']);
        });
    }
};