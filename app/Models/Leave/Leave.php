<?php

namespace App\Models\Leave;

use App\Models\Employee\Employee;
use App\Models\Plan\LeavePlan;
use Illuminate\Database\Eloquent\Model;
use App\Traits\OrganizationScoped;
use Innovity\ApprovalEngine\Traits\Approvable;

use App\Traits\Userstamps;
use App\Traits\Auditable;

class Leave extends Model
{
    use Userstamps, Auditable;
    use OrganizationScoped;
    use Approvable;
    protected $table = 'leaves';
    protected $fillable = ['plan_id', 'employee_id', 'leave_category_type', 'leave_count', 'day_type', 'reason', 'from', 'to', 'status'];

    public function getEmployee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
    public function getPlan()
    {
        return $this->belongsTo(LeavePlan::class, 'plan_id', 'id');
    }
}
