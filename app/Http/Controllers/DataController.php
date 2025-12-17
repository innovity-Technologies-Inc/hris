<?php

namespace App\Http\Controllers;

use App\Models\BonusPlan;
use App\Models\Branch;
use App\Models\CompanyLocation;
use App\Models\Department;
use App\Models\Division;
use App\Models\EmployeeEligiblePlan;
use App\Models\EmployeeLeavePlan;
use App\Models\LeaveCount;
use App\Models\LeavePlan;
use App\Models\MealPlan;
use App\Models\OffDayPlan;
use App\Models\OTPlan;
use App\Models\RosterPlan;
use App\Models\SalaryGrade;
use App\Models\Section;
use App\Models\ShiftPlan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DataController extends Controller
{

    public function getUnit($company_id){
        $units = CompanyLocation::where('company_id', $company_id)->select('id', 'name')->get();
        return response()->json($units);

    }

    public function getDivision($company_id, $location_id){
        $divisions = Division::where('company_id', $company_id)
            ->where('location_id', $location_id)->get();
        return response()->json($divisions);
    }

    public function getDepartment($company_id, $location_id, $division_id){
        $departments = Department::where('company_id', $company_id)
            ->where('location_id', $location_id)
            ->where('division_id', $division_id)
            ->get();
        return response()->json($departments);
    }

    public function getSection($company_id, $location_id, $division_id, $department_id){
        $sections = Section::where('company_id', $company_id)
            ->where('location_id', $location_id)
            ->where('division_id', $division_id)->where('department_id', $department_id)->get();
        return response()->json($sections);
    }

    public function getGradeByAct($tofsil_id){
        $grades = SalaryGrade::where('tofsil_id', $tofsil_id)->get();
        return response()->json($grades);
    }

    public function getBranchesByBank($bank_id){
        $branches = Branch::where('bank_id', $bank_id)->get();
        return response()->json($branches);
    }

    public function getLeavePlan($employee_id){
        $eligibility = EmployeeEligiblePlan::where('employee_id', $employee_id)->first();
        if (isset($eligibility)){
            if ($eligibility->leave_plan_status == 'active'){
                if ($eligibility->leave_plan_from <= Carbon::today()){
                    $plans = EmployeeLeavePlan::with('getPlan')
                        ->where('employee_id', $employee_id)->get();
                    return response()->json($plans);
                }
                return response()->json(['error' => 'Leave Plan is not active yet']);
            }
            return response()->json(['error' => 'Leave Plan is not active yet']);
        }
        return response()->json(['error' => 'No Plans Found']);
    }

    public function getLeaveDetails($employee_id, $plan_id){
        $plan_name = LeavePlan::where('id', $plan_id)->first()->name;
        $limit = LeavePlan::where('id', $plan_id)->first()->leave_limit;
        $leave_count_data = LeaveCount::where('employee_id', $employee_id)->where('plan_id', $plan_id)->first();
        if ($leave_count_data){
            $taken = $leave_count_data->leave_taken;
        }else{
            $taken = 0;
        }
        return response()->json([
            'name' => $plan_name,
            'limit' => $limit,
            'taken' => $taken
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

    public function getOtPlanDetails($id)
    {
        $plan = OTPlan::find($id);
        return response()->json([
            'id' => $plan->id,
            'name' => $plan->name,
            'type' => $plan->ot_type,
            'config' => $plan->ot_config_type,
            'rate' => $plan->custom_overtime_rate,
            'multiplier' => $plan->overtime_multiplier,
            'salary_type' => $plan->salary_rate_type,
            'start_time' => Carbon::parse($plan->overtime_start_time)->format('h:i A'),
            'end_time' => Carbon::parse($plan->overtime_end_time)->format('h:i A'),
        ]);
    }
    public function getShiftPlanDetails($id)
    {
        $plan = ShiftPlan::find($id);
        return response()->json([
            'id' => $plan->id,
            'name' => $plan->name,
            'start_time' => Carbon::parse($plan->clock_in_time)->format('h:i A'),
            'end_time' => Carbon::parse($plan->clock_out_time)->format('h:i A'),
        ]);
    }

    public function getRosterPlanDetails($id)
    {
        $plan = RosterPlan::find($id);
        return response()->json([
            'id' => $plan->id,
            'name' => $plan->name,
            'swapping' => $plan->swapping,
            'first_shift_name' => $plan->getFirstShift->name,
            'first_shift_start' => Carbon::parse($plan->getFirstShift->clock_in_time)->format('h:i A'),
            'first_shift_end' => Carbon::parse($plan->getFirstShift->clock_out_time)->format('h:i A'),
            'second_shift_name' => $plan->getSecondShift->name,
            'second_shift_start' => Carbon::parse($plan->getSecondShift->clock_in_time)->format('h:i A'),
            'second_shift_end' => Carbon::parse($plan->getSecondShift->clock_out_time)->format('h:i A'),
        ]);
    }

    public function getBonusPlanDetails($id){
        $plan = BonusPlan::find($id);
        return response()->json($plan);
    }
    public function getLeavePlanDetails($id){
        $plan = LeavePlan::find($id);
        return response()->json($plan);
    }

    public function getShiftDetails($shift_id){
        $shift = ShiftPlan::find($shift_id);
        return response()->json([
            'shift' => $shift
        ]);
    }
}
