<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeSalaryBreakdown extends Model
{
    protected $fillable = [
        'employee_id',
        'basic_salary',
        'house_allowance',
        'transport_allowance',
        'food_allowance',
        'medical_allowance',
        'other_earnings',
        'gross_salary',
        'currency',
    ];

    public function getEmployee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}
