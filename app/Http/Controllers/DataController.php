<?php

namespace App\Http\Controllers;

use App\HelperClass;
use App\Models\Attendance;
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
use App\Services\AttendanceServices;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DataController extends Controller
{

    protected $attendancesService;
    public function __construct(AttendanceServices $attendancesService){
        $this->attendancesService = $attendancesService;
    }

    public function getUnit($company_id){
        $units = CompanyLocation::where('company_id', $company_id)->select('id', 'name')->get();
        return response()->json($units);

    }

    public function getDivisions($company_id, $location_id = null)
    {
        $location_id = ($location_id === 'null') ? null : $location_id;

        $query = Division::where('company_id', $company_id);

        // only filter by location if given
        if (!is_null($location_id)) {
            $query->where('location_id', $location_id);
        }

        return $query->select('id', 'name')->get();
    }

    public function getDepartments($company_id, $location_id = null, $division_id = null)
    {
        $location_id = ($location_id === 'null') ? null : $location_id;
        $division_id = ($division_id === 'null') ? null : $division_id;

        $query = Department::where('company_id', $company_id);

        if (!is_null($location_id)) {
            $query->where('location_id', $location_id);
        }

        if (!is_null($division_id)) {
            $query->where('division_id', $division_id);
        }

        return $query->select('id', 'department_name')->get();
    }



    public function getSections($company_id, $location_id = null, $division_id = null, $department_id = null)
    {
        $location_id = ($location_id === 'null') ? null : $location_id;
        $division_id = ($division_id === 'null') ? null : $division_id;
        $department_id = ($department_id === 'null') ? null : $department_id;

        $query = Section::where('company_id', $company_id);

        if ($location_id) {
            $query->where('location_id', $location_id);
        }
        if ($division_id) {
            $query->where('division_id', $division_id);
        }
        if ($department_id) {
            $query->where('department_id', $department_id);
        }

        return $query->select('id', 'name')->get();
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
        $plan = OffDayPlan::with('getShift')->find($id);

        if (!$plan) {
            return response()->json(['error' => 'Plan not found'], 404);
        }

        $shift = $plan->getShift;
        $startTime = $shift ? Carbon::parse($shift->clock_in_time)->format('h:i A') : 'N/A';
        $endTime = $shift ? Carbon::parse($shift->clock_out_time)->format('h:i A') : 'N/A';
        $shiftName = $shift ? $shift->name : 'No shift assigned';
        $graceTime = $shift ? $shift->grace_time : 0;
        $earlyOutGrace = $shift ? $shift->early_out_grace_minutes : 0;

        // Build configuration description
        $configurationDescription = '';
        if ($plan->offday_config_type === 'Salary Based') {
            if ($plan->salary_rate_type === 'Basic Rate') {
                $configurationDescription = 'Salary Based - Basic Rate';
            } else {
                $configurationDescription = "Salary Based - {$plan->offday_multiplier}x Multiplier";
            }
        } else {
            $configurationDescription = "Custom Rate - " . number_format($plan->custom_offday_rate ?? 0, 2) . " per hour";
        }

        return response()->json([
            'id' => $plan->id,
            'name' => $plan->name,
            'short_name' => $plan->short_name,
            'config_type' => $plan->offday_config_type,
            'salary_rate_type' => $plan->salary_rate_type,
            'offday_multiplier' => $plan->offday_multiplier,
            'custom_offday_rate' => $plan->custom_offday_rate,
            'configuration_description' => $configurationDescription,
            'shift_name' => $shiftName,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'grace_time' => $graceTime,
            'early_out_grace' => $earlyOutGrace,
        ]);
    }

    public function getOtPlanDetails($id)
    {
        $plan = OTPlan::find($id);
        return response()->json([
            'id' => $plan->id,
            'name' => $plan->name,
            'config' => $plan->ot_config_type,
            'rate' => $plan->custom_overtime_rate,
            'multiplier' => $plan->overtime_multiplier,
            'salary_type' => $plan->salary_rate_type,
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

    public function getAttendanceDetails($employee_id)
    {
        $today = Carbon::today();

        $leaveFlag = $this->attendancesService->isLeaveDay($employee_id, $today);
        if ($leaveFlag){

            return response()->json([
                'status' => 'leave_day',
                'time' => $today,
                'leave_day' => $leaveFlag
            ]);

        }else{

            $offDay = $this->attendancesService->isOffDay($employee_id, $today);
//            dd($offDay);
            if ($offDay == 'off_day'){
                return response()->json([
                    'status' => 'off_day',
                    'time' => $today,
                    'off_day' => $offDay
                ]);
            }else{
                return $this->attendanceRecords($employee_id, $today);
            }

        }

    }

    public function attendanceRecords($employee_id, $today){
        $record = Attendance::where('employee_id', $employee_id)
            ->whereDate('in_time', $today)
            ->first();

        if (!$record) {
            return response()->json([
                'status' => 'clock_in',
                'time' => $today,
                'record' => $record
            ]);
        }

        if (is_null($record->out_time)) {
            return response()->json([
                'status' => 'clock_out',
                'time' => $today,
                'record' => $record
            ]);
        }

        return response()->json([
            'status' => 'completed',
            'time' => $today,
            'record' => $record
        ]);
    }






}
