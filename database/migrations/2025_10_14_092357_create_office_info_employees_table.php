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
            // Basic Identifiers
            $table->unsignedBigInteger('employee_id')->index()->nullable();
            $table->string('emp_type')->nullable();
            $table->unsignedBigInteger('grade_id')->nullable();
            $table->string('hr_file_no')->nullable();
            $table->unsignedBigInteger('tofsil_id')->nullable();
            $table->text('file_note')->nullable();

            // Joining Information
            $table->unsignedBigInteger('joining_company_id')->nullable();
            $table->unsignedBigInteger('joining_business_unit_id')->nullable();
            $table->unsignedBigInteger('joining_division_id')->nullable();
            $table->unsignedBigInteger('joining_department_id')->nullable();
            $table->unsignedBigInteger('joining_section_id')->nullable();
            $table->unsignedBigInteger('joining_designation_id')->nullable();
            $table->date('date_of_join')->nullable();

            // Current Posting Information
            $table->unsignedBigInteger('current_company_id')->nullable();
            $table->unsignedBigInteger('current_business_unit_id')->nullable();
            $table->unsignedBigInteger('current_division_id')->nullable();
            $table->unsignedBigInteger('current_department_id')->nullable();
            $table->unsignedBigInteger('current_section_id')->nullable();
            $table->unsignedBigInteger('current_designation_id')->nullable();

            // Orientation Information
            $table->boolean('orientation_required');
            $table->date('orientation_from')->nullable();
            $table->date('orientation_to')->nullable();
            $table->string('orientation_type')->nullable();
            $table->unsignedInteger('orientation_days')->nullable();

            // Employment & Performance
            $table->date('confirmation_date')->nullable();
            $table->unsignedInteger('probation_duration')->nullable();
            $table->date('next_promotion_date')->nullable();
            $table->string('promotion_cycle')->nullable();
            $table->string('increment_cycle')->nullable();

            // Attendance & Benefits
            $table->json('weekends')->nullable(); // e.g. "Friday,Saturday"
            $table->json('alternate_off_day')->nullable();
            $table->enum('ot_allowed',['yes', 'no']);
            $table->enum('pf_eligible',['yes', 'no']);
            $table->string('salary_type')->nullable(); // e.g. "Monthly", "Hourly"
            $table->enum('transport_eligible',['yes', 'no'])->default('yes');

            // Loan & Benefits Eligibility
            $table->enum('can_apply_loan',['yes', 'no']);
            $table->date('pf_effective_date')->nullable();
            $table->enum('can_apply_advance',['yes', 'no']);
            $table->enum('gratuity_eligible',['yes', 'no']);


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
