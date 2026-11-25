<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Fecha y hora en que se entregó el pedido
            $table->timestamp('delivered_at')->nullable()->after('status');
            // Quién confirmó la entrega (recepcionista)
            $table->foreignId('delivered_by')->nullable()->after('delivered_at')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['delivered_by']);
            $table->dropColumn(['delivered_at', 'delivered_by']);
        });
    }
};