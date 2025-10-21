<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeEligiblePlan extends Model
{
    use HasFactory;

    protected $table = 'employee_eligible_plans';

    protected $fillable = [
        'employee_id',
        'shift_plan_from',
        'shift_plan_to',
        'shift_plan_status',
        'leave_plan_from',
        'leave_plan_to',
        'leave_plan_status',
        'ot_plan_from',
        'ot_plan_to',
        'ot_plan_status',
        'attendance_bonus_plan_from',
        'attendance_bonus_plan_to',
        'attendance_bonus_plan_status',
        'day_off_work_plan_from',
        'day_off_work_plan_to',
        'day_off_work_plan_status',
        'roster_plans_from',
        'roster_plans_to',
        'roster_plans_status',
        'bonus_plan_from',
        'bonus_plan_to',
        'bonus_plan_status',
        'allowance_plan_from',
        'allowance_plan_to',
        'allowance_plan_status',
        'late_deduction_plan_from',
        'late_deduction_plan_to',
        'late_deduction_plan_status',
        'production_plan_from',
        'production_plan_to',
        'production_plan_status',
        'early_out_deduction_plan_from',
        'early_out_deduction_plan_to',
        'early_out_deduction_plan_status',
        'salary_breakdown_plan_from',
        'salary_breakdown_plan_to',
        'salary_breakdown_plan_status',
        'medical_plan_from',
        'medical_plan_to',
        'medical_plan_status',
        'night_bill_plan_from',
        'night_bill_plan_to',
        'night_bill_plan_status',
        'tiffin_plan_from',
        'tiffin_plan_to',
        'tiffin_plan_status',
        'dinner_plan_from',
        'dinner_plan_to',
        'dinner_plan_status',
        'breakfast_plan_from',
        'breakfast_plan_to',
        'breakfast_plan_status',
        'food_com_plan_from',
        'food_com_plan_to',
        'food_com_plan_status',
        'excessive_late_plan_from',
        'excessive_late_plan_to',
        'excessive_late_plan_status',
        'lunch_plan_from',
        'lunch_plan_to',
        'lunch_plan_status',
        'snacks_plan_from',
        'snacks_plan_to',
        'snacks_plan_status',
    ];

    protected $casts = [
        'shift_plan_from' => 'date',
        'shift_plan_to' => 'date',
        'leave_plan_from' => 'date',
        'leave_plan_to' => 'date',
        'ot_plan_from' => 'date',
        'ot_plan_to' => 'date',
        'attendance_bonus_plan_from' => 'date',
        'attendance_bonus_plan_to' => 'date',
        'day_off_work_plan_from' => 'date',
        'day_off_work_plan_to' => 'date',
        'roster_plans_from' => 'date',
        'roster_plans_to' => 'date',
        'bonus_plan_from' => 'date',
        'bonus_plan_to' => 'date',
        'allowance_plan_from' => 'date',
        'allowance_plan_to' => 'date',
        'late_deduction_plan_from' => 'date',
        'late_deduction_plan_to' => 'date',
        'production_plan_from' => 'date',
        'production_plan_to' => 'date',
        'early_out_deduction_plan_from' => 'date',
        'early_out_deduction_plan_to' => 'date',
        'salary_breakdown_plan_from' => 'date',
        'salary_breakdown_plan_to' => 'date',
        'medical_plan_from' => 'date',
        'medical_plan_to' => 'date',
        'night_bill_plan_from' => 'date',
        'night_bill_plan_to' => 'date',
        'tiffin_plan_from' => 'date',
        'tiffin_plan_to' => 'date',
        'dinner_plan_from' => 'date',
        'dinner_plan_to' => 'date',
        'breakfast_plan_from' => 'date',
        'breakfast_plan_to' => 'date',
        'food_com_plan_from' => 'date',
        'food_com_plan_to' => 'date',
        'excessive_late_plan_from' => 'date',
        'excessive_late_plan_to' => 'date',
        'lunch_plan_from' => 'date',
        'lunch_plan_to' => 'date',
        'snacks_plan_from' => 'date',
        'snacks_plan_to' => 'date',
    ];


    public function getEmployee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}

