<?php

namespace App\Models\Payroll;

use App\Models\Company\Designation;
use App\Models\Employee\Employee;
use Illuminate\Database\Eloquent\Model;
use App\Traits\OrganizationScoped;

use App\Traits\Userstamps;
use App\Traits\Auditable;
use Innovity\ApprovalEngine\Traits\Approvable;

class Promotion extends Model
{
    use Userstamps, Auditable, Approvable;
    use OrganizationScoped;
    protected $fillable = [
        'employee_id',
        'previous_designation',
        'new_designation',
        'increment_base',
        'increment_method',
        'salary_increase_amount',
        'increment_amount_value',
        'previous_basic_salary',
        'previous_gross_salary',
        'new_gross_salary',
        'effective_from',
        'effective_to',
        'status',
        'is_adjustment'
    ];

    public function getEmployee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function getPreviousDesignation()
    {
        return $this->belongsTo(Designation::class, 'previous_designation', 'id');
    }

    public function getNewDesignation()
    {
        return $this->belongsTo(Designation::class, 'new_designation', 'id');
    }

}
