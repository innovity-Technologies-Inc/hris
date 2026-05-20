<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;

use App\Models\Company\Department;
use App\Models\Company\Designation;
use App\Models\Company\JobCreation;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\Request;

class JobCreationController extends Controller
{
    public function index(Request $request, FlexSearch $flexsearch){
        $title = 'Job Creation';
        $section = 'Company Setup';
        $sub_section = 'Job Creation';
        $designations = Designation::all()->sortBy('company_designation');
        $departments = Department::all()->sortBy('department_name');
        $query = JobCreation::query()->with(['getDesignation', 'getDepartment']);
        $searchTerm = $request->get('keyword');
        $searchableFields = ['job_ind', 'display_designation', 'display_serial', 'getDesignation.company_designation', 'getDepartment.department_name'];
        $job_creations = $flexsearch->apply($query, [], $searchTerm, $searchableFields)->orderBy('id', 'desc')->paginate(10);
        if ($request->ajax()) {
            return view('company.job_creation.search_results', compact('job_creations'))->render();
        }
        return view('company.job_creation.index', compact('title', 'section', 'sub_section', 'designations', 'departments', 'job_creations'));
    }
    public function create(){
        $title = 'Create Job Creation';
        $section = 'Company Setup';
        $sub_section = 'Job Creation';
        $designations = Designation::all()->sortBy('company_designation');
        $departments = Department::all()->sortBy('department_name');
        return view('company.job_creation.form', compact('title', 'section', 'sub_section', 'designations', 'departments'));
    }
      public function store(Request $request)
    {
        $validatedData =  $request->validate([
            'designation_id' => 'required',
            'department_id' => 'required',
            'job_ind' => 'required|string|max:255',
            'display_designation' => 'required|string|max:255',
            'display_serial' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'remarks' => 'nullable|string',
        ], [
            'designation_id.required' => 'Please select a designation.',
            'department_id.required' => 'Please select a department.',
            'job_ind.required' => 'Please enter a job ind.',
            'display_designation.required' => 'Please enter a display designation.',
            'display_serial.required' => 'Please enter a display serial.',
            'status.required' => 'Please select a status.',
        ]);
        JobCreation::create($validatedData);
        return redirect()->route('job_creations.index')
            ->with([
                'message' => 'Job Creation Saved Successfully',
                'alert-type' => 'success'
            ]);
    }
    public function edit($id){
        $title = 'Edit Job Creation';
        $section = 'Company Setup';
        $sub_section = 'Job Creation';
        $designations = Designation::all()->sortBy('company_designation');
        $departments = Department::all()->sortBy('department_name');
        $job_creation = JobCreation::findOrFail($id);
        return view('company.job_creation.form', compact('title', 'section', 'sub_section', 'designations', 'departments', 'job_creation'));
    }
    public function update(Request $request, $id){
        $validatedData =  $request->validate([
            'designation_id' => 'required',
            'department_id' => 'required',
            'job_ind' => 'required|string|max:255',
            'display_designation' => 'required|string|max:255',
            'display_serial' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'remarks' => 'nullable|string',
        ], [
            'designation_id.required' => 'Please select a designation.',
            'department_id.required' => 'Please select a department.',
            'job_ind.required' => 'Please enter a job ind.',
            'display_designation.required' => 'Please enter a display designation.',
            'display_serial.required' => 'Please enter a display serial.',
            'status.required' => 'Please select a status.',
        ]);
        $job_creation = JobCreation::findOrFail($id);
        $job_creation->update($validatedData);
        return redirect()->route('job_creations.index')
            ->with([
                'message' => 'Job Creation Updated Successfully',
                'alert-type' => 'success'
            ]);
    }
    public function destroy($id){
        $job_creation = JobCreation::findOrFail($id);
        $job_creation->delete();
        return redirect()->route('job_creations.index')
            ->with([
                'message' => 'Job Creation Deleted Successfully',
                'alert-type' => 'success'
            ]);
    }
}

