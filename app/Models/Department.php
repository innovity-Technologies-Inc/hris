<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'department_name',
        'short_name',
        'division_id',
        'location_id',
        'company_id',
        'status',
    ];

    public function getDivision()
    {
        return $this->belongsTo(Division::class, 'division_id', 'id');
    }
    public function getCompany()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }
    public function getLocation()
    {
        return $this->belongsTo(CompanyLocation::class, 'location_id', 'id');
    }
}
