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
        Schema::create('coaches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('type', ['normal', 'gym_coach'])->default('normal');
            $table->string('sport');
            $table->json('best_record')->nullable()->comment('Stores activity, time, and place in JSON format');
            $table->text('introduction')->nullable();
            $table->text('training_experience');
            $table->text('motivation');
            $table->string('headline');
            $table->string('photo')->nullable();
            $table->string('video_url')->nullable();
            $table->decimal('monthly_price_egp', 10, 2);
            $table->string('instapay_link')->nullable();
            $table->timestamps();
            
            // Add index for better performance on user_id since it will be used for lookups
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coaches');
    }
};
