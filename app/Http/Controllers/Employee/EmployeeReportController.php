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
        $divisionDist = $this->reportService->getHierarchyDistribution('division');
        $deptDist = $this->reportService->getHierarchyDistribution('department');
        
        $birthdays = $this->reportService->getUpcomingBirthdays();
        $serviceSummary = $this->reportService->getServiceAnalysis();

        return view('employee.reports', compact(
            'title', 'section', 'sub_section', 
            'ageDist', 'ageStats', 'loyaltyDist', 'companyDist', 
            'divisionDist', 'deptDist', 'birthdays', 'serviceSummary'
        ));
    }
}
