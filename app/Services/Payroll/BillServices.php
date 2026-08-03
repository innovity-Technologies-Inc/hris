<?php

namespace App\Services\Payroll;

use App\Models\Payroll\Bill;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Pagination\LengthAwarePaginator;

class BillServices
{
    /**
     * Get paginated list of bills.
     */
    public function getBillsList(array $filters, ?string $keyword, FlexSearch $flexsearch): LengthAwarePaginator
    {
        $query = Bill::with('employee')->latest();
        $searchableColumns = ['employee.full_name', 'type', 'expense_type', 'payment_status'];

        return $flexsearch
            ->apply($query, $filters, $keyword, $searchableColumns)
            ->paginate(10);
    }

    /**
     * Update the payment status of a bill.
     */
    public function updatePaymentStatus(int $id, string $status, ?string $method = null, ?string $remarks = null, $attachment = null): Bill
    {
        $bill = Bill::findOrFail($id);
        
        $updateData = [
            'payment_status' => $status,
        ];

        if ($status === 'paid') {
            $updateData['payment_method'] = $method;
            $updateData['remarks'] = $remarks;

            if ($attachment && $attachment->isValid()) {
                // Delete old file if exists
                if ($bill->attachment_path) {
                    \App\HelperClass::delete_file($bill->attachment_path);
                }
                $updateData['attachment_path'] = \App\HelperClass::file_upload($attachment, 'bills');
            }
        } else {
            // If marking as unpaid, clear payment details
            if ($bill->attachment_path) {
                \App\HelperClass::delete_file($bill->attachment_path);
            }
            $updateData['payment_method'] = null;
            $updateData['remarks'] = null;
            $updateData['attachment_path'] = null;
        }

        $bill->update($updateData);

        // Sync with EmployeeMovement if applicable
        if ($bill->type === 'travel-movement') {
            $movement = \App\Models\Movement\EmployeeMovement::find($bill->expense_id);
            if ($movement) {
                $movement->update(['payment_status' => $bill->payment_status]);
            }
        }

        return $bill;
    }

    /**
     * Delete a bill.
     */
    public function deleteBill(int $id): void
    {
        $bill = Bill::findOrFail($id);
        if ($bill->attachment_path) {
            \App\HelperClass::delete_file($bill->attachment_path);
        }
        $bill->delete();
    }
}
