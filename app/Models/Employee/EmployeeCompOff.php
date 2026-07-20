<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Model;
use App\Models\Employee\Employee;

class EmployeeCompOff extends Model
{
    protected $table = 'employee_comp_offs';

    protected $fillable = [
        'employee_id',
        'comp_off_days',
        'used_days',
        'balance_days',
        'last_earned_date',
        'status',
    ];

    /**
     * Get the employee that owns this comp-off record.
     */
    public function getEmployee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}
