<?php

namespace App\Exports\Payroll;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class PenaltyExport implements FromCollection, WithHeadings, WithMapping
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
            'Penalty Plan',
            'Occurrence Date',
            'Cause',
            'Penalty Amount',
            'Status',
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
            $row->penaltyPlan?->title ?? 'N/A',
            $row->occurrence_date ? Carbon::parse($row->occurrence_date)->format('d M Y') : '—',
            $row->cause ?? 'N/A',
            $row->penalty_amount ?? '0.00',
            ucfirst($row->status ?? 'N/A'),
            $row->created_at ? Carbon::parse($row->created_at)->format('d M Y') : '—',
        ];
    }
}
