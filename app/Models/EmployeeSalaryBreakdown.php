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
        'performance_bonus',
        'overtime_pay',
        'other_earnings',
        'gross_salary',
        'currency',
        'effective_date',
    ];
    protected $casts = [
        'basic_salary' => 'decimal:2',
        'house_allowance' => 'decimal:2',
        'transport_allowance' => 'decimal:2',
        'food_allowance' => 'decimal:2',
        'medical_allowance' => 'decimal:2',
        'performance_bonus' => 'decimal:2',
        'overtime_pay' => 'decimal:2',
        'other_earnings' => 'decimal:2',
        'gross_salary' => 'decimal:2',
        'effective_date' => 'date',
    ];

    public function getEmployee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}
