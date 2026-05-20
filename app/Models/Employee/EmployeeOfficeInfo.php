<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\OrganizationScoped;

class EmployeeOfficeInfo extends Model
{
    use HasFactory, OrganizationScoped;

    protected $fillable = [
        'employee_id', 'emp_type', 'grade_id', 'hr_file_no', 'tofsil_id',
        'file_note', 'joining_company_id', 'joining_business_unit_id', 'joining_division_id',
        'joining_department_id',  'joining_section_id', 'joining_designation_id', 'date_of_join',
        'current_company_id', 'current_business_unit_id', 'current_division_id', 'current_department_id',
         'current_section_id', 'current_designation_id', 'orientation_required', 'orientation_from',
        'orientation_to', 'orientation_type', 'orientation_days', 'confirmation_date', 'probation_duration',
        'next_promotion_date', 'promotion_cycle', 'increment_cycle', 'weekends', 'alternate_off_day',
        'ot_allowed', 'pf_eligible', 'salary_type', 'transport_eligible', 'can_apply_loan',
        'pf_effective_date', 'can_apply_advance', 'gratuity_eligible',
    ];

    protected $casts = [
        'weekends' => 'array',
        'alternate_off_day' => 'array',
    ];

    public function getJoiningDesignation()
    {
        return $this->belongsTo(Designation::class, 'joining_designation_id', 'id');
    }
    public function getCurrentDesignation()
    {
        return $this->belongsTo(Designation::class, 'current_designation_id', 'id');
    }

    public function getJoiningSection()
    {
        return $this->belongsTo(Section::class, 'joining_section_id', 'id');
    }
    public function getCurrentSection()
    {
        return $this->belongsTo(Section::class, 'current_section_id', 'id');
    }

    public function getJoiningDepartment()
    {
        return $this->belongsTo(Department::class, 'joining_department_id', 'id');
    }
    public function getCurrentDepartment()
    {
        return $this->belongsTo(Department::class, 'current_department_id', 'id');
    }

    public function getJoiningDivision()
    {
        return $this->belongsTo(Division::class, 'joining_division_id', 'id');
    }
    public function getCurrentDivision()
    {
        return $this->belongsTo(Division::class, 'current_division_id', 'id');
    }

    public function getJoiningCompany()
    {
        return $this->belongsTo(Company::class, 'joining_company_id', 'id');
    }
    public function getCurrentCompany()
    {
        return $this->belongsTo(Company::class, 'current_company_id', 'id');
    }
    public function getJoiningBusinessUnit()
    {
        return $this->belongsTo(CompanyLocation::class, 'joining_business_unit_id', 'id');
    }
    public function getCurrentBusinessUnit()
    {
        return $this->belongsTo(CompanyLocation::class, 'current_business_unit_id', 'id');
    }
    public function getEmployee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
    public function getGrade(){
        return $this->belongsTo(SalaryGrade::class, 'grade_id', 'id');
    }
    public function getTofsil(){
        return $this->belongsTo(Tofsil::class, 'tofsil_id', 'id');
    }
}

