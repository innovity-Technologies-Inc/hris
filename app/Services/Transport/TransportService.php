<?php

namespace App\Services\Transport;

use App\Models\Transport\Vehicle;
use App\Models\Transport\VehicleAllocation;
use App\Models\Transport\VehicleRequisition;
use App\Models\Transport\EmployeeTransport;
use App\Models\Transport\VehicleDriver;
use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeOfficeInfo;
use App\Models\Company\Designation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class TransportService
{
    /**
     * Find vehicles by status.
     *
     * @param string $status Active|Inactive
     * @return Collection
     */
    public function findVehiclesByStatus(string $status): Collection
    {
        return Vehicle::where('status', $status)
            ->orderBy('model_number')
            ->get();
    }

    /**
     * Find vehicles by allocation type.
     *
     * @param string $type trip|transport
     * @return Collection
     */
    public function findVehiclesByAllocationType(string $type): Collection
    {
        return Vehicle::where('allocation_type', $type)
            ->where('status', 'Active')
            ->orderBy('model_number')
            ->get();
    }

    /**
     * Find vehicles by allocation status.
     *
     * @param bool $isAllocated
     * @return Collection
     */
    public function findVehiclesByIsAllocated(bool $isAllocated): Collection
    {
        return Vehicle::where('is_allocated', $isAllocated)
            ->where('status', 'Active')
            ->orderBy('model_number')
            ->get();
    }

    /**
     * Get all active vehicles.
     *
     * @return Collection
     */
    public function getActiveVehicles(): Collection
    {
        return Vehicle::where('status', 'Active')
            ->orderBy('model_number')
            ->get();
    }

    /**
     * Get all unallocated (free) vehicles that are active.
     *
     * @return Collection
     */
    public function getUnallocatedVehicles(): Collection
    {
        return Vehicle::where('status', 'Active')
            ->where('is_allocated', 0)
            ->orderBy('vehicle_category')
            ->orderBy('model_number')
            ->get();
    }

    /**
     * Get allocated vehicles with remaining time until allocation ends.
     *
     * @return Collection
     */
    public function getAllocatedVehiclesWithRemainingTime(): Collection
    {
        $now = Carbon::now();

        return Vehicle::where('is_allocated', true)
            ->where('status', 'Active')
            ->with(['activeAllocation' => function ($query) use ($now) {
                $query->where('status', 'Active')
                    ->where('end_date', '>=', $now);
            }])
            ->get()
            ->map(function ($vehicle) use ($now) {
                $allocation = $vehicle->activeAllocation;
                if ($allocation && $allocation->end_date) {
                    $endDate = Carbon::parse($allocation->end_date);
                    $vehicle->remaining_days = $now->diffInDays($endDate, false);
                    $vehicle->remaining_hours = $now->diffInHours($endDate, false) % 24;
                    $vehicle->allocation_end_date = $endDate->format('Y-m-d');
                } else {
                    $vehicle->remaining_days = null;
                    $vehicle->remaining_hours = null;
                    $vehicle->allocation_end_date = null;
                }
                return $vehicle;
            });
    }

    /**
     * Get vehicles suitable for new allocation (active and unallocated).
     *
     * @return Collection
     */
    public function getVehiclesForAllocation(): Collection
    {
        return $this->getUnallocatedVehicles();
    }

    /**
     * Get distinct vehicle categories from unallocated active vehicles.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAvailableVehicleCategories(): \Illuminate\Support\Collection
    {
        return Vehicle::where('status', 'Active')
            ->where('is_allocated', 0)
            ->distinct()
            ->pluck('vehicle_category')
            ->filter()
            ->values();
    }

    /**
     * Get vehicles with their current allocation status and details.
     *
     * @return Collection
     */
    public function getVehiclesWithAllocationStatus(): Collection
    {
        $now = Carbon::now();

        return Vehicle::where('status', 'Active')
            ->with(['activeAllocation' => function ($query) use ($now) {
                $query->where('status', 'Active')
                    ->where('end_date', '>=', $now);
            }])
            ->orderBy('is_allocated')
            ->orderBy('model_number')
            ->get()
            ->map(function ($vehicle) use ($now) {
                $allocation = $vehicle->activeAllocation;

                // Calculate allocation status inline
                $vehicle->allocation_status_label = $vehicle->is_allocated ? 'Allocated' : 'Free';
                $vehicle->allocation_status_class = $vehicle->is_allocated ? 'bg-danger' : 'bg-success';

                if ($allocation && $allocation->end_date) {
                    $endDate = Carbon::parse($allocation->end_date);
                    $vehicle->remaining_time = $this->formatRemainingTime($now, $endDate);
                    $vehicle->current_allocation = $allocation;
                } else {
                    $vehicle->remaining_time = '-';
                    $vehicle->current_allocation = null;
                }

                return $vehicle;
            });
    }

    /**
     * Format remaining time in a human-readable format.
     *
     * @param Carbon $now
     * @param Carbon $endDate
     * @return string
     */
    protected function formatRemainingTime(Carbon $now, Carbon $endDate): string
    {
        if ($endDate->isPast()) {
            return 'Expired';
        }

        $diff = $now->diff($endDate);

        if ($diff->days > 0) {
            return $diff->days . 'd ' . $diff->h . 'h';
        }

        if ($diff->h > 0) {
            return $diff->h . 'h ' . $diff->i . 'm';
        }

        return $diff->i . ' minutes';
    }

    /**
     * Get pending vehicle requisitions (Trip applications).
     *
     * @return Collection
     */
    public function getPendingTripRequisitions(): Collection
    {
        return VehicleRequisition::where('approval_status', 'Pending')
            ->whereNull('assigned_vehicle_id')
            ->with(['getEmployee', 'getDepartment'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get approved vehicle requisitions awaiting allocation.
     *
     * @return Collection
     */
    public function getApprovedUnallocatedRequisitions(): Collection
    {
        return VehicleRequisition::where('approval_status', 'Approved')
            ->whereNull('assigned_vehicle_id')
            ->with(['getEmployee', 'getDepartment'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get pending employee transport services.
     *
     * @return Collection
     */
    public function getPendingEmployeeTransports(): Collection
    {
        return EmployeeTransport::where('status', 'Pending')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get pending vehicle requisitions.
     *
     * @return Collection
     */
    public function getPendingVehicleRequisitions(): Collection
    {
        return VehicleRequisition::with(['getEmployee', 'getDepartment'])
            ->where('approval_status', 'Pending')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get approved employee transport services awaiting allocation.
     *
     * @return Collection
     */
    public function getApprovedUnallocatedEmployeeTransports(): Collection
    {
        return EmployeeTransport::where('status', 'Approved')
            ->whereDoesntHave('allocations', function ($query) {
                $query->where('status', 'Active');
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get vehicles by category.
     *
     * @param string $category
     * @return Collection
     */
    public function getVehiclesByCategory(string $category): Collection
    {
        return Vehicle::where('vehicle_category', $category)
            ->where('status', 'Active')
            ->orderBy('model_number')
            ->get();
    }

    /**
     * Get vehicles by seating capacity (minimum).
     *
     * @param int $minSeats
     * @return Collection
     */
    public function getVehiclesByMinimumSeating(int $minSeats): Collection
    {
        return Vehicle::where('seating_capacity', '>=', $minSeats)
            ->where('status', 'Active')
            ->where('is_allocated', false)
            ->orderBy('seating_capacity')
            ->get();
    }

    /**
     * Allocate vehicles to a requisition or transport application.
     *
     * @param array $vehicleIds
     * @param string $allocationType trip|transport
     * @param int $referenceId The ID of the requisition or employee transport
     * @param array $allocationData Additional allocation data
     * @return array Created allocations
     */
    public function allocateVehicles(array $vehicleIds, string $allocationType, int $referenceId, array $allocationData): array
    {
        $allocations = [];

        DB::beginTransaction();
        try {
            foreach ($vehicleIds as $vehicleId) {
                $vehicle = Vehicle::findOrFail($vehicleId);

                // Create allocation record
                $allocation = VehicleAllocation::create([
                    'vehicle_id' => $vehicleId,
                    'name' => $allocationData['name'] ?? 'Allocation #' . time(),
                    'allocation_type' => $allocationType,
                    'allocation_purpose' => $allocationData['purpose'] ?? null,
                    'description' => $allocationData['description'] ?? null,
                    'start_date' => $allocationData['start_date'] ?? now(),
                    'end_date' => $allocationData['end_date'] ?? null,
                    'allocated_to' => $allocationData['allocated_to'] ?? null,
                    'reference_type' => $allocationType === 'trip' ? 'vehicle_requisition' : 'employee_transport',
                    'reference_id' => $referenceId,
                    'status' => 'Active',
                ]);

                // Update vehicle allocation status
                $vehicle->update([
                    'is_allocated' => true,
                    'allocation_type' => $allocationType,
                    'allocation_purpose' => $allocationData['purpose'] ?? null,
                ]);

                $allocations[] = $allocation;
            }

            DB::commit();
            return $allocations;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Release vehicle from allocation.
     *
     * @param int $vehicleId
     * @return bool
     */
    public function releaseVehicle(int $vehicleId): bool
    {
        $vehicle = Vehicle::findOrFail($vehicleId);

        // Deactivate current allocation
        VehicleAllocation::where('vehicle_id', $vehicleId)
            ->where('status', 'Active')
            ->update(['status' => 'Inactive']);

        // Update vehicle - set is_allocated to false
        return $vehicle->update([
            'is_allocated' => false,
            'allocation_type' => null,
            'allocation_purpose' => null,
        ]);
    }

    /**
     * Get allocation history for a vehicle.
     *
     * @param int $vehicleId
     * @return Collection
     */
    public function getVehicleAllocationHistory(int $vehicleId): Collection
    {
        return VehicleAllocation::where('vehicle_id', $vehicleId)
            ->with('vehicle')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get all allocation history.
     *
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getAllAllocationHistory(int $perPage = 15)
    {
        return VehicleAllocation::with(['getVehicle', 'getRoutes'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Check if vehicles are available for a given date range.
     *
     * @param array $vehicleIds
     * @param string $startDate
     * @param string $endDate
     * @return array Array of available vehicle IDs
     */
    public function checkVehicleAvailability(array $vehicleIds, string $startDate, string $endDate): array
    {
        $unavailableVehicles = VehicleAllocation::whereIn('vehicle_id', $vehicleIds)
            ->where('status', 'Active')
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($q) use ($startDate, $endDate) {
                        $q->where('start_date', '<=', $startDate)
                          ->where('end_date', '>=', $endDate);
                    });
            })
            ->pluck('vehicle_id')
            ->toArray();

        return array_diff($vehicleIds, $unavailableVehicles);
    }

    /**
     * Get dashboard statistics.
     *
     * @return array
     */
    public function getDashboardStats(): array
    {
        return [
            'total_vehicles' => Vehicle::where('status', 'Active')->count(),
            'allocated_vehicles' => Vehicle::where('status', 'Active')->where('is_allocated', true)->count(),
            'available_vehicles' => Vehicle::where('status', 'Active')->where('is_allocated', false)->count(),
            'pending_requisitions' => VehicleRequisition::where('approval_status', 'Pending')->count(),
            'pending_transports' => EmployeeTransport::where('status', 'Pending')->count(),
            'pending_requests' => VehicleRequisition::where('approval_status', 'Pending')->count() + EmployeeTransport::where('status', 'Pending')->count(),
            'active_allocations' => VehicleAllocation::where('status', 'Active')->count(),
        ];
    }

    /**
     * Get available vehicles for assignment.
     *
     * @param int|null $includeVehicleId
     * @return Collection
     */
    public function getAvailableVehicles($includeVehicleId = null): Collection
    {
        $assignedVehicleIds = VehicleDriver::where('status', 'active')
            ->pluck('vehicle_id');

        $query = Vehicle::where('status', 'Active')
            ->whereNotIn('id', $assignedVehicleIds);

        if ($includeVehicleId) {
            $query->orWhere('id', $includeVehicleId);
        }

        return $query->orderBy('model_number')->get();
    }

    /**
     * Get eligible drivers (employees with 'Driver' designation).
     * Excludes drivers that are already assigned with active status.
     *
     * @return Collection
     */
    public function getEligibleDrivers(): Collection
    {
        // Get employee IDs already assigned to vehicles with active status
        $assignedDriverIds = VehicleDriver::where('status', 'active')
            ->pluck('driver_id');

        $driverDesignationIds = Designation::where('company_designation', 'like', '%Driver%')->pluck('id');

        if ($driverDesignationIds->isEmpty()) return new Collection();

        $driverEmployeeIds = EmployeeOfficeInfo::whereIn('current_designation_id', $driverDesignationIds)
            ->pluck('employee_id');

        return Employee::whereIn('id', $driverEmployeeIds)
            ->whereNotIn('id', $assignedDriverIds)
            ->orderBy('full_name')
            ->get();
    }

    /**
     * Get employee transport with allocations and vehicle/driver details.
     *
     * @param int $id
     * @return EmployeeTransport
     */
    public function getEmployeeTransportDetails(int $id): EmployeeTransport
    {
        $employeeTransport = EmployeeTransport::findOrFail($id);

        // Manually load allocations with vehicle and driver info
        $allocations = VehicleAllocation::where('reference_type', EmployeeTransport::class)
            ->where('reference_id', $id)
            ->with(['vehicle', 'routes'])
            ->get();

        // For each allocation, get the assigned driver from vehicle_drivers table
        foreach ($allocations as $allocation) {
            $vehicleDriver = VehicleDriver::where('vehicle_id', $allocation->vehicle_id)
                ->where('status', 'active')
                ->with('driver')
                ->first();

            $allocation->assigned_driver = $vehicleDriver ? $vehicleDriver->driver : null;
        }

        $employeeTransport->allocations = $allocations;

        return $employeeTransport;
    }

    /**
     * Get comprehensive vehicle history including creation, drivers, allocations, and current status.
     *
     * @param int $vehicleId
     * @return array
     */
    public function getVehicleHistory(int $vehicleId): array
    {
        $vehicle = Vehicle::with(['activeAllocation', 'driverAssignment.getDriver'])->findOrFail($vehicleId);

        // Get all driver assignments history
        $driverHistory = VehicleDriver::where('vehicle_id', $vehicleId)
            ->with('getDriver')
            ->orderBy('start_date', 'desc')
            ->get()
            ->map(function ($assignment) {
                // Generate initials from driver name
                $driverName = $assignment->getDriver->full_name ?? 'Unknown';
                $nameParts = explode(' ', trim($driverName));
                $initials = '';
                foreach ($nameParts as $part) {
                    if (!empty($part)) {
                        $initials .= strtoupper(substr($part, 0, 1));
                    }
                }
                $initials = substr($initials, 0, 2) ?: 'NA'; // Limit to 2 characters

                return [
                    'id' => $assignment->id,
                    'driver_name' => $driverName,
                    'driver_id' => $assignment->driver_id,
                    'employee_system_id' => $assignment->getDriver->system_id ?? 'N/A',
                    'driver_photo' => $assignment->getDriver->photo_path ?? null,
                    'driver_initials' => $initials,
                    'start_date' => $assignment->start_date,
                    'end_date' => $assignment->end_date,
                    'status' => $assignment->status,
                    'duration_days' => $assignment->end_date
                        ? Carbon::parse($assignment->start_date)->diffInDays(Carbon::parse($assignment->end_date))
                        : Carbon::parse($assignment->start_date)->diffInDays(now()),
                ];
            });

        // Get all allocations (trips and transports)
        $allocationHistory = VehicleAllocation::where('vehicle_id', $vehicleId)
            ->with(['getVehicleRequisition.getEmployee', 'getEmployeeTransport', 'getRoutes'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($allocation) {
                $type = $allocation->allocation_type;
                $referenceDetails = null;

                if ($allocation->reference_type === 'vehicle_requisition' && $allocation->getVehicleRequisition) {
                    $req = $allocation->getVehicleRequisition;
                    $referenceDetails = [
                        'type' => 'Vehicle Requisition',
                        'purpose' => $req->purpose_of_travel,
                        'requested_by' => $req->getEmployee->full_name ?? 'N/A',
                        'employee_id' => $req->getEmployee->system_id ?? 'N/A',
                        'from' => $req->pickup_location,
                        'to' => $req->destination,
                        'passengers' => $req->no_of_passengers,
                        'trip_type' => $req->trip_type,
                    ];
                } elseif ($allocation->reference_type === 'employee_transport' && $allocation->getEmployeeTransport) {
                    $transport = $allocation->getEmployeeTransport;
                    $referenceDetails = [
                        'type' => 'Employee Transport',
                        'service_name' => $transport->service_name,
                        'transport_type' => $transport->transport_type,
                        'purpose' => $transport->purpose,
                        'from' => $transport->pickup_location,
                        'to' => $transport->drop_location,
                        'passengers' => $transport->estimated_passengers,
                    ];
                }

                return [
                    'id' => $allocation->id,
                    'name' => $allocation->name,
                    'allocation_type' => $allocation->allocation_type,
                    'allocation_purpose' => $allocation->allocation_purpose,
                    'start_date' => $allocation->start_date,
                    'end_date' => $allocation->end_date,
                    'status' => $allocation->status,
                    'reference_details' => $referenceDetails,
                    'routes_count' => $allocation->getRoutes->count(),
                    'duration_days' => $allocation->end_date
                        ? Carbon::parse($allocation->start_date)->diffInDays(Carbon::parse($allocation->end_date))
                        : Carbon::parse($allocation->start_date)->diffInDays(now()),
                ];
            });

        // Get current status
        $currentStatus = [
            'is_allocated' => $vehicle->is_allocated,
            'status' => $vehicle->status,
            'allocation_type' => $vehicle->allocation_type,
            'allocation_purpose' => $vehicle->allocation_purpose,
            'active_allocation' => null,
            'assigned_driver' => null,
        ];

        if ($vehicle->activeAllocation) {
            $currentStatus['active_allocation'] = [
                'id' => $vehicle->activeAllocation->id,
                'name' => $vehicle->activeAllocation->name,
                'start_date' => $vehicle->activeAllocation->start_date,
                'end_date' => $vehicle->activeAllocation->end_date,
                'remaining_days' => $vehicle->activeAllocation->end_date
                    ? now()->diffInDays(Carbon::parse($vehicle->activeAllocation->end_date), false)
                    : null,
            ];
        }

        if ($vehicle->driverAssignment) {
            $currentStatus['assigned_driver'] = [
                'id' => $vehicle->driverAssignment->id,
                'driver_name' => $vehicle->driverAssignment->getDriver->full_name ?? 'Unknown',
                'employee_id' => $vehicle->driverAssignment->getDriver->system_id ?? 'N/A',
                'start_date' => $vehicle->driverAssignment->start_date,
            ];
        }

        return [
            'vehicle' => $vehicle,
            'created_at' => $vehicle->created_at,
            'driver_history' => $driverHistory,
            'allocation_history' => $allocationHistory,
            'current_status' => $currentStatus,
            'statistics' => [
                'total_drivers_assigned' => $driverHistory->count(),
                'total_allocations' => $allocationHistory->count(),
                'total_trips' => $allocationHistory->where('allocation_type', 'trip_based')->count(),
                'total_transports' => $allocationHistory->where('allocation_type', 'employee_transport')->count(),
                'active_allocations' => $allocationHistory->where('status', 'Active')->count(),
                'completed_allocations' => $allocationHistory->where('status', 'Completed')->count(),
            ],
        ];
    }

    /**
     * Get inactive driver assignments for history display.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getInactiveDriverAssignments()
    {
        return VehicleDriver::with(['getVehicle', 'getDriver'])
            ->where('status', 'inactive')
            ->orderBy('updated_at', 'desc');
    }

    /**
     * Get vehicle details by ID for preview/display purposes.
     *
     * @param int $id
     * @return array|null
     */
    public function getVehicleDetailsById(int $id): ?array
    {
        $vehicle = Vehicle::find($id);

        if (!$vehicle) {
            return null;
        }

        return [
            'id' => $vehicle->id,
            'vehicle_category' => $vehicle->vehicle_category,
            'model_number' => $vehicle->model_number,
            'manufacture_year' => $vehicle->manufacture_year,
            'fuel_type' => $vehicle->fuel_type,
            'color' => $vehicle->color,
            'license_number' => $vehicle->license_number,
            'seating_capacity' => $vehicle->seating_capacity,
            'status' => $vehicle->status,
            'vehicle_image' => $vehicle->vehicle_image ? asset('storage/' . $vehicle->vehicle_image) : null,
        ];
    }

    /**
     * Get driver (employee) details by ID with office information.
     *
     * @param int $id
     * @return array|null
     */
    public function getDriverDetailsById(int $id): ?array
    {
        $employee = Employee::find($id);

        if (!$employee) {
            return null;
        }

        $officeInfo = EmployeeOfficeInfo::with('getCurrentDesignation')
            ->where('employee_id', $id)
            ->first();

        return [
            'id' => $employee->id,
            'full_name' => $employee->full_name,
            'system_id' => $employee->system_id,
            'personal_mobile' => $employee->personal_mobile,
            'work_mobile' => $employee->work_mobile,
            'work_email' => $employee->work_email,
            'personal_email' => $employee->personal_email,
            'photo_path' => $employee->photo_path ? asset('storage/' . $employee->photo_path) : null,
            'designation' => $officeInfo?->getCurrentDesignation?->company_designation ?? 'N/A',
        ];
    }

    /**
     * Get vehicle details with driver information.
     *
     * @param int $id
     * @return array
     */
    public function getVehicleDetailsWithDriver(int $id): array
    {
        $vehicle = Vehicle::find($id);
        if (!$vehicle) {
            return ['error' => 'Vehicle not found'];
        }

        // Check if vehicle has an assigned driver
        $vehicleDriver = VehicleDriver::where('vehicle_id', $id)
            ->where('status', 'active')
            ->with('getDriver')
            ->first();

        $driverData = null;
        if ($vehicleDriver && $vehicleDriver->getDriver) {
            $driver = $vehicleDriver->getDriver;
            $driverData = [
                'id' => $driver->id,
                'full_name' => $driver->full_name,
                'system_id' => $driver->system_id,
                'personal_mobile' => $driver->personal_mobile,
                'work_mobile' => $driver->work_mobile,
                'photo_path' => $driver->photo_path ? asset('storage/' . $driver->photo_path) : null,
            ];
        }

        return [
            'id' => $vehicle->id,
            'vehicle_category' => $vehicle->vehicle_category,
            'model_number' => $vehicle->model_number,
            'manufacture_year' => $vehicle->manufacture_year,
            'fuel_type' => $vehicle->fuel_type,
            'color' => $vehicle->color,
            'license_number' => $vehicle->license_number,
            'seating_capacity' => $vehicle->seating_capacity,
            'status' => $vehicle->status,
            'vehicle_image' => $vehicle->vehicle_image ? asset('storage/' . $vehicle->vehicle_image) : null,
            'has_driver' => $vehicleDriver ? true : false,
            'driver' => $driverData,
        ];
    }
}
