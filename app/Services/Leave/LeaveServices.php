<?php

namespace App\Services\Leave;

use App\Enums\UserType;
use App\Imports\Leave\LeavesImport;
use App\Models\Company\Holiday;
use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeCompOff;
use App\Models\Employee\EmployeeLeavePlan;
use App\Models\Employee\EmployeeOfficeInfo;
use App\Models\Leave\Leave;
use App\Models\Leave\LeaveCount;
use App\Models\Plan\LeavePlan;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class LeaveServices
{
    /**
     * Get paginated leaves for index.
     */
    public function getLeavesPaginated(FlexSearch $flexsearch, Request $request)
    {
        $query = Leave::with('getEmployee', 'getPlan');
        $searchableColumns = ['getEmployee.full_name', 'getPlan.name', 'getPlan.leave_type', 'leave_count'];
        $keyword = $request->input('keyword');
        $filters = [
            'from>=' => $request->input('from'),
            'from<=' => $request->input('to'),
        ];

        return $flexsearch->apply($query, $filters, $keyword, $searchableColumns)->paginate(10);
    }

    /**
     * Get data required for leave creation form.
     */
    public function getCreateFormData($user): array
    {
        $isEmployee = $user->user_type === UserType::Employee;

        if ($isEmployee) {
            $employees = Employee::where('id', $user->employee_id)->whereHas('assignedLeavePlans')->get();
        } else {
            $employees = Employee::where('status', 'active')->whereHas('assignedLeavePlans')->orderBy('full_name')->get();
        }

        return [
            'employees' => $employees,
            'isEmployee' => $isEmployee,
        ];
    }

    /**
     * Store leave request and execute status updates.
     */
    public function storeLeave(array $data, $user): Leave
    {
        $employeeId = $data['employee_id'];
        $planId = $data['plan_id'] ?? null;
        $categoryType = $data['leave_category_type'] ?? 'standard';
        $status = $data['status'] ?? 'pending';

        if ($user->user_type === UserType::Employee) {
            $status = 'pending';
        }

        return DB::transaction(function () use ($data, $employeeId, $planId, $categoryType, $status) {
            Log::info('Saving leave request for ' . $employeeId . ' Category: ' . $categoryType);
            
            $payload = $data;
            if ($categoryType === 'compensatory') {
                $payload['plan_id'] = null;
            }
            $payload['status'] = $status;

            $leave = Leave::create($payload);

            if ($status === 'approved') {
                if ($categoryType === 'compensatory') {
                    $compOff = EmployeeCompOff::where('employee_id', $employeeId)->first();
                    if ($compOff) {
                        $compOff->used_days += (float) $leave->leave_count;
                        $compOff->balance_days = $compOff->comp_off_days - $compOff->used_days;
                        $compOff->save();
                    }
                } else {
                    $leaveCount = LeaveCount::where('employee_id', $employeeId)
                        ->where('plan_id', $planId)
                        ->first();

                    if ($leaveCount) {
                        Log::info('Updating leave count for ' . $employeeId);
                        $leaveCount->increment('leave_taken', $leave->leave_count);
                    } else {
                        Log::info('Creating leave count for ' . $employeeId);
                        LeaveCount::create([
                            'employee_id' => $employeeId,
                            'plan_id' => $planId,
                            'leave_taken' => $leave->leave_count
                        ]);
                    }
                }
            } else {
                $leave->startWorkflow('leave');
            }

            return $leave;
        });
    }

    /**
     * Delete leave request and update leave counts.
     */
    public function deleteLeave(int $id, $user): bool
    {
        if ($user->user_type === UserType::Employee) {
            abort(403, 'Unauthorized access.');
        }

        $leaveRequest = Leave::findOrFail($id);

        return DB::transaction(function () use ($leaveRequest) {
            if ($leaveRequest->status === 'approved') {
                if ($leaveRequest->leave_category_type === 'compensatory') {
                    $compOff = EmployeeCompOff::where('employee_id', $leaveRequest->employee_id)->first();
                    if ($compOff) {
                        $compOff->used_days = max(0, $compOff->used_days - (float) $leaveRequest->leave_count);
                        $compOff->balance_days = $compOff->comp_off_days - $compOff->used_days;
                        $compOff->save();
                    }
                } else {
                    $leaveCount = LeaveCount::where('employee_id', $leaveRequest->employee_id)
                        ->where('plan_id', $leaveRequest->plan_id)
                        ->first();
                    if ($leaveCount) {
                        $leaveCount->decrement('leave_taken', $leaveRequest->leave_count);
                    }
                }
            }

            return $leaveRequest->delete();
        });
    }

    /**
     * Get single leave record by ID.
     */
    public function getLeaveById(int $id): Leave
    {
        return Leave::findOrFail($id);
    }

    /**
     * Import leaves from Excel/CSV file.
     */
    public function importLeaves($file): void
    {
        Excel::import(new LeavesImport(), $file);
    }

    /**
     * Get employee leave info for profile view.
     */
    public function getEmployeeLeaveProfileData(int $employeeId, $user): array
    {
        $employee = Employee::findOrFail($employeeId);

        if ($user->user_type === UserType::Employee && $user->employee_id != $employeeId) {
            abort(403, 'Unauthorized access to other profiles.');
        }

        $leaveDetails = EmployeeLeavePlan::with('leaveCount')->where('employee_id', $employeeId)->get();
        
        $currentYear = now()->year;
        foreach ($leaveDetails as $detail) {
            $detail->taken_current_year = (int) Leave::where('employee_id', $employeeId)
                ->where('plan_id', $detail->plan_id)
                ->where('status', 'approved')
                ->whereYear('from', $currentYear)
                ->sum('leave_count');
        }

        $leaveHistory = Leave::where('employee_id', $employeeId)->orderBy('id', 'desc')->get();

        return [
            'employee' => $employee,
            'leaveDetails' => $leaveDetails,
            'leaveHistory' => $leaveHistory,
        ];
    }

    /**
     * Calculate leave end date excluding weekends/holidays if applicable.
     */
    public function calculateEndDate(array $data): string
    {
        $employeeId = $data['employee_id'];
        $planId = $data['plan_id'] ?? null;
        $startDateStr = $data['start_date'];
        $leaveCount = (float) $data['leave_count'];
        $categoryType = $data['leave_category_type'] ?? 'standard';

        if ($categoryType === 'compensatory') {
            $offDayInclude = 'no';
        } else {
            $plan = LeavePlan::findOrFail($planId);
            $offDayInclude = $plan->off_day_include;
        }

        $startDate = Carbon::parse($startDateStr);

        if ($offDayInclude === 'yes') {
            $daysToAdd = ceil($leaveCount) - 1;
            return $startDate->copy()->addDays($daysToAdd)->format('Y-m-d');
        }

        $officeInfo = EmployeeOfficeInfo::where('employee_id', $employeeId)->first();
        $weekends = $officeInfo ? ($officeInfo->weekends ?? []) : ['Friday', 'Saturday'];

        $holidaysList = Holiday::all();
        $holidayDates = collect();
        foreach ($holidaysList as $holiday) {
            $period = CarbonPeriod::create(
                Carbon::parse($holiday->start_date),
                Carbon::parse($holiday->end_date)
            );
            foreach ($period as $date) {
                $holidayDates->push($date->format('Y-m-d'));
            }
        }
        $holidayDates = $holidayDates->unique();

        $validDaysNeeded = ceil($leaveCount);
        $validDaysCount = 0;
        $currentDate = $startDate->copy();

        for ($i = 0; $i < 365; $i++) {
            $currentDayOfWeek = $currentDate->format('l');
            $currentDateStr = $currentDate->format('Y-m-d');

            $isWeekend = in_array($currentDayOfWeek, $weekends);
            $isHoliday = $holidayDates->contains($currentDateStr);

            if (!$isWeekend && !$isHoliday) {
                $validDaysCount++;
            }

            if ($validDaysCount >= $validDaysNeeded) {
                break;
            }

            $currentDate->addDay();
        }

        return $currentDate->format('Y-m-d');
    }
}
