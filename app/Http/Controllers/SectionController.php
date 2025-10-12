<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Division;
use App\Models\Section;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function index()
    {
        $title = 'Sections';
        $section = 'Section Setup';
        $sections = Section::latest()->paginate(10);
        $divisions = Division::all();
        $departments = Department::all();

        return view('company_setup.sections.index', compact('title', 'section', 'sections', 'divisions', 'departments'));
    }
    public function create()
    {
        $divisions = Division::all();
        $departments = Department::all();
        return view('company_setup.sections.form', compact('divisions', 'departments'));
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate(
            [
                'division_id' => 'required|exists:divisions,id',
                'department_id' => 'required|exists:departments,id',
                'section_name' => 'required|string|max:255',
                'short_name' => 'required|string|max:50',
                'status' => 'required|in:active,inactive',
            ],
            [
                'division_id.required' => 'Please select a division.',
                'department_id.required' => 'Please select a department.',
                'section_name.required' => 'Please enter a section name.',
                'short_name.required' => 'Please enter a short name.',
                'status.required' => 'Please select a status.',
            ]
        );

        Section::create($validatedData);

        return redirect()->route('sections.index')
            ->with([
                'message' => 'Section Saved Successfully',
                'alert-type' => 'success'
            ]);
    }
    public function edit($id)
    {
        $section = Section::findOrFail($id);
        $divisions = Division::all();
        $departments = Department::all();
        return view('company_setup.sections.form', compact('section', 'divisions', 'departments'));
    }
    public function update(Request $request, $id)
    {   
        $section = Section::findOrFail($id);
        $validatedData = $request->validate(
            [
                'division_id' => 'required|exists:divisions,id',
                'department_id' => 'required|exists:departments,id',
                'section_name' => 'required|string|max:255',
                'short_name' => 'required|string|max:50',
                'status' => 'required|in:active,inactive',
            ],
            [
                'division_id.required' => 'Please select a division.',
                'department_id.required' => 'Please select a department.',
                'section_name.required' => 'Please enter a section name.',
                'short_name.required' => 'Please enter a short name.',
                'status.required' => 'Please select a status.',
            ]
        );
        $section->update($validatedData);
        return redirect()->route('sections.index')
            ->with([
                'message' => 'Section Updated Successfully',
                'alert-type' => 'success'
            ]);
        }
    public function destroy($id)
    {
        $section = Section::findOrFail($id);
        $section->delete();

        return redirect()->route('sections.index')
            ->with([
                'message' => 'Section Deleted Successfully',
                'alert-type' => 'success'
            ]);
        }
}
