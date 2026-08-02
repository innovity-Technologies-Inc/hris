<?php

namespace App\Models\Movement;

use App\Models\Employee\Employee;
use App\Models\Plan\DAPlan;
use App\Models\Plan\TAPlan;
use Illuminate\Database\Eloquent\Model;
use App\Traits\OrganizationScoped;

use App\Traits\Userstamps;
use App\Traits\Auditable;
use Innovity\ApprovalEngine\Traits\Approvable;

class EmployeeMovement extends Model
{
    use Userstamps, Auditable;
    use OrganizationScoped;
    use Approvable;
    protected $fillable = [
        'employee_id', 'from_date', 'to_date',
        'distance', 'ta_plan_id', 'da_plan_id',
        'total_ta', 'total_da', 'total_days', 'total_allowance',
        'status', 'payment_status',
    ];

    public function getEmployee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function getTaPlan()
    {
        return $this->belongsTo(TAPlan::class, 'ta_plan_id', 'id');
    }

    public function getDaPlan()
    {
        return $this->belongsTo(DAPlan::class, 'da_plan_id', 'id');
    }

    public function details()
    {
        return $this->hasMany(EmployeeMovementDetail::class, 'employee_movement_id');
    }
}
