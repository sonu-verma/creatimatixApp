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
        Schema::create('sports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_turf')->constrained('turfs')->onDelete('cascade');
            $table->foreignId('id_sport')->constrained('sport_types')->onDelete('cascade');
            $table->string('name');
            $table->integer('rate_per_hour');
            $table->string('dimensions');
            $table->integer('capacity');
            $table->text('rules')->nullable();
            $table->integer('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sports');
    }
};
