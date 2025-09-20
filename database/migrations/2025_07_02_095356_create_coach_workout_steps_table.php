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
        Schema::create('coach_workout_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained('coach_workout_sections')->onDelete('cascade');
            $table->string('step_number');
            $table->enum('step_type', ['step', 'repeat']);
            $table->integer('order')->default(0);
            $table->timestamps();
            
            // Index for better performance
            $table->index(['section_id', 'step_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coach_workout_steps');
    }
};
