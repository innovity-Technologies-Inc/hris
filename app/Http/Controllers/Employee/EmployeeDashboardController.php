<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Employee\Employee;
use App\Services\Employee\EmployeeDashboardServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeDashboardController extends Controller
{
    protected $dashboardServices;

    public function __construct(EmployeeDashboardServices $dashboardServices)
    {
        $this->dashboardServices = $dashboardServices;
    }

    /**
     * Show the dashboard for the logged-in employee.
     */
    public function index()
    {
        $user = Auth::user();
        
        if (!$user->employee_id) {
            return redirect()->route('dashboard')->with([
                'message' => 'This account is not linked to an employee profile.',
                'alert-type' => 'warning'
            ]);
        }

        return $this->show($user->employee_id);
    }

    /**
     * Show the dashboard for a specific employee.
     */
    public function show($id)
    {
        $employee = Employee::withoutGlobalScopes()->with(['officeInfo.getCurrentDesignation', 'officeInfo.getCurrentDepartment'])->findOrFail($id);
        
        // Authorization: Employee can only see their own dashboard
        if (Auth::user()->user_type === 'Employee' && Auth::user()->employee_id != $id) {
            abort(403, 'Unauthorized access to another employee\'s dashboard.');
        }

        // Additional scoping for Company/Unit users can be added here if needed
        // but withoutGlobalScopes allows HR/Admin to see everyone as intended.

        $title = 'Employee Dashboard';
        $section = 'Employee';
        $sub_section = 'Dashboard / ' . $employee->full_name;

        $stats = $this->dashboardServices->getDashboardStats($id);
        $timeline = $this->dashboardServices->getTimelineEvents($id);

        return view('employee_dashboard.index', compact('title', 'section', 'sub_section', 'employee', 'stats', 'timeline'));
    }
}
