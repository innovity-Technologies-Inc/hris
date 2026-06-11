<?php

namespace App\Models\Attendance;

use Illuminate\Database\Eloquent\Model;

use App\Traits\OrganizationScoped;
use App\Models\Employee\Employee;
use App\Models\Plan\ShiftPlan;
use App\Models\Plan\OTPlan;
use App\Models\Plan\OffDayPlan;

use App\Traits\Userstamps;
use App\Traits\Auditable;

class Attendance extends Model
{
    use Userstamps, Auditable;
    use OrganizationScoped;
    protected $table = 'attendance';
    protected $fillable = [
        'employee_id',
        'in_time',
        'in_status',
        'out_time',
        'out_status',
        'shift_type',
        'working_time',
        'late_count',
        'early_out_count',
        'overtime',
        'attendance_status',
        'workstation',
        'shift_id',
        'ot_id',
        'offday_id'
    ];
    public function getEmployee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
    public function getShift()
    {
        return $this->belongsTo(ShiftPlan::class, 'shift_id', 'id');
    }
    public function getOT()
    {
        return $this->belongsTo(OTPlan::class, 'ot_id', 'id');
    }
    public function getOffDay()
    {
        return $this->belongsTo(OffDayPlan::class, 'offday_id', 'id');
    }

}

