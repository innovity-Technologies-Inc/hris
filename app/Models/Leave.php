<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\OrganizationScoped;

class Leave extends Model
{
    use OrganizationScoped;
    protected $table = 'leaves';
    protected $fillable = ['plan_id', 'employee_id', 'leave_count', 'reason', 'from', 'to', 'status'];

    public function getEmployee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
    public function getPlan()
    {
        return $this->belongsTo(LeavePlan::class, 'plan_id', 'id');
    }
}
