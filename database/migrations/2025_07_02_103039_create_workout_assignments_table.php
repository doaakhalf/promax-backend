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
        Schema::create('workout_assignments', function (Blueprint $table) {
            $table->id();
            
            // Coach who assigned the workout (can be either coach or gym_coach)
            $table->foreignId('coach_id')->constrained('users')->onDelete('cascade');
            
            // Athlete who received the workout
            $table->foreignId('athlete_id')->constrained('users')->onDelete('cascade');
            
            // The assigned workout
            $table->foreignId('workout_id')->constrained('workouts')->onDelete('cascade');
            
            // Scheduled date for the workout
            $table->date('scheduled_date');
            
            // Status of the assignment (scheduled, in_progress, completed, skipped, etc.)
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'skipped'])->default('scheduled');
            
            // Additional notes from the coach
            $table->text('coach_notes')->nullable();
            
            // Feedback from the athlete
            $table->text('athlete_feedback')->nullable();
            
            // Completion data (for tracking actual completion)
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            
            // Soft deletes for archiving
            $table->softDeletes();
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['coach_id', 'status', 'scheduled_date']);
            $table->index(['athlete_id', 'status', 'scheduled_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workout_assignments');
    }
};
