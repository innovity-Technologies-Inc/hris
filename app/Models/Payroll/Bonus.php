<?php

namespace App\Models\Payroll;

use App\Models\Plan\BonusPlan;
use App\Models\Employee\Employee;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

use App\Traits\OrganizationScoped;

use App\Traits\Userstamps;
use App\Traits\Auditable;

class Bonus extends Model
{
    use Userstamps, Auditable;
    use OrganizationScoped;
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
        return $this->belongsTo(PayrollProcess::class, 'process_id', 'id');
    }
    public function getBonus(){
        return $this->belongsTo(BonusPlan::class, 'bonus_id', 'id');
    }


}

