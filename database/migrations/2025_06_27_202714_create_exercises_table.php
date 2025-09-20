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
        Schema::create('exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // gym coach who created it
            $table->string('name_en');
            $table->string('name_ar');
            $table->string('type');
            $table->json('target_body_parts')->comment('Stores array of selected body parts. Possible values: 
            ["Chest", "Back", "Legs", "Arms", "Shoulders", "Core"] in English or 
            ["صدر", "ظهر", "أرجل", "أذرع", "أكتاف", "بطن"] in Arabic');
            $table->text('description_en')->nullable();
            $table->text('description_ar')->nullable();
            $table->string('image_path'); // store image
            $table->string('video_url')->nullable(); // optional
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercises');
    }
};
