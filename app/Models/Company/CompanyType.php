<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Userstamps;
use App\Traits\Auditable;

class CompanyType extends Model
{
    use Userstamps, Auditable;
    protected $table = 'company_types';
    protected $fillable = ['name', 'short_name', 'status'];
}

