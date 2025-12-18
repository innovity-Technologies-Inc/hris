<?php

namespace App\Services;

use App\Models\EmployeeRosterPlan;
use App\Models\EmployeeShiftPlan;

class AttendanceServices
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getTodayShift($employee_id)
    {
        $roster = EmployeeRosterPlan::where('employee_id', $employee_id)->where('status', 'active')->first();
        if (!empty($roster)) {

        }else{
            $shift = EmployeeShiftPlan::where('employee_id', $employee_id)
                ->where('status', 'active')->first();
        }
        return $shift;
    }

    public function attendanceStore($request)
    {
        $request->validate([
            'attendance.*.employee_id' => 'required|exists:employees,id',
            'attendance.*.clock_in' => 'required|date',
            'attendance.*.clock_out' => 'required|date|after:attendance.*.clock_in',
        ]);
        $data = $request->attendance;
        foreach ($data as $item) {
            $employee_id = $item['employee_id'];
            $clock_in = $item['clock_in'];
            $clock_out = $item['clock_out'];
            $shift = $this->getTodayShift($employee_id);

        }
    }

}
