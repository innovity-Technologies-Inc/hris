<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyLocation extends Model
{
    protected $fillable = [
        'company_id',
        'name',
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

    public function divisions()
    {
        return $this->hasMany(Division::class, 'location_id');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class, 'branch_unit_id');
    }

    public function employeeOfficeInfos()
    {
        return $this->hasMany(EmployeeOfficeInfo::class, 'current_business_unit_id');
    }

    public function organizationStructures()
    {
        return $this->hasMany(OrganizationStructure::class, 'branch_unit_id');
    }
}
