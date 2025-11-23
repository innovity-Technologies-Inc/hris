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
        $totalActiveMealPlan = !empty($activeMealPlans) ? $activeMealPlans->count() : 0;
        $previousMealPlans = EmployeeMealPlan::where('employee_id', $id)->where('status', 'inactive')->get();
        $totalPreviousMealPlan = !empty($previousMealPlans) ? $previousMealPlans->count() : 0;

        $shiftPlans = ShiftPlan::where('active_ind', 'active')->get();
        $activeShiftPLan = EmployeeShiftPlan::where('employee_id', $id)->where('status', 'active')->first();
        $totalActiveShiftPlan = !empty($activeShiftPLan) ? $activeShiftPLan->count() : 0;
        $previousShiftPlans = EmployeeShiftPlan::where('employee_id', $id)->where('status', 'inactive')->get();
        $totalPreviousShiftPlan = !empty($previousShiftPlans) ? $previousShiftPlans->count() : 0;

        $rosterPlans = RosterPlan::where('status', 'active')->get();
        $activeRosterPLan = EmployeeRosterPlan::where('employee_id', $id)->where('status', 'active')->first();
        $totalActiveRosterPlan = !empty($activeRosterPLan) ? $activeRosterPLan->count() : 0;
        $previousRosterPlans = EmployeeRosterPlan::where('employee_id', $id)->where('status', 'inactive')->get();
        $totalPreviousRosterPlan = !empty($previousRosterPlans) ? $previousRosterPlans->count() : 0;

        $otPlans = OTPlan::where('active_ind', 'active')->get();
        $activeOtPLan = EmployeeOtPlan::where('employee_id', $id)->where('status', 'active')->first();
        $totalActiveOtPlan = !empty($activeOtPLan) ? $activeOtPLan->count() : 0;
        $previousOtPlans = EmployeeOtPlan::where('employee_id', $id)->where('status', 'inactive')->get();
        $totalPreviousOtPlan = !empty($previousOtPlans) ? $previousOtPlans->count() : 0;

        $offDayPlans = OffDayPlan::where('status', 'active')->get();
        $activeOffDayPLan = EmployeeOffdayPlan::where('employee_id', $id)->where('status', 'active')->first();
        $totalActiveOffDayPlan = !empty($activeOffDayPLan) ? $activeOffDayPLan->count() : 0;
        $previousOffDayPlans = EmployeeOffdayPlan::where('employee_id', $id)->where('status', 'inactive')->get();
        $totalPreviousOffDayPlan = !empty($previousOffDayPlans) ? $previousOffDayPlans->count() : 0;

        return view('employees.profile', compact('title', 'section', 'sub_section', 'section_url',
            'employee', 'activeMealPlans', 'previousMealPlans', 'activeShiftPLan', 'previousShiftPlans', 'activeRosterPLan',
            'previousRosterPlans', 'activeOtPLan', 'previousOtPlans', 'activeOffDayPLan', 'previousOffDayPlans', 'offDayPlans',
        'mealPlans', 'shiftPlans', 'rosterPlans', 'otPlans', 'totalActiveMealPlan', 'totalPreviousMealPlan',
        'totalActiveOffDayPlan', 'totalPreviousOffDayPlan', 'totalActiveShiftPlan', 'totalPreviousShiftPlan',
            'totalActiveRosterPlan', 'totalPreviousRosterPlan', 'totalActiveOtPlan', 'totalPreviousOtPlan'));
    }

    public function assignOffDayPlan(Request $request){
        $validated = $this->empPlans->validation($request);
        $this->empPlans->planSave($validated, EmployeeOffdayPlan::class);
        return response()->json(['message' => 'Off Day Plan Assigned Successfully']);
    }

    public function getMealPlanByType($type){
        $meal_plans = MealPlan::where('type', $type)->get();
        return response()->json($meal_plans);
    }
    public function getMealPlanDetails($id){
        $plan = MealPlan::find($id);
        return response()->json([
            'id' => $plan->id,
            'name' => $plan->name,
            'type' => $plan->type,
            'cost' => $plan->cost,
        ]);
    }
    public function getOffDayPlanDetails($id){
        $plan = OffDayPlan::find($id);
        return response()->json([
            'id' => $plan->id,
            'name' => $plan->name,
            'short_name' => $plan->short_name,
            'remuneration' => $plan->remuneration,
            'start_time' => $plan->start_time,
            'end_time' => $plan->end_time,
        ]);
    }


}
