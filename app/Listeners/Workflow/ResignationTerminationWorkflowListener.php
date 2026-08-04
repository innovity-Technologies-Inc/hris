<?php

namespace App\Listeners\Workflow;

use Innovity\ApprovalEngine\Events\ApprovalCompleted;
use Innovity\ApprovalEngine\Events\ApprovalRejected;
use App\Models\Resignation\Resignation;
use App\Models\Offboarding\Offboarding;
use App\Models\Employee\Employee;

class ResignationTerminationWorkflowListener
{
    public function handleCompleted(ApprovalCompleted $event): void
    {
        $approvable = $event->approvalRequest->approvable;

        if ($approvable instanceof Resignation) {
            $approvable->update([
                'status' => 'approved',
            ]);

            // Update employee status to resigned
            $employee = Employee::withoutGlobalScopes()->find($approvable->employee_id);
            if ($employee) {
                $employee->update(['status' => 'resigned']);
            }
        } elseif ($approvable instanceof Offboarding) {
            $approvable->update([
                'status' => 'approved',
            ]);

            // Ensure employee status is updated correctly based on type
            $employee = Employee::withoutGlobalScopes()->find($approvable->employee_id);
            if ($employee) {
                $newStatus = $approvable->offboarding_type === 'termination' ? 'terminated' : 'resigned';
                $employee->update(['status' => $newStatus]);
            }
        }
    }

    public function handleRejected(ApprovalRejected $event): void
    {
        $approvable = $event->approvalRequest->approvable;

        if ($approvable instanceof Resignation) {
            $approvable->update([
                'status' => 'rejected',
            ]);

            // Restore employee status to active
            $employee = Employee::withoutGlobalScopes()->find($approvable->employee_id);
            if ($employee) {
                $employee->update(['status' => 'active']);
            }
        } elseif ($approvable instanceof Offboarding) {
            $approvable->update([
                'status' => 'rejected',
            ]);

            // Restore employee status to active since offboarding was rejected
            $employee = Employee::withoutGlobalScopes()->find($approvable->employee_id);
            if ($employee) {
                $employee->update(['status' => 'active']);
            }
        }
    }
}
