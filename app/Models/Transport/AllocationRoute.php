<?php

namespace App\Models\Transport;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Traits\Userstamps;
use App\Traits\Auditable;

class AllocationRoute extends Model
{
    use Userstamps, Auditable;
    protected $table = 'allocation_routes';

    protected $fillable = [
        'vehicle_allocation_id',
        'route_name',
        'start_point',
        'end_point',
        'waypoints',
        'distance_km',
        'estimated_duration_minutes',
        'departure_time',
        'arrival_time',
        'route_date',
        'route_description',
        'special_instructions',
        'status',
    ];

    protected $casts = [
        'waypoints' => 'array',
        'route_date' => 'date',
        'distance_km' => 'decimal:2',
    ];

    /**
     * Status options.
     */
    public const STATUSES = [
        'Active',
        'Completed',
        'Cancelled',
    ];

    /**
     * Get the vehicle allocation this route belongs to.
     */
    public function allocation(): BelongsTo
    {
        return $this->belongsTo(VehicleAllocation::class, 'vehicle_allocation_id', 'id');
    }

    /**
     * Get status badge class.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'Active' => 'bg-success',
            'Completed' => 'bg-info',
            'Cancelled' => 'bg-danger',
            default => 'bg-secondary',
        };
    }

    /**
     * Format estimated duration.
     */
    public function getFormattedDurationAttribute(): string
    {
        if (!$this->estimated_duration_minutes) {
            return '-';
        }

        $hours = floor($this->estimated_duration_minutes / 60);
        $minutes = $this->estimated_duration_minutes % 60;

        if ($hours > 0) {
            return $hours . 'h ' . $minutes . 'm';
        }

        return $minutes . ' min';
    }

    /**
     * Scope for active routes.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    /**
     * Scope for today's routes.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('route_date', now()->toDateString());
    }
}

