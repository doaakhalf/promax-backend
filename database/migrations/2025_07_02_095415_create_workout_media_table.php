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
        Schema::create('workout_media', function (Blueprint $table) {
            $table->id();
            $table->morphs('mediaable'); // For both coach and gym coach workouts
            $table->string('file_path');
            $table->string('file_type'); // image, video, document, etc.
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
            
            // Index for better performance
            $table->index(['mediaable_id', 'mediaable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workout_media');
    }
};
