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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            
            // Coach providing the subscription
            $table->foreignId('coach_id')->constrained('users')->onDelete('cascade');
            
            // Athlete subscribing to the coach
            $table->foreignId('athlete_id')->constrained('users')->onDelete('cascade');
            
            // Subscription details
            $table->string('subscription_plan'); // e.g., 'monthly', 'quarterly', 'yearly'
            $table->decimal('amount', 10, 2); // Subscription amount in EGP
            $table->string('currency')->default('EGP');
            $table->string('payment_method')->nullable(); // 'credit_card', 'vodafone_cash', etc.
            $table->string('payment_status')->default('pending'); // 'pending', 'paid', 'failed', 'refunded'
            $table->string('transaction_id')->nullable(); // Reference from payment gateway
            
            // Subscription period
            $table->date('start_date');
            $table->date('end_date');
            $table->date('renewal_date')->nullable(); // Next payment date for recurring subscriptions
            
            // Status of the subscription
            $table->enum('status', ['active', 'pending', 'expired', 'cancelled', 'paused'])->default('pending');
            
            // Additional metadata
            $table->json('metadata')->nullable(); // For storing additional data like payment details, notes, etc.
            
            // Soft deletes for record keeping
            $table->softDeletes();
            $table->timestamps();
            
            // Indexes for better performance
            $table->unique(['coach_id', 'athlete_id', 'status']); // Prevents duplicate active subscriptions
            $table->index(['athlete_id', 'status']);
            $table->index(['coach_id', 'status']);
            $table->index(['end_date', 'status']); // For finding expiring subscriptions
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
