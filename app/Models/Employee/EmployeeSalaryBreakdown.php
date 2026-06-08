<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Model;
use App\Traits\OrganizationScoped;

use App\Traits\Userstamps;
use App\Traits\Auditable;

class EmployeeSalaryBreakdown extends Model
{
    use Userstamps, Auditable;
    use OrganizationScoped;
    protected $fillable = [
        'employee_id',
        'pay_scale_id',
        'basic_salary',
        'house_allowance',
        'transport_allowance',
        'food_allowance',
        'medical_allowance',
        'other_earnings',
        'basic_salary_percentage',
        'house_allowance_percentage',
        'transport_allowance_percentage',
        'food_allowance_percentage',
        'medical_allowance_percentage',
        'other_earnings_percentage',
        'gross_salary',
    ];

    public function payScale()
    {
        return $this->belongsTo(\App\Models\Company\PayScale::class, 'pay_scale_id');
    }

    public function getEmployee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}

