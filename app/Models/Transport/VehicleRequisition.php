<?php

namespace App\Models\Transport;

use App\Models\Department;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleRequisition extends Model
{
    protected $table = 'vehicle_requisitions';

    protected $fillable = [
        'employee_id',
        'department',
        'trip_type',
        'trip_mode',
        'purpose_of_travel',
        'start_date_time',
        'end_date_time',
        'pickup_location',
        'destination',
        'route',
        'no_of_passengers',
        'vehicle_type_required',
        'driver_required',
        'self_drive',
        'special_requirement',
        'preferred_vehicle',
        'approval_status',
        'approval_remarks',
        'assigned_vehicle_id',
        'dispatch_time',
        'expected_return_time',
    ];

    protected $casts = [
        'start_date_time' => 'datetime',
        'end_date_time' => 'datetime',
    ];

    /**
     * Get the employee who made the requisition.
     */
    public function getEmployee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    /**
     * Get the department associated with the requisition.
     */
    public function getDepartment(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department', 'id');
    }

    /**
     * Get the assigned vehicle for this requisition.
     */
    public function getAssignedVehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'assigned_vehicle_id', 'id');
    }

    /**
     * Get the driver assigned to the vehicle (if any).
     * This is an indirect relationship through the vehicle.
     */
    public function getAssignedDriver()
    {
        if (!$this->assigned_vehicle_id) {
            return null;
        }

        return VehicleDriver::where('vehicle_id', $this->assigned_vehicle_id)
            ->where('status', 'active')
            ->with('getDriver')
            ->first();
    }

    /**
     * Get approval status badge class.
     */
    public function getApprovalStatusBadgeAttribute(): string
    {
        return match ($this->approval_status) {
            'Approved' => 'bg-success',
            'Rejected' => 'bg-danger',
            default => 'bg-warning text-dark',
        };
    }
}
