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
use App\Traits\OrganizationScoped;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Traits\Userstamps;
use App\Traits\Auditable;
use Innovity\ApprovalEngine\Traits\Approvable;

class Transfer extends Model
{
    use Userstamps, Auditable, Approvable;
    use HasFactory, OrganizationScoped;

    protected $fillable = [
        'employee_id',
        'movement_type_id',
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
        'effective_from',
        'effective_to',
        'is_adjustment',
        'created_by',
        'completed_by',
        'completed_at',
    ];

    protected $casts = [
        'effective_from' => 'date',
        'effective_to' => 'date',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function movementType(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Company\MovementType::class, 'movement_type_id');
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

    public function attachments(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(\App\Models\Payroll\MovementAttachment::class, 'attachable');
    }
}
