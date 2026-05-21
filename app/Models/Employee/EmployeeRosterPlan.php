<?php

namespace App\Models\Employee;

use App\Models\Plan\RosterPlan;
use Illuminate\Database\Eloquent\Model;

class EmployeeRosterPlan extends Model
{
    protected $table = 'employee_roster_plans';

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
        return $this->belongsTo(RosterPlan::class, 'plan_id', 'id');
    }
}

