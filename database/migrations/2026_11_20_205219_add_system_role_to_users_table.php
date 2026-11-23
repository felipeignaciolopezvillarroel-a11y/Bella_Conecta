<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Si ya tienes 'role', lo renombramos a 'system_role' (o lo creamos)
            if (!Schema::hasColumn('users', 'system_role')) {
                $table->string('system_role')->default('usuario')->after('password');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('system_role');
        });
    }
};