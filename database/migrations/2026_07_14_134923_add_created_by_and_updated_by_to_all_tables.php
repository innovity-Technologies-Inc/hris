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
        $dbName = \Illuminate\Support\Facades\DB::connection()->getDatabaseName();
        $tables = array_map(
            fn($row) => reset($row),
            array_map(fn($row) => (array) $row, \Illuminate\Support\Facades\DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema = ?", [$dbName]))
        );

        foreach ($tables as $table) {
            if (in_array($table, ['migrations', 'password_reset_tokens', 'sessions', 'cache', 'cache_locks', 'jobs', 'job_batches', 'failed_jobs'])) {
                continue;
            }

            Schema::table($table, function (Blueprint $tableBlueprint) use ($table) {
                if (!Schema::hasColumn($table, 'created_by')) {
                    $tableBlueprint->unsignedBigInteger('created_by')->nullable();
                }
                if (!Schema::hasColumn($table, 'updated_by')) {
                    $tableBlueprint->unsignedBigInteger('updated_by')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Altering columns globally is typically not rolled back, but we can safely ignore
    }
};
