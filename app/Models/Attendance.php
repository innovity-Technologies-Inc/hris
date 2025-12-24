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
        'workstation'
    ];
    public function getEmployee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

}
