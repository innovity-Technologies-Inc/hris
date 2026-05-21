<?php

namespace App\Models\Employee;

use App\Models\Plan\MealPlan;
use Illuminate\Database\Eloquent\Model;

class EmployeeMealPlan extends Model
{
    protected $table = 'employee_meal_plans';

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
        return $this->belongsTo(MealPlan::class, 'plan_id', 'id');
    }
}

