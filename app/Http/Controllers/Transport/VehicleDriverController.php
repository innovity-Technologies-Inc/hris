<?php

namespace App\Http\Controllers\Transport;

use App\Http\Controllers\Controller;
use App\Models\Transport\Vehicle;
use App\Models\Transport\VehicleDriver;
use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeOfficeInfo;
use App\Models\Company\Designation;
use App\Services\Transport\TransportService;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class VehicleDriverController extends Controller
{
    protected $transportService;

    public function __construct(TransportService $transportService)
    {
        $this->transportService = $transportService;
    }
    /**
     * Display a listing of vehicle driver assignments.
     */
    public function index(FlexSearch $flexsearch, Request $request)
    {
        $title = 'Assign Driver';
        $section = 'Transport';
        $sub_section = 'Assign Driver';

        $query = VehicleDriver::with(['getVehicle', 'getDriver'])->where('status', 'active');
        $searchableColumns = ['status'];
        $keyword = $request->input('keyword');
        $filters = [];

        $vehicleDrivers = $flexsearch->apply($query, $filters, $keyword, $searchableColumns)->latest()->paginate(10);

        if ($request->ajax()) {
            return view('transport.vehicle_driver.search_results', compact('vehicleDrivers'))->render();
        }

        return view('transport.vehicle_driver.index', compact('title', 'section', 'sub_section', 'vehicleDrivers'));
    }

    /**
     * Show the form for creating a new vehicle driver assignment.
     */
    public function create()
    {
        $title = 'Assign Driver';
        $section = 'Assign Driver';
        $section_url = route('transport.vehicle_drivers.index');
        $sub_section = 'New Assignment';

        // Get available vehicles (active and not currently assigned to any active driver)
        $availableVehicles = $this->transportService->getAvailableVehicles();

        // Get eligible drivers (employees with 'Driver' designation)
        $eligibleDrivers = $this->transportService->getEligibleDrivers();

        return view('transport.vehicle_driver.form', compact(
            'title',
            'section',
            'sub_section',
            'section_url',
            'availableVehicles',
            'eligibleDrivers'
        ));
    }

    /**
     * Display the specified vehicle driver assignment.
     */
    public function show($id)
    {
        $title = 'Driver Assignment Details';
        $section = 'Assign Driver';
        $section_url = route('transport.vehicle_drivers.index');
        $sub_section = 'View';

        $vehicleDriver = VehicleDriver::with(['getVehicle', 'getDriver'])->findOrFail($id);

        return view('transport.vehicle_driver.show', compact(
            'title',
            'section',
            'sub_section',
            'section_url',
            'vehicleDriver'
        ));
    }

    /**
     * Store a newly created vehicle driver assignment.
     */
    public function store(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'driver_id' => 'required|exists:employees,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ], [
            'vehicle_id.required' => 'Please select a vehicle.',
            'driver_id.required' => 'Please select a driver.',
            'start_date.required' => 'Start date is required.',
            'end_date.after_or_equal' => 'End date must be after or equal to start date.',
        ]);

        try {
            Log::info('Assigning Driver to Vehicle');

            VehicleDriver::create([
                'vehicle_id' => $request->vehicle_id,
                'driver_id' => $request->driver_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'status' => 'active', // Always set to active by default
            ]);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->withInput()->with([
                'message' => 'Something Went Wrong',
                'alert-type' => 'error'
            ]);
        }

        Log::info('Driver Assigned Successfully');

        return redirect()->route('transport.vehicle_drivers.index')->with([
            'message' => 'Driver Assigned Successfully',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Show the form for editing an existing assignment.
     */
    public function edit($id)
    {
        $title = 'Edit Driver Assignment';
        $section = 'Assign Driver';
        $section_url = route('transport.vehicle_drivers.index');
        $sub_section = 'Edit';

        $vehicleDriver = VehicleDriver::with(['getVehicle', 'getDriver'])->findOrFail($id);

        // Get available vehicles (include current vehicle)
        $availableVehicles = $this->transportService->getAvailableVehicles($vehicleDriver->vehicle_id);

        // Get eligible drivers
        $eligibleDrivers = $this->transportService->getEligibleDrivers();

        return view('transport.vehicle_driver.form', compact(
            'title',
            'section',
            'sub_section',
            'section_url',
            'vehicleDriver',
            'availableVehicles',
            'eligibleDrivers'
        ));
    }

    /**
     * Update the specified assignment.
     */
    public function update(Request $request, $id)
    {
        $vehicleDriver = VehicleDriver::findOrFail($id);

        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'driver_id' => 'required|exists:employees,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            Log::info('Updating Vehicle Driver Assignment');

            $vehicleDriver->update($request->only([
                'vehicle_id',
                'driver_id',
                'start_date',
                'end_date',
                'status',
            ]));

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->withInput()->with([
                'message' => 'Something Went Wrong',
                'alert-type' => 'error'
            ]);
        }

        Log::info('Vehicle Driver Assignment Updated Successfully');

        return redirect()->route('transport.vehicle_drivers.index')->with([
            'message' => 'Assignment Updated Successfully',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Remove the specified assignment (soft delete by setting status to inactive).
     */
    public function destroy($id)
    {
        try {
            Log::info('Deactivating Vehicle Driver Assignment');

            $vehicleDriver = VehicleDriver::findOrFail($id);
            $vehicleDriver->update(['status' => 'inactive']);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong',
                'alert-type' => 'error'
            ]);
        }

        Log::info('Vehicle Driver Assignment Deactivated Successfully');

        return redirect()->route('transport.vehicle_drivers.index')->with([
            'message' => 'Assignment Deactivated Successfully',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Display history of inactive assignments grouped by date.
     */
    public function history(FlexSearch $flexsearch, Request $request)
    {
        $title = 'Assignment History';
        $section = 'Assign Driver';
        $section_url = route('transport.vehicle_drivers.index');
        $sub_section = 'History Logs';

        $query = $this->transportService->getInactiveDriverAssignments();
        $searchableColumns = ['status'];
        $keyword = $request->input('keyword');
        $filters = [];

        $vehicleDrivers = $flexsearch->apply($query, $filters, $keyword, $searchableColumns)
            ->paginate(10);

        // Group by date for display
        $inactiveAssignments = $vehicleDrivers->getCollection()->groupBy(function($item) {
            return \Carbon\Carbon::parse($item->updated_at)->format('Y-m-d');
        });

        if ($request->ajax()) {
            return view('transport.vehicle_driver.history_results', compact('inactiveAssignments', 'vehicleDrivers'))->render();
        }

        return view('transport.vehicle_driver.history', compact(
            'title',
            'section',
            'sub_section',
            'section_url',
            'inactiveAssignments',
            'vehicleDrivers'
        ));
    }

    /**
     * API endpoint to get vehicle details for preview card.
     */
    public function getVehicleDetails($id)
    {
        $vehicleDetails = $this->transportService->getVehicleDetailsById($id);

        if (!$vehicleDetails) {
            return response()->json(['error' => 'Vehicle not found'], 404);
        }

        return response()->json($vehicleDetails);
    }

    /**
     * API endpoint to get driver details for preview card.
     */
    public function getDriverDetails($id)
    {
        $driverDetails = $this->transportService->getDriverDetailsById($id);

        if (!$driverDetails) {
            return response()->json(['error' => 'Driver not found'], 404);
        }

        return response()->json($driverDetails);
    }
}

