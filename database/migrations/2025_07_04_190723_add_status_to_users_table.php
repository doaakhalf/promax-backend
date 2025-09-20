<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, add the status column as nullable
        Schema::table('users', function (Blueprint $table) {
            $table->enum('status', ['incomplete', 'pending', 'approved'])->default('incomplete')->after('role_id');
        });

        // Then, update the status based on role_id
        // Note: This requires the roles table to exist and be populated
        // If roles table doesn't exist yet, you'll need to run this after roles are created
        if (Schema::hasTable('roles')) {
            // For coaches and gym_coaches,Atheletes default status is 'pending'
            \DB::table('users')
                ->whereIn('role_id', function ($query) {
                    $query->select('id')
                        ->from('roles')
                        ->whereIn('name', ['coach', 'gym_coach','athlete']);
                })
                ->update(['status' => 'incomplete']);

           
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
