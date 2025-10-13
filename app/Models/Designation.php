<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    protected $fillable = [
        'company_id',
        'location_id',
        'division_id',
        'designation_level',
        'company_designation',
        'status',
    ];
    public function getCompany()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }
    public function getLocation()
    {
        return $this->belongsTo(CompanyLocation::class, 'location_id', 'id');
    }
    public function getDivision()
    {
        return $this->belongsTo(Division::class, 'division_id', 'id');
    }
}
