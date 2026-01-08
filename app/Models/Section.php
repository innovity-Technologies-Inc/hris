<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = [
        'name',
        'short_name',
        'department_id',
        'division_id',
        'location_id',
        'company_id',
        'status',
    ];

    public function getDepartment()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }
    public function getDivision()
    {
        return $this->belongsTo(Division::class, 'division_id', 'id');
    }
    public function getLocation()
    {
        return $this->belongsTo(CompanyLocation::class, 'location_id', 'id');
    }
    public function getCompany()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class, 'section_id');
    }

    public function employeeOfficeInfos()
    {
        return $this->hasMany(EmployeeOfficeInfo::class, 'current_section_id');
    }

    public function organizationStructures()
    {
        return $this->hasMany(OrganizationStructure::class, 'section_id');
    }
}
