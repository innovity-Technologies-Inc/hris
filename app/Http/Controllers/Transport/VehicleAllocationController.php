<?php

namespace App\Http\Controllers\Transport;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transport\StoreVehicleAllocationRequest;
use App\Models\Transport\AllocationRoute;
use App\Models\Transport\EmployeeTransport;
use App\Models\Transport\Vehicle;
use App\Models\Transport\VehicleAllocation;
use App\Models\Transport\VehicleRequisition;
use App\Services\Transport\TransportService;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Exports\Transport\VehicleAllocationExport;
use Maatwebsite\Excel\Facades\Excel;

class VehicleAllocationController extends Controller
{
    protected TransportService $transportService;

    public function __construct(TransportService $transportService)
    {
        $this->transportService = $transportService;
    }

    /**
     * Display the allocation dashboard with all vehicles and their status.
     */
    public function dashboard()
    {
        $title = 'Vehicle Allocation Dashboard';
        $section = 'Transport';
        $sub_section = 'Vehicle Allocation';

        $stats = $this->transportService->getDashboardStats();
        $pendingTransports = $this->transportService->getPendingEmployeeTransports();
        $pendingRequisitions = $this->transportService->getPendingVehicleRequisitions();
        $activeAllocations = VehicleAllocation::with(['getVehicle'])
            ->whereIn('status', ['Active', 'Allocated'])
            ->latest()
            ->get();
        $availableVehicles = $this->transportService->getUnallocatedVehicles();
        $endingSoon = VehicleAllocation::with(['getVehicle'])
            ->whereIn('status', ['Active', 'Allocated'])
            ->whereNotNull('end_date')
            ->whereDate('end_date', '<=', now()->addDays(7))
            ->orderBy('end_date')
            ->get();

        return view('transport.vehicle_allocation.dashboard', compact(
            'title',
            'section',
            'sub_section',
            'stats',
            'pendingTransports',
            'pendingRequisitions',
            'activeAllocations',
            'availableVehicles',
            'endingSoon'
        ));
    }

    /**
     * Display allocation history.
     */
    public function history(FlexSearch $flexsearch, Request $request)
    {
        $title = 'Allocation History';
        $section = 'Transport';
        $sub_section = 'Allocation History';

        $query = VehicleAllocation::with(['getVehicle', 'getRoutes']);
        $searchableColumns = ['name', 'allocation_type', 'status'];
        $keyword = $request->input('keyword');
        $filters = [];

        // Apply vehicle filter
        if ($request->filled('vehicle_id')) {
            $query->where('vehicle_id', $request->vehicle_id);
        }

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Apply allocation type filter
        if ($request->filled('allocation_type')) {
            $query->where('allocation_type', $request->allocation_type);
        }

        // Apply date filters
        if ($request->filled('from_date')) {
            $query->whereDate('start_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('end_date', '<=', $request->to_date);
        }

        $allocations = $flexsearch->apply($query, $filters, $keyword, $searchableColumns)
            ->latest()
            ->paginate(15);

        $vehicles = Vehicle::orderBy('model_number')->get();

        if ($request->ajax()) {
            return view('transport.vehicle_allocation.history_results', compact('allocations'))->render();
        }

        return view('transport.vehicle_allocation.history', compact(
            'title',
            'section',
            'sub_section',
            'allocations',
            'vehicles'
        ));
    }

    public function exportExcel(FlexSearch $flexsearch, Request $request)
    {
        $query = VehicleAllocation::with(['getVehicle', 'getRoutes']);
        $searchableColumns = ['name', 'allocation_type', 'status'];
        $keyword = $request->input('keyword');
        $filters = [];

        if ($request->filled('vehicle_id')) {
            $query->where('vehicle_id', $request->vehicle_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('allocation_type')) {
            $query->where('allocation_type', $request->allocation_type);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('start_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('end_date', '<=', $request->to_date);
        }

        $records = $flexsearch->apply($query, $filters, $keyword, $searchableColumns)->latest()->get();

        return Excel::download(new VehicleAllocationExport($records), 'vehicle_allocations.xlsx');
    }

    public function printIndex(FlexSearch $flexsearch, Request $request)
    {
        $query = VehicleAllocation::with(['getVehicle', 'getRoutes']);
        $searchableColumns = ['name', 'allocation_type', 'status'];
        $keyword = $request->input('keyword');
        $filters = [];

        if ($request->filled('vehicle_id')) {
            $query->where('vehicle_id', $request->vehicle_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('allocation_type')) {
            $query->where('allocation_type', $request->allocation_type);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('start_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('end_date', '<=', $request->to_date);
        }

        $records = $flexsearch->apply($query, $filters, $keyword, $searchableColumns)->latest()->get();

        return view('transport.vehicle_allocation.print_index', compact('records'));
    }

    /**
     * Step 1: Select allocation type.
     */
    public function create(Request $request)
    {
        $title = 'New Vehicle Allocation';
        $section = 'Vehicle Allocation';
        $section_url = route('transport.vehicle_allocations.dashboard');
        $sub_section = 'Step 1 - Select Type';

        // Get pending transport services (not yet allocated/approved)
        $pendingTransports = EmployeeTransport::where('status', 'Pending')
            ->latest()
            ->get();

        // Get pending requisitions (not yet allocated/approved)
        $pendingRequisitions = VehicleRequisition::where('approval_status', 'Pending')
            ->whereNull('assigned_vehicle_id')
            ->with(['getEmployee', 'getDepartment'])
            ->latest()
            ->get();

        return view('transport.vehicle_allocation.create_step1', compact(
            'title',
            'section',
            'sub_section',
            'section_url',
            'pendingTransports',
            'pendingRequisitions'
        ));
    }

    /**
     * Step 2: Select vehicles.
     */
    public function step2(Request $request)
    {
        // Check if coming back from step3 (GET request with session data)
        if ($request->isMethod('get') && session()->has('allocation_data')) {
            $allocationData = session('allocation_data', []);
            
            // Get reference details
            $reference = null;
            if (!empty($allocationData['reference_id']) && !empty($allocationData['reference_type'])) {
                $referenceType = $allocationData['reference_type'];
                if ($referenceType == 'App\\Models\\Transport\\EmployeeTransport') {
                    $reference = EmployeeTransport::find($allocationData['reference_id']);
                } elseif ($referenceType == 'App\\Models\\Transport\\VehicleRequisition') {
                    $reference = VehicleRequisition::find($allocationData['reference_id']);
                }
            }
        } else {
            // Store allocation data from step1 POST
            $allocationData = $request->only([
                'allocation_type', 'reference_type', 'reference_id',
                'name', 'start_date', 'end_date', 'remarks'
            ]);

            // If reference is provided, fetch data from it
            $reference = null;
            if ($request->filled('reference_id') && $request->filled('reference_type')) {
                $referenceType = $request->reference_type;
                if ($referenceType == 'App\\Models\\Transport\\EmployeeTransport') {
                    $reference = EmployeeTransport::find($request->reference_id);
                    if ($reference && empty($allocationData['name'])) {
                        $allocationData['name'] = $reference->service_name;
                        $allocationData['start_date'] = $reference->start_date->format('Y-m-d');
                        $allocationData['end_date'] = $reference->end_date->format('Y-m-d');
                    }
                }
            }

            // Handle Trip Requisition reference
            if ($request->filled('requisition_id')) {
                $reference = VehicleRequisition::find($request->requisition_id);
                if ($reference) {
                    $allocationData['reference_type'] = 'App\\Models\\Transport\\VehicleRequisition';
                    $allocationData['reference_id'] = $reference->id;
                    if (empty($allocationData['name'])) {
                        $allocationData['name'] = 'Trip: ' . ($reference->purpose_of_travel ?? 'Requisition #' . $reference->id);
                    }
                    // Set dates from trip requisition
                    if ($reference->start_date_time) {
                        $allocationData['start_date'] = $reference->start_date_time->format('Y-m-d');
                    }
                    if ($reference->end_date_time) {
                        $allocationData['end_date'] = $reference->end_date_time->format('Y-m-d');
                    }
                }
            }

            session(['allocation_data' => $allocationData]);
        }

        $title = 'New Vehicle Allocation';
        $section = 'Vehicle Allocation';
        $section_url = route('transport.vehicle_allocations.dashboard');
        $sub_section = 'Step 2 - Select Vehicles';

        $availableVehicles = $this->transportService->getUnallocatedVehicles();
        $vehicleTypes = $this->transportService->getAvailableVehicleCategories();

        return view('transport.vehicle_allocation.create_step2', compact(
            'title',
            'section',
            'sub_section',
            'section_url',
            'allocationData',
            'availableVehicles',
            'vehicleTypes',
            'reference'
        ));
    }

    /**
     * Step 3: Review and confirm.
     */
    public function step3(Request $request)
    {
        // If GET request, check for session data and redirect if missing
        if ($request->isMethod('get')) {
            if (!session()->has('allocation_data') || !session()->has('selected_vehicle_ids')) {
                return redirect()->route('transport.vehicle_allocations.create')->with([
                    'message' => 'Please start the allocation process from the beginning.',
                    'alert-type' => 'warning'
                ]);
            }

            $allocationData = session('allocation_data', []);
            $selectedVehicleIds = session('selected_vehicle_ids', []);

            if (empty($selectedVehicleIds)) {
                return redirect()->route('transport.vehicle_allocations.create')->with([
                    'message' => 'No vehicles selected. Please start over.',
                    'alert-type' => 'warning'
                ]);
            }

            $selectedVehicles = Vehicle::with('driverAssignment.driver')
                ->whereIn('id', $selectedVehicleIds)
                ->get();

            // Get reference details
            $reference = null;
            if (!empty($allocationData['reference_id']) && !empty($allocationData['reference_type'])) {
                $referenceType = $allocationData['reference_type'];
                if ($referenceType == 'App\\Models\\Transport\\EmployeeTransport') {
                    $reference = EmployeeTransport::find($allocationData['reference_id']);
                    // Auto-populate dates from reference if not set
                    if ($reference && empty($allocationData['start_date'])) {
                        $allocationData['start_date'] = $reference->start_date?->format('Y-m-d');
                        $allocationData['end_date'] = $reference->end_date?->format('Y-m-d');
                    }
                } elseif ($referenceType == 'App\\Models\\Transport\\VehicleRequisition') {
                    $reference = VehicleRequisition::find($allocationData['reference_id']);
                    // Auto-populate dates from trip requisition if not set
                    if ($reference && empty($allocationData['start_date'])) {
                        $allocationData['start_date'] = $reference->start_date_time?->format('Y-m-d');
                        $allocationData['end_date'] = $reference->end_date_time?->format('Y-m-d');
                    }
                }
            }

            $title = 'New Vehicle Allocation';
            $section = 'Vehicle Allocation';
            $section_url = route('transport.vehicle_allocations.dashboard');
            $sub_section = 'Step 3 - Confirm';

            return view('transport.vehicle_allocation.create_step3', compact(
                'title',
                'section',
                'sub_section',
                'section_url',
                'allocationData',
                'selectedVehicles',
                'reference'
            ));
        }

        // POST request handling
        $request->validate([
            'vehicle_ids' => 'required|array|min:1',
            'vehicle_ids.*' => 'exists:vehicles,id',
        ], [
            'vehicle_ids.required' => 'Please select at least one vehicle.',
            'vehicle_ids.min' => 'Please select at least one vehicle.',
        ]);

        $allocationData = session('allocation_data', []);
        $allocationData = array_merge($allocationData, $request->only([
            'allocation_type', 'reference_type', 'reference_id',
            'name', 'start_date', 'end_date', 'remarks'
        ]));

        session(['allocation_data' => $allocationData]);
        session(['selected_vehicle_ids' => $request->vehicle_ids]);

        $selectedVehicles = Vehicle::with('driverAssignment.driver')
            ->whereIn('id', $request->vehicle_ids)
            ->get();

        // Get reference details
        $reference = null;
        if (!empty($allocationData['reference_id']) && !empty($allocationData['reference_type'])) {
            $referenceType = $allocationData['reference_type'];
            if ($referenceType == 'App\\Models\\Transport\\EmployeeTransport') {
                $reference = EmployeeTransport::find($allocationData['reference_id']);
                // Auto-populate dates from reference if not set
                if ($reference && empty($allocationData['start_date'])) {
                    $allocationData['start_date'] = $reference->start_date?->format('Y-m-d');
                    $allocationData['end_date'] = $reference->end_date?->format('Y-m-d');
                }
            } elseif ($referenceType == 'App\\Models\\Transport\\VehicleRequisition') {
                $reference = VehicleRequisition::find($allocationData['reference_id']);
                // Auto-populate dates from trip requisition if not set
                if ($reference && empty($allocationData['start_date'])) {
                    $allocationData['start_date'] = $reference->start_date_time?->format('Y-m-d');
                    $allocationData['end_date'] = $reference->end_date_time?->format('Y-m-d');
                }
            }
        }

        $title = 'New Vehicle Allocation';
        $section = 'Vehicle Allocation';
        $section_url = route('transport.vehicle_allocations.dashboard');
        $sub_section = 'Step 3 - Confirm';

        return view('transport.vehicle_allocation.create_step3', compact(
            'title',
            'section',
            'sub_section',
            'section_url',
            'allocationData',
            'selectedVehicles',
            'reference'
        ));
    }

    /**
     * Store the allocation.
     */
    public function store(StoreVehicleAllocationRequest $request)
    {
        try {
            DB::beginTransaction();

            Log::info('Creating Vehicle Allocation');

            $validated = $request->validated();
            $vehicleIds = $validated['vehicle_ids'];
            $createdAllocations = [];

            // Get reference if exists
            $reference = null;
            $allocationType = $validated['allocation_type'];
            $allocationPurpose = null;

            if (!empty($validated['reference_id']) && !empty($validated['reference_type'])) {
                $referenceType = $validated['reference_type'];
                if ($referenceType == 'App\\Models\\Transport\\EmployeeTransport') {
                    $reference = EmployeeTransport::find($validated['reference_id']);
                    $allocationPurpose = $reference->service_name ?? 'Employee Transport';
                } elseif ($referenceType == 'App\\Models\\Transport\\VehicleRequisition') {
                    $reference = VehicleRequisition::find($validated['reference_id']);
                    $allocationPurpose = $reference->purpose ?? 'Trip Based';
                }
            }

            // Create allocation for each vehicle
            foreach ($vehicleIds as $vehicleId) {
                $allocation = VehicleAllocation::create([
                    'vehicle_id' => $vehicleId,
                    'name' => $validated['name'] ?? ($reference->service_name ?? $reference->purpose ?? 'Vehicle Allocation'),
                    'allocation_type' => $allocationType,
                    'allocation_purpose' => $allocationPurpose,
                    'start_date' => $validated['start_date'] ?? ($reference->start_date ?? now()),
                    'end_date' => $validated['end_date'] ?? ($reference->end_date ?? null),
                    'reference_type' => $validated['reference_type'] ?? null,
                    'reference_id' => $validated['reference_id'] ?? null,
                    'status' => 'Active',
                    'approval_remarks' => $validated['remarks'] ?? null,
                    'approved_at' => now(),
                ]);

                // Update vehicle allocation status, type, and purpose
                Vehicle::where('id', $vehicleId)->update([
                    'is_allocated' => true,
                    'allocation_type' => $allocationType,
                    'allocation_purpose' => $allocationPurpose,
                ]);

                // Create route if provided
                if (!empty($validated['route_start']) || !empty($validated['route_end'])) {
                    AllocationRoute::create([
                        'vehicle_allocation_id' => $allocation->id,
                        'route_name' => $validated['name'] ?? ($allocationPurpose ?? 'Route'),
                        'start_point' => $validated['route_start'] ?? 'N/A',
                        'end_point' => $validated['route_end'] ?? 'N/A',
                        'distance_km' => $validated['distance_km'] ?? null,
                        'estimated_duration_minutes' => $validated['estimated_duration_minutes'] ?? null,
                        'departure_time' => $validated['departure_time'] ?? null,
                        'arrival_time' => $validated['arrival_time'] ?? null,
                        'route_description' => $validated['route_description'] ?? null,
                        'special_instructions' => $validated['special_instructions'] ?? null,
                        'status' => 'Active',
                    ]);
                }

                $createdAllocations[] = $allocation;
            }

            // Update reference status - APPROVE when vehicle is allocated
            if ($reference) {
                if ($reference instanceof EmployeeTransport) {
                    $reference->update([
                        'status' => 'Approved',
                        'approved_at' => now(),
                    ]);
                } elseif ($reference instanceof VehicleRequisition) {
                    $reference->update([
                        'approval_status' => 'Approved',
                        'approved_at' => now(),
                        'assigned_vehicle_id' => $vehicleIds[0], // Assign first vehicle
                    ]);
                }
            }

            // Clear session data
            session()->forget(['allocation_data', 'selected_vehicle_ids']);

            DB::commit();

            Log::info('Vehicle Allocation Created Successfully');

            return response()->json([
                'success' => true,
                'message' => 'Vehicle(s) Allocated Successfully',
                'redirect' => route('transport.vehicle_allocations.dashboard')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Allocation Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something Went Wrong: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified allocation details.
     */
    public function show($id)
    {
        $title = 'Allocation Details';
        $section = 'Vehicle Allocation';
        $section_url = route('transport.vehicle_allocations.dashboard');
        $sub_section = 'View';

        $allocation = VehicleAllocation::with([
                'getVehicle.driverAssignment.driver',
                'getRoutes'
            ])
            ->findOrFail($id);

        return view('transport.vehicle_allocation.show', compact(
            'title',
            'section',
            'sub_section',
            'section_url',
            'allocation'
        ));
    }

    /**
     * Release a vehicle from allocation.
     */
    public function release(Request $request, $id)
    {
        try {
            Log::info('Releasing Vehicle from Allocation');

            $allocation = VehicleAllocation::findOrFail($id);
            $vehicleId = $allocation->vehicle_id;

            // Check if allocation is already released/inactive
            if ($allocation->status === 'Inactive' || $allocation->status === 'Completed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Vehicle allocation is already released'
                ], 400);
            }

            // Check if allocation is active
            if ($allocation->status !== 'Active') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only active vehicles can be released'
                ], 400);
            }

            // Update allocation with remarks
            $remarksToAdd = $request->release_remarks ? "\n\nRelease Remarks: " . $request->release_remarks : '';
            
            // Update allocation status to Inactive
            $allocation->update([
                'status' => 'Inactive',
                'approval_remarks' => $allocation->approval_remarks . $remarksToAdd
            ]);

            // Release the vehicle (sets is_allocated to false)
            $this->transportService->releaseVehicle($vehicleId);

            Log::info('Vehicle Released Successfully');

            return response()->json([
                'success' => true,
                'message' => 'Vehicle Released Successfully',
                'redirect' => route('transport.vehicle_allocations.dashboard')
            ], 200);

        } catch (\Exception $e) {
            Log::error('Release Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something Went Wrong: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Extend allocation end date.
     */
    public function extend(Request $request, $id)
    {
        $validated = $request->validate([
            'new_end_date' => 'required|date|after:today',
            'extension_remarks' => 'nullable|string|max:500',
        ]);

        try {
            Log::info('Extending Vehicle Allocation');

            $allocation = VehicleAllocation::findOrFail($id);

            $allocation->update([
                'end_date' => $validated['new_end_date'],
                'approval_remarks' => $allocation->approval_remarks .
                    "\n\nExtended on " . now()->format('Y-m-d') . ": " . ($validated['extension_remarks'] ?? 'No remarks'),
            ]);

            Log::info('Vehicle Allocation Extended Successfully');

            return response()->json([
                'success' => true,
                'message' => 'Allocation Extended Successfully',
                'redirect' => route('transport.vehicle_allocations.dashboard')
            ], 200);

        } catch (\Exception $e) {
            Log::error('Extension Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something Went Wrong'
            ], 500);
        }
    }

    /**
     * Get application details via AJAX.
     */
    public function getApplicationDetails(Request $request)
    {
        $type = $request->input('type');
        $id = $request->input('id');

        if ($type === 'trip') {
            $application = VehicleRequisition::with(['getEmployee', 'getDepartment'])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $application->id,
                    'type' => 'Trip Requisition',
                    'employee' => $application->getEmployee?->full_name ?? 'N/A',
                    'department' => $application->getDepartment?->department_name ?? 'N/A',
                    'trip_type' => $application->trip_type,
                    'trip_mode' => $application->trip_mode,
                    'purpose' => $application->purpose_of_travel,
                    'start_date' => $application->start_date_time?->format('Y-m-d H:i'),
                    'end_date' => $application->end_date_time?->format('Y-m-d H:i'),
                    'pickup' => $application->pickup_location,
                    'destination' => $application->destination,
                    'passengers' => $application->no_of_passengers,
                    'vehicle_type' => $application->vehicle_type_required,
                    'status' => $application->approval_status,
                ]
            ]);
        } else {
            $application = EmployeeTransport::with(['company'])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $application->id,
                    'type' => 'Employee Transport',
                    'service_name' => $application->service_name ?? 'N/A',
                    'company' => $application->company?->name ?? 'N/A',
                    'transport_type' => $application->transport_type,
                    'purpose' => $application->purpose,
                    'start_date' => $application->start_date?->format('Y-m-d'),
                    'end_date' => $application->end_date?->format('Y-m-d'),
                    'pickup' => $application->pickup_location,
                    'drop' => $application->drop_location,
                    'estimated_passengers' => $application->estimated_passengers,
                    'status' => $application->status,
                ]
            ]);
        }
    }
}

