<?php

namespace App\Listeners\Workflow;

use Innovity\ApprovalEngine\Events\ApprovalCompleted;
use Innovity\ApprovalEngine\Events\ApprovalRejected;
use App\Models\Payroll\Promotion;

class PromotionWorkflowListener
{
    public function handleCompleted(ApprovalCompleted $event): void
    {
        $approvable = $event->approvalRequest->approvable;

        if ($approvable instanceof Promotion) {
            $approvable->update([
                'status' => 'approved',
            ]);
        }
    }

    public function handleRejected(ApprovalRejected $event): void
    {
        $approvable = $event->approvalRequest->approvable;

        if ($approvable instanceof Promotion) {
            $approvable->update([
                'status' => 'rejected',
            ]);
        }
    }
}
