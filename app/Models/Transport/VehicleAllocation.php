<?php

namespace App\Models\Transport;

use App\Models\Employee\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class VehicleAllocation extends Model
{
    protected $table = 'vehicle_allocations';

    protected $fillable = [
        'vehicle_id',
        'name',
        'allocation_type',
        'allocation_purpose',
        'description',
        'start_date',
        'end_date',
        'allocated_to',
        'reference_type',
        'reference_id',
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
     * Get the vehicle associated with this allocation.
     */
    public function getVehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id', 'id');
    }

    /**
     * Get routes for this allocation.
     */
    public function getRoutes(): HasMany
    {
        return $this->hasMany(AllocationRoute::class, 'vehicle_allocation_id', 'id');
    }

    /**
     * Get the referenced vehicle requisition.
     */
    public function getVehicleRequisition(): BelongsTo
    {
        return $this->belongsTo(VehicleRequisition::class, 'reference_id', 'id');
    }

    /**
     * Get the referenced employee transport.
     */
    public function getEmployeeTransport(): BelongsTo
    {
        return $this->belongsTo(EmployeeTransport::class, 'reference_id', 'id');
    }

    /**
     * Check if allocation has ended.
     */
    public function hasEnded(): bool
    {
        return $this->end_date && $this->end_date->isPast();
    }

    /**
     * Get remaining days.
     */
    public function getRemainingDaysAttribute(): ?int
    {
        if (!$this->end_date || $this->end_date->isPast()) {
            return null;
        }

        return now()->diffInDays($this->end_date, false);
    }

    /**
     * Scope for active allocations.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }
}

