<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Services\Employee\EmployeeReportServices;
use Illuminate\Http\Request;

class EmployeeReportController extends Controller
{
    protected $reportService;

    public function __construct(EmployeeReportServices $reportService)
    {
        $this->reportService = $reportService;
    }

    /**
     * Show the employee reports dashboard.
     */
    public function index()
    {
        if (!auth()->user()->can('employee-management.analytics') || auth()->user()->user_type === 'Employee') {
            abort(403, 'Unauthorized access to analytics.');
        }

        $title = 'Workforce Analytics';
        $section = 'Employee';
        $sub_section = 'Analytics';

        $ageDist = $this->reportService->getAgeDistribution();
        $ageStats = $this->reportService->getAgeAnalysis();
        $loyaltyDist = $this->reportService->getServiceLoyalty();
        $companyDist = $this->reportService->getCompanyDistribution();
        
        // Filter Dropdown Options (OrganizationScoped applies automatically)
        $filterOptions = [
            'companies' => \App\Models\Company\Company::pluck('name', 'id')->toArray(),
            'businessUnits' => \App\Models\Company\CompanyLocation::pluck('name', 'id')->toArray(),
            'divisions' => \App\Models\Company\Division::pluck('name', 'id')->toArray(),
            'departments' => \App\Models\Company\Department::pluck('department_name', 'id')->toArray(),
        ];

        // Dynamic Hierarchies
        $generalSettings = \App\HelperClass::getGeneralSetting();
        $dynamicHierarchies = [];

        if (isset($generalSettings->branch_status) && $generalSettings->branch_status == 1) {
            $dynamicHierarchies['branch'] = [
                'title' => 'Business Unit Breakdown',
                'icon' => 'mdi-domain',
                'color' => 'primary',
                'chartType' => 'bar',
                'data' => $this->reportService->getHierarchyDistribution('branch')
            ];
        }
        if (isset($generalSettings->division_status) && $generalSettings->division_status == 1) {
            $dynamicHierarchies['division'] = [
                'title' => 'Division Breakdown',
                'icon' => 'mdi-sitemap',
                'color' => 'purple',
                'chartType' => 'polarArea',
                'data' => $this->reportService->getHierarchyDistribution('division')
            ];
        }
        if (isset($generalSettings->department_status) && $generalSettings->department_status == 1) {
            $dynamicHierarchies['department'] = [
                'title' => 'Department Breakdown',
                'icon' => 'mdi-domain',
                'color' => 'warning',
                'chartType' => 'bar',
                'data' => $this->reportService->getHierarchyDistribution('department')
            ];
        }
        if (isset($generalSettings->section_status) && $generalSettings->section_status == 1) {
            $dynamicHierarchies['section'] = [
                'title' => 'Section Breakdown',
                'icon' => 'mdi-vector-intersection',
                'color' => 'info',
                'chartType' => 'doughnut',
                'data' => $this->reportService->getHierarchyDistribution('section')
            ];
        }

        $birthdays = $this->reportService->getUpcomingBirthdays();
        $serviceSummary = $this->reportService->getServiceAnalysis();

        return view('employee.reports', compact(
            'title', 'section', 'sub_section', 
            'ageDist', 'ageStats', 'loyaltyDist', 'companyDist', 
            'dynamicHierarchies', 'filterOptions', 'birthdays', 'serviceSummary'
        ));
    }

    /**
     * Get filtered hierarchy data via AJAX.
     */
    public function getFilteredHierarchyData(Request $request, $type)
    {
        if (!auth()->user()->can('employee-management.analytics') || auth()->user()->user_type === 'Employee') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $filters = $request->only(['company_id', 'business_unit_id', 'division_id', 'department_id']);
        
        $data = $this->reportService->getHierarchyDistribution($type, $filters);
        
        return response()->json($data);
    }
}
