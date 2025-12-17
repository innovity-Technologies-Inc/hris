<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\EmployeeEligiblePlan;
use App\Models\EmployeeLeavePlan;
use App\Models\Leave;
use App\Models\LeaveCount;
use App\Models\LeavePlan;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class LeavesImport implements ToCollection
{
    public function collection(Collection $collection)
    {
        // Skip the first row (header)
        $collection->skip(1)->each(function ($row, $index) {

            // Convert all values to string to avoid numeric loss
            $row = $row->map(fn($cell) => $this->toString($cell));

            // Skip completely empty rows
            if ($row->filter()->isEmpty()) {
                return;
            }

            // Validate and process the row
            try {
                $this->processRow($row, $index);
            } catch (\Exception $e) {
                Log::error("Leave Import Error - Row " . ($index + 2) . ": " . $e->getMessage());
                throw $e;
            }
        });
    }

    private function processRow($row, $index)
    {
        // Extract data from row (using numeric indices like other imports)
        $employeeIdentifier = $row[0] ?? null;  // employee_id
        $planIdentifier = $row[1] ?? null;      // plan_name
        $fromDate = $this->parseDate($row[2] ?? null);  // from_date
        $toDate = $this->parseDate($row[3] ?? null);    // to_date
        $leaveCount = $this->toInt($row[4] ?? null);    // leave_count
        $reason = $row[5] ?? null;              // reason
        $status = strtolower($row[6] ?? 'pending');  // status

        // Validate required fields
        if (empty($employeeIdentifier)) {
            throw new \Exception("Employee ID/System ID is required");
        }

        if (empty($planIdentifier)) {
            throw new \Exception("Plan ID/Name is required");
        }

        if (empty($fromDate)) {
            throw new \Exception("From date is required");
        }

        if (empty($toDate)) {
            throw new \Exception("To date is required");
        }

        if (empty($leaveCount) || $leaveCount < 1) {
            throw new \Exception("Leave count must be at least 1 day");
        }

        if (empty($reason)) {
            throw new \Exception("Reason is required");
        }

        // Validate status
        if (!in_array($status, ['pending', 'approved', 'rejected'])) {
            throw new \Exception("Status must be one of: pending, approved, rejected");
        }

        // Find employee
        $employee = Employee::where('system_id', $employeeIdentifier)
            ->orWhere('applicant_id', $employeeIdentifier)
            ->first();

        if (!$employee) {
            throw new \Exception("Employee not found with identifier: {$employeeIdentifier}");
        }

        // Find leave plan
        if (is_numeric($planIdentifier)) {
            $leavePlan = LeavePlan::find($planIdentifier);
        } else {
            $leavePlan = LeavePlan::where('name', $planIdentifier)
                ->orWhere('short_name', $planIdentifier)
                ->first();
        }

        if (!$leavePlan) {
            throw new \Exception("Leave plan not found with identifier: {$planIdentifier}");
        }

        // Check if employee is eligible for this leave plan
        $eligibility = EmployeeEligiblePlan::where('employee_id', $employee->id)->first();

        if (!$eligibility) {
            throw new \Exception("Employee {$employee->system_id} has no eligible plans assigned");
        }

        if ($eligibility->leave_plan_status != 'active') {
            throw new \Exception("Leave plan is not active for employee {$employee->system_id}");
        }

        if ($eligibility->leave_plan_from <= Carbon::today()) {
            throw new \Exception("Leave plan has not started yet for employee {$employee->system_id}");
        }

        // Check if employee has this specific leave plan assigned
        $employeeLeavePlan = EmployeeLeavePlan::where('employee_id', $employee->id)
            ->where('plan_id', $leavePlan->id)
            ->first();

        if (!$employeeLeavePlan) {
            throw new \Exception("Employee {$employee->system_id} is not assigned to {$leavePlan->name} plan");
        }

        // Validate max consecutive days
        if ($leaveCount > $leavePlan->max_no_of_days) {
            throw new \Exception(
                "Cannot request more than {$leavePlan->max_no_of_days} consecutive days for {$leavePlan->name}. " .
                "Requested: {$leaveCount} days"
            );
        }

        // Check leave balance
        $leaveCountData = LeaveCount::where('employee_id', $employee->id)
            ->where('plan_id', $leavePlan->id)
            ->first();

        $leaveTaken = $leaveCountData ? $leaveCountData->leave_taken : 0;
        $remainingLeaves = $leavePlan->leave_limit - $leaveTaken;

        if ($leaveCount > $remainingLeaves) {
            throw new \Exception(
                "Insufficient leave balance for {$leavePlan->name}. " .
                "Available: {$remainingLeaves} days, Requested: {$leaveCount} days"
            );
        }

        // Validate dates
        $fromDateCarbon = Carbon::parse($fromDate);
        $toDateCarbon = Carbon::parse($toDate);

        if ($toDateCarbon->lt($fromDateCarbon)) {
            throw new \Exception("To date cannot be before from date");
        }

        // Create leave request
        $leave = Leave::create([
            'employee_id' => $employee->id,
            'plan_id' => $leavePlan->id,
            'from' => $fromDate,
            'to' => $toDate,
            'leave_count' => $leaveCount,
            'reason' => $reason,
            'status' => $status,
        ]);

        Log::info("Leave request created for employee {$employee->system_id}, Plan: {$leavePlan->name}, Status: {$status}");

        // If status is approved, update leave count
        if ($status == 'approved') {
            if ($leaveCountData) {
                $leaveCountData->increment('leave_taken', $leaveCount);
            } else {
                LeaveCount::create([
                    'employee_id' => $employee->id,
                    'plan_id' => $leavePlan->id,
                    'leave_taken' => $leaveCount,
                ]);
            }

            Log::info("Leave count updated for employee {$employee->system_id}, Added: {$leaveCount} days");
        }
    }

    private function getEmployeeId($model, $identifier)
    {
        if (empty($identifier)) return null;
        $record = $model::where('system_id', $identifier)
            ->orWhere('applicant_id', $identifier)
            ->orWhere('id', $identifier)
            ->first();
        return $record ? $record->id : null;
    }

    /**
     * Parse date from various formats
     */
    private function parseDate($value)
    {
        if (empty($value)) return null;

        try {
            // Handle Excel date serial number
            if (is_numeric($value)) {
                return Carbon::instance(Date::excelToDateTimeObject($value))->format('Y-m-d');
            }

            // Handle string dates
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            throw new \Exception("Invalid date format: {$value}");
        }
    }

    /**
     * Convert numeric values to integer
     */
    private function toInt($value)
    {
        if (is_null($value) || $value === '') return null;

        return (int) floatval($value);
    }

    /**
     * Convert any cell value to safe string
     */
    private function toString($value)
    {
        if (is_null($value)) return null;

        if (is_numeric($value)) {
            if (floor($value) == $value) {
                return (string) $value;
            }
            return number_format($value, 2, '.', '');
        }

        return trim((string)$value);
    }

    /**
     * Get import statistics
     */
    public function getStats()
    {
        return [
            'success' => $this->successCount,
            'failed' => $this->failedCount,
            'errors' => $this->errors,
        ];
    }
}
