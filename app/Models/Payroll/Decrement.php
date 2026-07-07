<?php

namespace App\Models\Payroll;

use App\Models\Employee\Employee;
use Illuminate\Database\Eloquent\Model;
use App\Traits\OrganizationScoped;

use App\Traits\Userstamps;
use App\Traits\Auditable;
use Innovity\ApprovalEngine\Traits\Approvable;

class Decrement extends Model
{
    use Userstamps, Auditable, Approvable;
    use OrganizationScoped;

    protected $fillable = [
        'employee_id',
        'pay_scale_id',
        'movement_type_id',
        'decrement_base',
        'decrement_method',
        'salary_decrease_amount',
        'decrement_amount_value',
        'previous_basic_salary',
        'previous_gross_salary',
        'new_gross_salary',
        'effective_from',
        'effective_to',
        'status',
        'is_adjustment'
    ];

    /**
     * Get the employee associated with this decrement.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getEmployee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function payScale()
    {
        return $this->belongsTo(\App\Models\Company\PayScale::class, 'pay_scale_id');
    }

    public function movementType()
    {
        return $this->belongsTo(\App\Models\Company\MovementType::class, 'movement_type_id');
    }
}
