<?php

namespace App\Models\Payroll;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Userstamps;
use App\Traits\Auditable;
use App\Traits\OrganizationScoped;
use App\Models\Employee\Employee;

class TaxDeductionHistory extends Model
{
    use Userstamps, Auditable, OrganizationScoped;

    protected $table = 'tax_deduction_histories';

    protected $fillable = [
        'employee_id',
        'payroll_process_id',
        'company_id',
        'branch_id',
        'division_id',
        'department_id',
        'section_id',
        'salary_month',
        'deduction_date',
        'annual_tax_payable',
        'monthly_tax_rate',
        'amount',
        'frequency',
        'hours_worked',
        'days_worked',
    ];

    protected $casts = [
        'deduction_date' => 'date',
        'annual_tax_payable' => 'decimal:2',
        'monthly_tax_rate' => 'decimal:2',
        'amount' => 'decimal:2',
        'hours_worked' => 'decimal:2',
        'days_worked' => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function payrollProcess()
    {
        return $this->belongsTo(PayrollProcess::class, 'payroll_process_id');
    }
}
