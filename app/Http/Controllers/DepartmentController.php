<?php

namespace App\Http\Controllers;
use App\Models\Department;
use App\Models\Division;
use App\Models\Company;
use App\Models\CompanyLocation;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $title = 'Departments';
        $section = 'Department Setup';
        $sub_section = 'Departments';
        $departments = Department::latest()->paginate(10);
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
                'division_id' => 'required|exists:divisions,id',
                'department_name' => 'required|string|max:255',
                'short_name' => 'required|string|max:50',
                'company_id' => 'required|exists:companies,id',
                'location_id' => 'required|exists:company_locations,id',
                'status' => 'required|in:active,inactive',
            ],
            [
                'division_id.required' => 'Please select a division.',
                'department_name.required' => 'Please enter a department name.',
                'short_name.required' => 'Please enter a short name.',
                'company_id.required' => 'Please select a company.',
                'location_id.required' => 'Please select a branch.',
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
                'division_id' => 'required|exists:divisions,id',
                'department_name' => 'required|string|max:255',
                'short_name' => 'required|string|max:50',
                'company_id' => 'required|exists:companies,id',
                'location_id' => 'required|exists:company_locations,id',
                'status' => 'required|in:active,inactive',
            ],
            [
                'division_id.required' => 'Please select a division.',
                'department_name.required' => 'Please enter a department name.',
                'short_name.required' => 'Please enter a short name.',
                'company_id.required' => 'Please select a company.',
                'location_id.required' => 'Please select a branch.',
                'status.required' => 'Please select a status.',
            ]
        );

        $department->update($validatedData);        return redirect()->route('departments.index')
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
