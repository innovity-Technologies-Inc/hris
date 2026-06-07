<?php

namespace App\Models\Payroll;

use App\Models\Employee\Employee;
use App\Models\Plan\PenaltyPlan;
use Illuminate\Database\Eloquent\Model;

class EmployeePenalty extends Model
{
    protected $table = 'employee_penalties';

    protected $fillable = [
        'employee_id',
        'penalty_plan_id',
        'occurrence_date',
        'cause',
        'penalty_amount',
        'status',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function penaltyPlan()
    {
        return $this->belongsTo(PenaltyPlan::class, 'penalty_plan_id');
    }
}
