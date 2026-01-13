<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\EmployeeOffdayPlan;
use App\Models\EmployeeOfficeInfo;
use App\Models\EmployeeOtPlan;
use App\Models\EmployeeRosterPlan;
use App\Models\EmployeeShiftPlan;
use App\Models\Holiday;
use App\Models\Leave;
use App\Models\OffDayPlan;
use App\Models\OTPlan;
use App\Models\ShiftPlan;
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

    public function getLeaveDays($employeeId){
        $leaves = Leave::all()->where('employee_id', $employeeId);

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

    public function getHolidays(){
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

    public function checkOffDay($employee_id, $clock_in, $index=null){
        $weekends = EmployeeOfficeInfo::find($employee_id)->weekends;
        $holidays = $this->getHolidays();
        $clock_in_day = $clock_in->copy()->format('l');
        if (in_array($clock_in_day, $weekends) || $holidays->contains($clock_in)) {
            $offDayPlans = EmployeeOffdayPlan::where('employee_id', $employee_id)->where('status', 'active')->get();
            if (empty($offDayPlans)){
                throw ValidationException::withMessages([
                    "attendance.$index.clock_in" => ["Off-day clock-in is not allowed without an active off-day plan."]
                ]);
            }

            // Check if clock_in matches ANY of the active off-day plans
            foreach ($offDayPlans as $offDayPlan) {
                $from = Carbon::parse($offDayPlan->from)->copy()->startOfDay();
                $to = Carbon::parse($offDayPlan->to)->copy()->endOfDay();

                if ($clock_in->between($from, $to)) {
                    $dataShiftType = "Off-Day";
                    $shift = $offDayPlan->getPlan->shift_id;

                    return [
                        'shift' => $shift,
                        'shift_type' => $dataShiftType
                    ];
                }
            }

            // If we reach here, clock_in doesn't match any active off-day plan
            throw ValidationException::withMessages([
                "attendance.$index.clock_in" => ["Off-day clock-in is not allowed without an active off-day plan."]
            ]);
        }
        return null;
    }


    public function isOffDay($employee_id, $clock_in){
        $weekends = EmployeeOfficeInfo::find($employee_id)->weekends;
        $holidays = $this->getHolidays();
        $clock_in_day = $clock_in->format('l');
        if (in_array($clock_in_day, $weekends) || $holidays->contains($clock_in)) {
            $offDayPlans = EmployeeOffdayPlan::where('employee_id', $employee_id)->where('status', 'active')->get();
            if ($offDayPlans->isEmpty()){
//                dd('No Off-Day Plan');
                return 'off_day';
            }else{
                return 'active';
            }
        }else{
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
        $roster = EmployeeRosterPlan::where('employee_id', $employee_id)->where('status', 'active')->first();
        if (!empty($roster)) {
            $from = Carbon::parse($roster->from)->copy()->startOfDay();
            $to = Carbon::parse($roster->to)->copy()->endOfDay();

            if ($clock_in->between($from, $to) && $roster->status == 'active') {
                Log::info('Roster Active');
                $dataShiftType = "Roster";
                $dayPassed = (int)$from->diffInDays($clock_in->copy()->startOfDay());
                $repeatDays = (int)$roster->getPlan->swapping;
                $cycle = intdiv($dayPassed, $repeatDays);
                if ($roster->third_shift_id != null) {
                    $shiftIndex = $cycle % 3;
                } else {
                    $shiftIndex = $cycle % 2;
                }
                if ($shiftIndex == 0) {
                    $shift = $roster->getPlan->first_shift_id;
                } elseif ($shiftIndex == 1) {
                    $shift = $roster->getPlan->second_shift_id;
                } elseif ($shiftIndex == 2) {
                    $shift = $roster->getPlan->third_shift_id;
                }
            } else {
                Log::info('Regular Shift');
                $shift = EmployeeShiftPlan::where('employee_id', $employee_id)
                    ->where('status', 'active')->first()->plan_id;
                    $dataShiftType = "Regular";
            }
        } else {
            Log::info('Regular Shift');
            $shift = EmployeeShiftPlan::where('employee_id', $employee_id)
                ->where('status', 'active')->first()->plan_id;
                $dataShiftType = "Regular";
        }
        return [
            'shift' => $shift,
            'shift_type' => $dataShiftType
        ];
    }

    public function getWorkingTime($clock_in, $clock_out)
    {
        return $clock_in->diffInMinutes($clock_out);
    }

    public function getLateTime($clock_in, $shift_start, $grace_time)
    {
        $graceperiod = $shift_start->copy()->addMinutes($grace_time);
        Log::info('Grace Period: '.$graceperiod);

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
        Log::info('Clock In: '.$clock_in);
        Log::info('Shift Start: '.$shift_start);
        Log::info('Grace Time: '.$grace_time);
        Log::info('Excessive Late After Minutes: '.$excessive_late_after_minutes);
        $duration = $this->getLateTime($clock_in, $shift_start, $grace_time);
        Log::info('Late Duration: '.$duration);
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
        $overtime_data = EmployeeOtPlan::where('employee_id', $employee_id)->where('status', 'active')->first();
        if (!empty($overtime_data)) {
            $from = Carbon::parse($overtime_data->from);
            $to = Carbon::parse($overtime_data->to);
            if ($clock_in->between($from, $to)) {
                return $overtime_data->plan_id;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    public function getOverTime($maxOtTime, $fullDayDuration, $clock_in, $clock_out)
    {
        $working_time = $this->getWorkingTime($clock_in, $clock_out);
        $ot_time = $working_time - $fullDayDuration;
        if ($ot_time >= $maxOtTime) {
            return $maxOtTime;
        } else {
            return $ot_time;
        }
    }

    public function getWorkType($clock_in, $clock_out, $shift_details, $overtime, $in_status, $out_status)
    {
        $working_time = $this->getWorkingTime($clock_in, $clock_out);
        if ($overtime > 0) {
            return 'Overtime & '.$in_status;
        }elseif ($working_time == $shift_details->treat_as_half_day_minutes) {
            return 'Half-Day';
        } elseif ($working_time >= $shift_details->treat_as_full_day_minutes) {
            return 'Full-Day';
        } else {
            return 'In: '.$in_status.' & Out: '.$out_status;
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
    public function singleAttendanceStore($item, $index){
            $employee_id = $item['employee_id'];
            $clock_in = $item['clock_in'];
            $clock_out = $item['clock_out'];

            $data = [
                'employee_id' => $employee_id,
                'in_time' => $clock_in,
                'out_time' => $clock_out,
                'workstation' => $item['workstation']
            ];

            $clock_in = Carbon::parse($clock_in);
            $clock_out = Carbon::parse($clock_out);
            Log::info('Clock In: '.$clock_in);
            Log::info('Clock Out: '.$clock_out);

            $this->checkLeaveDay($employee_id, $clock_in, $index);

            $offDayData = $this->checkOffDay($employee_id, $clock_in, $index);

                        Log::info('Clock In BOD: '.$clock_in);

            if (!empty($offDayData)){
                Log::info('OFF Day Work Plan Enable');
                $data['shift_type'] = $offDayData['shift_type'];
                $shift = $offDayData['shift'];
            }else{
                Log::info('Checking Shift');
                $shift_data = $this->getTodayShift($employee_id, $clock_in);
                $shift = $shift_data['shift'];
                $data['shift_type'] = $shift_data['shift_type'];
            }

                                    Log::info('Clock In AOD: '.$clock_in);


            $shift_details = ShiftPlan::findorFail($shift);
            Log::info($shift_details);
            Log::info('Clock In SD: '.$clock_in);


            $shift_start = $shift_details->clock_in_time;
            $shift_end = $shift_details->clock_out_time;

            $grace_time = $shift_details->grace_time;
            $early_out_grace_minutes = $shift_details->early_out_grace_minutes;
            $excessive_late_after_minutes = $shift_details->excessive_late_after_minutes;

            Log::info('Clock In BS: '.$clock_in);

            $shiftTime = $this->buildShiftTime($clock_in, $shift_start, $shift_end);

            Log::info('Clock In ABS: '.$clock_in);
            $shift_start = $shiftTime['start'];
            $shift_end = $shiftTime['end'];


            Log::info('Clock In BW: '.$clock_in);
            $data['working_time'] = $this->getWorkingTime($clock_in, $clock_out);
            Log::info('Clock In AW: '.$clock_in);
            $data['late_count'] = $this->getLateTime($clock_in, $shift_start, $grace_time);
            Log::info('Clock In LC: '.$clock_in);

            $data['early_out_count'] = $this->getEarlyOutTime($clock_out, $shift_end, $early_out_grace_minutes);

            $data['in_status'] = $this->getClockInStatus($clock_in, $shift_start, $grace_time, $excessive_late_after_minutes);
            $data['out_status'] = $this->getClockOutStatus($clock_out, $shift_end, $early_out_grace_minutes);

            $ot = $this->getOverTimeDetails($employee_id, $clock_in);
            Log::info($ot);
            if (empty($ot)) {
                $data['overtime'] = 0;
            }else{
                $otDetails = OTPlan::findorFail($ot);

                Log::info($otDetails);

                if (!empty($otDetails)) {
                    $data['overtime'] = $this->getOverTime($otDetails->maximum_overtime, $shift_details->treat_as_full_day_minutes, $clock_in, $clock_out);
                } else {
                    $data['overtime'] = 0;
                }
            }


            $data['attendance_status'] = $this->getWorkType($clock_in, $clock_out, $shift_details, $data['overtime'], $data['in_status'], $data['out_status']);
            Log::info($data);
        //    dd($data);
            Attendance::create($data);
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
        $clock_in = $record->in_time;
        $clock_out = $request->out_time;
        $employee_id = $record->employee_id;


        $data = [
            'out_time' => $clock_out
        ];

        $clock_in = Carbon::parse($clock_in);
        $clock_out = Carbon::parse($clock_out);

        $offDayData = $this->checkOffDay($employee_id, $clock_in);

        if (!empty($offDayData)){
            Log::info('OFF Day Work Plan Enable');
            $data['shift_type'] = $offDayData['shift_type'];
            $shift = $offDayData['shift'];
        }else{
            Log::info('Checking Shift');
            $shift_data = $this->getTodayShift($employee_id, $clock_in);
            $shift = $shift_data['shift'];
            $data['shift_type'] = $shift_data['shift_type'];
        }

        $shift_details = ShiftPlan::findorFail($shift);
        Log::info($shift_details);

        $shift_start = $shift_details->clock_in_time;
        $shift_end = $shift_details->clock_out_time;

        $grace_time = $shift_details->grace_time;
        $early_out_grace_minutes = $shift_details->early_out_grace_minutes;
        $excessive_late_after_minutes = $shift_details->excessive_late_after_minutes;

        $shiftTime = $this->buildShiftTime($clock_in, $shift_start, $shift_end);
        $shift_start = $shiftTime['start'];
        $shift_end = $shiftTime['end'];


        $data['working_time'] = $this->getWorkingTime($clock_in, $clock_out);
        $data['late_count'] = $this->getLateTime($clock_in, $shift_start, $grace_time);
        $data['early_out_count'] = $this->getEarlyOutTime($clock_out, $shift_end, $early_out_grace_minutes);
        $data['in_status'] = $this->getClockInStatus($clock_in, $shift_start, $grace_time, $excessive_late_after_minutes);
        $data['out_status'] = $this->getClockOutStatus($clock_out, $shift_end, $early_out_grace_minutes);

        $ot = $this->getOverTimeDetails($employee_id, $clock_in);
        Log::info($ot);
        if (empty($ot)) {
            $data['overtime'] = 0;
        }else{
            $otDetails = OTPlan::findorFail($ot);

            Log::info($otDetails);

            if (!empty($otDetails)) {
                $data['overtime'] = $this->getOverTime($otDetails->maximum_overtime, $shift_details->treat_as_full_day_minutes, $clock_in, $clock_out);
            } else {
                $data['overtime'] = 0;
            }
        }


        $data['work_type'] = $this->getWorkType($clock_in, $clock_out, $shift_details, $data['overtime'], $data['in_status'], $data['out_status']);
        Log::info($data);
        //    dd($data);
        $record->update($data);
    }
}
