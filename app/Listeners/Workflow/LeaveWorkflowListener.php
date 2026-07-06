<?php

namespace App\Listeners\Workflow;

use Innovity\ApprovalEngine\Events\ApprovalCompleted;
use Innovity\ApprovalEngine\Events\ApprovalRejected;
use App\Models\Leave\Leave;
use App\Models\Leave\LeaveCount;

class LeaveWorkflowListener
{
    public function handleCompleted(ApprovalCompleted $event): void
    {
        $approvable = $event->approvalRequest->approvable;

        if ($approvable instanceof Leave) {
            $approvable->update([
                'status' => 'approved',
            ]);

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
