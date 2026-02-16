<?php

namespace App\Models\Payroll;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    protected $table = 'payrolls';
    protected $fillable = [
        'employee_id',
        'process_id',
        'batch_id',
        'salary',
        'deduction_amount',
        'leaves_count',
        'offday_work_count',
        'absent_count',
        'late_count',
        'excessive_late_count',
        'overtime_count',
        'overtime_amount',
        'offday_work_salary',
        'bonus_amount'
    ];

    public function getEmployee(){
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function getBatch(){
        return $this->belongsTo(PayrollProcess::class, 'batch_id', 'id');
    }

}
