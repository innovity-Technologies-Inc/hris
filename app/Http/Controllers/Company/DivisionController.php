<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;

use App\Models\Company\Company;
use App\Models\Company\CompanyLocation;
use App\Models\Company\Division;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\Request;

class DivisionController extends Controller
{
    public function index(Request $request, FlexSearch $flexsearch)
    {
        $title = 'Company Divisions';
        $section = 'Division Setup';
        $sub_section = 'Company Divisions';
        $query = Division::query()->with(['getCompany', 'getLocation']);
        $searchTerm = $request->get('keyword');
        $searchableFields = ['getCompany.name', 'name', 'short_name', 'getLocation.name'];
        $divisions = $flexsearch->apply( $query, [], $searchTerm, $searchableFields)->orderBy('id', 'desc')->paginate(10);
        if ($request->ajax()) {
            return view('company.divisions.search_results', compact('divisions'))->render();
        }
        return view('company.divisions.index', compact('title', 'section', 'sub_section', 'divisions'));
    }
    public function create()
    {
        $companies = Company::all();
        return view('company.divisions.form', compact('companies'));
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate(
            [
                'company_id' => 'required',
                'location_id' => 'nullable|string|max:255',
                'name' => 'required|string|max:255',
                'short_name' => 'required|string|max:50',
                'status' => 'required|in:active,inactive',
            ],
            [
                'name.required' => 'Please enter a division name.',
                'short_name.required' => 'Please enter a short name.',
                'status.required' => 'Please select a status.',
            ]
        );

        Division::create($validatedData);

        return redirect()->route('divisions.index')
            ->with([
                'message' => 'Division Saved Successfully',
                'alert-type' => 'success'
            ]);
    }

    public function edit($id)
    {
        $division = Division::findOrFail($id);
        $companies = Company::all();
        $locations = CompanyLocation::all();
        return view('company.divisions.form', compact('division', 'companies', 'locations'));
    }

    public function update(Request $request, $id)
    {

        $request->validate(
            [
                'company_id' => 'required',
                'location_id' => 'nullable|string|max:255',
                'name' => 'required|string|max:255',
                'short_name' => 'required|string|max:50',
                'status' => 'required',

            ],
            [
                'name.required' => 'Please enter a division name.',
                'short_name.required' => 'Please enter a short name.',
                'status.required' => 'Please select a status.',
                'company_id.required' => 'Please select a company.',
            ]
        );

        $division = Division::findOrFail($id);
        $division->update($request->all());

        return redirect()->route('divisions.index')
            ->with([
                'message' => 'Division updated Successfully',
                'alert-type' => 'success'
            ]);
    }

    public function destroy($id)
    {
        $division = Division::findOrFail($id);
        $division->delete();

        return redirect()->route('divisions.index')
            ->with('success', 'Division deleted successfully.');
    }
}

