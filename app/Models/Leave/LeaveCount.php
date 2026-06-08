<?php

namespace App\Models\Leave;

use App\Models\Employee\Employee;
use App\Models\Plan\LeavePlan;
use Illuminate\Database\Eloquent\Model;

use App\Traits\Userstamps;
use App\Traits\Auditable;

class LeaveCount extends Model
{
    use Userstamps, Auditable;
    protected $table = 'leaves_count';
    protected $fillable = ['plan_id', 'employee_id', 'leave_taken'];

    public function getEmployee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
    public function getPlan()
    {
        return $this->belongsTo(LeavePlan::class, 'plan_id', 'id');
    }
}



