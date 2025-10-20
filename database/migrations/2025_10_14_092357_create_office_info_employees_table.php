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
        Schema::create('office_info_employees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id')->unique(); // Links to the employees table

            // Payroll Info
            $table->string('emp_type');
            $table->string('paygrade')->nullable();
            $table->string('category');
            $table->string('hr_file_no')->nullable();
            $table->string('totali')->nullable();
            $table->text('file_note')->nullable();

            // Joining Information 
            $table->unsignedBigInteger('joining_company_id')->nullable();
            $table->unsignedBigInteger('joining_business_unit_id')->nullable();
            $table->unsignedBigInteger('joining_division_id')->nullable();
            $table->unsignedBigInteger('joining_department_id')->nullable();
            $table->unsignedBigInteger('joining_designation_id')->nullable();
            $table->unsignedBigInteger('joining_section_id')->nullable();
            $table->string('joining_subsection')->nullable();
            $table->string('joining_floor')->nullable();
            $table->date('date_of_join');

            // Current Information
            $table->unsignedBigInteger('current_company_id')->nullable();
            $table->unsignedBigInteger('current_business_unit_id')->nullable();
            $table->unsignedBigInteger('current_division_id')->nullable();
            $table->unsignedBigInteger('current_department_id')->nullable();
            $table->unsignedBigInteger('current_designation_id')->nullable();
            $table->unsignedBigInteger('current_section_id')->nullable();
            $table->string('current_subsection')->nullable();
            $table->string('current_floor')->nullable();
            $table->date('current_info_effective_date')->nullable();

            // Orientation
            $table->boolean('orientation_required')->default(false);
            $table->date('orientation_from')->nullable();
            $table->date('orientation_to')->nullable();
            $table->string('orientation_type')->nullable();
            $table->unsignedInteger('orientation_days')->nullable();

            // Duration & Cycles
            $table->date('confirmation_date')->nullable();
            $table->string('probation_duration')->nullable();
            $table->date('next_promotion_date')->nullable();
            $table->string('promotion_cycle')->nullable();
            $table->string('increment_cycle')->nullable();

            // Work Schedule
            $table->json('weekends')->nullable();
            $table->string('alternate_off_day')->nullable();

            // Eligibility & Benefits
            $table->boolean('ot_allowed')->default(false);
            $table->boolean('pf_eligible')->default(false);
            $table->string('salary_type');
            $table->decimal('imprest_fund', 10, 2)->nullable();
            $table->boolean('transport_eligible')->default(false);
            $table->string('status')->default('Active');
            $table->boolean('can_apply_loan')->default(false);
            $table->date('pf_effective_date')->nullable();
            $table->string('cash_collector')->nullable();
            $table->boolean('can_apply_advance')->default(false);
            $table->boolean('gratuity_eligible')->default(false);
            $table->string('separation_type')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('office_info_employees');
    }
};
