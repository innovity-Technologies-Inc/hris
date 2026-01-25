<?php

namespace App\Http\Controllers\Transport;

use App\Http\Controllers\Controller;
use App\Models\Transport\Vehicle;
use App\HelperClass;
use App\Services\TransportService;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VehicleController extends Controller
{
    protected TransportService $transportService;

    public function __construct(TransportService $transportService)
    {
        $this->transportService = $transportService;
    }
    public function index(FlexSearch $flexsearch, Request $request)
    {
        $title = 'Vehicles';
        $section = 'Transport';
        $sub_section = 'Vehicles';

        $query = Vehicle::query();
        $searchableColumns = ['vehicle_category', 'model_number', 'ownership_type', 'status'];
        $keyword = $request->input('keyword');
        $filters = [];

        $vehicles = $flexsearch->apply($query, $filters, $keyword, $searchableColumns)->latest()->paginate(10);

        if ($request->ajax()) {
            return view('transport.vehicle.search_results', compact('vehicles'))->render();
        }

        return view('transport.vehicle.index', compact('title', 'section', 'sub_section', 'vehicles'));
    }

    public function create()
    {
        $title = 'Add Vehicle';
        $section = 'Vehicles';
        $section_url = route('transport.vehicles.index');
        $sub_section = 'Add';

        return view('transport.vehicle.form', compact(
            'title',
            'section',
            'sub_section',
            'section_url'
        ));
    }

    public function show($id)
    {
        $title = 'View Vehicle';
        $section = 'Transport';
        $sub_section = 'Vehicle Details';

        $vehicle = Vehicle::findOrFail($id);

        return view('transport.vehicle.view', compact('title', 'section', 'sub_section', 'vehicle'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'vehicle_category' => 'required|in:Car,Bus,Micro Bus,Truck,Bike,Van,Airplane,Ship',
            'model_number' => 'required|string|max:255',
            'manufacture_year' => 'required|digits:4|integer|min:1900|max:' . (date('Y') + 1),
            'body_type' => 'nullable|string|max:255',
            'fuel_type' => 'required|in:Petrol,Diesel,CNG,Electric',
            'engine_capacity' => 'nullable|string|max:50',
            'seating_capacity' => 'nullable|integer|min:1|max:500',
            'color' => 'nullable|string|max:100',
            'mileage' => 'nullable|numeric|min:0',
            'license_number' => 'nullable|string|max:100',
            'license_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'vehicle_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:5120',
            'purchase_type' => 'required|in:Purchase,Lease,Rent',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'purchase_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'ownership_type' => 'required|in:Company-owned,Third-party',
            'third_party_name' => 'nullable|required_if:ownership_type,Third-party|string|max:255',
            'is_allocated' => 'nullable|boolean',
            'allocation_purpose' => 'nullable|string|max:255',
            'allocation_type' => 'nullable|in:trip,transport',
            'status' => 'required|in:Active,Inactive',
        ]);

        try {
            Log::info('Adding Vehicle');

            $data = $request->except(['license_document', 'vehicle_image', 'purchase_document']);
            $data['is_allocated'] = $request->has('is_allocated') ? 1 : 0;

            // Handle file uploads
            if ($request->hasFile('license_document')) {
                $data['license_document'] = HelperClass::file_upload($request->file('license_document'), 'transport/vehicles/license_documents');
            }

            if ($request->hasFile('vehicle_image')) {
                $data['vehicle_image'] = HelperClass::file_upload($request->file('vehicle_image'), 'transport/vehicles/vehicle_images');
            }

            if ($request->hasFile('purchase_document')) {
                $data['purchase_document'] = HelperClass::file_upload($request->file('purchase_document'), 'transport/vehicles/purchase_documents');
            }

            Vehicle::create($data);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong',
                'alert-type' => 'error'
            ]);
        }

        Log::info('Vehicle Added Successfully');

        return redirect()->route('transport.vehicles.index')->with([
            'message' => 'Vehicle Added Successfully',
            'alert-type' => 'success'
        ]);
    }

    public function edit($id)
    {
        $title = 'Edit Vehicle';
        $section = 'Vehicles';
        $section_url = route('transport.vehicles.index');
        $sub_section = 'Edit';

        $vehicle = Vehicle::findOrFail($id);

        return view('transport.vehicle.form', compact(
            'title',
            'section',
            'sub_section',
            'section_url',
            'vehicle'
        ));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'vehicle_category' => 'required|in:Car,Bus,Micro Bus,Truck,Bike,Van,Airplane,Ship',
            'model_number' => 'required|string|max:255',
            'manufacture_year' => 'required|digits:4|integer|min:1900|max:' . (date('Y') + 1),
            'body_type' => 'nullable|string|max:255',
            'fuel_type' => 'required|in:Petrol,Diesel,CNG,Electric',
            'engine_capacity' => 'nullable|string|max:50',
            'seating_capacity' => 'nullable|integer|min:1|max:500',
            'color' => 'nullable|string|max:100',
            'mileage' => 'nullable|numeric|min:0',
            'license_number' => 'nullable|string|max:100',
            'license_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'vehicle_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:5120',
            'purchase_type' => 'required|in:Purchase,Lease,Rent',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'purchase_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'ownership_type' => 'required|in:Company-owned,Third-party',
            'third_party_name' => 'nullable|required_if:ownership_type,Third-party|string|max:255',
            'is_allocated' => 'nullable|boolean',
            'allocation_purpose' => 'nullable|string|max:255',
            'allocation_type' => 'nullable|in:trip,transport',
            'status' => 'required|in:Active,Inactive',
        ]);

        try {
            Log::info('Updating Vehicle');

            $vehicle = Vehicle::findOrFail($id);
            $data = $request->except(['license_document', 'vehicle_image', 'purchase_document']);
            $data['is_allocated'] = $request->has('is_allocated') ? 1 : 0;

            // Handle file uploads
            if ($request->hasFile('license_document')) {
                // Delete old file if exists
                if ($vehicle->license_document) {
                    HelperClass::file_delete($vehicle->license_document);
                }
                $data['license_document'] = HelperClass::file_upload($request->file('license_document'), 'transport/vehicles/license_documents');
            }

            if ($request->hasFile('vehicle_image')) {
                // Delete old file if exists
                if ($vehicle->vehicle_image) {
                    HelperClass::file_delete($vehicle->vehicle_image);
                }
                $data['vehicle_image'] = HelperClass::file_upload($request->file('vehicle_image'), 'transport/vehicles/vehicle_images');
            }

            if ($request->hasFile('purchase_document')) {
                // Delete old file if exists
                if ($vehicle->purchase_document) {
                    HelperClass::file_delete($vehicle->purchase_document);
                }
                $data['purchase_document'] = HelperClass::file_upload($request->file('purchase_document'), 'transport/vehicles/purchase_documents');
            }

            $vehicle->update($data);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong',
                'alert-type' => 'error'
            ]);
        }

        Log::info('Vehicle Updated Successfully');

        return redirect()->route('transport.vehicles.index')->with([
            'message' => 'Vehicle Updated Successfully',
            'alert-type' => 'success'
        ]);
    }

    public function destroy($id)
    {
        try {
            Log::info('Deleting Vehicle');

            $vehicle = Vehicle::findOrFail($id);

            // Delete associated files
            if ($vehicle->license_document) {
                HelperClass::file_delete($vehicle->license_document);
            }
            if ($vehicle->vehicle_image) {
                HelperClass::file_delete($vehicle->vehicle_image);
            }
            if ($vehicle->purchase_document) {
                HelperClass::file_delete($vehicle->purchase_document);
            }

            $vehicle->delete();

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong',
                'alert-type' => 'error'
            ]);
        }

        Log::info('Vehicle Deleted Successfully');

        return redirect()->route('transport.vehicles.index')->with([
            'message' => 'Vehicle Deleted Successfully',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Display vehicle history including creation, drivers, allocations, and current status.
     */
    public function history($id)
    {
        $title = 'Vehicle History';
        $section = 'Transport';
        $sub_section = 'Vehicle History';

        try {
            $historyData = $this->transportService->getVehicleHistory($id);

            return view('transport.vehicle.history', compact(
                'title',
                'section',
                'sub_section',
                'historyData'
            ));
        } catch (\Exception $e) {
            Log::error('Error fetching vehicle history: ' . $e->getMessage());
            return redirect()->route('transport.vehicles.index')->with([
                'message' => 'Error loading vehicle history',
                'alert-type' => 'error'
            ]);
        }
    }
}
