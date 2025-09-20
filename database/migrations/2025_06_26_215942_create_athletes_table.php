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
        Schema::create('athletes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('profile_image')->nullable();
            $table->enum('gender', ['male', 'female', 'other']);
            $table->decimal('weight', 5, 2)->comment('Weight in kilograms');
            $table->enum('training_frequency', ['2', '3','4','5','6','7'])->comment('Number of training sessions per week');
            $table->string('inbody_file')->nullable()->comment('Path to uploaded InBody file (PDF/image)');
            $table->timestamps();
            
            // Add index for better performance on user_id
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('athletes');
    }
};
