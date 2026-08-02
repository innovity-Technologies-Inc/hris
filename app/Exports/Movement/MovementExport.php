<?php

namespace App\Exports\Movement;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class MovementExport implements FromCollection, WithHeadings, WithMapping
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
            'From Date',
            'To Date',
            'Total Days',
            'Source Address',
            'Destination Address',
            'Distance (km)',
            'TA Plan',
            'DA Plan',
            'Total TA',
            'Total DA',
            'Total Allowance',
            'Reason',
            'Status',
            'Payment Status',
        ];
    }

    public function map($row): array
    {
        static $index = 0;
        $index++;

        return [
            $index,
            $row->getEmployee?->full_name ?? 'N/A',
            $row->getEmployee?->system_id ?? 'N/A',
            $row->from_date ? Carbon::parse($row->from_date)->format('d M Y') : '—',
            $row->to_date  ? Carbon::parse($row->to_date)->format('d M Y')  : '—',
            $row->total_days ?? '0',
            $row->details->first()?->source_address ?? 'N/A',
            $row->details->last()?->destination_address ?? 'N/A',
            $row->distance ?? '0',
            $row->getTaPlan?->name ?? 'N/A',
            $row->getDaPlan?->name ?? 'N/A',
            $row->total_ta ?? '0',
            $row->total_da ?? '0',
            $row->total_allowance ?? '0',
            $row->details->first()?->reason ?? '—',
            ucfirst($row->status ?? 'N/A'),
            ucfirst($row->payment_status ?? 'unpaid'),
        ];
    }
}
