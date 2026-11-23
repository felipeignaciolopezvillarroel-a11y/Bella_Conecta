<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('brand')->nullable(); // Marca
            $table->text('description')->nullable();
            $table->string('icon')->default('🧴'); // Emoji para la web
            $table->unsignedInteger('price')->default(0); // Precio normal
            $table->unsignedInteger('discount_price')->nullable(); // Precio con descuento
            $table->unsignedInteger('stock')->default(0); // Stock disponible
            $table->boolean('active')->default(true);
            $table->boolean('featured')->default(false); // Para el Home
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};