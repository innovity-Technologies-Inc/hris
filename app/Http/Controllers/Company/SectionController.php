<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;

use App\Models\Company\Company;
use App\Models\Company\CompanyLocation;
use App\Models\Company\Department;
use App\Models\Company\Division;
use App\Models\Company\Section;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function index(Request $request, FlexSearch $flexsearch)
    {
        $title = 'Sections';
        $section = 'Section Setup';
        $query = Section::query()->with(['getCompany', 'getLocation', 'getDivision', 'getDepartment']);
        $searchTerm = $request->get('keyword');
        $searchableFields = ['name', 'short_name', 'getCompany.name', 'getLocation.name', 'getDivision.name', 'getDepartment.department_name'];
        $sections = $flexsearch->apply($query, [], $searchTerm, $searchableFields)->orderBy('id', 'desc')->paginate(10);
        if ($request->ajax()) {
            return view('company.sections.search_results', compact('sections'))->render();
        }
        return view('company.sections.index', compact('title', 'section', 'sections'));
    }
    public function create()
    {
        $companies = Company::all();
        $divisions = Division::all();
        $departments = Department::all();
        $locations = CompanyLocation::all();
        return view('company.sections.form', compact('divisions', 'departments', 'companies', 'locations'));
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate(
            [
                'company_id' => 'required|exists:companies,id',
                'location_id' => 'nullable|exists:company_locations,id',
                'division_id' => 'nullable|exists:divisions,id',
                'department_id' => 'nullable|exists:departments,id',
                'name' => 'required|string|max:255',
                'short_name' => 'required|string|max:50',
                'status' => 'required|in:active,inactive',
            ],
            [
                'name.required' => 'Please enter a section name.',
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
        $companies = Company::all();
        $divisions = Division::all();
        $departments = Department::all();
        $locations = CompanyLocation::all();
        return view('company.sections.form', compact('section', 'divisions', 'departments', 'companies', 'locations'));
    }
    public function update(Request $request, $id)
    {
        $section = Section::findOrFail($id);
        $validatedData = $request->validate(
            [
                'company_id' => 'required|exists:companies,id',
                'location_id' => 'nullable|exists:company_locations,id',
                'division_id' => 'nullable|exists:divisions,id',
                'department_id' => 'nullable|exists:departments,id',
                'name' => 'required|string|max:255',
                'short_name' => 'required|string|max:50',
                'status' => 'required|in:active,inactive',
            ],
            [
                'division_id.required' => 'Please select a division.',
                'department_id.required' => 'Please select a department.',
                'name.required' => 'Please enter a section name.',
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

