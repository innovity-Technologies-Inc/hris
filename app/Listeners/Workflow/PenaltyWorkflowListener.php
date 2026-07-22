<?php

namespace App\Listeners\Workflow;

use Innovity\ApprovalEngine\Events\ApprovalCompleted;
use Innovity\ApprovalEngine\Events\ApprovalRejected;
use App\Models\Payroll\EmployeePenalty;

class PenaltyWorkflowListener
{
    public function handleCompleted(ApprovalCompleted $event): void
    {
        $approvable = $event->approvalRequest->approvable;

        if ($approvable instanceof EmployeePenalty) {
            $approvable->update([
                'status' => 'approved',
            ]);
        }
    }

    public function handleRejected(ApprovalRejected $event): void
    {
        $approvable = $event->approvalRequest->approvable;

        if ($approvable instanceof EmployeePenalty) {
            $approvable->update([
                'status' => 'rejected',
            ]);
        }
    }
}
