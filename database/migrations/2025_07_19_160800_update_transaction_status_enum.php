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
        // First, update any existing 'rejected' status to 'clearance_requested'
        DB::table('transactions')
            ->where('status', 'rejected')
            ->update(['status' => 'clearance_requested']);

        // Drop the existing enum constraint and recreate with new values
        DB::statement("ALTER TABLE transactions MODIFY COLUMN status ENUM('pending', 'under_review', 'completed', 'clearance_requested', 'clearance_approved', 'cancelled') DEFAULT 'pending' COMMENT 'حالة المعاملة'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert any 'clearance_requested' back to 'rejected'
        DB::table('transactions')
            ->where('status', 'clearance_requested')
            ->update(['status' => 'rejected']);
            
        DB::table('transactions')
            ->where('status', 'clearance_approved')
            ->update(['status' => 'under_review']);

        // Restore original enum
        DB::statement("ALTER TABLE transactions MODIFY COLUMN status ENUM('pending', 'under_review', 'completed', 'rejected', 'cancelled') DEFAULT 'pending' COMMENT 'حالة المعاملة'");
    }
};

