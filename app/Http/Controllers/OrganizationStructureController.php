<?php

namespace App\Http\Controllers;

use App\Models\OrganizationStructure;
use App\Models\Group;
use App\Models\Company;
use App\Models\CompanyLocation;
use App\Models\Division;
use App\Models\Department;
use App\Models\Section;
use App\HelperClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrganizationStructureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $organizationStructures = OrganizationStructure::with([
            'getGroup',
            'getCompany',
            'getBranchUnit',
            'getDivision',
            'getDepartment',
            'getSection'
        ])->latest()->get();

        return view('organization_structure.index', compact('organizationStructures'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $groups = Group::where('status', 'active')->get();
        $companies = Company::where('status', 'active')->get();
        $locations = CompanyLocation::where('status', 'active')->get();
        $divisions = Division::where('status', 'active')->get();
        $departments = Department::where('status', 'active')->get();
        $sections = Section::where('status', 'active')->get();

        return view('organization_structure.form', compact(
            'groups',
            'companies',
            'locations',
            'divisions',
            'departments',
            'sections'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:group,company,location,division,department,section',
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:organization_structure,email',
            'contact_no' => 'required|string|max:20',
            'address' => 'nullable|string',
            'status' => 'nullable|in:active,inactive',
            'photo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'group_id' => 'nullable|exists:groups,id',
            'company_id' => 'nullable|exists:companies,id',
            'branch_unit_id' => 'nullable|exists:company_locations,id',
            'division_id' => 'nullable|exists:divisions,id',
            'department_id' => 'nullable|exists:departments,id',
            'section_id' => 'nullable|exists:sections,id',
        ]);

        // Custom validation based on type
        $validator->after(function ($validator) use ($request) {
            $type = $request->input('type');

            $typeRequirements = [
                'group' => ['group_id'],
                'company' => ['group_id', 'company_id'],
                'location' => ['group_id', 'company_id', 'branch_unit_id'],
                'division' => ['group_id', 'company_id', 'division_id'],
                'department' => ['group_id', 'company_id', 'division_id', 'department_id'],
                'section' => ['group_id', 'company_id', 'division_id', 'department_id', 'section_id'],
            ];

            if (isset($typeRequirements[$type])) {
                foreach ($typeRequirements[$type] as $field) {
                    if (!$request->filled($field)) {
                        $fieldName = str_replace('_id', '', $field);
                        $validator->errors()->add($field, "The {$fieldName} field is required for {$type} type.");
                    }
                }
            }
        });

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->only([
            'type',
            'name',
            'designation',
            'email',
            'contact_no',
            'address',
            'group_id',
            'company_id',
            'branch_unit_id',
            'division_id',
            'department_id',
            'section_id',
        ]);

        // Transform type and status to match database enum values
        $typeMap = [
            'group' => 'Group',
            'company' => 'Company',
            'location' => 'Branch Unit',
            'division' => 'Division',
            'department' => 'Department',
            'section' => 'Section'
        ];

        $data['type'] = $typeMap[$request->input('type')] ?? $request->input('type');
        $data['status'] = ucfirst($request->input('status', 'active'));

        // Handle profile image upload using HelperClass
        if ($request->hasFile('photo_path')) {
            $photo = $request->file('photo_path');
            $file_path = HelperClass::file_upload($photo, 'organization_structure');
            $data['photo_path'] = $file_path;
        }

        OrganizationStructure::create($data);

        return redirect()->route('organization-structure.index')
            ->with('success', 'Key member added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(OrganizationStructure $organizationStructure)
    {
        $organizationStructure->load([
            'getGroup',
            'getCompany',
            'getBranchUnit',
            'getDivision',
            'getDepartment',
            'getSection'
        ]);

        return view('organization_structure.show', compact('organizationStructure'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $organizationStructure = OrganizationStructure::findOrFail($id);
        $groups = Group::where('status', 'active')->get();
        $companies = Company::where('status', 'active')->get();
        $locations = CompanyLocation::where('status', 'active')->get();
        $divisions = Division::where('status', 'active')->get();
        $departments = Department::where('status', 'active')->get();
        $sections = Section::where('status', 'active')->get();

        return view('organization_structure.form', compact(
            'organizationStructure',
            'groups',
            'companies',
            'locations',
            'divisions',
            'departments',
            'sections'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $organizationStructure = OrganizationStructure::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'type' => 'required|in:group,company,location,division,department,section',
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:organization_structure,email,' . $id,
            'contact_no' => 'required|string|max:20',
            'address' => 'nullable|string',
            'status' => 'nullable|in:active,inactive',
            'photo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'group_id' => 'nullable|exists:groups,id',
            'company_id' => 'nullable|exists:companies,id',
            'branch_unit_id' => 'nullable|exists:company_locations,id',
            'division_id' => 'nullable|exists:divisions,id',
            'department_id' => 'nullable|exists:departments,id',
            'section_id' => 'nullable|exists:sections,id',
        ]);

        // Custom validation based on type
        $validator->after(function ($validator) use ($request) {
            $type = $request->input('type');

            $typeRequirements = [
                'group' => ['group_id'],
                'company' => ['group_id', 'company_id'],
                'location' => ['group_id', 'company_id', 'branch_unit_id'],
                'division' => ['group_id', 'company_id', 'division_id'],
                'department' => ['group_id', 'company_id', 'division_id', 'department_id'],
                'section' => ['group_id', 'company_id', 'division_id', 'department_id', 'section_id'],
            ];

            if (isset($typeRequirements[$type])) {
                foreach ($typeRequirements[$type] as $field) {
                    if (!$request->filled($field)) {
                        $fieldName = str_replace('_id', '', $field);
                        $validator->errors()->add($field, "The {$fieldName} field is required for {$type} type.");
                    }
                }
            }
        });

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->only([
            'type',
            'name',
            'designation',
            'email',
            'contact_no',
            'address',
            'group_id',
            'company_id',
            'branch_unit_id',
            'division_id',
            'department_id',
            'section_id',
        ]);

        // Transform type and status to match database enum values
        $typeMap = [
            'group' => 'Group',
            'company' => 'Company',
            'location' => 'Branch Unit',
            'division' => 'Division',
            'department' => 'Department',
            'section' => 'Section'
        ];

        $data['type'] = $typeMap[$request->input('type')] ?? $request->input('type');
        $data['status'] = ucfirst($request->input('status', 'active'));

        // Handle profile image upload using HelperClass
        if ($request->hasFile('photo_path')) {
            // Delete old image if exists
            if ($organizationStructure->photo_path) {
                HelperClass::file_delete($organizationStructure->photo_path);
            }

            $photo = $request->file('photo_path');
            $file_path = HelperClass::file_upload($photo, 'organization_structure');
            $data['photo_path'] = $file_path;
        }

        $organizationStructure->update($data);

        return redirect()->route('organization-structure.index')
            ->with('success', 'Key member updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $organizationStructure = OrganizationStructure::findOrFail($id);

        // Delete profile image if exists
        if ($organizationStructure->photo_path) {
            HelperClass::file_delete($organizationStructure->photo_path);
        }

        $organizationStructure->delete();

        return redirect()->route('organization-structure.index')
            ->with('success', 'Key member deleted successfully.');
    }

    /**
     * Get all active groups
     */
    public function getGroups()
    {
        $groups = Group::where('status', 'active')->get(['id', 'name']);
        return response()->json($groups);
    }

    /**
     * Get companies by group
     */
    public function getCompanies($group_id)
    {
        $companies = Company::where('status', 'active')
            ->where('group_id', $group_id)
            ->get(['id', 'name']);
        return response()->json($companies);
    }

    /**
     * Get locations by company
     */
    public function getLocations($company_id)
    {
        $locations = CompanyLocation::where('status', 'active')
            ->where('company_id', $company_id)
            ->get(['id', 'unit_name']);
        return response()->json($locations);
    }

    /**
     * Get divisions by company
     */
    public function getDivisions($company_id)
    {
        $divisions = Division::where('status', 'active')
            ->where('company_id', $company_id)
            ->get(['id', 'division_name']);
        return response()->json($divisions);
    }

    /**
     * Get departments by division
     */
    public function getDepartments($division_id)
    {
        $departments = Department::where('status', 'active')
            ->where('division_id', $division_id)
            ->get(['id', 'department_name']);
        return response()->json($departments);
    }

    /**
     * Get sections by department
     */
    public function getSections($department_id)
    {
        $sections = Section::where('status', 'active')
            ->where('department_id', $department_id)
            ->get(['id', 'section_name']);
        return response()->json($sections);
    }
}
