<?php

namespace App\Models\Payroll;

use App\Models\BonusPlan;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Bonus extends Model
{
    protected $table = 'bonuses';

    protected $fillable = [
        'employee_id',
        'batch_id',
        'process_id',
        'amount',
    ];
    public function getEmployee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
    public function getBatch(){
        return $this->belongsTo(PayrollProcess::class, 'batch_id', 'id');
    }
    public function getBonus(){
        return $this->belongsTo(BonusPlan::class, 'bonus_id', 'id');
    }


}
