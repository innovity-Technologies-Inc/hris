<?php

namespace App\Exports\Transfer;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class TransferExport implements FromCollection, WithHeadings, WithMapping
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
            'Current Company',
            'Current Unit/Branch',
            'Requested Company',
            'Requested Unit/Branch',
            'Movement Type',
            'Effective From',
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
            $row->currentCompany?->name ?? 'N/A',
            $row->currentBusinessUnit?->name ?? 'N/A',
            $row->requestedCompany?->name ?? 'N/A',
            $row->requestedBusinessUnit?->name ?? 'N/A',
            $row->movementType?->name ?? 'N/A',
            $row->effective_from ? Carbon::parse($row->effective_from)->format('d M Y') : '—',
            ucfirst($row->status ?? 'N/A'),
            $row->created_at ? Carbon::parse($row->created_at)->format('d M Y') : '—',
        ];
    }
}
