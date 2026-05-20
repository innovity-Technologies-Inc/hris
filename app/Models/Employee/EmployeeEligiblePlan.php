<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\OrganizationScoped;

class EmployeeEligiblePlan extends Model
{
    use HasFactory, OrganizationScoped;

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
        'early_out_deduction_plan_from',
        'early_out_deduction_plan_to',
        'early_out_deduction_plan_status',
        'medical_plan_from',
        'medical_plan_to',
        'medical_plan_status',
        'excessive_late_plan_from',
        'excessive_late_plan_to',
        'excessive_late_plan_status',
        'meal_plan_from',
        'meal_plan_to',
        'meal_plan_status',
    ];

    protected $casts = [
        'shift_plan_from' => 'date',
        'shift_plan_to' => 'date',
        'leave_plan_from' => 'date',
        'leave_plan_to' => 'date',
        'ot_plan_from' => 'date',
        'ot_plan_to' => 'date',
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
        'early_out_deduction_plan_from' => 'date',
        'early_out_deduction_plan_to' => 'date',
        'medical_plan_from' => 'date',
        'medical_plan_to' => 'date',
        'excessive_late_plan_from' => 'date',
        'excessive_late_plan_to' => 'date',
        'meal_plan_from' => 'date',
        'meal_plan_to' => 'date',
    ];


    public function getEmployee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}


