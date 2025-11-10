<?php

namespace App\Http\Controllers;

use App\Models\OrganizationStructure;
use App\Models\Group;
use App\Models\Company;
use App\Models\CompanyLocation;
use App\Models\Division;
use App\Models\Department;
use App\Models\Section;
use App\Models\Employee;
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
            'getSection',
            'getEmployee'
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
        $employees = Employee::all();

        return view('organization_structure.form', compact(
            'groups',
            'companies',
            'locations',
            'divisions',
            'departments',
            'sections',
            'employees'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'member_type' => 'required|in:Board Member,Key Member',
            'type' => 'required|in:group,company,location,division,department,section',
            'name' => 'nullable|string|max:255',
            'position' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:organization_structure,email',
            'contact_no' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'status' => 'nullable|in:active,inactive',
            'photo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'group_id' => 'nullable|exists:groups,id',
            'company_id' => 'nullable|exists:companies,id',
            'branch_unit_id' => 'nullable|exists:company_locations,id',
            'division_id' => 'nullable|exists:divisions,id',
            'department_id' => 'nullable|exists:departments,id',
            'section_id' => 'nullable|exists:sections,id',
            'employee_id' => 'nullable|exists:employees,id',
        ]);

        // Custom validation based on type and member_type
        $validator->after(function ($validator) use ($request) {
            $type = $request->input('type');
            $memberType = $request->input('member_type');

            // Board Member: only group and company types
            if ($memberType === 'Board Member' && !in_array($type, ['group', 'company'])) {
                $validator->errors()->add('type', 'Board Members can only be assigned to Group or Company.');
            }

            // Key Member: only location, division, department, section types
            if ($memberType === 'Key Member' && in_array($type, ['group', 'company'])) {
                $validator->errors()->add('type', 'Key Members cannot be assigned to Group or Company.');
            }

            // Key Member must have employee_id
            if ($memberType === 'Key Member' && !$request->filled('employee_id')) {
                $validator->errors()->add('employee_id', 'Employee selection is required for Key Members.');
            }

            // Board Member must have name, email, contact_no
            if ($memberType === 'Board Member') {
                if (!$request->filled('name')) {
                    $validator->errors()->add('name', 'Name is required for Board Members.');
                }
                if (!$request->filled('email')) {
                    $validator->errors()->add('email', 'Email is required for Board Members.');
                }
                if (!$request->filled('contact_no')) {
                    $validator->errors()->add('contact_no', 'Contact number is required for Board Members.');
                }
            }

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
            'member_type',
            'type',
            'name',
            'position',
            'email',
            'contact_no',
            'address',
            'group_id',
            'company_id',
            'branch_unit_id',
            'division_id',
            'department_id',
            'section_id',
            'employee_id',
        ]);

        // If Key Member, get name from employee
        if ($request->input('member_type') === 'Key Member' && $request->filled('employee_id')) {
            $employee = \App\Models\Employee::find($request->input('employee_id'));
            if ($employee) {
                $data['name'] = $employee->full_name;
            }
        }

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
        $employees = Employee::all();

        return view('organization_structure.form', compact(
            'organizationStructure',
            'groups',
            'companies',
            'locations',
            'divisions',
            'departments',
            'sections',
            'employees'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $organizationStructure = OrganizationStructure::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'member_type' => 'required|in:Board Member,Key Member',
            'type' => 'required|in:group,company,location,division,department,section',
            'name' => 'nullable|string|max:255',
            'position' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:organization_structure,email,' . $id,
            'contact_no' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'status' => 'nullable|in:active,inactive',
            'photo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'group_id' => 'nullable|exists:groups,id',
            'company_id' => 'nullable|exists:companies,id',
            'branch_unit_id' => 'nullable|exists:company_locations,id',
            'division_id' => 'nullable|exists:divisions,id',
            'department_id' => 'nullable|exists:departments,id',
            'section_id' => 'nullable|exists:sections,id',
            'employee_id' => 'nullable|exists:employees,id',
        ]);

        // Custom validation based on type and member_type
        $validator->after(function ($validator) use ($request) {
            $type = $request->input('type');
            $memberType = $request->input('member_type');

            // Board Member: only group and company types
            if ($memberType === 'Board Member' && !in_array($type, ['group', 'company'])) {
                $validator->errors()->add('type', 'Board Members can only be assigned to Group or Company.');
            }

            // Key Member: only location, division, department, section types
            if ($memberType === 'Key Member' && in_array($type, ['group', 'company'])) {
                $validator->errors()->add('type', 'Key Members cannot be assigned to Group or Company.');
            }

            // Key Member must have employee_id
            if ($memberType === 'Key Member' && !$request->filled('employee_id')) {
                $validator->errors()->add('employee_id', 'Employee selection is required for Key Members.');
            }

            // Board Member must have name, email, contact_no
            if ($memberType === 'Board Member') {
                if (!$request->filled('name')) {
                    $validator->errors()->add('name', 'Name is required for Board Members.');
                }
                if (!$request->filled('email')) {
                    $validator->errors()->add('email', 'Email is required for Board Members.');
                }
                if (!$request->filled('contact_no')) {
                    $validator->errors()->add('contact_no', 'Contact number is required for Board Members.');
                }
            }

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
            'member_type',
            'type',
            'name',
            'position',
            'email',
            'contact_no',
            'address',
            'group_id',
            'company_id',
            'branch_unit_id',
            'division_id',
            'department_id',
            'section_id',
            'employee_id',
        ]);

        // If Key Member, get name from employee
        if ($request->input('member_type') === 'Key Member' && $request->filled('employee_id')) {
            $employee = \App\Models\Employee::find($request->input('employee_id'));
            if ($employee) {
                $data['name'] = $employee->full_name;
            }
        }

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

    /**
     * Get employees for Select2
     */
    public function getEmployees(Request $request)
    {
        $search = $request->input('search', '');
        $page = $request->input('page', 1);
        $perPage = 20;

        $query = \App\Models\Employee::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'LIKE', "%{$search}%")
                    ->orWhere('system_id', 'LIKE', "%{$search}%")
                    ->orWhere('applicant_id', 'LIKE', "%{$search}%");
            });
        }

        $employees = $query->select('id', 'full_name', 'system_id')
            ->orderBy('full_name')
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();

        return response()->json($employees);
    }

    /**
     * Get employee by ID
     */
    public function getEmployeeById($id)
    {
        $employee = \App\Models\Employee::select('id', 'full_name', 'system_id')
            ->findOrFail($id);
        return response()->json($employee);
    }
}
