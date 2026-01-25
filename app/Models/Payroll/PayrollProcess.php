<?php

namespace App\Models\Payroll;

use App\Models\Company;
use App\Models\CompanyLocation;
use App\Models\Department;
use App\Models\Division;
use App\Models\Section;
use Illuminate\Database\Eloquent\Model;

class PayrollProcess extends Model
{
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
        'status',
        'approval_status',
        'generated_by',
        'approved_by',
        'total_amount',
        'total_employee',
    ];
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
}
