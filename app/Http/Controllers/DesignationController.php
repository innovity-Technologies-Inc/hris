<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyLocation;
use App\Models\Designation;
use App\Models\Division;
use Illuminate\Http\Request;

class DesignationController extends Controller
{
    public function index(){
        $title = 'Designation';
        $section = 'Company Setup';
        $sub_section = 'Designation';
        $designations = Designation::orderBy('company_designation')->paginate(10);
        return view('company_setup.designation.index', compact('title', 'section', 'sub_section', 'designations'));
    }
    public function create(){
        $title = 'Create Designation';
        $section = 'Company Setup';
        $sub_section = 'Designation';
        return view('company_setup.designation.form', compact('title', 'section', 'sub_section'));
    }
      public function store(Request $request)
    {
        $validatedData =  $request->validate([
            'designation_level' => 'required|string|max:255',
            'company_designation' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ], [
            'short_name.required' => 'Please enter a short name.',
            'status.required' => 'Please select a status.',
        ]);
        Designation::create($validatedData);
        return redirect()->route('designations.index')
            ->with([
                'message' => 'Designation Saved Successfully',
                'alert-type' => 'success'
            ]);
    }

    public function edit($id){
        $title = 'Edit Designation';
        $section = 'Company Setup';
        $sub_section = 'Designation';
        $designation = Designation::findOrFail($id);
        return view('company_setup.designation.form', compact('title', 'section', 'sub_section', 'designation'));
    }
    public function update(Request $request, $id){
        $validatedData =  $request->validate([
            'designation_level' => 'required|string|max:255',
            'company_designation' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ], [
            'short_name.required' => 'Please enter a short name.',
            'status.required' => 'Please select a status.',
        ]);
        $designation = Designation::findOrFail($id);
        $designation->update($validatedData);
        return redirect()->route('designations.index')
            ->with([
                'message' => 'Designation Updated Successfully',
                'alert-type' => 'success'
            ]);
    }
    public function destroy($id){
        $designation = Designation::findOrFail($id);
        $designation->delete();
        return redirect()->route('designations.index')
            ->with([
                'message' => 'Designation Deleted Successfully',
                'alert-type' => 'success'
            ]);
    }
}

