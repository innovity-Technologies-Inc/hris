<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'attandances';
    protected $fillable = ['employee_id', 'in_time', 'in_status', 'out_time', 'out_status',
        'date', 'working_hours', 'late_count', 'early_out_count', 'overtime_hours', 'status'
    ];
    public function getEmployee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

}
