<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeOtPlan extends Model
{
    protected $table = 'employee_ot_plans';

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
        return $this->belongsTo(OtPlan::class, 'plan_id', 'id');
    }
}
