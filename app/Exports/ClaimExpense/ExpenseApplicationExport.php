<?php

namespace App\Exports\ClaimExpense;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class ExpenseApplicationExport implements FromCollection, WithHeadings, WithMapping
{
    protected $records;

    public function __construct($records)
    {
        $this->records = $records;
    }

    public function collection()
    {
        return $this->records;
    }

    public function headings(): array
    {
        return [
            '#',
            'Employee Name',
            'Employee ID',
            'Expense Type',
            'Amount',
            'Payment Method',
            'Purpose',
            'Status',
            'Remarks',
            'Created At',
        ];
    }

    public function map($row): array
    {
        static $index = 0;
        $index++;

        return [
            $index,
            $row->employee?->full_name ?? 'N/A',
            $row->employee?->applicant_id ?? 'N/A',
            $row->expenseType?->name ?? 'N/A',
            $row->amount ?? '0',
            $row->payment_method ?? 'N/A',
            $row->purpose ?? 'N/A',
            ucfirst($row->status ?? 'N/A'),
            $row->remarks ?? '—',
            $row->created_at ? Carbon::parse($row->created_at)->format('d M Y') : '—',
        ];
    }
}
