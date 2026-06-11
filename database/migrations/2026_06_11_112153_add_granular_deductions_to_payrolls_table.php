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
        Schema::table('payrolls', function (Blueprint $table) {
            $table->decimal('late_deduction_amount', 15, 2)->default(0)->after('deduction_amount');
            $table->decimal('excessive_late_deduction_amount', 15, 2)->default(0)->after('late_deduction_amount');
            $table->decimal('absent_deduction_amount', 15, 2)->default(0)->after('excessive_late_deduction_amount');
            $table->decimal('early_exit_deduction_amount', 15, 2)->default(0)->after('absent_deduction_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropColumn([
                'late_deduction_amount',
                'excessive_late_deduction_amount',
                'absent_deduction_amount',
                'early_exit_deduction_amount'
            ]);
        });
    }
};
