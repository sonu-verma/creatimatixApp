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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('turf_id')->constrained('turfs')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('rating')->comment('Rating from 1 to 5');
            $table->text('comment')->nullable();
            $table->boolean('status')->default(true)->comment('Review approval status');
            $table->timestamps();

            // Ensure one review per user per turf
            $table->unique(['turf_id', 'user_id']);
            
            // Add index for better performance
            $table->index(['turf_id', 'status']);
            $table->index(['user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
