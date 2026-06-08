<?php

namespace App\Models\Employee;

use App\Models\Plan\ShiftPlan;
use Illuminate\Database\Eloquent\Model;

use App\Traits\Userstamps;
use App\Traits\Auditable;

class EmployeeShiftPlan extends Model
{
    use Userstamps, Auditable;
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

