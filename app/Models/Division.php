<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\OrganizationScoped;

class Division extends Model
{
    use OrganizationScoped;
    protected $fillable = [
        'name',
        'short_name',
        'remarks',
        'status',
        'company_id',
        'location_id',
    ];
    public function getCompany()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function getLocation()
    {
        return $this->belongsTo(CompanyLocation::class, 'location_id', 'id');
    }

    public function departments()
    {
        return $this->hasMany(Department::class, 'division_id');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class, 'division_id');
    }

    public function employeeOfficeInfos()
    {
        return $this->hasMany(EmployeeOfficeInfo::class, 'current_division_id');
    }

    public function organizationStructures()
    {
        return $this->hasMany(OrganizationStructure::class, 'division_id');
    }
}
