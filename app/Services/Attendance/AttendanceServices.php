<?php

namespace App\Services\Attendance;

use App\Models\Attendance\Attendance;
use App\Models\Employee\EmployeeOffdayPlan;
use App\Models\Employee\EmployeeOfficeInfo;
use App\Models\Employee\EmployeeOtPlan;
use App\Models\Employee\EmployeeRosterPlan;
use App\Models\Employee\EmployeeShiftPlan;
use App\Models\Company\Holiday;
use App\Models\Leave\Leave;
use App\Models\Plan\OffDayPlan;
use App\Models\Plan\OTPlan;
use App\Models\Plan\ShiftPlan;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AttendanceServices
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function checkLeaveDay($employeeId, $clockIn, $index)
    {
        $clockIn = $clockIn->copy()->startOfDay();
        $leaves = $this->getLeaveDays($employeeId);
        $leaveExists = $leaves->contains($clockIn);

        if ($leaveExists) {
            throw ValidationException::withMessages([
                "attendance.$index.clock_in" => ['Employee is on leave for this date. Clock-in is not allowed.']
            ]);
        }
    }

    public function getLeaveDays($employeeId)
    {
        $leaves = Leave::where('employee_id', $employeeId)
            ->where('status', 'approved')
            ->get();

        $leaveDates = [];

        foreach ($leaves as $leaveDate) {

            $period = CarbonPeriod::create(
                Carbon::parse($leaveDate->from),
                Carbon::parse($leaveDate->to)
            );

            foreach ($period as $date) {
                $leaveDates[] = $date;
            }
        }
        $leaveDates = collect($leaveDates)->unique();
//        dd($leaveDates);
        return $leaveDates;
    }

    public function getHolidays()
    {
        $holidays = Holiday::all();

        $holidayDates = [];

        foreach ($holidays as $holiday) {

            $period = CarbonPeriod::create(
                Carbon::parse($holiday->start_date),
                Carbon::parse($holiday->end_date)
            );

            foreach ($period as $date) {
                $holidayDates[] = $date;
            }
        }
        $holidayDates = collect($holidayDates)->unique();
        return $holidayDates;
    }

    public function checkOffDay($employee_id, $clock_in, $index = null)
    {
        $officeInfo = EmployeeOfficeInfo::where('employee_id', $employee_id)->first();
        $weekends = $officeInfo ? ($officeInfo->weekends ?? []) : ['Friday', 'Saturday'];
        $holidays = $this->getHolidays();
        $clock_in_day = $clock_in->copy()->format('l');
        
        if (in_array($clock_in_day, $weekends) || $holidays->contains($clock_in->copy()->startOfDay())) {
            $offDayPlans = EmployeeOffdayPlan::where('employee_id', $employee_id)->where('status', 'active')->get();
            
            // Check if clock_in matches ANY of the active off-day plans
            foreach ($offDayPlans as $offDayPlan) {
                $from = Carbon::parse($offDayPlan->from)->copy()->startOfDay();
                $to = $offDayPlan->to ? Carbon::parse($offDayPlan->to)->copy()->endOfDay() : null;

                if ($clock_in->gte($from) && (!$to || $clock_in->lte($to))) {
                    $planModel = $offDayPlan->getPlan;
                    $dataShiftType = ($planModel && $planModel->type === 'comp-off') ? "comp-off-offday" : "paid-offday";
                    $shift = $planModel ? $planModel->shift_id : null;
                    $offday_id = $offDayPlan->plan_id; // Store the actual plan ID

                    return [
                        'shift' => $shift,
                        'shift_type' => $dataShiftType,
                        'offday_id' => $offday_id
                    ];
                }
            }

            // If it's a weekend/holiday but no active plan, throw exception to block clock-in
            if ($index !== null) {
                throw ValidationException::withMessages([
                    "attendance.$index.clock_in" => ["Off-day clock-in is not allowed without an active off-day plan."]
                ]);
            }
        }
        return null;
    }


    public function isOffDay($employee_id, $clock_in)
    {
        $officeInfo = EmployeeOfficeInfo::where('employee_id', $employee_id)->first();
        $weekends = $officeInfo ? ($officeInfo->weekends ?? []) : ['Friday', 'Saturday'];
        $holidays = $this->getHolidays();
        $clock_in_day = $clock_in->format('l');
        
        if (in_array($clock_in_day, $weekends) || $holidays->contains($clock_in->copy()->startOfDay())) {
            $offDayPlans = EmployeeOffdayPlan::where('employee_id', $employee_id)->where('status', 'active')->get();
            
            $hasPlanForToday = false;
            foreach ($offDayPlans as $offDayPlan) {
                $from = Carbon::parse($offDayPlan->from)->copy()->startOfDay();
                $to = $offDayPlan->to ? Carbon::parse($offDayPlan->to)->copy()->endOfDay() : null;

                if ($clock_in->gte($from) && (!$to || $clock_in->lte($to))) {
                    $hasPlanForToday = true;
                    break;
                }
            }

            if (!$hasPlanForToday) {
                return 'off_day';
            } else {
                return 'active';
            }
        } else {
            return 'active';
        }
    }

    public function isLeaveDay($employeeId, $clockIn)
    {
        $clockIn = $clockIn->copy()->startOfDay();
        $leaves = $this->getLeaveDays($employeeId);
        return $leaves->contains($clockIn); //contains give boolean value
    }

    public function getTodayShift($employee_id, $clock_in)
    {
        // 1. Check Roster Plans first
        $roster_links = EmployeeRosterPlan::where('employee_id', $employee_id)
            ->where('status', 'active')
            ->get();

        foreach ($roster_links as $roster) {
            $from = Carbon::parse($roster->from)->startOfDay();
            $to = $roster->to ? Carbon::parse($roster->to)->endOfDay() : null;

            if ($clock_in->gte($from) && (!$to || $clock_in->lte($to))) {
                Log::info("Roster Active for Employee $employee_id on {$clock_in->toDateString()}");
                $dataShiftType = "Roster";
                $dayPassed = (int)$from->diffInDays($clock_in->copy()->startOfDay());
                
                // Assuming swapping is stored as numeric days (e.g., '7') or needs conversion
                $repeatDays = is_numeric($roster->getPlan->swapping) ? (int)$roster->getPlan->swapping : 7;
                $cycle = intdiv($dayPassed, $repeatDays);
                
                if ($roster->getPlan->third_shift_id != null) {
                    $shiftIndex = $cycle % 3;
                } else {
                    $shiftIndex = $cycle % 2;
                }
                
                if ($shiftIndex == 0) {
                    $shift = $roster->getPlan->first_shift_id;
                } elseif ($shiftIndex == 1) {
                    $shift = $roster->getPlan->second_shift_id;
                } else {
                    $shift = $roster->getPlan->third_shift_id;
                }

                return [
                    'shift' => $shift,
                    'shift_type' => $dataShiftType
                ];
            }
        }

        // 2. Fallback to Regular Shift
        $shift_links = EmployeeShiftPlan::where('employee_id', $employee_id)
            ->where('status', 'active')
            ->get();

        foreach ($shift_links as $link) {
            $from = Carbon::parse($link->from)->startOfDay();
            $to = $link->to ? Carbon::parse($link->to)->endOfDay() : null;

            if ($clock_in->gte($from) && (!$to || $clock_in->lte($to))) {
                return [
                    'shift' => $link->plan_id,
                    'shift_type' => "Regular"
                ];
            }
        }

        // 3. Absolute fallback (default to shift 1 if no plan linked)
        return [
            'shift' => 1,
            'shift_type' => "Regular"
        ];
    }

    public function getWorkingTime($clock_in, $clock_out)
    {
        return $clock_in->diffInMinutes($clock_out);
    }

    public function getLateTime($clock_in, $shift_start, $grace_time)
    {
        $graceperiod = $shift_start->copy()->addMinutes($grace_time);
        Log::info('Grace Period: ' . $graceperiod);

        if ($clock_in <= $graceperiod) {
            Log::info('Clock In is within grace period');
            return 0;
        } else {
            Log::info('Clock In is after grace period');
            return $shift_start->diffInMinutes($clock_in);
        }
    }

    public function getEarlyOutTime($clock_out, $shift_end, $early_out_grace_minutes)
    {
        $graceperiod = $shift_end->copy()->subMinutes($early_out_grace_minutes);

        if ($clock_out >= $graceperiod) {
            return 0;
        } else {
            return $clock_out->diffInMinutes($shift_end);
        }
    }

    public function getClockInStatus($clock_in, $shift_start, $grace_time, $excessive_late_after_minutes)
    {
        Log::info('Clock In: ' . $clock_in);
        Log::info('Shift Start: ' . $shift_start);
        Log::info('Grace Time: ' . $grace_time);
        Log::info('Excessive Late After Minutes: ' . $excessive_late_after_minutes);
        $duration = $this->getLateTime($clock_in, $shift_start, $grace_time);
        Log::info('Late Duration: ' . $duration);
        if ($duration >= $excessive_late_after_minutes) {
            return 'Excessive-Late';
        } elseif ($duration > $grace_time) {
            return 'Late';
        }
        return 'On-Time';
    }

    public function getClockOutStatus($clock_out, $shift_end, $early_out_grace_minutes)
    {
        $duration = $this->getEarlyOutTime($clock_out, $shift_end, $early_out_grace_minutes);
        if ($duration > $early_out_grace_minutes) {
            return 'Early-Exit';
        }
        return 'On-Time';
    }

    public function getOverTimeDetails($employee_id, $clock_in)
    {
        // 1. Check general OT eligibility
        $eligibility = \App\Models\Employee\EmployeeEligiblePlan::where('employee_id', $employee_id)->first();
        if (!$eligibility || $eligibility->ot_plan_status !== 'active') {
            return null;
        }

        // 2. Check if current date is within eligibility range (null 'to' means running)
        $eligibilityFrom = Carbon::parse($eligibility->ot_plan_from)->startOfDay();
        $eligibilityTo = $eligibility->ot_plan_to ? Carbon::parse($eligibility->ot_plan_to)->endOfDay() : null;

        if ($clock_in->lt($eligibilityFrom) || ($eligibilityTo && $clock_in->gt($eligibilityTo))) {
            return null;
        }

        // 3. Find specific active plan for the date
        $overtime_plans = EmployeeOtPlan::where('employee_id', $employee_id)
            ->where('status', 'active')
            ->get();

        foreach ($overtime_plans as $plan) {
            $from = Carbon::parse($plan->from)->startOfDay();
            $to = $plan->to ? Carbon::parse($plan->to)->endOfDay() : null;
            
            if ($clock_in->gte($from) && (!$to || $clock_in->lte($to))) {
                Log::info("Overtime Active for Employee $employee_id on {$clock_in->toDateString()} (Plan ID: {$plan->plan_id})");
                return $plan->plan_id;
            }
        }

        return null;
    }

    public function getOverTime($clock_out, $shift_end)
    {
        if ($clock_out > $shift_end) {
            return abs($shift_end->diffInMinutes($clock_out));
        }else{
            return 0;
        }
    }

    public function getWorkType($clock_in, $clock_out, $shift_details, $overtime, $in_status, $out_status, $shift_type = 'Regular')
    {
        if (in_array($shift_type, ['Off-Day', 'paid-offday', 'comp-off-offday', 'Paid-Off-Day', 'Comp-Off-Off-Day'])) {
            return $shift_type;
        }
        
        $working_time = $this->getWorkingTime($clock_in, $clock_out);
        if ($overtime > 0) {
            return 'Overtime & ' . $in_status;
        } elseif ($working_time == $shift_details->treat_as_half_day_minutes) {
            return 'Half-Day';
        } elseif ($working_time >= $shift_details->treat_as_full_day_minutes) {
            return 'Full-Day';
        } else {
            return 'In: ' . $in_status . ' & Out: ' . $out_status;
        }
    }

    public function incrementCompOffBalance($employeeId, $earnedDate)
    {
        $compOff = \App\Models\Employee\EmployeeCompOff::where('employee_id', $employeeId)->first();
        $dateStr = Carbon::parse($earnedDate)->format('Y-m-d');

        if ($compOff) {
            $compOff->comp_off_days += 1;
            $compOff->balance_days = $compOff->comp_off_days - $compOff->used_days;
            $compOff->last_earned_date = $dateStr;
            $compOff->save();
        } else {
            \App\Models\Employee\EmployeeCompOff::create([
                'employee_id' => $employeeId,
                'comp_off_days' => 1.00,
                'used_days' => 0.00,
                'balance_days' => 1.00,
                'last_earned_date' => $dateStr,
                'status' => 'active',
            ]);
        }
    }

    public function getAttendanceStatus($clock_in, $clock_out, $shift_details)
    {
        $working_time = $this->getWorkingTime($clock_in, $clock_out);
        if ($working_time >= $shift_details->treat_as_half_day_minutes) {
            return 'Present';
        } else {
            return 'Absent';
        }
    }

    public function buildShiftTime($clock_in, $shift_start, $shift_end)
    {

        // Attach clock-in DATE to shift TIMES
        $shift_start = Carbon::parse($clock_in->format('Y-m-d') . ' ' . $shift_start);

        $shift_end = Carbon::parse($clock_in->format('Y-m-d') . ' ' . $shift_end);

        // Overnight shift handling
        if ($shift_end->lt($shift_start)) {
            $shift_end->addDay();
        }
        return [
            'start' => $shift_start,
            'end' => $shift_end];
    }

    private function calculateAttendanceData($employee_id, $clock_in, $clock_out, $workstation = null)
    {
        $clock_in = Carbon::parse($clock_in);
        $clock_out = Carbon::parse($clock_out);

        // Overnight shift handling
        if ($clock_out < $clock_in) {
            $clock_out->addDay();
        }

        $data = [
            'employee_id' => $employee_id,
            'in_time' => $clock_in,
            'out_time' => $clock_out,
        ];

        if ($workstation) {
            $data['workstation'] = $workstation;
        }

        $offDayData = $this->checkOffDay($employee_id, $clock_in);

        if (!empty($offDayData)) {
            $data['shift_type'] = $offDayData['shift_type'];
            $shift = $offDayData['shift'];
            $data['offday_id'] = $offDayData['offday_id'];
        } else {
            $shift_data = $this->getTodayShift($employee_id, $clock_in);
            $shift = $shift_data['shift'];
            $data['shift_type'] = $shift_data['shift_type'];
            $data['offday_id'] = null;
        }

        $shift_details = ShiftPlan::findOrFail($shift);
        $data['shift_id'] = $shift;

        $shiftTime = $this->buildShiftTime($clock_in, $shift_details->clock_in_time, $shift_details->clock_out_time);
        $shift_start = $shiftTime['start'];
        $shift_end = $shiftTime['end'];

        $data['working_time'] = $this->getWorkingTime($clock_in, $clock_out);
        $data['late_count'] = $this->getLateTime($clock_in, $shift_start, $shift_details->grace_time);
        $data['early_out_count'] = $this->getEarlyOutTime($clock_out, $shift_end, $shift_details->early_out_grace_minutes);

        $data['in_status'] = $this->getClockInStatus($clock_in, $shift_start, $shift_details->grace_time, $shift_details->excessive_late_after_minutes);
        $data['out_status'] = $this->getClockOutStatus($clock_out, $shift_end, $shift_details->early_out_grace_minutes);

        $otId = $this->getOverTimeDetails($employee_id, $clock_in);
        if (empty($otId)) {
            $data['overtime'] = 0;
            $data['ot_id'] = null;
        } else {
            $data['overtime'] = $this->getOverTime($clock_out, $shift_end);
            $data['ot_id'] = $otId;
        }

        $data['attendance_status'] = $this->getWorkType($clock_in, $clock_out, $shift_details, $data['overtime'], $data['in_status'], $data['out_status'], $data['shift_type']);

        return $data;
    }

    public function singleAttendanceStore($item, $index)
    {
        $this->checkLeaveDay($item['employee_id'], Carbon::parse($item['clock_in']), $index);
        $data = $this->calculateAttendanceData($item['employee_id'], $item['clock_in'], $item['clock_out'], $item['workstation']);
        Attendance::create($data);

        if (isset($data['shift_type']) && $data['shift_type'] === 'comp-off-offday') {
            $this->incrementCompOffBalance($item['employee_id'], $item['clock_in']);
        }
    }

    public function attendanceUpdate($id, $item)
    {
        $record = Attendance::findOrFail($id);
        $data = $this->calculateAttendanceData($record->employee_id, $item['clock_in'], $item['clock_out'], $item['workstation'] ?? null);
        $record->update($data);
    }

    public function attendanceStore($request)
    {
        $request->validate([
            'attendance.*.employee_id' => 'required|exists:employees,id',
            'attendance.*.clock_in' => 'required|date',
            'attendance.*.clock_out' => 'required|date|after:attendance.*.clock_in',
        ], [
            'attendance.*.employee_id.required' => 'The employee field is required.',
            'attendance.*.clock_in.required' => 'The clock-in field is required.',
            'attendance.*.clock_in.date' => 'The clock-in must be a valid date.',
            'attendance.*.clock_out.required' => 'The clock-out field is required.',
            'attendance.*.clock_out.date' => 'The clock-out must be a valid date.',
            'attendance.*.clock_out.after' => 'The clock-out must be after clock-in.',
        ]);

        $attendance = $request->attendance;
        foreach ($attendance as $index => $item) {
            $this->singleAttendanceStore($item, $index);
        }
    }


    public function clockOutStore($request)
    {
        $id = $request->attendance_id;
        $record = Attendance::findOrFail($id);
        $data = $this->calculateAttendanceData($record->employee_id, $record->in_time, $request->out_time);
        $record->update($data);
    }
}

