<?php

namespace App\Http\Controllers;

use App\HelperClass;
use App\Models\CompanyLocation;
use App\Models\Company;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\Request;


class CompanyLocationController extends Controller
{
    public function index(Request $request, FlexSearch $flexsearch)
    {
        $title = 'Company Branches';
        $section = 'Company Setup';
        $sub_section = 'Company Branches';
        $query = CompanyLocation::query()->with(['getCompany']);
        $searchTerm = $request->get('keyword');
        $searchableFields = ['name', 'location_address', 'city', 'state', 'division', 'country', 'getCompany.name'];
        $locations = $flexsearch->apply($query, [], $searchTerm, $searchableFields)->orderBy('id', 'desc')->paginate(10);
        if ($request->ajax()) {
            return view('company_setup.company_locations.search_results', compact('locations'))->render();
        }
        return view('company_setup.company_locations.index', compact('title', 'section', 'sub_section', 'locations'));
    }


    public function create()
    {
        $companies = Company::all();
        return view('company_setup.company_locations.form', compact('companies'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate(
            [
                'company_id' => 'required',
                'name' => 'required|string|max:255',
                'location_address' => 'required|string|max:255',
                'state' => 'nullable|string|max:255',
                'division' => 'nullable|string|max:255',
                'city' => 'nullable|string|max:255',
                'country' => 'nullable|string|max:255',
                'status' => 'required',
            ],
            [
                'company_id.required' => 'Please select a company.',
                'name.required' => 'Please enter a unit name.',
                'location_address.required' => 'Please enter a location address.',
                'state.required' => 'Please enter a state.',
                'division.required' => 'Please enter a division.',
                'city.required' => 'Please enter a city.',
                'country.required' => 'Please enter a country.',
                'status.required' => 'Please select a status.',
            ]
        );


        CompanyLocation::create($validatedData);

        return redirect()->route('company_locations.index')
            ->with([
                'message' => 'Company Location Saved Successfully',
                'alert-type' => 'success'
            ]);
    }

    public function edit($id)
    {
        $company_location = CompanyLocation::findOrFail($id);
        $companies = Company::all();

        return view('company_setup.company_locations.form', compact('company_location', 'companies'));
    }

    public function update(Request $request, $id)
    {

        $request->validate(
            [
                'company_id' => 'required|exists:companies,id',
                'name' => 'required|string|max:255',
                'location_address' => 'required|string|max:255',
                'state' => 'nullable|string|max:255',
                'division' => 'nullable|string|max:255',
                'city' => 'nullable|string|max:255',
                'country' => 'nullable|string|max:255',
                'status' => 'required|string',
            ],
            [
                'company_id.required' => 'Please select a company.',
                'name.required' => 'Please enter a branch name.',
                'location_address.required' => 'Please enter a location address.',
                'status.required' => 'Please select a status.',
            ]
        );

        $company_location = CompanyLocation::findOrFail($id);
        $company_location->update($request->all());

        return redirect()->route('company_locations.index')
            ->with([
                'message' => 'Company Location updated Successfully',
                'alert-type' => 'success'
            ]);
    }

    public function destroy($id)
    {
        $company_location = CompanyLocation::findOrFail($id);
        $company_location->delete();

        return redirect()->route('company_locations.index')
            ->with('success', 'Company location deleted successfully.');
    }
}
