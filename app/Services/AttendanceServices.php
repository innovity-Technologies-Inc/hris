<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\EmployeeOtPlan;
use App\Models\EmployeeRosterPlan;
use App\Models\EmployeeShiftPlan;
use App\Models\OTPlan;
use App\Models\ShiftPlan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AttendanceServices
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getTodayShift($employee_id, $clock_in)
    {
        $roster = EmployeeRosterPlan::where('employee_id', $employee_id)->where('status', 'active')->first();
        if (!empty($roster)) {
            $clock_in = Carbon::parse($clock_in);
            $from = Carbon::parse($roster->from)->copy()->startOfDay();
            $to = Carbon::parse($roster->to)->copy()->endOfDay();

            if ($clock_in->between($from, $to) && $roster->status == 'active') {
                $dayPassed = (int)$from->diffInDays($clock_in->startOfDay());
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
                $shift = EmployeeShiftPlan::where('employee_id', $employee_id)
                    ->where('status', 'active')->first()->plan_id;
            }
        } else {
            $shift = EmployeeShiftPlan::where('employee_id', $employee_id)
                ->where('status', 'active')->first()->plan_id;
        }
        return $shift;
    }

    public function getWorkingTime($clock_in, $clock_out)
    {
        return $clock_in->diffInMinutes($clock_out);
    }

    public function getLateTime($clock_in, $shift_start, $grace_time)
    {
        $graceperiod = $shift_start->copy()->addMinutes($grace_time);

        if ($clock_in <= $graceperiod) {
            return 0;
        } else {
            return $shift_start->diffInMinutes($clock_in);
        }
    }

    public function getEarlyOutTime($clock_out, $shift_end, $early_out_grace_minutes)
    {
        $clock_out = Carbon::parse($clock_out);
        $graceperiod = $shift_end->copy()->subMinutes($early_out_grace_minutes);

        if ($clock_out >= $graceperiod) {
            return 0;
        } else {
            return $clock_out->diffInMinutes($shift_end);
        }
    }

    public function getClockInStatus($clock_in, $shift_start, $grace_time, $excessive_late_after_minutes)
    {
        $duration = $this->getLateTime($clock_in, $shift_start, $grace_time);
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

    public function getWorkType($clock_in, $clock_out, $shift_details, $overtime)
    {
        $working_time = $this->getWorkingTime($clock_in, $clock_out);
        if ($overtime > 0) {
            return 'Overtime';
        } elseif ($working_time >= $shift_details->half_day_minutes && $working_time < $shift_details->full_day_minutes) {
            return 'Half-Day';
        } elseif ($working_time == $shift_details->full_day_minutes) {
            return 'Full-Day';
        } else {
            return null;
        }
    }

    public function getAttendanceStatus($clock_in, $clock_out, $shift_details)
    {
        $working_time = $this->getWorkingTime($clock_in, $clock_out);
        if ($working_time >= $shift_details->half_day_minutes) {
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

    public function attendanceStore($request)
    {
        $request->validate([
            'attendance.*.employee_id' => 'required|exists:employees,id',
            'attendance.*.clock_in' => 'required|date',
            'attendance.*.clock_out' => 'required|date|after:attendance.*.clock_in',
        ]);

        $attendance = $request->attendance;
        foreach ($attendance as $item) {
            $employee_id = $item['employee_id'];
            $clock_in = $item['clock_in'];
            $clock_out = $item['clock_out'];

            $data = [
                'employee_id' => $employee_id,
                'in_time' => $clock_in,
                'out_time' => $clock_out,
            ];

            $clock_in = Carbon::parse($clock_in);
            $clock_out = Carbon::parse($clock_out);

            $shift = $this->getTodayShift($employee_id, $clock_in);
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
            $otDetails = OTPlan::findorFail($ot);

            Log::info($otDetails);

            if (!empty($otDetails)) {
                $data['overtime'] = $this->getOverTime($otDetails->maximum_overtime, $shift_details->treat_as_full_day_minutes, $clock_in, $clock_out);
            } else {
                $data['overtime'] = 0;
            }

            $data['work_type'] = $this->getWorkType($clock_in, $clock_out, $shift_details, $data['overtime']);
            $data['attendance_status'] = $this->getAttendanceStatus($clock_in, $clock_out, $shift_details);
            Log::info($data);
//            dd($data);
            Attendance::create($data);
        }
    }
}
