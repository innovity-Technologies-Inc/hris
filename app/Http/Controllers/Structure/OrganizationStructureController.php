<?php

namespace App\Http\Controllers\Structure;

use App\Http\Controllers\Controller;
use App\Http\Requests\Structure\StoreKeyPersonRequest;
use App\Http\Requests\Structure\UpdateKeyPersonRequest;
use App\Services\Structure\KeyPeopleServices;
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
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Exception;
use Illuminate\Support\Facades\Log;

class OrganizationStructureController extends Controller
{
    protected $keyPeopleServices;

    public function __construct(KeyPeopleServices $keyPeopleServices)
    {
        $this->keyPeopleServices = $keyPeopleServices;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, FlexSearch $flexsearch)
    {
        $keyword = $request->get('keyword');
        $keyPeople = $this->keyPeopleServices->search($flexsearch, $keyword, 20);

        if ($request->ajax()) {
            return view('structure.search_results', compact('keyPeople'))->render();
        }

        return view('structure.index', compact('keyPeople'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
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
    public function store(StoreKeyPersonRequest $request)
    {
        try {
            $photo = $request->file('photo_path');
            $person = $this->keyPeopleServices->store($request->validated(), $photo);

            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return $this->createdResponse('Key Person created successfully.', [
                    'redirect' => route('organization-structure.index'),
                    'data' => $person
                ]);
            }

            return redirect()->route('organization-structure.index')
                ->with('success', 'Key Person created successfully.');
        } catch (Exception $e) {
            Log::error('Error saving Key Person: ' . $e->getMessage());
            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return $this->errorResponse('Something went wrong. Please try again later.', 500);
            }
            return redirect()->back()->with('error', 'Something went wrong. Please try again later.')->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $member = $this->keyPeopleServices->getById($id);
        return view('structure.show', compact('member'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $organizationStructure = $this->keyPeopleServices->getById($id);
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
    public function update(UpdateKeyPersonRequest $request, $id)
    {
        try {
            $photo = $request->file('photo_path');
            $person = $this->keyPeopleServices->update($id, $request->validated(), $photo);

            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return $this->successResponse('Key Person updated successfully.', [
                    'redirect' => route('organization-structure.index'),
                    'data' => $person
                ]);
            }

            return redirect()->route('organization-structure.index')
                ->with('success', 'Key Person updated successfully.');
        } catch (Exception $e) {
            Log::error('Error updating Key Person: ' . $e->getMessage());
            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return $this->errorResponse('Something went wrong. Please try again later.', 500);
            }
            return redirect()->back()->with('error', 'Something went wrong. Please try again later.')->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $this->keyPeopleServices->delete($id);

            if (request()->expectsJson() || request()->ajax() || request()->wantsJson()) {
                return $this->deletedResponse('Key Person deleted successfully.');
            }

            return redirect()->route('organization-structure.index')
                ->with('success', 'Key Person deleted successfully.');
        } catch (Exception $e) {
            Log::error('Error deleting Key Person: ' . $e->getMessage());
            if (request()->expectsJson() || request()->ajax() || request()->wantsJson()) {
                return $this->errorResponse('Something went wrong. Please try again later.', 500);
            }
            return redirect()->back()->with('error', 'Something went wrong. Please try again later.');
        }
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

        $query = Employee::query();

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
        $employee = Employee::select('id', 'full_name', 'system_id')
            ->findOrFail($id);
        return response()->json($employee);
    }

    /**
     * Display the structural view of the organization
     */
    public function structuralView()
    {
        $groups = Group::where('status', 'active')
            ->withCount(['organizationStructures as key_members_count' => function ($q) {
                $q->where('status', 'Active')
                  ->whereIn('type', ['Group', 'Company']);
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

        $levelMapping = [
            'group' => ['field' => 'group_id', 'types' => ['Group', 'Company']],
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

        if (isset($mapping['types'])) {
            $query->whereIn('type', $mapping['types']);
        }

        $keyPeople = $query->with('getEmployee')
            ->get()
            ->map(function ($member) {
                // Determine photo path: custom photo if present, otherwise employee photo
                $photoPath = $member->photo_path;
                if (!$photoPath && $member->getEmployee) {
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
