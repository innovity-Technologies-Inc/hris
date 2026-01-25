<?php

namespace App\Models\Payroll;

use App\Models\Designation;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Model;

class Increment extends Model
{
    protected $fillable = [
        'employee_id',
        'increment_base',
        'increment_method',
        'salary_increase_amount',
        'previous_basic_salary',
        'previous_gross_salary',
        'new_basic_salary',
        'effective_from',
        'effective_to',
        'status',
    ];


    /**
     * Get the employee associated with this increment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getEmployee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}
