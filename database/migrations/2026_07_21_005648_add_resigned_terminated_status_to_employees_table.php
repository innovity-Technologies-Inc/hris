<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Expand employees.status ENUM to include 'resigned' and 'terminated'
     * for the Offboarding module.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE employees MODIFY COLUMN status ENUM('active', 'inactive', 'incomplete', 'pending', 'resigned', 'terminated') NOT NULL DEFAULT 'active'");
    }

    /**
     * Reverse the migrations.
     * Restore employees.status ENUM to previous values.
     * Note: any rows with 'resigned' or 'terminated' status will prevent rollback.
     */
    public function down(): void
    {
        // Reset resigned/terminated employees to inactive before rolling back
        DB::statement("UPDATE employees SET status = 'inactive' WHERE status IN ('resigned', 'terminated')");
        DB::statement("ALTER TABLE employees MODIFY COLUMN status ENUM('active', 'inactive', 'incomplete', 'pending') NOT NULL DEFAULT 'active'");
    }
};
