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
use Carbon\Carbon;
use Illuminate\Http\Request;

class EmployeePlansController extends Controller
{
    protected $empPlans;

    public function __construct(EmployeePlansServices $empPlans)
    {
        $this->empPlans = $empPlans;
    }

    public function plansView($id, Request $request, $type)
    {
        $title = 'Employee Profile';
        $section = 'Employees';
        $sub_section = 'Profile';
        $section_url = route('employees.index');
        $employee = Employee::find($id);
        if ($type === 'meal-plans'){
            $mealPlans = MealPlan::where('status', 'active')->get();
            $activeMealPlans = EmployeeMealPlan::where('employee_id', $id)->where('status', 'active')->get();
            $totalActiveMealPlan = !empty($activeMealPlans) ? $activeMealPlans->count() : 0;
            $previousMealPlans = EmployeeMealPlan::where('employee_id', $id)->where('status', 'inactive')->get();
            $totalPreviousMealPlan = !empty($previousMealPlans) ? $previousMealPlans->count() : 0;

            if ($request->ajax()) {
                return view('employees.partials.profile_view.partials.meal_plan', compact('title', 'section', 'sub_section', 'section_url',
                    'employee', 'mealPlans', 'activeMealPlans', 'previousMealPlans', 'totalActiveMealPlan',
                    'totalPreviousMealPlan', 'type'))->render();
            }

            return view('employees.profile', compact('title', 'section', 'sub_section', 'section_url',
                'employee', 'mealPlans', 'activeMealPlans', 'previousMealPlans', 'totalActiveMealPlan',
                'totalPreviousMealPlan', 'type'));
        }elseif ($type === 'shift-plans'){
            $shiftPlans = ShiftPlan::where('active_ind', 'active')->get();
            $activeShiftPLan = EmployeeShiftPlan::where('employee_id', $id)->where('status', 'active')->first();
            $totalActiveShiftPlan = !empty($activeShiftPLan) ? 1 : 0;
            $previousShiftPlans = EmployeeShiftPlan::where('employee_id', $id)->where('status', 'inactive')->get();
            $totalPreviousShiftPlan = !empty($previousShiftPlans) ? $previousShiftPlans->count() : 0;

            if ($request->ajax()) {
                return view('employees.partials.profile_view.partials.shift_plan', compact('title', 'section', 'sub_section', 'section_url',
                    'employee', 'activeShiftPLan', 'previousShiftPlans', 'shiftPlans', 'totalActiveShiftPlan', 'totalPreviousShiftPlan', 'type'))->render();
            }

            return view('employees.profile', compact('title', 'section', 'sub_section', 'section_url',
                'employee', 'activeShiftPLan', 'previousShiftPlans', 'shiftPlans', 'totalActiveShiftPlan', 'totalPreviousShiftPlan', 'type'));

        }elseif ($type === 'roster-plans'){
            $rosterPlans = RosterPlan::where('status', 'active')->get();
            $activeRosterPLan = EmployeeRosterPlan::where('employee_id', $id)->where('status', 'active')->first();
            $totalActiveRosterPlan = !empty($activeRosterPLan) ? 1 : 0;
            $previousRosterPlans = EmployeeRosterPlan::where('employee_id', $id)->where('status', 'inactive')->get();
            $totalPreviousRosterPlan = !empty($previousRosterPlans) ? $previousRosterPlans->count() : 0;

            if ($request->ajax()) {
                return view('employees.partials.profile_view.partials.roster_plan', compact('title', 'section', 'sub_section', 'section_url',
                    'employee', 'activeRosterPLan',
                    'previousRosterPlans', 'rosterPlans',
                    'totalActiveRosterPlan', 'totalPreviousRosterPlan', 'type'))->render();
            }

            return view('employees.profile', compact('title', 'section', 'sub_section', 'section_url',
                'employee', 'activeRosterPLan',
                'previousRosterPlans', 'rosterPlans',
                'totalActiveRosterPlan', 'totalPreviousRosterPlan', 'type'));
        }elseif ($type === 'ot-plans'){
            $otPlans = OTPlan::where('active_ind', 'active')->get();
            $activeOtPLan = EmployeeOtPlan::where('employee_id', $id)->where('status', 'active')->first();
            $totalActiveOtPlan = !empty($activeOtPLan) ? 1 : 0;
            $previousOtPlans = EmployeeOtPlan::where('employee_id', $id)->where('status', 'inactive')->get();
            $totalPreviousOtPlan = !empty($previousOtPlans) ? $previousOtPlans->count() : 0;

            if ($request->ajax()) {
                return view('employees.partials.profile_view.partials.ot_plan', compact('title', 'section', 'sub_section', 'section_url',
                    'employee', 'activeOtPLan', 'previousOtPlans', 'otPlans', 'totalActiveOtPlan', 'totalPreviousOtPlan', 'type'))->render();
            }

            return view('employees.profile', compact('title', 'section', 'sub_section', 'section_url',
                'employee', 'activeOtPLan', 'previousOtPlans', 'otPlans', 'totalActiveOtPlan', 'totalPreviousOtPlan', 'type'));

        }elseif ($type === 'offday-plans'){
            $offDayPlans = OffDayPlan::where('status', 'active')->get();
            $activeOffDayPLan = EmployeeOffdayPlan::where('employee_id', $id)->where('status', 'active')->first();
            $totalActiveOffDayPlan = !empty($activeOffDayPLan) ? 1 : 0;
            $previousOffDayPlans = EmployeeOffdayPlan::where('employee_id', $id)->where('status', 'inactive')->get();
            $totalPreviousOffDayPlan = !empty($previousOffDayPlans) ? $previousOffDayPlans->count() : 0;

            if ($request->ajax()) {
                return view('employees.partials.profile_view.partials.offday_plan', compact('title', 'section', 'sub_section', 'section_url',
                    'employee', 'activeOffDayPLan', 'previousOffDayPlans', 'offDayPlans',
                    'totalActiveOffDayPlan', 'totalPreviousOffDayPlan', 'type'))->render();
            }

            return view('employees.profile', compact('title', 'section', 'sub_section', 'section_url',
                'employee', 'activeOffDayPLan', 'previousOffDayPlans', 'offDayPlans',
                'totalActiveOffDayPlan', 'totalPreviousOffDayPlan', 'type'));

        }

    }

    public function assignPlan(Request $request, $type)
    {
        $validated = $this->empPlans->validation($request);

        if ($type === 'meal-plans'){

        }elseif ($type === 'shift-plans'){
            $this->empPlans->planSave($validated, EmployeeShiftPlan::class);
        }elseif ($type === 'roster-plans'){
            $this->empPlans->planSave($validated, EmployeeRosterPlan::class);
        }elseif ($type === 'ot-plans'){
            $this->empPlans->planSave($validated, EmployeeOtPlan::class);
        }elseif ($type === 'offday-plans'){
            $this->empPlans->planSave($validated, EmployeeOffdayPlan::class);
        }

        return redirect()->back()->with([
            'message' => 'Plan Assigned Successfully',
            'alert-type' => 'success',
        ]);
    }

    public function removePlan($type, $id)
    {
        if ($type === 'meal-plans'){

        }elseif ($type === 'shift-plans'){
            $this->empPlans->planRemove($id, EmployeeShiftPlan::class);
        }elseif ($type === 'roster-plans'){
            $this->empPlans->planRemove($id, EmployeeRosterPlan::class);
        }elseif ($type === 'ot-plans'){
            $this->empPlans->planRemove($id, EmployeeOtPlan::class);
        }elseif ($type === 'offday-plans'){
            $this->empPlans->planRemove($id, EmployeeOffdayPlan::class);
        }

        return redirect()->back()->with([
            'message' => 'Plan Removed Successfully',
            'alert-type' => 'success',
        ]);
    }

    public function deletePlan($id, $type)
    {
        if ($type === 'meal-plans'){

        }elseif ($type === 'shift-plans'){
            $this->empPlans->planDelete($id, EmployeeShiftPlan::class);
        }elseif ($type === 'roster-plans'){
            $this->empPlans->planDelete($id, EmployeeRosterPlan::class);
        }elseif ($type === 'ot-plans'){
            $this->empPlans->planDelete($id, EmployeeOtPlan::class);
        }elseif ($type === 'offday-plans'){
            $this->empPlans->planDelete($id, EmployeeOffdayPlan::class);
        }

        return redirect()->back()->with([
            'message' => 'Plan Deleted Successfully',
            'alert-type' => 'success',
        ]);
    }



    public function getMealPlanByType($type)
    {
        $meal_plans = MealPlan::where('type', $type)->get();
        return response()->json($meal_plans);
    }

    public function getMealPlanDetails($id)
    {
        $plan = MealPlan::find($id);
        return response()->json([
            'id' => $plan->id,
            'name' => $plan->name,
            'type' => $plan->type,
            'cost' => $plan->cost,
        ]);
    }

    public function getOffDayPlanDetails($id)
    {
        $plan = OffDayPlan::find($id);
        return response()->json([
            'id' => $plan->id,
            'name' => $plan->name,
            'short_name' => $plan->short_name,
            'remuneration' => $plan->remuneration,
            'start_time' => Carbon::parse($plan->start_time)->format('h:i A'),
            'end_time' => Carbon::parse($plan->end_time)->format('h:i A'),
        ]);
    }


}
