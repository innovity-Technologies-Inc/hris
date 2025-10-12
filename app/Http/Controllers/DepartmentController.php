<?php

namespace App\Http\Controllers;
use App\Models\Department;
use App\Models\Division;
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
        return view('company_setup.departments.form', compact('divisions'));
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate(
            [
                'division_id' => 'required|exists:divisions,id',
                'department_name' => 'required|string|max:255',
                'short_name' => 'required|string|max:50',
                'job_number_code' => 'required|string|max:20',
                'status' => 'required|in:active,inactive',
            ],
            [
                'division_id.required' => 'Please select a division.',
                'department_name.required' => 'Please enter a department name.',
                'short_name.required' => 'Please enter a short name.',
                'job_number_code.required' => 'Please enter a job number code.',
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
        return view('company_setup.departments.form', compact('department', 'divisions'));
    }
    public function update(Request $request, $id)
    {   
        $department = Department::findOrFail($id);

        $validatedData = $request->validate(
            [
                'division_id' => 'required|exists:divisions,id',
                'department_name' => 'required|string|max:255',
                'short_name' => 'required|string|max:50',
                'job_number_code' => 'required|string|max:20',
                'status' => 'required|in:active,inactive',
            ],
            [
                'division_id.required' => 'Please select a division.',
                'department_name.required' => 'Please enter a department name.',
                'short_name.required' => 'Please enter a short name.',
                'job_number_code.required' => 'Please enter a job number code.',
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
