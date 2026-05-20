<?php

namespace App\Models\Company;

use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeOfficeInfo;
use App\Models\Structure\OrganizationStructure;
use App\Traits\OrganizationScoped;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use OrganizationScoped;
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

    public function sections()
    {
        return $this->hasMany(Section::class, 'department_id');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class, 'department_id');
    }

    public function employeeOfficeInfos()
    {
        return $this->hasMany(EmployeeOfficeInfo::class, 'current_department_id');
    }

    public function organizationStructures()
    {
        return $this->hasMany(OrganizationStructure::class, 'department_id');
    }
}
