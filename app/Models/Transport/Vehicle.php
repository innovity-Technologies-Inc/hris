<?php

namespace App\Models\Transport;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Vehicle extends Model
{
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
     * Vehicle categories.
     */
    public const CATEGORIES = [
        'Car',
        'Bus',
        'Micro Bus',
        'Truck',
        'Bike',
        'Van',
        'Airplane',
        'Ship',
    ];

    /**
     * Fuel types.
     */
    public const FUEL_TYPES = [
        'Petrol',
        'Diesel',
        'CNG',
        'Electric',
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
     * Get allocation status label.
     */
    public function getAllocationStatusLabelAttribute(): string
    {
        return $this->is_allocated ? 'Allocated' : 'Free';
    }

    /**
     * Get allocation status badge class.
     */
    public function getAllocationStatusClassAttribute(): string
    {
        return $this->is_allocated ? 'bg-danger' : 'bg-success';
    }

    /**
     * Get display name (category + model).
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->vehicle_category . ' - ' . $this->model_number;
    }

    /**
     * Scope for active vehicles.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    /**
     * Scope for unallocated vehicles.
     */
    public function scopeUnallocated($query)
    {
        return $query->where('is_allocated', false);
    }

    /**
     * Scope for allocated vehicles.
     */
    public function scopeAllocated($query)
    {
        return $query->where('is_allocated', true);
    }
}
