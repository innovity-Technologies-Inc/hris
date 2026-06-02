<?php

namespace App\Services\Employee;

use App\Models\Employee\Employee;
use App\Models\Payroll\Payroll;
use App\Models\Payroll\Promotion;
use App\Models\Payroll\Increment;
use App\Models\Transfer\Transfer;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class EmployeeDashboardServices
{
    /**
     * Get aggregated statistics for the employee dashboard.
     */
    public function getDashboardStats(int $employeeId): array
    {
        $employee = Employee::findOrFail($employeeId);
        
        // Calculate Tenure
        $officeInfo = $employee->officeInfo;
        $joiningDate = ($officeInfo && $officeInfo->date_of_join) ? Carbon::parse($officeInfo->date_of_join) : $employee->created_at;
        $now = Carbon::now();
        
        $diff = $joiningDate->diff($now);
        
        $parts = [];
        if ($diff->y > 0) $parts[] = $diff->y . 'y';
        if ($diff->m > 0) $parts[] = $diff->m . 'm';
        if ($diff->d > 0 || empty($parts)) $parts[] = $diff->d . 'd';
        
        $tenureString = implode(' ', $parts);

        // Aggregate Earnings from Payrolls
        $payrolls = Payroll::where('employee_id', $employeeId)->get();
        
        $totalSalary = $payrolls->sum('salary');
        $totalOT = $payrolls->sum('overtime_amount');
        $totalOffdayWork = $payrolls->sum('offday_work_salary');
        $totalBonus = $payrolls->sum('bonus_amount');
        
        $totalEarnings = $totalSalary + $totalOT + $totalOffdayWork;

        return [
            'tenure' => $tenureString,
            'total_earnings' => $totalEarnings,
            'total_bonus' => $totalBonus,
            'payrolls_count' => $payrolls->count(),
        ];
    }

    /**
     * Get a sorted collection of all career milestones for the timeline.
     */
    public function getTimelineEvents(int $employeeId): Collection
    {
        $events = collect();
        $employee = Employee::findOrFail($employeeId);

        // 1. Onboarding (Born)
        if ($employee->date_of_birth) {
            $events->push([
                'date' => Carbon::parse($employee->date_of_birth),
                'type' => 'birth',
                'title' => 'Born',
                'description' => "Born on " . Carbon::parse($employee->date_of_birth)->format('M d, Y') . " (Current Age: " . Carbon::parse($employee->date_of_birth)->age . " years)",
                'icon' => 'calendar',
                'color' => 'secondary'
            ]);
        }

        // 2. Onboarding (Profile Creation)
        $events->push([
            'date' => $employee->created_at,
            'type' => 'onboarding',
            'title' => 'Profile Created',
            'description' => 'Employee profile was registered in the system.',
            'icon' => 'user-plus',
            'color' => 'primary'
        ]);

        // 3. Official Joining Date
        if ($employee->officeInfo && $employee->officeInfo->date_of_join) {
            $joiningDate = Carbon::parse($employee->officeInfo->date_of_join);
            $events->push([
                'date' => $joiningDate,
                'type' => 'joining',
                'title' => 'Joined Organization',
                'description' => 'Official start date of employment.',
                'icon' => 'briefcase',
                'color' => 'info'
            ]);

            // 4. Probation End Date
            if ($employee->officeInfo->probation_duration > 0) {
                $probationEndDate = $joiningDate->copy()->addDays($employee->officeInfo->probation_duration);
                $isCompleted = $probationEndDate->isPast();
                $events->push([
                    'date' => $probationEndDate,
                    'type' => 'probation_end',
                    'title' => 'Probation Period End',
                    'description' => $isCompleted ? 'Successfully completed the probation period.' : 'Scheduled end of the probation period.',
                    'icon' => $isCompleted ? 'shield-check' : 'clock',
                    'color' => $isCompleted ? 'success' : 'warning'
                ]);
            }
        }

        // 3. Profile Approval (using updated_at if active)
        if ($employee->status === 'active') {
            $events->push([
                'date' => $employee->updated_at,
                'type' => 'approval',
                'title' => 'Profile Approved',
                'description' => 'Employee profile was verified and activated.',
                'icon' => 'check-circle',
                'color' => 'success'
            ]);
        }

        // 3. Transfers
        $transfers = Transfer::withoutGlobalScopes()->where('employee_id', $employeeId)->get();
        foreach ($transfers as $transfer) {
            $events->push([
                'date' => $transfer->created_at,
                'type' => 'transfer_request',
                'title' => 'Transfer Requested',
                'description' => "Requested transfer to " . ($transfer->requestedCompany->name ?? 'New Unit'),
                'icon' => 'arrow-right-circle',
                'color' => 'info'
            ]);

            if ($transfer->status === 'completed' && $transfer->completed_at) {
                $events->push([
                    'date' => Carbon::parse($transfer->completed_at),
                    'type' => 'transfer_completed',
                    'title' => 'Transfer Completed',
                    'description' => "Transfer to " . ($transfer->requestedCompany->name ?? 'New Unit') . " was finalized.",
                    'icon' => 'truck',
                    'color' => 'success'
                ]);
            }
        }

        // 4. Promotions
        $promotions = Promotion::where('employee_id', $employeeId)->get();
        foreach ($promotions as $promotion) {
            $events->push([
                'date' => Carbon::parse($promotion->effective_from),
                'type' => 'promotion',
                'title' => 'Career Promotion',
                'description' => "Promoted to " . ($promotion->getNewDesignation->company_designation ?? 'New Designation'),
                'icon' => 'trending-up',
                'color' => 'purple'
            ]);
        }

        // 5. Increments
        $increments = Increment::where('employee_id', $employeeId)->get();
        foreach ($increments as $increment) {
            $events->push([
                'date' => Carbon::parse($increment->effective_from),
                'type' => 'increment',
                'title' => 'Salary Increment',
                'description' => "Salary increased by " . number_format($increment->increment_amount_value, 2),
                'icon' => 'dollar-sign',
                'color' => 'orange'
            ]);
        }

        return $events->sortByDesc('date')->values();
    }
}
