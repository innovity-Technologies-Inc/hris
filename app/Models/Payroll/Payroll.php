<?php

namespace App\Models\Payroll;

use App\Models\Employee\Employee;
use Illuminate\Database\Eloquent\Model;

use App\Traits\OrganizationScoped;

use App\Traits\Userstamps;
use App\Traits\Auditable;

class Payroll extends Model
{
    use Userstamps, Auditable;
    use OrganizationScoped;
    protected $table = 'payrolls';
    protected $fillable = [
        'employee_id',
        'process_id',
        'batch_id',
        'salary',
        'deduction_amount',
        'late_deduction_amount',
        'excessive_late_deduction_amount',
        'absent_deduction_amount',
        'early_exit_deduction_amount',
        'penalty_amount',
        'advance_deduction_amount',
        'leaves_count',
        'offday_work_count',
        'absent_count',
        'absent_dates',
        'late_count',
        'excessive_late_count',
        'early_exit_count',
        'overtime_count',
        'overtime_amount',
        'offday_work_salary',
        'bonus_amount',
        'total_salary',
    ];

    protected $casts = [
        'absent_dates' => 'array',
    ];

    public function getEmployee(){
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function getBatch(){
        return $this->belongsTo(PayrollProcess::class, 'process_id', 'id');
    }

}

