<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->foreignId('stylist_id')->nullable()->constrained()->nullOnDelete();
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])
                  ->default('confirmed');
            $table->text('notes')->nullable();
            $table->string('code')->unique();
            $table->timestamps();

            $table->unique(['stylist_id', 'date', 'start_time', 'status'], 'unique_active_slot');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};