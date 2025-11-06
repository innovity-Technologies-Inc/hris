<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyLocation extends Model
{
    protected $fillable = [
        'company_id',
        'unit_name',
        'location_address',
        'state',
        'division',
        'city',
        'country',
        'status',
    ];

    public function getCompany()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

}
