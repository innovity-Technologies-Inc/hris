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
        Schema::create('tax_deduction_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('payroll_process_id')->nullable();
            
            // Organizational context fields
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('division_id')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('section_id')->nullable();
            
            $table->string('salary_month', 7); // e.g. "2026-07"
            $table->date('deduction_date');
            
            $table->decimal('annual_tax_payable', 15, 2)->default(0.00);
            $table->decimal('monthly_tax_rate', 15, 2)->default(0.00);
            $table->decimal('amount', 15, 2)->default(0.00);
            
            $table->string('frequency', 20); // Hourly, Daily, Weekly, Monthly
            $table->decimal('hours_worked', 8, 2)->nullable();
            $table->decimal('days_worked', 5, 2)->nullable();
            
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('payroll_process_id')->references('id')->on('payroll_process')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_deduction_histories');
    }
};
