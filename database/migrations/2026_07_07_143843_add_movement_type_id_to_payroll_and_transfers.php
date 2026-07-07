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
        $tables = ['increments', 'decrements', 'promotions', 'demotions', 'transfers'];
        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                // Drop if it exists from failed execution (since MySQL doesn't roll back DDL)
                if (Schema::hasColumn($table, 'movement_type_id')) {
                    Schema::table($table, function (Blueprint $tableBlueprint) {
                        $tableBlueprint->dropColumn('movement_type_id');
                    });
                }

                Schema::table($table, function (Blueprint $tableBlueprint) {
                    $tableBlueprint->foreignId('movement_type_id')->nullable()->constrained('movement_types')->nullOnDelete();
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['increments', 'decrements', 'promotions', 'demotions', 'transfers'];
        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $tableBlueprint) {
                    $tableBlueprint->dropConstrainedForeignId('movement_type_id');
                });
            }
        }
    }
};
