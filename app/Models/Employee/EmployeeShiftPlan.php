<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Model;

class EmployeeShiftPlan extends Model
{
    protected $table = 'employee_shift_plans';

    protected $fillable = [
        'employee_id',
        'plan_id',
        'from',
        'to',
        'status',
    ];
    public function getEmployee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
    public function getPlan()
    {
        return $this->belongsTo(ShiftPlan::class, 'plan_id', 'id');
    }
}

