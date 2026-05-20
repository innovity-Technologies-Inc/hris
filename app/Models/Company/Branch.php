<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $table = 'branches';
    protected $fillable = ['name', 'bank_id', 'address', 'routing_no', 'swift_code', 'remarks', 'status'];

    public function getBank(){
        return $this->belongsTo(Bank::class, 'bank_id', 'id');
    }
}
