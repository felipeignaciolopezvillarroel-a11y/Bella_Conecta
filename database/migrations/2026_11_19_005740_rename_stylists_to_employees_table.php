<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Renombrar tabla
        Schema::rename('stylists', 'employees');

        // Renombrar FK en bookings
        Schema::table('bookings', function (Blueprint $table) {
            $table->renameColumn('stylist_id', 'employee_id');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->renameColumn('employee_id', 'stylist_id');
        });

        Schema::rename('employees', 'stylists');
    }
};