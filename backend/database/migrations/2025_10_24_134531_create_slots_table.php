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
        Schema::create('slots', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('turf_id');
            $table->date('slot_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->enum('status', ['available', 'booked', 'blocked'])->default('available');
            $table->decimal('price', 10, 2)->nullable();
            $table->unsignedBigInteger('booking_id')->nullable();
            $table->timestamps();
            
            $table->foreign('turf_id')->references('id')->on('turfs')->onDelete('cascade');
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('set null');
            $table->unique(['turf_id', 'slot_date', 'start_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slots');
    }
};
