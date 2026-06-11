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
        $tables = [
            'employee_shift_plans',
            'employee_roster_plans',
            'employee_ot_plans',
            'employee_meal_plans',
            'employee_offday_plans'
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->date('to')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'employee_shift_plans',
            'employee_roster_plans',
            'employee_ot_plans',
            'employee_meal_plans',
            'employee_offday_plans'
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->date('to')->nullable(false)->change();
            });
        }
    }
};
