<?php

namespace App\Models\Transfer;

use App\Models\Employee\Employee;
use App\Models\Company\Company;
use App\Models\Company\CompanyLocation;
use App\Models\Company\Division;
use App\Models\Company\Department;
use App\Models\Company\Section;
use App\Models\Company\Designation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'current_company_id',
        'current_business_unit_id',
        'current_division_id',
        'current_department_id',
        'current_section_id',
        'current_designation_id',
        'requested_company_id',
        'requested_business_unit_id',
        'requested_division_id',
        'requested_department_id',
        'requested_section_id',
        'requested_designation_id',
        'status',
        'approval_count_required',
        'current_approval_count',
        'remarks',
        'created_by',
        'completed_by',
        'completed_at',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function currentCompany(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'current_company_id');
    }

    public function currentBusinessUnit(): BelongsTo
    {
        return $this->belongsTo(CompanyLocation::class, 'current_business_unit_id');
    }

    public function currentDivision(): BelongsTo
    {
        return $this->belongsTo(Division::class, 'current_division_id');
    }

    public function currentDepartment(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'current_department_id');
    }

    public function currentSection(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'current_section_id');
    }

    public function currentDesignation(): BelongsTo
    {
        return $this->belongsTo(Designation::class, 'current_designation_id');
    }

    public function requestedCompany(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'requested_company_id');
    }

    public function requestedBusinessUnit(): BelongsTo
    {
        return $this->belongsTo(CompanyLocation::class, 'requested_business_unit_id');
    }

    public function requestedDivision(): BelongsTo
    {
        return $this->belongsTo(Division::class, 'requested_division_id');
    }

    public function requestedDepartment(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'requested_department_id');
    }

    public function requestedSection(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'requested_section_id');
    }

    public function requestedDesignation(): BelongsTo
    {
        return $this->belongsTo(Designation::class, 'requested_designation_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function completer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(TransferApproval::class);
    }
}
