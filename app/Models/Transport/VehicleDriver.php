<?php

namespace App\Models\Transport;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleDriver extends Model
{
    protected $table = 'vehicle_drivers';

    protected $fillable = [
        'vehicle_id',
        'driver_id',
        'start_date',
        'end_date',
        'status',
    ];

    /**
     * Get the vehicle associated with this assignment.
     */
    public function getVehicle(): BelongsTo
    {
        return $this->belongsTo(VehicleAcquisition::class, 'vehicle_id', 'id');
    }

    /**
     * Get the driver (employee) associated with this assignment.
     */
    public function getDriver(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'driver_id', 'id');
    }

}
