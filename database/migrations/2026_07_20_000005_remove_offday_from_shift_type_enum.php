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
        // First temporarily expand enum to include paid-off if needed and migrate any legacy 'Off-Day' values
        DB::statement("UPDATE attendance SET shift_type = 'paid-off' WHERE shift_type = 'Off-Day' OR shift_type = 'Paid-Off-Day' OR shift_type = 'paid-offday'");
        DB::statement("UPDATE attendance SET shift_type = 'comp-off' WHERE shift_type = 'comp-off-offday' OR shift_type = 'Comp-Off-Off-Day'");
        
        // Alter shift_type column in attendance table to ENUM('Regular', 'Roster', 'paid-off', 'comp-off')
        DB::statement("ALTER TABLE attendance MODIFY COLUMN shift_type ENUM('Regular', 'Roster', 'paid-off', 'comp-off') NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE attendance MODIFY COLUMN shift_type ENUM('Regular', 'Roster', 'Off-Day', 'paid-off', 'comp-off') NULL");
    }
};
