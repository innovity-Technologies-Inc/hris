<?php

namespace App\Http\Controllers\Transport;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transport\StoreEmployeeTransportRequest;
use App\Http\Requests\Transport\UpdateEmployeeTransportRequest;
use App\Models\Company\Company;
use App\Models\Transport\EmployeeTransport;
use App\Models\Transport\RouteMap;
use App\Services\Transport\TransportService;
use App\HelperClass;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Exports\Transport\EmployeeTransportExport;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeTransportController extends Controller
{
    protected TransportService $transportService;

    public function __construct(TransportService $transportService)
    {
        $this->transportService = $transportService;
    }

    /**
     * Display a listing of employee transport services.
     */
    public function index(FlexSearch $flexsearch, Request $request)
    {
        $title = 'Employee Transport';
        $section = 'Transport';
        $sub_section = 'Employee Transport';

        $query = EmployeeTransport::query();
        $searchableColumns = ['service_name', 'transport_type', 'purpose', 'pickup_location', 'drop_location', 'status'];
        $keyword = $request->input('keyword');
        $filters = [];

        // Apply status filter if provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Apply transport type filter if provided
        if ($request->filled('transport_type')) {
            $query->where('transport_type', $request->transport_type);
        }

        // Apply date range filter
        if ($request->filled('start_date')) {
            $query->where('start_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('end_date', '<=', $request->end_date);
        }

        $employeeTransports = $flexsearch->apply($query, $filters, $keyword, $searchableColumns)
            ->latest()
            ->paginate(10);

        if ($request->ajax()) {
            return view('transport.employee_transport.search_results', compact('employeeTransports'))->render();
        }

        return view('transport.employee_transport.index', compact(
            'title',
            'section',
            'sub_section',
            'employeeTransports'
        ));
    }

    public function exportExcel(FlexSearch $flexsearch, Request $request)
    {
        $query = EmployeeTransport::with('getCompany');
        $searchableColumns = ['service_name', 'transport_type', 'purpose', 'pickup_location', 'drop_location', 'status'];
        $keyword = $request->input('keyword');
        $filters = [];

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('transport_type')) {
            $query->where('transport_type', $request->transport_type);
        }

        if ($request->filled('start_date')) {
            $query->where('start_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('end_date', '<=', $request->end_date);
        }

        $records = $flexsearch->apply($query, $filters, $keyword, $searchableColumns)->latest()->get();

        return Excel::download(new EmployeeTransportExport($records), 'employee_transports.xlsx');
    }

    public function printIndex(FlexSearch $flexsearch, Request $request)
    {
        $query = EmployeeTransport::with('getCompany');
        $searchableColumns = ['service_name', 'transport_type', 'purpose', 'pickup_location', 'drop_location', 'status'];
        $keyword = $request->input('keyword');
        $filters = [];

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('transport_type')) {
            $query->where('transport_type', $request->transport_type);
        }

        if ($request->filled('start_date')) {
            $query->where('start_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('end_date', '<=', $request->end_date);
        }

        $records = $flexsearch->apply($query, $filters, $keyword, $searchableColumns)->latest()->get();

        return view('transport.employee_transport.print_index', compact('records'));
    }

    /**
     * Search employee transport services (AJAX).
     */
    public function search(FlexSearch $flexsearch, Request $request)
    {
        $query = EmployeeTransport::query();
        $searchableColumns = ['service_name', 'transport_type', 'purpose', 'pickup_location', 'drop_location', 'status'];
        $keyword = $request->input('keyword');
        $filters = [];

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('transport_type')) {
            $query->where('transport_type', $request->transport_type);
        }
        if ($request->filled('start_date')) {
            $query->where('start_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('end_date', '<=', $request->end_date);
        }

        $employeeTransports = $flexsearch->apply($query, $filters, $keyword, $searchableColumns)
            ->latest()
            ->paginate(10);

        return view('transport.employee_transport.search_results', compact('employeeTransports'))->render();
    }

    /**
     * Show the form for creating a new employee transport service.
     */
    public function create()
    {
        $title = 'New Employee Transport Service';
        $section = 'Employee Transport';
        $section_url = route('transport.employee_transports.index');
        $sub_section = 'Create';

        $companies = Company::where('status', 'Active')->orderBy('name')->get();
        $transportTypes = ['Daily Commute', 'Shuttle Service', 'Special Transport', 'Field Work'];
        $types = ['company' => 'Company', 'branch' => 'Branch', 'division' => 'Division', 'department' => 'Department', 'section' => 'Section'];
        $generalSettings = HelperClass::getGeneralSetting();
        $routeMaps = RouteMap::where('status', 'Active')->orderBy('route_name')->get();

        return view('transport.employee_transport.form', compact(
            'title',
            'section',
            'sub_section',
            'section_url',
            'companies',
            'transportTypes',
            'types',
            'generalSettings',
            'routeMaps'
        ));
    }

    /**
     * Store a newly created employee transport service.
     */
    public function store(StoreEmployeeTransportRequest $request)
    {
        try {
            Log::info('Creating Employee Transport Service');

            $employeeTransport = EmployeeTransport::create($request->validated());

            Log::info('Employee Transport Service Created Successfully');

            return response()->json([
                'success' => true,
                'message' => 'Employee Transport Service Created Successfully',
                'redirect' => route('transport.employee_transports.index')
            ], 201);

        } catch (\Exception $e) {
            Log::error('Employee Transport Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something Went Wrong: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified employee transport service.
     */
    public function show($id)
    {
        $title = 'View Employee Transport Service';
        $section = 'Employee Transport';
        $section_url = route('transport.employee_transports.index');
        $sub_section = 'Details';

        $employeeTransport = $this->transportService->getEmployeeTransportDetails($id);
        $employeeTransport->load('routeMap');

        return view('transport.employee_transport.show', compact(
            'title',
            'section',
            'sub_section',
            'section_url',
            'employeeTransport'
        ));
    }

    /**
     * Show the form for editing the specified employee transport service.
     */
    public function edit($id)
    {
        $title = 'Edit Employee Transport Service';
        $section = 'Employee Transport';
        $section_url = route('transport.employee_transports.index');
        $sub_section = 'Edit';

        $employeeTransport = EmployeeTransport::findOrFail($id);

        // Only allow editing if status is Pending
        if ($employeeTransport->status !== 'Pending') {
            return redirect()->route('transport.employee_transports.index')->with([
                'message' => 'Cannot edit a service that is not pending',
                'alert-type' => 'warning'
            ]);
        }

        $companies = Company::where('status', 'Active')->orderBy('name')->get();
        $transportTypes = ['Daily Commute', 'Shuttle Service', 'Special Transport', 'Field Work'];
        $types = ['company' => 'Company', 'branch' => 'Branch', 'division' => 'Division', 'department' => 'Department', 'section' => 'Section'];
        $generalSettings = HelperClass::getGeneralSetting();
        $routeMaps = RouteMap::where('status', 'Active')->orderBy('route_name')->get();

        return view('transport.employee_transport.form', compact(
            'title',
            'section',
            'sub_section',
            'section_url',
            'employeeTransport',
            'companies',
            'transportTypes',
            'types',
            'generalSettings',
            'routeMaps'
        ));
    }

    /**
     * Update the specified employee transport service in storage.
     */
    public function update(UpdateEmployeeTransportRequest $request, $id)
    {
        try {
            Log::info('Updating Employee Transport Service');

            $employeeTransport = EmployeeTransport::findOrFail($id);

            // Only allow updating if status is Pending
            if ($employeeTransport->status !== 'Pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot update a service that is not pending'
                ], 400);
            }

            $employeeTransport->update($request->validated());

            Log::info('Employee Transport Service Updated Successfully');

            return response()->json([
                'success' => true,
                'message' => 'Employee Transport Service Updated Successfully',
                'redirect' => route('transport.employee_transports.index')
            ], 200);

        } catch (\Exception $e) {
            Log::error('Employee Transport Update Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something Went Wrong: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process rejection of the employee transport service.
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'approval_remarks' => 'required|string|max:1000',
        ], [
            'approval_remarks.required' => 'Please provide a reason for rejection.',
        ]);

        try {
            Log::info('Rejecting Employee Transport Service');

            $employeeTransport = EmployeeTransport::findOrFail($id);

            $employeeTransport->update([
                'status' => 'Rejected',
                'approval_remarks' => $request->approval_remarks,
                'approved_at' => now(),
            ]);

            Log::info('Employee Transport Service Rejected');

            return response()->json([
                'success' => true,
                'message' => 'Service Rejected Successfully',
                'redirect' => route('transport.employee_transports.index')
            ], 200);

        } catch (\Exception $e) {
            Log::error('Rejection Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something Went Wrong'
            ], 500);
        }
    }

    /**
     * Cancel the employee transport service.
     */
    public function cancel($id)
    {
        try {
            Log::info('Cancelling Employee Transport Service');

            $employeeTransport = EmployeeTransport::findOrFail($id);

            if ($employeeTransport->status !== 'Pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only pending services can be cancelled'
                ], 400);
            }

            $employeeTransport->update(['status' => 'Cancelled']);

            Log::info('Employee Transport Service Cancelled');

            return response()->json([
                'success' => true,
                'message' => 'Service Cancelled Successfully',
                'redirect' => route('transport.employee_transports.index')
            ], 200);

        } catch (\Exception $e) {
            Log::error('Cancellation Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something Went Wrong'
            ], 500);
        }
    }

    /**
     * Delete the employee transport service.
     */
    public function destroy($id)
    {
        try {
            Log::info('Deleting Employee Transport Service');

            $employeeTransport = EmployeeTransport::findOrFail($id);
            $employeeTransport->delete();

            Log::info('Employee Transport Service Deleted');

            return response()->json([
                'success' => true,
                'message' => 'Service Deleted Successfully'
            ], 200);

        } catch (\Exception $e) {
            Log::error('Deletion Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something Went Wrong'
            ], 500);
        }
    }
}
