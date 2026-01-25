<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeMovement extends Model
{
    protected $fillable = [
        'employee_id', 'from_date', 'to_date',
        'source_address', 'source_lat', 'source_lng',
        'destination_address', 'dest_lat', 'dest_lng',
        'distance', 'ta_plan_id', 'da_plan_id',
        'total_ta', 'total_da', 'total_days', 'total_allowance',
        'reason', 'status',
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
}
