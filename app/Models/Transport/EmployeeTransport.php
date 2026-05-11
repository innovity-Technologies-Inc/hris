<?php

namespace App\Models\Transport;

use App\Models\Branch;
use App\Models\Company;
use App\Models\Department;
use App\Models\Division;
use App\Models\Employee;
use App\Models\Section;
use App\Models\Transport\VehicleAllocation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Traits\OrganizationScoped;

class EmployeeTransport extends Model
{
    use OrganizationScoped;
    protected $table = 'employee_transports';

    protected $fillable = [
        'type',
        'company_id',
        'branch_id',
        'division_id',
        'department_id',
        'section_id',
        'service_name',
        'transport_type',
        'purpose',
        'start_date',
        'end_date',
        'pickup_time',
        'drop_time',
        'pickup_location',
        'drop_location',
        'route_details',
        'estimated_passengers',
        'special_requirements',
        'remarks',
        'status',
        'approved_by',
        'approved_at',
        'approval_remarks',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'approved_at' => 'datetime',
    ];

    /**
     * Get the company this transport service belongs to.
     */
    public function getCompany(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    /**
     * Get the branch this transport service belongs to.
     */
    public function getBranch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }

    /**
     * Get the division this transport service belongs to.
     */
    public function getDivision(): BelongsTo
    {
        return $this->belongsTo(Division::class, 'division_id', 'id');
    }

    /**
     * Get the department this transport service belongs to.
     */
    public function getDepartment(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    /**
     * Get the section this transport service belongs to.
     */
    public function getSection(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'section_id', 'id');
    }

    /**
     * Get the vehicle allocations for this transport service.
     */
    public function getAllocations(): HasMany
    {
        return $this->hasMany(VehicleAllocation::class, 'reference_id', 'id')
            ->where('reference_type', self::class);
    }

    /**
     * Get the status badge CSS class.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'Pending' => 'bg-warning text-dark',
            'Approved' => 'bg-success',
            'Rejected' => 'bg-danger',
            'Cancelled' => 'bg-secondary',
            default => 'bg-secondary',
        };
    }
}
