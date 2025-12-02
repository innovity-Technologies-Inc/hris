<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveCount extends Model
{
    protected $table = 'leaves_count';
    protected $fillable = ['plan_id', 'employee_id', 'taken_leave'];

    public function getEmployee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
    public function getPlan()
    {
        return $this->belongsTo(OffDayPlan::class, 'plan_id', 'id');
    }
}


