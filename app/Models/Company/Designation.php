<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Userstamps;
use App\Traits\Auditable;

class Designation extends Model
{
    use Userstamps, Auditable;
    protected $fillable = [
        'designation_level',
        'company_designation',
        'status',
    ];
}

