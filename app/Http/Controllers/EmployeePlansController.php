<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeMealPlan;
use App\Models\EmployeeOffdayPlan;
use App\Models\EmployeeOtPlan;
use App\Models\EmployeeRosterPlan;
use App\Models\EmployeeShiftPlan;
use App\Models\MealPlan;
use App\Models\OffDayPlan;
use App\Models\OTPlan;
use App\Models\RosterPlan;
use App\Models\ShiftPlan;
use App\Services\EmployeePlansServices;
use Illuminate\Http\Request;

class EmployeePlansController extends Controller
{
    protected $empPlans;

    public function __construct(EmployeePlansServices $empPlans)
    {
        $this->empPlans = $empPlans;
    }

    public function plansView($id)
    {
        $title = 'Employee Profile';
        $section = 'Employees';
        $sub_section = 'Profile';
        $section_url = route('employees.index');
        $employee = Employee::find($id);
        $mealPlans = MealPlan::where('status', 'active')->get();
        $activeMealPlans = EmployeeMealPlan::where('employee_id', $id)->where('status', 'active')->get();
        $previousMealPlans = EmployeeMealPlan::where('employee_id', $id)->where('status', 'inactive')->get();

        $shiftPlans = ShiftPlan::where('active_ind', 'active')->get();
        $activeShiftPLan = EmployeeShiftPlan::where('employee_id', $id)->where('status', 'active')->first();
        $previousShiftPlans = EmployeeShiftPlan::where('employee_id', $id)->where('status', 'inactive')->get();

        $rosterPlans = RosterPlan::where('status', 'active')->get();
        $activeRosterPLan = EmployeeRosterPlan::where('employee_id', $id)->where('status', 'active')->first();
        $previousRosterPlans = EmployeeRosterPlan::where('employee_id', $id)->where('status', 'inactive')->get();

        $otPlans = OTPlan::where('active_ind', 'active')->get();
        $activeOtPLan = EmployeeOtPlan::where('employee_id', $id)->where('status', 'active')->first();
        $previousOtPlans = EmployeeOtPlan::where('employee_id', $id)->where('status', 'inactive')->get();

        $offDayPlans = OffDayPlan::where('status', 'active')->get();
        $activeOffDayPLan = EmployeeOffdayPlan::where('employee_id', $id)->where('status', 'active')->first();
        $previousOffDayPlans = EmployeeOffdayPlan::where('employee_id', $id)->where('status', 'inactive')->get();

        return view('employees.profile', compact('title', 'section', 'sub_section', 'section_url',
            'employee', 'activeMealPlans', 'previousMealPlans', 'activeShiftPLan', 'previousShiftPlans', 'activeRosterPLan',
            'previousRosterPlans', 'activeOtPLan', 'previousOtPlans', 'activeOffDayPLan', 'previousOffDayPlans', 'offDayPlans',
        'mealPlans', 'shiftPlans', 'rosterPlans', 'otPlans'));
    }

    public function createMealPlan(Request $request){

    }


}
