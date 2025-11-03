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
        Schema::create('employee_eligible_plans', function (Blueprint $table) {
            $table->id()->index();
            $table->unsignedBigInteger('employee_id')->unique();

            // Dates and status for each specific plan
            $table->date('shift_plan_from')->nullable();
            $table->date('shift_plan_to')->nullable();
            $table->enum('shift_plan_status', ['active', 'inactive'])->default('inactive');

            $table->date('leave_plan_from')->nullable();
            $table->date('leave_plan_to')->nullable();
            $table->enum('leave_plan_status', ['active', 'inactive'])->default('inactive');

            $table->date('ot_plan_from')->nullable();
            $table->date('ot_plan_to')->nullable();
            $table->enum('ot_plan_status', ['active', 'inactive'])->default('inactive');

            $table->date('attendance_bonus_plan_from')->nullable();
            $table->date('attendance_bonus_plan_to')->nullable();
            $table->enum('attendance_bonus_plan_status', ['active', 'inactive'])->default('inactive');

            $table->date('day_off_work_plan_from')->nullable();
            $table->date('day_off_work_plan_to')->nullable();
            $table->enum('day_off_work_plan_status', ['active', 'inactive'])->default('inactive');

            $table->date('roster_plans_from')->nullable();
            $table->date('roster_plans_to')->nullable();
            $table->enum('roster_plans_status', ['active', 'inactive'])->default('inactive');

            $table->date('bonus_plan_from')->nullable();
            $table->date('bonus_plan_to')->nullable();
            $table->enum('bonus_plan_status', ['active', 'inactive'])->default('inactive');

            $table->date('allowance_plan_from')->nullable();
            $table->date('allowance_plan_to')->nullable();
            $table->enum('allowance_plan_status', ['active', 'inactive'])->default('inactive');

            $table->date('late_deduction_plan_from')->nullable();
            $table->date('late_deduction_plan_to')->nullable();
            $table->enum('late_deduction_plan_status', ['active', 'inactive'])->default('inactive');

            $table->date('production_plan_from')->nullable();
            $table->date('production_plan_to')->nullable();
            $table->enum('production_plan_status', ['active', 'inactive'])->default('inactive');

            $table->date('early_out_deduction_plan_from')->nullable();
            $table->date('early_out_deduction_plan_to')->nullable();
            $table->enum('early_out_deduction_plan_status', ['active', 'inactive'])->default('inactive');

            $table->date('salary_breakdown_plan_from')->nullable();
            $table->date('salary_breakdown_plan_to')->nullable();
            $table->enum('salary_breakdown_plan_status', ['active', 'inactive'])->default('inactive');

            $table->date('medical_plan_from')->nullable();
            $table->date('medical_plan_to')->nullable();
            $table->enum('medical_plan_status', ['active', 'inactive'])->default('inactive');

            $table->date('night_bill_plan_from')->nullable();
            $table->date('night_bill_plan_to')->nullable();
            $table->enum('night_bill_plan_status', ['active', 'inactive'])->default('inactive');

            $table->date('tiffin_plan_from')->nullable();
            $table->date('tiffin_plan_to')->nullable();
            $table->enum('tiffin_plan_status', ['active', 'inactive'])->default('inactive');

            $table->date('dinner_plan_from')->nullable();
            $table->date('dinner_plan_to')->nullable();
            $table->enum('dinner_plan_status', ['active', 'inactive'])->default('inactive');

            $table->date('breakfast_plan_from')->nullable();
            $table->date('breakfast_plan_to')->nullable();
            $table->enum('breakfast_plan_status', ['active', 'inactive'])->default('inactive');

            $table->date('food_com_plan_from')->nullable();
            $table->date('food_com_plan_to')->nullable();
            $table->enum('food_com_plan_status', ['active', 'inactive'])->default('inactive');

            $table->date('excessive_late_plan_from')->nullable();
            $table->date('excessive_late_plan_to')->nullable();
            $table->enum('excessive_late_plan_status', ['active', 'inactive'])->default('inactive');

            $table->date('lunch_plan_from')->nullable();
            $table->date('lunch_plan_to')->nullable();
            $table->enum('lunch_plan_status', ['active', 'inactive'])->default('inactive');

            $table->date('snacks_plan_from')->nullable();
            $table->date('snacks_plan_to')->nullable();
            $table->enum('snacks_plan_status', ['active', 'inactive'])->default('inactive');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_eligible_plans');
    }
};

