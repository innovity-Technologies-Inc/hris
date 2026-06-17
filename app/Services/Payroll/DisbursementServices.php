<?php

namespace App\Services\Payroll;

use App\Models\Payroll\Disbursement;
use App\Models\Payroll\DisbursementItem;
use App\Models\Payroll\DisbursementAttachment;
use App\Models\Payroll\PayrollProcess;
use App\Models\Payroll\Payroll;
use App\Models\Payroll\Bonus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DisbursementServices
{
    public function getPendingProcesses($request, $flexsearch)
    {
        // Get all approved Salary and Bonus processes
        $query = PayrollProcess::whereIn('type', ['salary', 'bonus'])
            ->where('approval_status', 'approved')
            ->where('total_amount', '>', 0)
            ->with(['generatedBy', 'getCompany', 'getBranch', 'getDepartment'])
            ->withCount([
                'payrolls as paid_count' => function ($q) {
                    $q->where('disbursement_status', 'paid');
                },
                'payrolls as eligible_count' => function ($q) {
                    $q->where('total_salary', '>', 0);
                },
                'bonuses as paid_bonus_count' => function ($q) {
                    $q->where('disbursement_status', 'paid');
                },
                'bonuses as eligible_bonus_count' => function ($q) {
                    $q->where('amount', '>', 0);
                }
            ]);

        // Check if there are un-disbursed items
        $query->where(function ($q) {
            $q->whereHas('payrolls', function ($p) {
                $p->where('disbursement_status', 'pending')->where('total_salary', '>', 0);
            })->orWhereHas('bonuses', function ($b) {
                $b->where('disbursement_status', 'pending')->where('amount', '>', 0);
            });
        });

        $filters = [];
        if ($request->filled('salary_month')) $filters['salary_month'] = $request->input('salary_month');
        if ($request->filled('type')) $filters['type'] = $request->input('type');

        return $flexsearch->apply($query, $filters, $request->get('keyword'), ['batch_id'])->orderBy('id', 'desc')->paginate(20);
    }

    public function getProcessDetails($id)
    {
        $process = PayrollProcess::with(['getCompany', 'getBranch', 'getDivision', 'getDepartment', 'getSection', 'generatedBy'])->findOrFail($id);
        
        $items = [];
        if ($process->type === 'salary') {
            $items = Payroll::with('getEmployee.officeInfo.getCurrentDesignation')
                ->where('process_id', $id)
                ->where('disbursement_status', 'pending')
                ->where('total_salary', '>', 0)
                ->get();
        } elseif ($process->type === 'bonus') {
            $items = Bonus::with('getEmployee.officeInfo.getCurrentDesignation')
                ->where('process_id', $id)
                ->where('disbursement_status', 'pending')
                ->where('amount', '>', 0)
                ->get();
        }

        return ['process' => $process, 'items' => $items];
    }

    public function processDisbursement($data)
    {
        return DB::transaction(function () use ($data) {
            $processId = $data['process_id'];
            $process = PayrollProcess::findOrFail($processId);
            $recordIds = $data['record_ids']; // Array of Payroll or Bonus IDs
            $paymentMethod = $data['payment_method'];
            $note = $data['note'] ?? null;
            $files = $data['attachments'] ?? [];

            if (empty($recordIds)) {
                throw new \Exception("No employees selected for disbursement.");
            }

            $totalAmount = 0;
            $itemsData = [];

            if ($process->type === 'salary') {
                $records = Payroll::whereIn('id', $recordIds)->where('disbursement_status', 'pending')->get();
                foreach ($records as $record) {
                    $totalAmount += $record->total_salary;
                    $itemsData[] = [
                        'employee_id' => $record->employee_id,
                        'record_id' => $record->id,
                        'amount' => $record->total_salary,
                    ];
                    $record->update(['disbursement_status' => 'paid']);
                }
            } elseif ($process->type === 'bonus') {
                $records = Bonus::whereIn('id', $recordIds)->where('disbursement_status', 'pending')->get();
                foreach ($records as $record) {
                    $totalAmount += $record->amount;
                    $itemsData[] = [
                        'employee_id' => $record->employee_id,
                        'record_id' => $record->id,
                        'amount' => $record->amount,
                    ];
                    $record->update(['disbursement_status' => 'paid']);
                }
            }

            if (empty($itemsData)) {
                throw new \Exception("Selected records are either invalid or already disbursed.");
            }

            $disbursement = Disbursement::create([
                'process_id' => $process->id,
                'batch_id' => uniqid('Disb_'),
                'process_type' => $process->type,
                'payment_method' => $paymentMethod,
                'total_amount' => $totalAmount,
                'total_employees' => count($itemsData),
                'note' => $note,
                'disbursed_by' => Auth::id(),
            ]);

            foreach ($itemsData as $item) {
                DisbursementItem::create([
                    'disbursement_id' => $disbursement->id,
                    'employee_id' => $item['employee_id'],
                    'record_id' => $item['record_id'],
                    'amount' => $item['amount'],
                ]);
            }

            if (!empty($files)) {
                foreach ($files as $file) {
                    $path = $file->store('disbursements', 'public');
                    DisbursementAttachment::create([
                        'disbursement_id' => $disbursement->id,
                        'file_path' => $path,
                        'original_name' => $file->getClientOriginalName(),
                    ]);
                }
            }

            Log::info("Disbursement created successfully.", ['disbursement_id' => $disbursement->id, 'batch_id' => $disbursement->batch_id]);

            return $disbursement;
        });
    }
}
