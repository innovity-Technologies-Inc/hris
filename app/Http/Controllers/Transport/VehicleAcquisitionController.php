<?php

namespace App\Http\Controllers\Transport;

use App\Http\Controllers\Controller;
use App\Models\Transport\VehicleAcquisition;
use App\HelperClass;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VehicleAcquisitionController extends Controller
{
    public function index(FlexSearch $flexsearch, Request $request)
    {
        $title = 'Vehicle Acquisition';
        $section = 'Transport';
        $sub_section = 'Vehicle Acquisition';

        $query = VehicleAcquisition::query();
        $searchableColumns = ['vehicle_category', 'model_number', 'ownership_type', 'status'];
        $keyword = $request->input('keyword');
        $filters = [];

        $vehicleAcquisitions = $flexsearch->apply($query, $filters, $keyword, $searchableColumns)->latest()->paginate(10);

        if ($request->ajax()) {
            return view('transport.vehicle_acquisition.search_results', compact('vehicleAcquisitions'))->render();
        }

        return view('transport.vehicle_acquisition.index', compact('title', 'section', 'sub_section', 'vehicleAcquisitions'));
    }

    public function create()
    {
        $title = 'Add Vehicle Acquisition';
        $section = 'Vehicle Acquisition';
        $section_url = route('transport.vehicle_acquisitions.index');
        $sub_section = 'Add';

        return view('transport.vehicle_acquisition.form', compact(
            'title',
            'section',
            'sub_section',
            'section_url'
        ));
    }

    public function show($id)
    {
        $title = 'View Vehicle Acquisition';
        $section = 'Transport';
        $sub_section = 'Vehicle Details';

        $vehicleAcquisition = VehicleAcquisition::findOrFail($id);

        return view('transport.vehicle_acquisition.view', compact('title', 'section', 'sub_section', 'vehicleAcquisition'));
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
            'status' => 'required|in:Active,Inactive',
        ]);

        try {
            Log::info('Adding Vehicle Acquisition');

            $data = $request->except(['license_document', 'vehicle_image', 'purchase_document']);

            // Handle file uploads
            if ($request->hasFile('license_document')) {
                $data['license_document'] = HelperClass::file_upload($request->file('license_document'), 'transport/vehicle_acquisitions/license_documents');
            }

            if ($request->hasFile('vehicle_image')) {
                $data['vehicle_image'] = HelperClass::file_upload($request->file('vehicle_image'), 'transport/vehicle_acquisitions/vehicle_images');
            }

            if ($request->hasFile('purchase_document')) {
                $data['purchase_document'] = HelperClass::file_upload($request->file('purchase_document'), 'transport/vehicle_acquisitions/purchase_documents');
            }

            VehicleAcquisition::create($data);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong',
                'alert-type' => 'error'
            ]);
        }

        Log::info('Vehicle Acquisition Added Successfully');

        return redirect()->route('transport.vehicle_acquisitions.index')->with([
            'message' => 'Vehicle Acquisition Added Successfully',
            'alert-type' => 'success'
        ]);
    }

    public function edit($id)
    {
        $title = 'Edit Vehicle Acquisition';
        $section = 'Vehicle Acquisition';
        $section_url = route('transport.vehicle_acquisitions.index');
        $sub_section = 'Edit';

        $vehicleAcquisition = VehicleAcquisition::findOrFail($id);

        return view('transport.vehicle_acquisition.form', compact(
            'title',
            'section',
            'sub_section',
            'section_url',
            'vehicleAcquisition'
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
            'status' => 'required|in:Active,Inactive',
        ]);

        try {
            Log::info('Updating Vehicle Acquisition');

            $vehicleAcquisition = VehicleAcquisition::findOrFail($id);
            $data = $request->except(['license_document', 'vehicle_image', 'purchase_document']);

            // Handle file uploads
            if ($request->hasFile('license_document')) {
                // Delete old file if exists
                if ($vehicleAcquisition->license_document) {
                    HelperClass::file_delete($vehicleAcquisition->license_document);
                }
                $data['license_document'] = HelperClass::file_upload($request->file('license_document'), 'transport/vehicle_acquisitions/license_documents');
            }

            if ($request->hasFile('vehicle_image')) {
                // Delete old file if exists
                if ($vehicleAcquisition->vehicle_image) {
                    HelperClass::file_delete($vehicleAcquisition->vehicle_image);
                }
                $data['vehicle_image'] = HelperClass::file_upload($request->file('vehicle_image'), 'transport/vehicle_acquisitions/vehicle_images');
            }

            if ($request->hasFile('purchase_document')) {
                // Delete old file if exists
                if ($vehicleAcquisition->purchase_document) {
                    HelperClass::file_delete($vehicleAcquisition->purchase_document);
                }
                $data['purchase_document'] = HelperClass::file_upload($request->file('purchase_document'), 'transport/vehicle_acquisitions/purchase_documents');
            }

            $vehicleAcquisition->update($data);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong',
                'alert-type' => 'error'
            ]);
        }

        Log::info('Vehicle Acquisition Updated Successfully');

        return redirect()->route('transport.vehicle_acquisitions.index')->with([
            'message' => 'Vehicle Acquisition Updated Successfully',
            'alert-type' => 'success'
        ]);
    }

    public function destroy($id)
    {
        try {
            Log::info('Deleting Vehicle Acquisition');

            $vehicleAcquisition = VehicleAcquisition::findOrFail($id);

            // Delete associated files
            if ($vehicleAcquisition->license_document) {
                HelperClass::file_delete($vehicleAcquisition->license_document);
            }
            if ($vehicleAcquisition->vehicle_image) {
                HelperClass::file_delete($vehicleAcquisition->vehicle_image);
            }
            if ($vehicleAcquisition->purchase_document) {
                HelperClass::file_delete($vehicleAcquisition->purchase_document);
            }

            $vehicleAcquisition->delete();

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong',
                'alert-type' => 'error'
            ]);
        }

        Log::info('Vehicle Acquisition Deleted Successfully');

        return redirect()->route('transport.vehicle_acquisitions.index')->with([
            'message' => 'Vehicle Acquisition Deleted Successfully',
            'alert-type' => 'success'
        ]);
    }
}
