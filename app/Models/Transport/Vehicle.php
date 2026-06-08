<?php

namespace App\Models\Transport;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

use App\Traits\Userstamps;
use App\Traits\Auditable;

class Vehicle extends Model
{
    use Userstamps, Auditable;
    protected $table = 'vehicles';

    protected $fillable = [
        'vehicle_category',
        'model_number',
        'manufacture_year',
        'body_type',
        'fuel_type',
        'engine_capacity',
        'seating_capacity',
        'color',
        'mileage',
        'license_number',
        'license_document',
        'vehicle_image',
        'purchase_type',
        'purchase_date',
        'purchase_price',
        'purchase_document',
        'ownership_type',
        'third_party_name',
        'is_allocated',
        'allocation_purpose',
        'allocation_type',
        'status',
    ];

    protected $casts = [
        'is_allocated' => 'boolean',
        'purchase_date' => 'date',
    ];

    /**
     * Get all allocations for this vehicle.
     */
    public function allocations(): HasMany
    {
        return $this->hasMany(VehicleAllocation::class, 'vehicle_id', 'id');
    }

    /**
     * Get the current active allocation.
     */
    public function activeAllocation(): HasOne
    {
        return $this->hasOne(VehicleAllocation::class, 'vehicle_id', 'id')
            ->where('status', 'Active')
            ->latest();
    }

    /**
     * Get the driver assignment for this vehicle.
     */
    public function driverAssignment(): HasOne
    {
        return $this->hasOne(VehicleDriver::class, 'vehicle_id', 'id')
            ->where('status', 'active');
    }

    /**
     * Get all driver assignments history.
     */
    public function driverHistory(): HasMany
    {
        return $this->hasMany(VehicleDriver::class, 'vehicle_id', 'id');
    }

    /**
     * Get vehicle requisitions assigned to this vehicle.
     */
    public function requisitions(): HasMany
    {
        return $this->hasMany(VehicleRequisition::class, 'assigned_vehicle_id', 'id');
    }

    /**
     * Check if vehicle is available for allocation.
     */
    public function isAvailable(): bool
    {
        return $this->status === 'Active' && !$this->is_allocated;
    }

    /**
     * Scope for allocated vehicles.
     */
    public function scopeAllocated($query)
    {
        return $query->where('is_allocated', true);
    }
}

