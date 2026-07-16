<?php

namespace App\Models\Transport;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\Userstamps;
use App\Traits\Auditable;

class RouteMap extends Model
{
    use Userstamps, Auditable;

    protected $table = 'route_maps';

    protected $fillable = [
        'route_name',
        'start_point',
        'end_point',
        'via_points',
        'route_details',
        'status',
    ];

    /**
     * Get employee transports associated with this route map.
     */
    public function employeeTransports(): HasMany
    {
        return $this->hasMany(EmployeeTransport::class, 'route_map_id', 'id');
    }

    /**
     * Get status badge CSS class.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return $this->status === 'Active' ? 'bg-success' : 'bg-danger';
    }
}
