<?php

namespace App\Http\Controllers\Transport;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transport\StoreVehicleRequest;
use App\Http\Requests\Transport\UpdateVehicleRequest;
use App\Models\Transport\Vehicle;
use App\HelperClass;
use App\Services\Transport\TransportService;
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

    public function store(StoreVehicleRequest $request)
    {
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

            Log::info('Vehicle Added Successfully');

            return response()->json([
                'success' => true,
                'message' => 'Vehicle Added Successfully',
                'redirect' => route('transport.vehicles.index')
            ], 201);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something Went Wrong: ' . $e->getMessage()
            ], 500);
        }
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

    public function update(UpdateVehicleRequest $request, $id)
    {
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

            Log::info('Vehicle Updated Successfully');

            return response()->json([
                'success' => true,
                'message' => 'Vehicle Updated Successfully',
                'redirect' => route('transport.vehicles.index')
            ], 200);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something Went Wrong: ' . $e->getMessage()
            ], 500);
        }
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

            Log::info('Vehicle Deleted Successfully');

            return response()->json([
                'success' => true,
                'message' => 'Vehicle Deleted Successfully'
            ], 200);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something Went Wrong'
            ], 500);
        }
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

