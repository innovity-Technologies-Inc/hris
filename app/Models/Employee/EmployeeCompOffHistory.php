<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Model;
use App\Models\Employee\Employee;
use App\Models\Leave\Leave;

class EmployeeCompOffHistory extends Model
{
    protected $table = 'employee_comp_off_histories';

    protected $fillable = [
        'employee_id',
        'leave_id',
        'type',
        'days',
        'previous_balance',
        'new_balance',
        'remarks',
    ];

    public function getEmployee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function getLeave()
    {
        return $this->belongsTo(Leave::class, 'leave_id', 'id');
    }
}
