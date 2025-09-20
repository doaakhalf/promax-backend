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
        Schema::create('workouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('workout_type', ['coach', 'gym_coach']);
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('instructions')->nullable();
            $table->boolean('is_template')->default(false);
            $table->timestamps();
            
            // Index for better performance on common queries
            $table->index(['user_id', 'workout_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workouts');
    }
};
