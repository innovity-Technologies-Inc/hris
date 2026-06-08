<?php

namespace App\Models\Employee;

use App\Models\Plan\BonusPlan;
use Illuminate\Database\Eloquent\Model;

use App\Traits\Userstamps;
use App\Traits\Auditable;

class EmployeeBonusPlan extends Model
{
    use Userstamps, Auditable;
    protected $fillable = [
        'employee_id',
        'plan_id',
        'status',
    ];
    public function getEmployee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
    public function getPlan()
    {
        return $this->belongsTo(BonusPlan::class, 'plan_id', 'id');
    }
}

