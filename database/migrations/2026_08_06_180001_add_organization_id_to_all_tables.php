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
        // 1. Get all tables in the active database schema
        $dbName = DB::connection()->getDatabaseName();
        $tablesQuery = DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema = ?", [$dbName]);
        $tables = array_map(function ($t) {
            return $t->table_name ?? $t->TABLE_NAME;
        }, $tablesQuery);

        // 2. Define tables to exclude from organization scoping
        $excludeTables = [
            'migrations',
            'sessions',
            'cache',
            'jobs',
            'failed_jobs',
            'organizations',
            'permissions',
            'model_has_permissions',
            'model_has_roles',
            'role_has_permissions',
            'activity_log',
            'personal_access_tokens',
            'job_batches'
        ];

        // Disable foreign key checks while altering tables
        Schema::disableForeignKeyConstraints();

        foreach ($tables as $table) {
            $tableNameOnly = str_contains($table, '.') ? substr($table, strrpos($table, '.') + 1) : $table;

            if (in_array($tableNameOnly, $excludeTables)) {
                continue;
            }

            // Skip tables that do not have an 'id' column
            if (!Schema::hasColumn($table, 'id')) {
                continue;
            }

            // Check if column already exists
            if (!Schema::hasColumn($table, 'organization_id')) {
                Schema::table($table, function (Blueprint $tableBlueprint) use ($table) {
                    // Add organization_id as nullable first
                    $tableBlueprint->unsignedBigInteger('organization_id')->nullable()->after('id');
                    
                    // Add foreign key constraint to organizations table
                    $tableBlueprint->foreign('organization_id')
                        ->references('id')
                        ->on('organizations')
                        ->onDelete('set null');
                });
            }
        }

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $dbName = DB::connection()->getDatabaseName();
        $tablesQuery = DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema = ?", [$dbName]);
        $tables = array_map(function ($t) {
            return $t->table_name ?? $t->TABLE_NAME;
        }, $tablesQuery);
        $excludeTables = [
            'migrations',
            'sessions',
            'cache',
            'jobs',
            'failed_jobs',
            'organizations',
            'permissions',
            'model_has_permissions',
            'model_has_roles',
            'role_has_permissions',
            'activity_log',
            'personal_access_tokens',
            'job_batches'
        ];

        Schema::disableForeignKeyConstraints();

        foreach ($tables as $table) {
            $tableNameOnly = str_contains($table, '.') ? substr($table, strrpos($table, '.') + 1) : $table;

            if (in_array($tableNameOnly, $excludeTables)) {
                continue;
            }

            // Skip tables that do not have an 'id' column
            if (!Schema::hasColumn($table, 'id')) {
                continue;
            }

            if (Schema::hasColumn($table, 'organization_id')) {
                Schema::table($table, function (Blueprint $tableBlueprint) use ($table) {
                    // Drop foreign key first
                    $tableBlueprint->dropForeign([ 'organization_id' ]);
                    $tableBlueprint->dropColumn('organization_id');
                });
            }
        }

        Schema::enableForeignKeyConstraints();
    }
};
