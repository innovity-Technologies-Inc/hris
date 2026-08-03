<?php

namespace App\Http\Controllers\Structure;

use App\Http\Controllers\Controller;
use App\Models\Structure\OrganizationStructure;
use App\Models\Company\Group;
use App\Models\Company\Company;
use App\Models\Company\CompanyLocation;
use App\Models\Company\Division;
use App\Models\Company\Department;
use App\Models\Company\Section;
use App\Models\Employee\Employee;
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
        $boardMembers = OrganizationStructure::with([
            'getGroup',
            'getCompany',
            'getBranchUnit',
            'getDivision',
            'getDepartment',
            'getSection',
            'getEmployee'
        ])->where('member_type', 'Board Member')->latest()->paginate(10, ['*'], 'board_page');

        $keyMembers = OrganizationStructure::with([
            'getGroup',
            'getCompany',
            'getBranchUnit',
            'getDivision',
            'getDepartment',
            'getSection',
            'getEmployee'
        ])->where('member_type', 'Key Member')->latest()->paginate(10, ['*'], 'key_page');

        return view('structure.index', compact('boardMembers', 'keyMembers'));
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

        return view('structure.form', compact(
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
                'company' => ['group_id'],
                'location' => ['group_id', 'branch_unit_id'],
                'division' => ['group_id', 'division_id'],
                'department' => ['group_id', 'division_id', 'department_id'],
                'section' => ['group_id', 'division_id', 'department_id', 'section_id'],
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
            $employee = \App\Models\Employee\Employee::find($request->input('employee_id'));
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
    public function show($id)
    {
        $member = OrganizationStructure::with([
            'getGroup',
            'getCompany',
            'getBranchUnit',
            'getDivision',
            'getDepartment',
            'getSection',
            'getEmployee'
        ])->findOrFail($id);

        return view('structure.show', compact('member'));
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

        return view('structure.form', compact(
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
                'company' => ['group_id'],
                'location' => ['group_id', 'branch_unit_id'],
                'division' => ['group_id', 'division_id'],
                'department' => ['group_id', 'division_id', 'department_id'],
                'section' => ['group_id', 'division_id', 'department_id', 'section_id'],
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
            $employee = \App\Models\Employee\Employee::find($request->input('employee_id'));
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
            ->get(['id', 'name']);
        return response()->json($locations);
    }

    /**
     * Get divisions by company
     */
    public function getDivisions($company_id)
    {
        $divisions = Division::where('status', 'active')
            ->where('company_id', $company_id)
            ->get(['id', 'name']);
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
            ->get(['id', 'name']);
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

        $query = \App\Models\Employee\Employee::query();

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
        $employee = \App\Models\Employee\Employee::select('id', 'full_name', 'system_id')
            ->findOrFail($id);
        return response()->json($employee);
    }

    /**
     * Display the structural view of the organization
     */
    public function structuralView()
    {
        // Get all groups with nested relationships
        $groups = Group::where('status', 'active')
            ->withCount(['organizationStructures as key_members_count' => function ($q) {
                $q->where('status', 'Active')
                  ->where('member_type', 'Board Member') // Only Board Members
                  ->whereIn('type', ['Group', 'Company']); // Board Members at group/company level
            }])
            ->with([
                'companies' => function ($query) {
                    $query->where('status', 'active')
                        ->withCount([
                            'employeeOfficeInfos as employees_count' => function ($q) {
                                $q->whereHas('employee');
                            },
                            'organizationStructures as key_members_count' => function ($q) {
                                $q->where('status', 'Active')
                                  ->where('member_type', 'Board Member') // Only Board Members
                                  ->where('type', 'Company');
                            }
                        ])
                        ->with([
                            'locations' => function ($q) {
                                $q->where('status', 'active')
                                    ->withCount([
                                        'employeeOfficeInfos as employees_count' => function ($q2) {
                                            $q2->whereHas('employee');
                                        },
                                        'organizationStructures as key_members_count' => function ($q2) {
                                            $q2->where('status', 'Active')
                                              ->where('type', 'Branch Unit');
                                        }
                                    ])
                                    ->with([
                                        'divisions' => function ($divQ) {
                                            $divQ->where('status', 'active')
                                                ->withCount([
                                                    'employeeOfficeInfos as employees_count' => function ($q2) {
                                                        $q2->whereHas('employee');
                                                    },
                                                    'organizationStructures as key_members_count' => function ($q2) {
                                                        $q2->where('status', 'Active')
                                                          ->where('type', 'Division');
                                                    }
                                                ])
                                                ->with([
                                                    'departments' => function ($deptQ) {
                                                        $deptQ->where('status', 'active')
                                                            ->withCount([
                                                                'employeeOfficeInfos as employees_count' => function ($q2) {
                                                                    $q2->whereHas('employee');
                                                                },
                                                                'organizationStructures as key_members_count' => function ($q2) {
                                                                    $q2->where('status', 'Active')
                                                                      ->where('type', 'Department');
                                                                }
                                                            ])
                                                            ->with([
                                                                'sections' => function ($secQ) {
                                                                    $secQ->where('status', 'active')
                                                                        ->withCount([
                                                                            'employeeOfficeInfos as employees_count' => function ($q2) {
                                                                                $q2->whereHas('employee');
                                                                            },
                                                                            'organizationStructures as key_members_count' => function ($q2) {
                                                                                $q2->where('status', 'Active')
                                                                                  ->where('type', 'Section');
                                                                            }
                                                                        ]);
                                                                }
                                                            ]);
                                                    }
                                                ]);
                                        }
                                    ]);
                            }
                        ]);
                }
            ])
            ->get();

        return view('structure.structure_view', compact('groups'));
    }

    /**
     * Get key people for a specific level
     */
    public function getKeyPeople($level, $id)
    {
        $query = OrganizationStructure::where('status', 'Active');

        // Map level to database field and type
        $levelMapping = [
            'group' => ['field' => 'group_id', 'types' => ['Group', 'Company']], // Board Members at group/company level
            'company' => ['field' => 'company_id', 'types' => ['Company']],
            'location' => ['field' => 'branch_unit_id', 'types' => ['Branch Unit']],
            'division' => ['field' => 'division_id', 'types' => ['Division']],
            'department' => ['field' => 'department_id', 'types' => ['Department']],
            'section' => ['field' => 'section_id', 'types' => ['Section']],
        ];

        if (!isset($levelMapping[$level])) {
            return response()->json([]);
        }

        $mapping = $levelMapping[$level];

        $query->where($mapping['field'], $id);

        // Filter by type(s)
        if (isset($mapping['types'])) {
            $query->whereIn('type', $mapping['types']);
        }

        // For group and company levels, show only Board Members
        if (in_array($level, ['group', 'company'])) {
            $query->where('member_type', 'Board Member');
        }

        $keyPeople = $query->with('getEmployee')
            ->get()
            ->map(function ($member) {
                // Determine photo path based on member type
                $photoPath = null;
                if ($member->member_type === 'Board Member') {
                    // Board members have photos in organization_structure table
                    $photoPath = $member->photo_path;
                } elseif ($member->member_type === 'Key Member' && $member->getEmployee) {
                    // Key members (employees) have photos in employees table
                    $photoPath = $member->getEmployee->photo_path;
                }

                return [
                    'id' => $member->id,
                    'name' => $member->name ?? ($member->getEmployee ? $member->getEmployee->full_name : 'N/A'),
                    'position' => $member->position ?? 'N/A',
                    'employee_id' => $member->employee_id,
                    'member_type' => $member->member_type,
                    'photo_path' => $photoPath,
                ];
            });

        return response()->json($keyPeople);
    }
}

