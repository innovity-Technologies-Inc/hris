<?php

namespace App\Listeners\Workflow;

use Innovity\ApprovalEngine\Events\ApprovalCompleted;
use Innovity\ApprovalEngine\Events\ApprovalRejected;
use App\Models\Payroll\PayrollProcess;

class PayrollWorkflowListener
{
    public function handleCompleted(ApprovalCompleted $event): void
    {
        $approvable = $event->approvalRequest->approvable;

        if ($approvable instanceof PayrollProcess) {
            $approvable->update([
                'approval_status' => 'approved',
            ]);

            if ($approvable->type === 'advance') {
                $approvable->advanceSalaries()->update(['status' => 'approved']);
            } elseif ($approvable->type === 'arrear') {
                $approvable->arrears()->update(['status' => 'approved']);
            }
        }
    }

    public function handleRejected(ApprovalRejected $event): void
    {
        $approvable = $event->approvalRequest->approvable;

        if ($approvable instanceof PayrollProcess) {
            $approvable->update([
                'approval_status' => 'rejected',
            ]);

            if ($approvable->type === 'advance') {
                $approvable->advanceSalaries()->update(['status' => 'rejected']);
            } elseif ($approvable->type === 'arrear') {
                $approvable->arrears()->update(['status' => 'rejected']);
            }
        }
    }
}
