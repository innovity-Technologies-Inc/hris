<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
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
        'work_type',
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
