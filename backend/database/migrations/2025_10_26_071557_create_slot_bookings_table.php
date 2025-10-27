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
        Schema::create('slot_bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('turf_id');
            $table->unsignedBigInteger('sport_id')->nullable();
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->decimal('duration', 3, 1)->default(1.0);
            $table->integer('start_slot_value');
            $table->integer('end_slot_value');
            $table->decimal('total_price', 10, 2);
            $table->string('sport_type')->nullable();
            $table->text('special_requests')->nullable();
            $table->enum('status', [0,1,2,3])->default(0); // 0 = pending, 1 = confirmed , 2 = cancelled, 3 = completed
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('turf_id')->references('id')->on('turfs')->onDelete('cascade');
            $table->foreign('sport_id')->references('id')->on('sports')->onDelete('set null');
            
            $table->index(['turf_id', 'date']);
            $table->index(['user_id', 'date']);

            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slot_bookings');
    }
};
