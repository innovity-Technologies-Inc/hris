<?php

namespace App\Models\Employee;

use App\Models\Leave\LeaveCount;
use App\Models\Plan\LeavePlan;
use Illuminate\Database\Eloquent\Model;

use App\Traits\Userstamps;
use App\Traits\Auditable;

class EmployeeLeavePlan extends Model
{
    use Userstamps, Auditable;
    protected $fillable = [
        'employee_id',
        'plan_id',
        'status',
    ];
    public function getEmployee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
    public function getPlan()
    {
        return $this->belongsTo(LeavePlan::class, 'plan_id', 'id');
    }
    public function leaveCount()
    {
        return $this->hasOne(LeaveCount::class, 'plan_id', 'plan_id')
            ->whereColumn('employee_id', 'employee_id');
    }
}

