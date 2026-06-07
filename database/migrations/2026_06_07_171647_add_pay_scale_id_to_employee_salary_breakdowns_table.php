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
        Schema::table('employee_salary_breakdowns', function (Blueprint $table) {
            $table->foreignId('pay_scale_id')->nullable()->after('employee_id')->constrained('pay_scales');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_salary_breakdowns', function (Blueprint $table) {
            $table->dropForeign(['pay_scale_id']);
            $table->dropColumn('pay_scale_id');
        });
    }
};
