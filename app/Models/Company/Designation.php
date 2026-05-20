<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    protected $fillable = [
        'designation_level',
        'company_designation',
        'status',
    ];
}

