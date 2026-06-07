<?php

namespace App\Http\Controllers\Employee;

use App\Enums\UserType;
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
        if (!auth()->user()->can('employee-management.analytics') || auth()->user()->user_type === UserType::Employee) {
            abort(403, 'Unauthorized access to analytics.');
        }

        $title = 'Workforce Analytics';
        $section = 'Employee';
        $sub_section = 'Analytics';

        $ageDist = $this->reportService->getAgeDistribution();
        $ageStats = $this->reportService->getAgeAnalysis();
        $loyaltyDist = $this->reportService->getServiceLoyalty();
        
        $birthdays = $this->reportService->getUpcomingBirthdays();
        $serviceSummary = $this->reportService->getServiceAnalysis();

        return view('employee.reports', compact(
            'title', 'section', 'sub_section', 
            'ageDist', 'ageStats', 'loyaltyDist', 'birthdays', 'serviceSummary'
        ));
    }

    /**
     * Get hierarchy data for drill-down via AJAX.
     */
    public function getHierarchyDrillDown(Request $request)
    {
        if (!auth()->user()->can('employee-management.analytics') || auth()->user()->user_type === UserType::Employee) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $level = $request->input('level', 'company');
        $parentId = $request->input('parent_id');

        $data = $this->reportService->getDrillDownData($level, $parentId);
        
        return response()->json($data);
    }
}
