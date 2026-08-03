<?php

namespace App\Exports\Payroll;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class BillExport implements FromCollection, WithHeadings, WithMapping
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
            'Billing Type',
            'Expense Detail',
            'Amount',
            'Payment Status',
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
            $row->type === 'claim-expense' 
                ? 'Claim Expense - ' . ($row->expense_type ?? '') 
                : ucfirst(str_replace('-', ' ', $row->type ?? 'N/A')),
            $row->expense_type ?? 'N/A',
            $row->amount ?? '0',
            ucfirst($row->payment_status ?? 'N/A'),
            $row->created_at ? Carbon::parse($row->created_at)->format('d M Y') : '—',
        ];
    }
}
