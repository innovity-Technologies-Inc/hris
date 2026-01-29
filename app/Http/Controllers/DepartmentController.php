<?php

namespace App\Http\Controllers;
use App\Models\Department;
use App\Models\Division;
use App\Models\Company;
use App\Models\CompanyLocation;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index(Request $request, FlexSearch $flexsearch)
    {
        $title = 'Departments';
        $section = 'Department Setup';
        $sub_section = 'Departments';
        $query = Department::query()->with(['getDivision', 'getCompany', 'getLocation']);
        $searchTerm = $request->get('keyword');
        $searchableFields = ['department_name', 'short_name', 'getDivision.name', 'getCompany.name', 'getLocation.name'];
        $departments = $flexsearch->apply($query, [], $searchTerm, $searchableFields)->orderBy('id', 'desc')->paginate(10);
        if ($request->ajax()) {
            return view('company_setup.departments.search_results', compact('departments'))->render();
        }
        $divisions = Division::all();
        return view('company_setup.departments.index', compact('title', 'section', 'sub_section', 'departments', 'divisions'));
    }

    public function create()
    {
        $divisions = Division::all();
        $companies = Company::all();
        $locations = CompanyLocation::all();
        return view('company_setup.departments.form', compact('divisions', 'companies', 'locations'));
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate(
            [
                'division_id' => 'nullable|exists:divisions,id',
                'department_name' => 'required|string|max:255',
                'short_name' => 'required|string|max:50',
                'company_id' => 'required|exists:companies,id',
                'location_id' => 'nullable|exists:company_locations,id',
                'status' => 'required|in:active,inactive',
            ],
            [
                'department_name.required' => 'Please enter a department name.',
                'short_name.required' => 'Please enter a short name.',
                'company_id.required' => 'Please select a company.',
                'status.required' => 'Please select a status.',
            ]
        );

        Department::create($validatedData);

        return redirect()->route('departments.index')
            ->with([
                'message' => 'Department Saved Successfully',
                'alert-type' => 'success'
            ]);
    }
    public function edit($id)
    {
        $department = Department::findOrFail($id);
        $divisions = Division::all();
        $companies = \App\Models\Company::all();
        $locations = \App\Models\CompanyLocation::all();
        return view('company_setup.departments.form', compact('department', 'divisions', 'companies', 'locations'));
    }
    public function update(Request $request, $id)
    {
        $department = Department::findOrFail($id);

        $validatedData = $request->validate(
            [
                'division_id' => 'nullable|exists:divisions,id',
                'department_name' => 'required|string|max:255',
                'short_name' => 'required|string|max:50',
                'company_id' => 'required|exists:companies,id',
                'location_id' => 'nullable|exists:company_locations,id',
                'status' => 'required|in:active,inactive',
            ],
            [
                'division_id.required' => 'Please select a division.',
                'department_name.required' => 'Please enter a department name.',
                'short_name.required' => 'Please enter a short name.',
                'company_id.required' => 'Please select a company.',
                'status.required' => 'Please select a status.',
            ]
        );

        $department->update($validatedData);

        return redirect()->route('departments.index')
            ->with([
                'message' => 'Department Updated Successfully',
                'alert-type' => 'success'
            ]);
    }
    public function destroy($id)
    {
        $department = Department::findOrFail($id);
        $department->delete();

        return redirect()->route('departments.index')
            ->with('success', 'Department deleted successfully.');
    }


}
