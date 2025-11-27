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

            $table->date('early_out_deduction_plan_from')->nullable();
            $table->date('early_out_deduction_plan_to')->nullable();
            $table->enum('early_out_deduction_plan_status', ['active', 'inactive'])->default('inactive');

            $table->date('medical_plan_from')->nullable();
            $table->date('medical_plan_to')->nullable();
            $table->enum('medical_plan_status', ['active', 'inactive'])->default('inactive');

            $table->date('excessive_late_plan_from')->nullable();
            $table->date('excessive_late_plan_to')->nullable();
            $table->enum('excessive_late_plan_status', ['active', 'inactive'])->default('inactive');

            $table->date('meal_plan_from')->nullable();
            $table->date('meal_plan_to')->nullable();
            $table->enum('meal_plan_status', ['active', 'inactive'])->default('inactive');

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

