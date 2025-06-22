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
        Schema::create('posts', function (Blueprint $table) {
          $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('post_type')->nullable();
            $table->string('activity_type')->nullable();
            $table->string('media')->nullable(); // Can store path/URL
            $table->timestamp('posted_on')->nullable();
            $table->unsignedBigInteger('posted_by');
            $table->timestamp('modified_on')->nullable();
            $table->boolean('tag_others')->default(false);
            $table->string('location')->nullable();

            $table->timestamps();

            $table->foreign('posted_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
