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

            // Add or update row in the bills table
            \App\Models\Payroll\Bill::updateOrCreate(
                [
                    'expense_id' => $approvable->id,
                    'type' => 'claim-expense',
                ],
                [
                    'employee_id' => $approvable->employee_id,
                    'amount' => $approvable->amount,
                    'expense_type' => $approvable->expenseType->name ?? 'Claim Expense',
                    'payment_status' => 'unpaid',
                ]
            );
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
