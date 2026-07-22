<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\EmployeeSearchRequest;
use App\Services\Employee\EmployeeSearchServices;
use App\Exports\Employee\EmployeeSearchExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\JsonResponse;

class EmployeeSearchController extends Controller
{
    protected $searchServices;

    public function __construct(EmployeeSearchServices $searchServices)
    {
        $this->searchServices = $searchServices;
    }

    /**
     * Display the employee search page
     */
    public function index()
    {
        $title = 'Search Employee';
        $section = 'Employees';
        $sub_section = 'Search';

        $filterOptions = $this->searchServices->getFilterOptions();

        return view('employee.search_employee', compact(
            'title',
            'section',
            'sub_section',
            'filterOptions'
        ));
    }

    /**
     * Get search results as JSON
     */
    public function search(EmployeeSearchRequest $request): JsonResponse
    {
        $employees = $this->searchServices->searchEmployees($request->validated());

        // Format employee data for client-side processing
        $formatted = $employees->map(function ($employee) {
            $age = $employee->date_of_birth
                ? \Carbon\Carbon::parse($employee->date_of_birth)->age
                : null;
            
            $officeInfo = $employee->officeInfo;

            return [
                'id' => $employee->id,
                'systemId' => $employee->system_id ?? '',
                'employeeId' => $employee->applicant_id ?? '',
                'name' => strtolower($employee->full_name ?? ''),
                'fullName' => $employee->full_name ?? '',
                'age' => $age,
                'gender' => $employee->gender ?? '',
                'maritalStatus' => $employee->marital_status ?? '',
                'bloodGroup' => $employee->blood_group ?? '',
                'religion' => $employee->religion ?? '',
                'nationality' => $employee->nationality ?? '',
                'country' => $employee->permanent_address['country'] ?? '',
                'email' => $employee->work_email ?? $employee->personal_email ?? '',
                'phone' => $employee->personal_mobile ?? '',
                'companyId' => $officeInfo->current_company_id ?? '',
                'company' => $officeInfo->getCurrentCompany->name ?? '',
                'businessUnitId' => $officeInfo->current_business_unit_id ?? '',
                'branch' => $officeInfo->getCurrentBusinessUnit->name ?? '',
                'divisionId' => $officeInfo->current_division_id ?? '',
                'division' => $officeInfo->getCurrentDivision->name ?? '',
                'departmentId' => $officeInfo->current_department_id ?? '',
                'department' => $officeInfo->getCurrentDepartment->department_name ?? '',
                'sectionId' => $officeInfo->current_section_id ?? '',
                'section' => $officeInfo->getCurrentSection->name ?? '',
                'employeeType' => $officeInfo->emp_type ?? '',
            ];
        });

        return $this->successResponse('Employees retrieved successfully.', $formatted);
    }

    /**
     * Export search results to Excel
     */
    public function export(EmployeeSearchRequest $request)
    {
        $employees = $this->searchServices->searchEmployees($request->validated());

        return Excel::download(new EmployeeSearchExport($employees), 'employee_search_export.xlsx');
    }
}
