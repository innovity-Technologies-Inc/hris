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
        $title = 'Employee Reports & Analytics';
        $section = 'Employee';
        $sub_section = 'Reports';

        $ageDist = $this->reportService->getAgeDistribution();
        $loyaltyDist = $this->reportService->getServiceLoyalty();
        $birthdays = $this->reportService->getUpcomingBirthdays();
        $serviceSummary = $this->reportService->getServiceAnalysis();

        return view('employee.reports', compact(
            'title', 'section', 'sub_section', 
            'ageDist', 'loyaltyDist', 'birthdays', 'serviceSummary'
        ));
    }
}
