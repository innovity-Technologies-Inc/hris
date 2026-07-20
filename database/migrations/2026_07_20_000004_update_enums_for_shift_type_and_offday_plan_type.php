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
        // Update type column in off_day_plans table
        DB::statement("ALTER TABLE off_day_plans MODIFY COLUMN type ENUM('Paid', 'paid-off', 'comp-off') NOT NULL DEFAULT 'paid-off'");
        DB::statement("UPDATE off_day_plans SET type = 'paid-off' WHERE type = 'Paid' OR type IS NULL OR type = ''");
        DB::statement("ALTER TABLE off_day_plans MODIFY COLUMN type ENUM('paid-off', 'comp-off') NOT NULL DEFAULT 'paid-off'");

        // Update shift_type column in attendance table to ENUM
        DB::statement("ALTER TABLE attendance MODIFY COLUMN shift_type ENUM('Regular', 'Roster', 'Off-Day', 'Paid-Off-Day', 'paid-offday', 'paid-off', 'comp-off-offday', 'Comp-Off-Off-Day', 'comp-off') NULL");
        DB::statement("UPDATE attendance SET shift_type = 'comp-off' WHERE shift_type IN ('comp-off-offday', 'Comp-Off-Off-Day')");
        DB::statement("UPDATE attendance SET shift_type = 'paid-off' WHERE shift_type IN ('paid-offday', 'Paid-Off-Day', 'Off-Day')");
        DB::statement("ALTER TABLE attendance MODIFY COLUMN shift_type ENUM('Regular', 'Roster', 'Off-Day', 'paid-off', 'comp-off') NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE off_day_plans MODIFY COLUMN type ENUM('Paid', 'comp-off') NOT NULL DEFAULT 'Paid'");
        DB::statement("ALTER TABLE attendance MODIFY COLUMN shift_type VARCHAR(255) NULL");
    }
};
