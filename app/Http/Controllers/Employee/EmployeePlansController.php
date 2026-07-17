<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;

use App\Enums\UserType;
use App\Models\Plan\BonusPlan;
use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeBonusPlan;
use App\Models\Employee\EmployeeLeavePlan;
use App\Models\Employee\EmployeeMealPlan;
use App\Models\Employee\EmployeeOffdayPlan;
use App\Models\Employee\EmployeeOtPlan;
use App\Models\Employee\EmployeeRosterPlan;
use App\Models\Employee\EmployeeShiftPlan;
use App\Models\Plan\LeavePlan;
use App\Models\Plan\MealPlan;
use App\Models\Plan\OffDayPlan;
use App\Models\Plan\OTPlan;
use App\Models\Plan\RosterPlan;
use App\Models\Plan\ShiftPlan;
use App\Http\Requests\Employee\EmployeePlanAssignmentRequest;
use App\Services\Employee\EmployeePlansServices;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        $section_url = route('employee.index');
        $employee = Employee::find($id);

        if (!$employee) {
            abort(404, 'Employee not found');
        }

        // Security check: Employees can only view their own profile
        if (auth()->user()->user_type === UserType::Employee && auth()->user()->employee_id != $id) {
            abort(403, 'Unauthorized access to other profiles.');
        }

        if ($type === 'meal-plans'){
            $mealPlans = MealPlan::where('status', 'active')->get();
            $activeMealPlans = EmployeeMealPlan::where('employee_id', $id)->where('status', 'active')->get();
            $totalActiveMealPlan = !empty($activeMealPlans) ? $activeMealPlans->count() : 0;
            $previousMealPlans = EmployeeMealPlan::where('employee_id', $id)->where('status', 'inactive')->get();
            $totalPreviousMealPlan = !empty($previousMealPlans) ? $previousMealPlans->count() : 0;

            if ($request->ajax()) {
                return view('employee.partials.profile_view.partials.meal_plan', compact('title', 'section', 'sub_section', 'section_url',
                    'employee', 'mealPlans', 'activeMealPlans', 'previousMealPlans', 'totalActiveMealPlan',
                    'totalPreviousMealPlan', 'type'))->render();
            }

            return view('employee.profile', compact('title', 'section', 'sub_section', 'section_url',
                'employee', 'mealPlans', 'activeMealPlans', 'previousMealPlans', 'totalActiveMealPlan',
                'totalPreviousMealPlan', 'type'));
        }elseif ($type === 'shift-plans'){
            $shiftPlans = ShiftPlan::where('active_ind', 'active')->get();
            $activeShiftPLan = EmployeeShiftPlan::where('employee_id', $id)->where('status', 'active')->first();
            $totalActiveShiftPlan = !empty($activeShiftPLan) ? 1 : 0;
            $previousShiftPlans = EmployeeShiftPlan::where('employee_id', $id)->where('status', 'inactive')->get();
            $totalPreviousShiftPlan = !empty($previousShiftPlans) ? $previousShiftPlans->count() : 0;

            if ($request->ajax()) {
                return view('employee.partials.profile_view.partials.shift_plan', compact('title', 'section', 'sub_section', 'section_url',
                    'employee', 'activeShiftPLan', 'previousShiftPlans', 'shiftPlans', 'totalActiveShiftPlan', 'totalPreviousShiftPlan', 'type'))->render();
            }

            return view('employee.profile', compact('title', 'section', 'sub_section', 'section_url',
                'employee', 'activeShiftPLan', 'previousShiftPlans', 'shiftPlans', 'totalActiveShiftPlan', 'totalPreviousShiftPlan', 'type'));

        }elseif ($type === 'roster-plans'){
            $rosterPlans = RosterPlan::where('status', 'active')->get();
            $activeRosterPLan = EmployeeRosterPlan::where('employee_id', $id)->where('status', 'active')->first();
            $totalActiveRosterPlan = !empty($activeRosterPLan) ? 1 : 0;
            $previousRosterPlans = EmployeeRosterPlan::where('employee_id', $id)->where('status', 'inactive')->get();
            $totalPreviousRosterPlan = !empty($previousRosterPlans) ? $previousRosterPlans->count() : 0;

            if ($request->ajax()) {
                return view('employee.partials.profile_view.partials.roster_plan', compact('title', 'section', 'sub_section', 'section_url',
                    'employee', 'activeRosterPLan',
                    'previousRosterPlans', 'rosterPlans',
                    'totalActiveRosterPlan', 'totalPreviousRosterPlan', 'type'))->render();
            }

            return view('employee.profile', compact('title', 'section', 'sub_section', 'section_url',
                'employee', 'activeRosterPLan',
                'previousRosterPlans', 'rosterPlans',
                'totalActiveRosterPlan', 'totalPreviousRosterPlan', 'type'));
        }elseif ($type === 'ot-plans'){
            $otPlans = OTPlan::where('status', 'active')->get();
            $activeOtPLan = EmployeeOtPlan::where('employee_id', $id)->where('status', 'active')->first();
            $totalActiveOtPlan = !empty($activeOtPLan) ? 1 : 0;
            $previousOtPlans = EmployeeOtPlan::where('employee_id', $id)->where('status', 'inactive')->get();
            $totalPreviousOtPlan = !empty($previousOtPlans) ? $previousOtPlans->count() : 0;

            if ($request->ajax()) {
                return view('employee.partials.profile_view.partials.ot_plan', compact('title', 'section', 'sub_section', 'section_url',
                    'employee', 'activeOtPLan', 'previousOtPlans', 'otPlans', 'totalActiveOtPlan', 'totalPreviousOtPlan', 'type'))->render();
            }

            return view('employee.profile', compact('title', 'section', 'sub_section', 'section_url',
                'employee', 'activeOtPLan', 'previousOtPlans', 'otPlans', 'totalActiveOtPlan', 'totalPreviousOtPlan', 'type'));

        }elseif ($type === 'offday-plans'){
            $offDayPlans = OffDayPlan::where('status', 'active')->get();
            $activeOffDayPlans = EmployeeOffdayPlan::where('employee_id', $id)->where('status', 'active')->get();
            $totalActiveOffDayPlan = !empty($activeOffDayPlans) ? $activeOffDayPlans->count() : 0;
            $previousOffDayPlans = EmployeeOffdayPlan::where('employee_id', $id)->where('status', 'inactive')->get();
            $totalPreviousOffDayPlan = !empty($previousOffDayPlans) ? $previousOffDayPlans->count() : 0;

            if ($request->ajax()) {
                return view('employee.partials.profile_view.partials.offday_plan', compact('title', 'section', 'sub_section', 'section_url',
                    'employee', 'activeOffDayPlans', 'previousOffDayPlans', 'offDayPlans',
                    'totalActiveOffDayPlan', 'totalPreviousOffDayPlan', 'type'))->render();
            }

            return view('employee.profile', compact('title', 'section', 'sub_section', 'section_url',
                'employee', 'activeOffDayPlans', 'previousOffDayPlans', 'offDayPlans',
                'totalActiveOffDayPlan', 'totalPreviousOffDayPlan', 'type'));

        }elseif ($type === 'bonus-plans'){
            $bonusPlans = BonusPlan::where('status', 'active')->get();
            $activeBonusPlans = EmployeeBonusPlan::where('employee_id', $id)->get();
            if ($request->ajax()) {
                return view('employee.partials.profile_view.partials.bonus_plan', compact('title', 'section', 'sub_section', 'section_url',
                    'employee', 'bonusPlans', 'type', 'activeBonusPlans'))->render();
            }

            return view('employee.profile', compact('title', 'section', 'sub_section', 'section_url',
                'employee', 'bonusPlans', 'type', 'activeBonusPlans'));

        }elseif ($type === 'leave-plans'){
            $gender = $employee->gender;
            $leavePlansQuery = LeavePlan::where('active_ind', 'active');
            if ($gender) {
                $leavePlansQuery->where(function ($query) use ($gender) {
                    $query->where('applicable_gender', 'Both')
                          ->orWhere('applicable_gender', $gender);
                });
            }
            $leavePlans = $leavePlansQuery->get();
            $activeLeavePlans = EmployeeLeavePlan::where('employee_id', $id)->get();

            if ($request->ajax()) {
                return view('employee.partials.profile_view.partials.leave_plan', compact('title', 'section', 'sub_section', 'section_url',
                    'employee', 'leavePlans', 'type', 'activeLeavePlans'))->render();
            }

            return view('employee.profile', compact('title', 'section', 'sub_section', 'section_url',
                'employee', 'leavePlans', 'type', 'activeLeavePlans'));

        }

    }

    public function assignPlan(EmployeePlanAssignmentRequest $request, $type)
    {
        // Restricted for Employees
        if (auth()->user()->user_type === UserType::Employee) {
            abort(403, 'Unauthorized access.');
        }

        try {
            if ($type === 'meal-plans') {
                $validated = $request->validated();
                $this->empPlans->mealPlanSave($validated, $request);
            } elseif ($type === 'shift-plans') {
                $validated = $request->validated();
                $this->empPlans->planSave($validated, EmployeeShiftPlan::class);
            } elseif ($type === 'roster-plans') {
                $validated = $request->validated();
                $this->empPlans->planSave($validated, EmployeeRosterPlan::class);
            } elseif ($type === 'ot-plans') {
                $validated = $request->validated();
                $this->empPlans->planSave($validated, EmployeeOtPlan::class);
            } elseif ($type === 'offday-plans') {
                $validated = $request->validated();
                $this->empPlans->multipleActivePlanSave($validated, EmployeeOffdayPlan::class);
            } elseif ($type === 'bonus-plans') {
                $bonusPlans = EmployeeBonusPlan::where('employee_id', $request->employee_id)->get();
                if (!empty($bonusPlans)){
                    foreach ($bonusPlans as $plan){
                        $plan->delete();
                    }
                    foreach ($request->plan_ids as $item) {
                        $validated = [
                            'employee_id' => $request->employee_id,
                            'plan_id' => $item,
                        ];
                        $this->empPlans->multipleActivePlanSave($validated, EmployeeBonusPlan::class);
                    }
                }else{
                    foreach ($request->plan_ids as $item) {
                        $validated = [
                            'employee_id' => $request->employee_id,
                            'plan_id' => $item,
                        ];
                        $this->empPlans->multipleActivePlanSave($validated, EmployeeBonusPlan::class);
                    }
                }
            }elseif ($type === 'leave-plans'){
                    $leavePlans = EmployeeLeavePlan::where('employee_id', $request->employee_id)->get();
                    if (!empty($leavePlans)){
                        foreach ($leavePlans as $plan){
                            $plan->delete();
                        }
                        foreach ($request->plan_ids as $item) {
                            $validated = [
                                'employee_id' => $request->employee_id,
                                'plan_id' => $item,
                            ];
                            $this->empPlans->multipleActivePlanSave($validated, EmployeeLeavePlan::class);
                        }
                    }else{
                        foreach ($request->plan_ids as $item) {
                            $validated = [
                                'employee_id' => $request->employee_id,
                                'plan_id' => $item,
                            ];
                            $this->empPlans->multipleActivePlanSave($validated, EmployeeLeavePlan::class);
                        }
                    }
                }

        }catch (\Exception $e){
            \Illuminate\Support\Facades\Log::error('Error in EmployeePlansController@assignPlan: ' . $e->getMessage(), ['exception' => $e]);

            return redirect()->back()->with([
                'message' => 'Something Went Wrong, Try Again Later',
                'alert-type' => 'error',
            ]);
        }

        return redirect()->back()->with([
            'message' => 'Plan Assigned Successfully',
            'alert-type' => 'success',
        ]);
    }

    public function removePlan($type, $id)
    {
        // Restricted for Employees
        if (auth()->user()->user_type === UserType::Employee) {
            abort(403, 'Unauthorized access.');
        }

        if ($type === 'meal-plans'){
            $this->empPlans->planRemove($id, EmployeeMealPlan::class);
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

    public function deletePlan($type, $id)
    {
        // Restricted for Employees
        if (auth()->user()->user_type === UserType::Employee) {
            abort(403, 'Unauthorized access.');
        }

        if ($type === 'meal-plans'){
            $this->empPlans->planDelete($id, EmployeeMealPlan::class);
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


}

