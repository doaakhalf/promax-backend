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
        Schema::create('coach_workout_step_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('step_id')->constrained('coach_workout_steps')->onDelete('cascade');
            $table->enum('duration_type', ['time', 'distance', 'lap', 'calories', 'heart_rate', 'open']);
            $table->decimal('duration_value', 10, 2)->nullable();
            $table->enum('target_type', ['none', 'pace', 'heart_rate','cadence','speed','power','custom_heart_rate'])->default('none');
            $table->decimal('target_min', 10, 2)->nullable();
            $table->decimal('target_max', 10, 2)->nullable();
            $table->text('instructions')->nullable();
            $table->integer('repeat_count')->nullable()->comment('Only for repeat type steps');
            $table->enum('step_category', ['run', 'recovery'])->nullable()->comment('Only for repeat type steps');
            $table->timestamps();
            
            // Index for better performance
            $table->index(['step_id', 'duration_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coach_workout_step_details');
    }
};
