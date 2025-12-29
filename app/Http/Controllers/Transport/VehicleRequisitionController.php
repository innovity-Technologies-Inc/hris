<?php

namespace App\Http\Controllers\Transport;

use App\Http\Controllers\Controller;
use App\Models\Transport\VehicleRequisition;
use App\Models\Transport\VehicleAcquisition;
use App\Models\Transport\VehicleDriver;
use App\Models\Employee;
use App\Models\Department;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VehicleRequisitionController extends Controller
{
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
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'nullable|exists:employees,id',
            'department' => 'nullable|exists:departments,id',
            'trip_type' => 'required|in:Official,Personal,Visitor',
            'trip_mode' => 'required|in:One-way,Round-trip,Multi-stop',
            'purpose_of_travel' => 'required|string|max:1000',
            'start_date_time' => 'required|date',
            'end_date_time' => 'required|date|after_or_equal:start_date_time',
            'pickup_location' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'route' => 'nullable|string|max:255',
            'no_of_passengers' => 'required|integer|min:1|max:100',
            'vehicle_type_required' => 'required|in:Car,Bus,Micro',
            'driver_required' => 'nullable|boolean',
            'self_drive' => 'nullable|boolean',
            'special_requirement' => 'nullable|string|max:500',
            'preferred_vehicle' => 'nullable|string|max:255',
        ], [
            'purpose_of_travel.required' => 'Purpose of travel is mandatory.',
            'start_date_time.required' => 'Start date and time is required.',
            'end_date_time.after_or_equal' => 'End date must be after or equal to start date.',
        ]);

        try {
            Log::info('Creating Vehicle Requisition');

            $data = $request->all();
            $data['driver_required'] = $request->has('driver_required') ? 1 : 0;
            $data['self_drive'] = $request->has('self_drive') ? 1 : 0;
            $data['approval_status'] = 'Pending';

            VehicleRequisition::create($data);

        } catch (\Exception $e) {
            Log::error('Vehicle Requisition Error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with([
                'message' => 'Something Went Wrong',
                'alert-type' => 'error'
            ]);
        }

        Log::info('Vehicle Requisition Created Successfully');

        return redirect()->route('transport.vehicle_requisitions.index')->with([
            'message' => 'Vehicle Requisition Submitted Successfully',
            'alert-type' => 'success'
        ]);
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
     * Show the approval form for the specified vehicle requisition.
     */
    public function approve($id)
    {
        $title = 'Approve Vehicle Requisition';
        $section = 'Vehicle Requisition';
        $section_url = route('transport.vehicle_requisitions.index');
        $sub_section = 'Approval';

        $vehicleRequisition = VehicleRequisition::with(['getEmployee', 'getDepartment'])->findOrFail($id);

        // Get available vehicles
        $availableVehicles = VehicleAcquisition::where('status', 'Active')
            ->orderBy('model_number')
            ->get();

        return view('transport.vehicle_requisition.approve', compact(
            'title',
            'section',
            'sub_section',
            'section_url',
            'vehicleRequisition',
            'availableVehicles'
        ));
    }

    /**
     * Process the approval of the vehicle requisition.
     */
    public function processApproval(Request $request, $id)
    {
        $request->validate([
            'approval_remarks' => 'nullable|string|max:1000',
            'assigned_vehicle_id' => 'required|exists:vehicle_acquisitions,id',
            'dispatch_time' => 'nullable|date_format:H:i',
            'expected_return_time' => 'nullable|date_format:H:i',
        ], [
            'assigned_vehicle_id.required' => 'Please select a vehicle to assign.',
        ]);

        try {
            Log::info('Approving Vehicle Requisition');

            $vehicleRequisition = VehicleRequisition::findOrFail($id);

            $vehicleRequisition->update([
                'approval_status' => 'Approved',
                'approval_remarks' => $request->approval_remarks,
                'assigned_vehicle_id' => $request->assigned_vehicle_id,
                'dispatch_time' => $request->dispatch_time,
                'expected_return_time' => $request->expected_return_time,
            ]);

        } catch (\Exception $e) {
            Log::error('Vehicle Requisition Approval Error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with([
                'message' => 'Something Went Wrong',
                'alert-type' => 'error'
            ]);
        }

        Log::info('Vehicle Requisition Approved Successfully');

        return redirect()->route('transport.vehicle_requisitions.index')->with([
            'message' => 'Vehicle Requisition Approved Successfully',
            'alert-type' => 'success'
        ]);
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

        } catch (\Exception $e) {
            Log::error('Vehicle Requisition Rejection Error: ' . $e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong',
                'alert-type' => 'error'
            ]);
        }

        Log::info('Vehicle Requisition Rejected');

        return redirect()->route('transport.vehicle_requisitions.index')->with([
            'message' => 'Vehicle Requisition Rejected',
            'alert-type' => 'success'
        ]);
    }

    /**
     * API endpoint to get vehicle details for preview card.
     */
    public function getVehicleDetails($id)
    {
        $vehicle = VehicleAcquisition::find($id);
        if (!$vehicle) return response()->json(['error' => 'Vehicle not found'], 404);

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

        return response()->json([
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
        ]);
    }
}
