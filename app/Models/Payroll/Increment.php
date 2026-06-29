<?php

namespace App\Models\Payroll;

use App\Models\Company\Designation;
use App\Models\Employee\Employee;
use Illuminate\Database\Eloquent\Model;
use App\Traits\OrganizationScoped;

use App\Traits\Userstamps;
use App\Traits\Auditable;
use Innovity\ApprovalEngine\Traits\Approvable;

class Increment extends Model
{
    use Userstamps, Auditable, Approvable;
    use OrganizationScoped;
    protected $fillable = [
        'employee_id',
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
