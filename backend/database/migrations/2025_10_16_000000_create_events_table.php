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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->json('sponsored_by')->nullable();
            $table->string('user_name')->nullable();
            $table->date('registration_start_date');
            $table->date('registration_end_date');
            $table->date('event_start_date');
            $table->date('event_end_date');
            $table->decimal('registration_amount', 10, 2)->default(0);
            $table->unsignedInteger('team_limit')->nullable();
            $table->string('sports_type');
            $table->enum('event_type', ['individual', 'team']);
            $table->string('location_lat');
            $table->string('location_lon');
            $table->string('banner')->nullable();
            $table->text('description')->nullable();
            $table->text('rules')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('address');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};


