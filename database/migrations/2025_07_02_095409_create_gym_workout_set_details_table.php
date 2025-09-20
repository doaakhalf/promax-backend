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
        Schema::create('gym_workout_set_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('set_id')->constrained('gym_workout_sets')->onDelete('cascade');
            $table->enum('duration_type', ['time', 'reps']);
            $table->decimal('duration_value', 10, 2);
            $table->integer('sets');
            $table->integer('reps');
            $table->integer('rest_seconds');
            $table->string('weight_type');
            $table->decimal('weight_kg', 8, 2);
            $table->timestamps();
            
            // Index for better performance
            $table->index(['set_id', 'duration_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gym_workout_set_details');
    }
};
