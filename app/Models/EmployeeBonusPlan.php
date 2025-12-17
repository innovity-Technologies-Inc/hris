<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeBonusPlan extends Model
{
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
