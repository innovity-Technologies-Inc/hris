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
        Schema::table('payroll_process', function (Blueprint $table) {
            $table->unsignedBigInteger('pay_group_id')->nullable()->after('batch_id');
            $table->date('start_date')->nullable()->after('section_id');
            $table->date('end_date')->nullable()->after('start_date');
            
            // Foreign key
            $table->foreign('pay_group_id')->references('id')->on('pay_groups')->onDelete('cascade');
        });

        Schema::table('payrolls', function (Blueprint $table) {
            $table->decimal('penalty_amount', 15, 2)->default(0)->after('deduction_amount');
            $table->decimal('total_salary', 15, 2)->default(0)->after('bonus_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payroll_process', function (Blueprint $table) {
            $table->dropForeign(['pay_group_id']);
            $table->dropColumn(['pay_group_id', 'start_date', 'end_date']);
        });

        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropColumn('penalty_amount');
        });
    }
};
