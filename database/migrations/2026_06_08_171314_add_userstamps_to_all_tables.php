<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    protected array $excludeTables = [
        'migrations',
        'password_reset_tokens',
        'sessions',
        'cache',
        'cache_locks',
        'jobs',
        'job_batches',
        'failed_jobs',
        'personal_access_tokens',
        'roles',
        'permissions',
        'model_has_permissions',
        'model_has_roles',
        'role_has_permissions',
        'activity_log',
        'users', // Usually users table might have it or not, let's include it unless it breaks. We will just check if column exists.
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tables = array_column(Schema::getTables(), 'name');

        foreach ($tables as $table) {
            if (in_array($table, $this->excludeTables)) {
                continue;
            }

            try {
                if (Schema::hasTable($table)) {
                    Schema::table($table, function (Blueprint $tableBlueprint) use ($table) {
                        if (!Schema::hasColumn($table, 'created_by')) {
                            $tableBlueprint->unsignedBigInteger('created_by')->nullable();
                            $tableBlueprint->foreign('created_by')->references('id')->on('users')->nullOnDelete();
                        }
                        
                        if (!Schema::hasColumn($table, 'updated_by')) {
                            $tableBlueprint->unsignedBigInteger('updated_by')->nullable();
                            $tableBlueprint->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
                        }
                    });
                }
            } catch (\Exception $e) {
                // Ignore views or tables that cannot be altered
                \Illuminate\Support\Facades\Log::warning("Skipped userstamps for table {$table}: " . $e->getMessage());
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = array_column(Schema::getTables(), 'name');

        foreach ($tables as $table) {
            if (in_array($table, $this->excludeTables)) {
                continue;
            }

            try {
                if (Schema::hasTable($table)) {
                    Schema::table($table, function (Blueprint $tableBlueprint) use ($table) {
                        if (Schema::hasColumn($table, 'created_by')) {
                            $tableBlueprint->dropForeign(['created_by']);
                            $tableBlueprint->dropColumn('created_by');
                        }
                        
                        if (Schema::hasColumn($table, 'updated_by')) {
                            $tableBlueprint->dropForeign(['updated_by']);
                            $tableBlueprint->dropColumn('updated_by');
                        }
                    });
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::warning("Skipped userstamps rollback for table {$table}: " . $e->getMessage());
            }
        }
    }
};
