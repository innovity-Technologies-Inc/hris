<?php

namespace App\Listeners\Workflow;

use Innovity\ApprovalEngine\Events\ApprovalCompleted;
use Innovity\ApprovalEngine\Events\ApprovalRejected;
use App\Models\Leave\Leave;
use App\Models\Leave\LeaveCount;
use App\Models\Employee\EmployeeCompOff;

class LeaveWorkflowListener
{
    public function handleCompleted(ApprovalCompleted $event): void
    {
        $approvable = $event->approvalRequest->approvable;

        if ($approvable instanceof Leave) {
            $approvable->update([
                'status' => 'approved',
            ]);

            if ($approvable->leave_category_type === 'compensatory') {
                $compOff = EmployeeCompOff::where('employee_id', $approvable->employee_id)->first();
                if ($compOff) {
                    $compOff->used_days += (float) $approvable->leave_count;
                    $compOff->balance_days = $compOff->comp_off_days - $compOff->used_days;
                    $compOff->save();
                }
            } else {
                $leaveCount = LeaveCount::where('employee_id', $approvable->employee_id)
                    ->where('plan_id', $approvable->plan_id)
                    ->first();

                if ($leaveCount) {
                    $leaveCount->increment('leave_taken', $approvable->leave_count);
                } else {
                    LeaveCount::create([
                        'employee_id' => $approvable->employee_id,
                        'plan_id' => $approvable->plan_id,
                        'leave_taken' => $approvable->leave_count
                    ]);
                }
            }
        }
    }

    public function handleRejected(ApprovalRejected $event): void
    {
        $approvable = $event->approvalRequest->approvable;

        if ($approvable instanceof Leave) {
            $approvable->update([
                'status' => 'rejected',
            ]);
        }
    }
}
