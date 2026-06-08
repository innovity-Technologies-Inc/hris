<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Userstamps;
use App\Traits\Auditable;

class BankAccount extends Model
{
    use Userstamps, Auditable;
    protected $table = 'bank_accounts';
    protected $fillable = ['bank_id', 'branch_id', 'account_no', 'holder_name', 'account_type', 'contact_person',
        'contact_person_no', 'email', 'status'];
    public function getBank(){
        return $this->belongsTo(Bank::class, 'bank_id', 'id');
    }
    public function getBranch(){
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }
}

