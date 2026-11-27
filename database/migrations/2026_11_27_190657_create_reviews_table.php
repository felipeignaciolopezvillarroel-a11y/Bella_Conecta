<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // Opcional
            $table->string('name');
            $table->string('email')->nullable();
            $table->unsignedTinyInteger('rating')->default(5);
            $table->text('review');
            $table->string('service')->nullable(); // Slug del servicio
            $table->boolean('approved')->default(false); // Requiere aprobación
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};