<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationStructure extends Model
{
    use HasFactory;

    protected $table = 'organization_structure';

    protected $fillable = [
        'name',
        'type',
        'group_id',
        'company_id',
        'branch_unit_id',
        'division_id',
        'department_id',
        'section_id',
        'designation',
        'contact_no',
        'email',
        'address',
        'photo_path',
        'status',
    ];

    // Relationships
    public function getGroup()
    {
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }

    public function getCompany()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function getBranchUnit()
    {
        return $this->belongsTo(CompanyLocation::class, 'branch_unit_id', 'id');
    }

    public function getDivision()
    {
        return $this->belongsTo(Division::class, 'division_id', 'id');
    }

    public function getDepartment()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function getSection()
    {
        return $this->belongsTo(Section::class, 'section_id', 'id');
    }
}

