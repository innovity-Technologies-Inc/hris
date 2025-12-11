<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeDeductionPlan extends Model
{
    use HasFactory;

    protected $table = 'employee_deduction_plans';

    protected $fillable = [
        'employee_id',
        'late_deduction',
        'early_out_deduction',
        'excessive_late_deduction',
        'status',
    ];

    /**
     * Get the employee that owns this deduction plan.
     */
    public function getEmployee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

}
