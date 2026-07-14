<?php

namespace App\Services\ClaimExpense;

use App\Models\ClaimExpense\ExpenseApplication;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ExpenseApplicationService
{
    public function createApplication(array $data, $receiptFile = null): ExpenseApplication
    {
        try {
            if ($receiptFile) {
                $path = $receiptFile->store('receipts', 'public');
                $data['receipt_path'] = '/storage/' . $path;
            }

            $data['status'] = 'pending';
            $data['created_by'] = auth()->id();

            $application = ExpenseApplication::create($data);

            // Start the approval workflow using laravel-approval-engine
            try {
                $application->startWorkflow('claim-expense');
            } catch (\Exception $e) {
                Log::error('Approval workflow failed to start for Expense Application #' . $application->id . ': ' . $e->getMessage());
            }

            return $application;
        } catch (\Exception $e) {
            Log::error('Expense Application Creation Error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function deleteApplication(ExpenseApplication $application): void
    {
        if ($application->status !== 'pending') {
            throw new \Exception('Only pending applications can be deleted.');
        }

        if ($application->receipt_path) {
            $relativePath = str_replace('/storage/', '', $application->receipt_path);
            Storage::disk('public')->delete($relativePath);
        }

        $application->delete();
    }
}
