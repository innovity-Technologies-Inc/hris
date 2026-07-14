<?php

namespace App\Listeners\Workflow;

use Innovity\ApprovalEngine\Events\ApprovalCompleted;
use Innovity\ApprovalEngine\Events\ApprovalRejected;
use App\Models\ClaimExpense\ExpenseApplication;

class ClaimExpenseWorkflowListener
{
    public function handleCompleted(ApprovalCompleted $event): void
    {
        $approvable = $event->approvalRequest->approvable;

        if ($approvable instanceof ExpenseApplication) {
            $approvable->update([
                'status' => 'approved',
            ]);
        }
    }

    public function handleRejected(ApprovalRejected $event): void
    {
        $approvable = $event->approvalRequest->approvable;

        if ($approvable instanceof ExpenseApplication) {
            $approvable->update([
                'status' => 'rejected',
            ]);
        }
    }
}
