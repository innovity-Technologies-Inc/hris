<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Company;
use App\Services\EmployeeServices;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\Request;

class EmployeeSearchController extends Controller
{
    protected $employeeServices;

    public function __construct(EmployeeServices $employeeServices)
    {
        $this->employeeServices = $employeeServices;
    }

    /**
     * Display the employee search page with real data from database
     * Uses FlexSearch for efficient searching and filtering
     *
     * @param Request $request
     * @param FlexSearch $flexsearch
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request, FlexSearch $flexsearch)
    {
        $title = 'Search Employee';
        $section = 'Employees';
        $sub_section = 'Search';

        // Build query with necessary relationships
        $query = Employee::with('officeInfo');

        // Handle country filter separately (JSON field)
        if ($request->filled('country')) {
            $query->where('permanent_address->country', $request->input('country'));
        }

        // Handle organizational filters via EmployeeOfficeInfo relationship
        if ($request->filled('company')) {
            $query->whereHas('officeInfo', function($q) use ($request) {
                $q->where('current_company_id', $request->input('company'));
            });
        }

        if ($request->filled('business_unit')) {
            $query->whereHas('officeInfo', function($q) use ($request) {
                $q->where('current_business_unit_id', $request->input('business_unit'));
            });
        }

        if ($request->filled('division')) {
            $query->whereHas('officeInfo', function($q) use ($request) {
                $q->where('current_division_id', $request->input('division'));
            });
        }

        if ($request->filled('department')) {
            $query->whereHas('officeInfo', function($q) use ($request) {
                $q->where('current_department_id', $request->input('department'));
            });
        }

        if ($request->filled('section')) {
            $query->whereHas('officeInfo', function($q) use ($request) {
                $q->where('current_section_id', $request->input('section'));
            });
        }

        // Handle employee type filter via EmployeeOfficeInfo relationship
        if ($request->filled('emp_type')) {
            $query->whereHas('officeInfo', function($q) use ($request) {
                $q->where('emp_type', $request->input('emp_type'));
            });
        }

        // Define searchable columns for FlexSearch fuzzy matching
        $searchableColumns = [
            'applicant_id',
            'full_name',
            'system_id',
            'personal_mobile',
            'work_email',
            'personal_email'
        ];

        // Get keyword search term
        $keyword = $request->input('keyword');

        // Build filters array from request
        $filters = $this->buildFilters($request);

        // Apply FlexSearch with filters and keyword search
        $employees = $flexsearch
            ->apply($query, $filters, $keyword, $searchableColumns)
            ->orderBy('id', 'desc')
            ->paginate(50);

        // Manually load relationships for each employee's office info
        $employees->each(function($employee) {
            if ($employee->officeInfo) {
                $employee->officeInfo->load([
                    'getCurrentCompany',
                    'getCurrentBusinessUnit',
                    'getCurrentDivision',
                    'getCurrentDepartment',
                    'getCurrentSection'
                ]);
            }
        });

        // Return AJAX response if requested
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $employees->items(),
                'total' => $employees->total(),
                'per_page' => $employees->perPage(),
                'current_page' => $employees->currentPage()
            ]);
        }

        // Get filter options from database for dropdowns
        $filterOptions = $this->getFilterOptions();

        return view('search.search_employee', compact(
            'title',
            'section',
            'sub_section',
            'employees',
            'filterOptions'
        ));
    }

    /**
     * Build filters array from request parameters
     * Follows FlexSearch filter format: ['field' => 'value'] or ['field>=' => value]
     *
     * @param Request $request
     * @return array
     */
    private function buildFilters(Request $request)
    {
        $filters = [];

        // Basic search filters - exact matches
        if ($request->filled('employee_id')) {
            $filters['applicant_id'] = $request->input('employee_id');
        }

        if ($request->filled('employee_name')) {
            $filters['full_name'] = $request->input('employee_name');
        }

        if ($request->filled('system_id')) {
            $filters['system_id'] = $request->input('system_id');
        }

        // Personal attributes - exact matches
        if ($request->filled('gender')) {
            $filters['gender'] = $request->input('gender');
        }

        if ($request->filled('marital_status')) {
            $filters['marital_status'] = $request->input('marital_status');
        }

        if ($request->filled('religion')) {
            $filters['religion'] = $request->input('religion');
        }

        if ($request->filled('nationality')) {
            $filters['nationality'] = $request->input('nationality');
        }

        if ($request->filled('blood_group')) {
            $filters['blood_group'] = $request->input('blood_group');
        }

        // Note: Organizational filters (company, branch, division, etc.)
        // would need to be handled via relationships in Employee model
        // For now, they are excluded as Employee model doesn't have direct fields
        // If EmployeeOfficeInfo relationship exists, those filters would be added here

        return $filters;
    }

    /**
     * Get unique filter options from database for dropdown population
     *
     * @return array
     */
    private function getFilterOptions()
    {
        // Get all employees with office info
        $allEmployees = Employee::with('officeInfo')
            ->select(
                'id',
                'applicant_id',
                'system_id',
                'full_name',
                'gender',
                'marital_status',
                'blood_group',
                'religion',
                'nationality',
                'date_of_birth',
                'permanent_address',
                'personal_mobile',
                'work_email'
            )->get();

        // Manually load relationships for each employee's office info
        $allEmployees->each(function($employee) {
            if ($employee->officeInfo) {
                // Load the related models
                $employee->officeInfo->load([
                    'getCurrentCompany',
                    'getCurrentBusinessUnit',
                    'getCurrentDivision',
                    'getCurrentDepartment',
                    'getCurrentSection'
                ]);
            }
        });

        // Get organizational data - only companies loaded initially
        // Other dropdowns (branch, division, department, section) are loaded via AJAX based on company selection
        $companies = Company::select('id', 'name')->orderBy('name')->get();

        return [
            'employees' => $allEmployees,
            'employee_names' => $allEmployees->pluck('full_name', 'full_name')->unique()->sort()->values(),
            'employee_ids' => $allEmployees->pluck('applicant_id', 'applicant_id')->unique()->sort()->values(),
            'system_ids' => $allEmployees->pluck('system_id', 'system_id')->unique()->sort()->values(),
            'genders' => ['Male', 'Female', 'Other'],
            'marital_statuses' => ['Single', 'Married', 'Divorced', 'Widowed'],
            'employee_types' => ['permanent', 'contractual'],
            'blood_groups' => ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'],
            'religions' => $allEmployees->pluck('religion')->filter()->unique()->sort()->values(),
            'nationalities' => $allEmployees->pluck('nationality')->filter()->unique()->sort()->values(),
            'countries' => $allEmployees->map(function($emp) {
                return $emp->permanent_address['country'] ?? null;
            })->filter()->unique()->sort()->values(),
            'companies' => $companies,
        ];
    }

    /**
     * Export search results (future implementation)
     *
     * @param Request $request
     * @param FlexSearch $flexsearch
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(Request $request, FlexSearch $flexsearch)
    {
        // Build same query as index method
        $query = Employee::query();
        $searchableColumns = ['applicant_id', 'full_name', 'system_id'];
        $keyword = $request->input('keyword');
        $filters = $this->buildFilters($request);

        // Get all matching employees (no pagination)
        $employees = $flexsearch
            ->apply($query, $filters, $keyword, $searchableColumns)
            ->orderBy('id', 'desc')
            ->get();

        // Export logic would go here (Excel, PDF, etc.)
        // For now, return JSON
        return response()->json([
            'success' => true,
            'total' => $employees->count(),
            'message' => 'Export functionality to be implemented'
        ]);
    }
}
