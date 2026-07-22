<?php

namespace App\Exports\Payroll;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class AdvanceSalaryDetailExport implements FromCollection, WithHeadings, WithMapping
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
            'System ID',
            'Advance Amount',
            'Deduction Month',
            'Reason',
            'Status',
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
            $row->employee?->system_id ?? 'N/A',
            $row->amount ?? '0.00',
            $row->deduction_month ? Carbon::parse($row->deduction_month)->format('F Y') : '—',
            $row->reason ?? '',
            ucfirst($row->status ?? 'Pending'),
        ];
    }
}
