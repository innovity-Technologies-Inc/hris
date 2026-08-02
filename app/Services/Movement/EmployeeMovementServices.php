<?php

namespace App\Services\Movement;

use App\Models\Movement\EmployeeMovement;
use App\Models\Movement\EmployeeMovementDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EmployeeMovementServices
{
    /**
     * Store or update employee travel movement application.
     */
    public function saveMovement(array $data, $request, ?int $id = null): EmployeeMovement
    {
        return DB::transaction(function () use ($data, $request, $id) {
            $movementData = [
                'employee_id' => $data['employee_id'],
                'from_date' => $data['from_date'],
                'to_date' => $data['to_date'],
                'distance' => $data['distance'],
                'total_days' => $data['total_days'],
                'status' => $data['status'],
            ];

            if ($id) {
                $movement = EmployeeMovement::findOrFail($id);
                $movement->update($movementData);
            } else {
                $movement = EmployeeMovement::create($movementData);
                try {
                    $movement->startWorkflow('travel-movement');
                } catch (\Exception $e) {
                    Log::error('Approval workflow failed to start for Travel Movement #' . $movement->id . ': ' . $e->getMessage());
                }
            }

            // Process route leg items
            $items = $data['items'] ?? [];
            $existingItemIds = $movement->details()->pluck('id')->toArray();
            $newItemIds = [];

            foreach ($items as $index => $itemData) {
                $detailId = $itemData['id'] ?? null;
                $detailData = [
                    'source_address' => $itemData['source_address'],
                    'source_lat' => $itemData['source_lat'],
                    'source_lng' => $itemData['source_lng'],
                    'destination_address' => $itemData['destination_address'],
                    'dest_lat' => $itemData['dest_lat'],
                    'dest_lng' => $itemData['dest_lng'],
                    'distance' => $itemData['distance'],
                    'reason' => $itemData['reason'] ?? null,
                ];

                // Handle attachment upload if present
                if ($request->hasFile("items.{$index}.attachment")) {
                    $file = $request->file("items.{$index}.attachment");
                    $filePath = \App\HelperClass::file_upload($file, 'movements');
                    $detailData['attachment_path'] = $filePath;
                }

                if ($detailId && in_array($detailId, $existingItemIds)) {
                    $detail = EmployeeMovementDetail::findOrFail($detailId);
                    
                    // Keep old attachment if no new file is uploaded
                    if (!isset($detailData['attachment_path'])) {
                        unset($detailData['attachment_path']);
                    } else {
                        // Delete old file
                        if ($detail->attachment_path) {
                            \App\HelperClass::file_delete($detail->attachment_path);
                        }
                    }
                    
                    $detail->update($detailData);
                    $newItemIds[] = $detailId;
                } else {
                    $detail = $movement->details()->create($detailData);
                    $newItemIds[] = $detail->id;
                }
            }

            // Clean up removed details
            $itemsToDelete = array_diff($existingItemIds, $newItemIds);
            foreach ($itemsToDelete as $deleteId) {
                $detail = EmployeeMovementDetail::find($deleteId);
                if ($detail) {
                    if ($detail->attachment_path) {
                        \App\HelperClass::file_delete($detail->attachment_path);
                    }
                    $detail->delete();
                }
            }

            return $movement->load('details');
        });
    }

    /**
     * Save or update allowances for an approved movement.
     */
    public function saveAllowances(array $data, int $id): EmployeeMovement
    {
        $movement = EmployeeMovement::findOrFail($id);
        $movement->update([
            'ta_plan_id' => $data['ta_plan_id'] ?? null,
            'da_plan_id' => $data['da_plan_id'] ?? null,
            'total_ta' => $data['total_ta'],
            'total_da' => $data['total_da'],
            'total_allowance' => $data['total_allowance'],
        ]);

        // Add or update row in the bills table
        \App\Models\Payroll\Bill::updateOrCreate(
            [
                'expense_id' => $movement->id,
                'type' => 'travel-movement',
            ],
            [
                'employee_id' => $movement->employee_id,
                'amount' => $movement->total_allowance,
                'expense_type' => 'Travel Movement',
                'payment_status' => 'unpaid',
            ]
        );

        return $movement;
    }

    /**
     * Delete an employee travel movement.
     */
    public function deleteMovement(int $id): void
    {
        DB::transaction(function () use ($id) {
            $movement = EmployeeMovement::findOrFail($id);
            
            // Delete all associated files
            foreach ($movement->details as $detail) {
                if ($detail->attachment_path) {
                    \App\HelperClass::file_delete($detail->attachment_path);
                }
            }
            
            $movement->delete(); // cascading DB delete will drop details
        });
    }
}
