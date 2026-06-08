<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Userstamps;
use App\Traits\Auditable;

class Bank extends Model
{
    use Userstamps, Auditable;
    protected $table = 'banks';
    protected $fillable = ['name', 'short_name', 'bank_code', 'contact_no', 'contact_person', 'contact_person_no', 'status'];
}

