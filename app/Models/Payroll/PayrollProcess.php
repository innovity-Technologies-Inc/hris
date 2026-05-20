<?php

namespace App\Models\Payroll;

use App\Models\Plan\BonusPlan;
use App\Models\Company\Company;
use App\Models\Company\CompanyLocation;
use App\Models\Company\Department;
use App\Models\Company\Division;
use App\Models\Company\Section;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

use App\Traits\OrganizationScoped;

class PayrollProcess extends Model
{
    use OrganizationScoped;
    protected $table = 'payroll_process';

    protected $fillable = [
        'batch_id',
        'company_id',
        'branch_id',
        'division_id',
        'department_id',
        'section_id',
        'salary_month',
        'type',
        'bonus_plan_ids',
        'status',
        'approval_status',
        'generated_by',
        'approved_by',
        'total_amount',
        'total_employee',
    ];

    protected $casts = [
        'bonus_plan_ids' => 'array',
    ];

    public function bonusPlan()
    {
        return $this->belongsTo(BonusPlan::class, 'bonus_plan_ids');
    }
    public function getCompany()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }
    public function getBranch(){
        return $this->belongsTo(CompanyLocation::class, 'branch_id', 'id');
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
    public function generatedBy(){
        return $this->belongsTo(User::class, 'generated_by', 'id');
    }
    public function approvedBy(){
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }
}

