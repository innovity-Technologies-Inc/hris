<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $table = 'banks';
    protected $fillable = ['name', 'short_name', 'bank_code', 'contact_no', 'contact_person', 'contact_person_no', 'status'];
}

