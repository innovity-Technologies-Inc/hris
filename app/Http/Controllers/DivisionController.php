<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyLocation;
use App\Models\Division;
use Illuminate\Http\Request;

class DivisionController extends Controller
{
    public function index()
    {
        $title = 'Company Divisions';
        $section = 'Division Setup';
        $sub_section = 'Company Divisions';
        $divisions = Division::latest()->paginate(10);
        $companies = Company::all();
        $locations = CompanyLocation::all();
        return view('company_setup.divisions.index', compact('title', 'section', 'sub_section', 'divisions', 'companies', 'locations'));
    }
    public function create()
    {
        $companies = Company::all();
        $locations = CompanyLocation::all();
        return view('company_setup.divisions.form', compact('companies', 'locations'));
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate(
            [
                'company_id' => 'required',
                'location_id' => 'required',
                'division_name' => 'required|string|max:255',
                'short_name' => 'required|string|max:50',
                'remarks' => 'nullable|string',
                'status' => 'required|in:active,inactive',
            ],
            [
                'division_name.required' => 'Please enter a division name.',
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
        return view('company_setup.divisions.form', compact('division', 'companies', 'locations'));
    }

    public function update(Request $request, $id)
    {

        $request->validate(
            [
                'company_id' => 'required',
                'location_id' => 'required',
                'division_name' => 'required|string|max:255',
                'short_name' => 'required|string|max:50',
                'remarks' => 'nullable|string|max:500',
                'status' => 'required',

            ],
            [
                'division_name.required' => 'Please enter a division name.',
                'short_name.required' => 'Please enter a short name.',
                'status.required' => 'Please select a status.',
                'company_id.required' => 'Please select a company.',
                'location_id.required' => 'Please select a location.',
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
