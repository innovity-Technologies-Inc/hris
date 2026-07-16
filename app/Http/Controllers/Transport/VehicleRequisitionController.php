<?php

namespace App\Http\Controllers\Transport;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transport\StoreVehicleRequisitionRequest;
use App\Models\Transport\Vehicle;
use App\Models\Transport\VehicleRequisition;
use App\Models\Transport\VehicleDriver;
use App\Models\Employee\Employee;
use App\Models\Company\Department;
use App\Services\Transport\TransportService;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VehicleRequisitionController extends Controller
{
    protected $transportService;

    public function __construct(TransportService $transportService)
    {
        $this->transportService = $transportService;
    }

    /**
     * Display a listing of vehicle requisitions.
     */
    public function index(FlexSearch $flexsearch, Request $request)
    {
        $title = 'Vehicle Requisition';
        $section = 'Transport';
        $sub_section = 'Vehicle Requisition';

        $query = VehicleRequisition::with(['getEmployee', 'getDepartment', 'getAssignedVehicle']);
        $searchableColumns = ['trip_type', 'trip_mode', 'vehicle_type_required', 'approval_status', 'pickup_location', 'destination'];
        $keyword = $request->input('keyword');
        $filters = [];

        // Apply status filter if provided
        if ($request->filled('status')) {
            $query->where('approval_status', $request->status);
        }

        $vehicleRequisitions = $flexsearch->apply($query, $filters, $keyword, $searchableColumns)->latest()->paginate(10);

        if ($request->ajax()) {
            return view('transport.vehicle_requisition.search_results', compact('vehicleRequisitions'))->render();
        }

        return view('transport.vehicle_requisition.index', compact('title', 'section', 'sub_section', 'vehicleRequisitions'));
    }

    /**
     * Show the form for creating a new vehicle requisition.
     */
    public function create()
    {
        $title = 'New Vehicle Requisition';
        $section = 'Vehicle Requisition';
        $section_url = route('transport.vehicle_requisitions.index');
        $sub_section = 'Create';

        $employees = Employee::orderBy('full_name')->get();
        $departments = Department::where('status', 'Active')->orderBy('department_name')->get();

        return view('transport.vehicle_requisition.form', compact(
            'title',
            'section',
            'sub_section',
            'section_url',
            'employees',
            'departments'
        ));
    }

    /**
     * Store a newly created vehicle requisition.
     */
    public function store(StoreVehicleRequisitionRequest $request)
    {
        try {
            Log::info('Creating Vehicle Requisition');

            $data = $request->validated();
            $data['driver_required'] = $request->has('driver_required') ? 1 : 0;
            $data['self_drive'] = $request->has('self_drive') ? 1 : 0;
            $data['approval_status'] = 'Pending';

            VehicleRequisition::create($data);

            Log::info('Vehicle Requisition Created Successfully');

            return response()->json([
                'success' => true,
                'message' => 'Vehicle Requisition Submitted Successfully',
                'redirect' => route('transport.vehicle_requisitions.index')
            ], 201);

        } catch (\Exception $e) {
            Log::error('Vehicle Requisition Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something Went Wrong: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified vehicle requisition.
     */
    public function show($id)
    {
        $title = 'View Vehicle Requisition';
        $section = 'Vehicle Requisition';
        $section_url = route('transport.vehicle_requisitions.index');
        $sub_section = 'Details';

        $vehicleRequisition = VehicleRequisition::with(['getEmployee', 'getDepartment', 'getAssignedVehicle'])->findOrFail($id);

        // Get driver if vehicle is assigned
        $assignedDriver = null;
        if ($vehicleRequisition->assigned_vehicle_id) {
            $vehicleDriver = VehicleDriver::where('vehicle_id', $vehicleRequisition->assigned_vehicle_id)
                ->where('status', 'active')
                ->with('getDriver')
                ->first();
            $assignedDriver = $vehicleDriver?->getDriver;
        }

        return view('transport.vehicle_requisition.show', compact(
            'title',
            'section',
            'sub_section',
            'section_url',
            'vehicleRequisition',
            'assignedDriver'
        ));
    }

    /**
     * Reject the specified vehicle requisition.
     */
    public function reject(Request $request, $id)
    {
        try {
            Log::info('Rejecting Vehicle Requisition');

            $vehicleRequisition = VehicleRequisition::findOrFail($id);

            $vehicleRequisition->update([
                'approval_status' => 'Rejected',
                'approval_remarks' => $request->rejection_reason ?? 'Rejected by admin',
            ]);

            Log::info('Vehicle Requisition Rejected');

            return response()->json([
                'success' => true,
                'message' => 'Vehicle Requisition Rejected',
                'redirect' => route('transport.vehicle_requisitions.index')
            ], 200);

        } catch (\Exception $e) {
            Log::error('Vehicle Requisition Rejection Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something Went Wrong'
            ], 500);
        }
    }

    /**
     * API endpoint to get vehicle details for preview card.
     */
    public function getVehicleDetails($id)
    {
        $vehicleDetails = $this->transportService->getVehicleDetailsWithDriver($id);

        if (isset($vehicleDetails['error'])) {
            return response()->json($vehicleDetails, 404);
        }

        return response()->json($vehicleDetails);
    }
}

