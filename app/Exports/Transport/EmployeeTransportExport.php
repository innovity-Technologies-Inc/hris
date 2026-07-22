<?php

namespace App\Exports\Transport;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class EmployeeTransportExport implements FromCollection, WithHeadings, WithMapping
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
            'Service Name',
            'Company',
            'Transport Type',
            'Purpose',
            'Start Date',
            'End Date',
            'Estimated Passengers',
            'Status',
        ];
    }

    public function map($row): array
    {
        return [
            $row->service_name ?? 'N/A',
            $row->getCompany?->name ?? 'N/A',
            $row->transport_type ?? 'N/A',
            $row->purpose ?? 'N/A',
            $row->start_date ? Carbon::parse($row->start_date)->format('Y-m-d') : '—',
            $row->end_date ? Carbon::parse($row->end_date)->format('Y-m-d') : '—',
            $row->estimated_passengers ?? 0,
            ucfirst($row->status ?? 'pending'),
        ];
    }
}
