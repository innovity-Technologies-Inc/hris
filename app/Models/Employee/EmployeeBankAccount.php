<?php

namespace App\Models\Employee;

use App\Models\Company\Bank;
use App\Models\Company\Branch;
use Illuminate\Database\Eloquent\Model;
use App\Traits\OrganizationScoped;

use App\Traits\Userstamps;
use App\Traits\Auditable;

class EmployeeBankAccount extends Model
{
    use Userstamps, Auditable;
    use OrganizationScoped;
    protected $table = 'employee_bank_accounts';

    protected $fillable = [
        'employee_id',
        'bank_id',
        'branch_id',
        'account_holder_name',
        'account_number',
        'status',
        'remarks',
    ];
    public function getEmployee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
    public function getBank()
    {
        return $this->belongsTo(Bank::class, 'bank_id', 'id');
    }
    public function getBranch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }
}

