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
        Schema::table('pay_groups', function (Blueprint $table) {
            $table->decimal('working_hours_per_day', 5, 2)->nullable()->after('payroll_frequency');
            $table->decimal('working_days_per_cycle', 5, 2)->nullable()->after('working_hours_per_day');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pay_groups', function (Blueprint $table) {
            $table->dropColumn(['working_hours_per_day', 'working_days_per_cycle']);
        });
    }
};
