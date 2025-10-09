<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyType extends Model
{
    protected $table = 'company_types';
    protected $fillable = ['name', 'short_name', 'status'];
}
