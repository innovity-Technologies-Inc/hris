<?php

namespace App\Exports\Transport;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class VehicleRequisitionExport implements FromCollection, WithHeadings, WithMapping
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
            'System ID',
            'Employee Name',
            'Trip Type',
            'Trip Mode',
            'Vehicle Type',
            'Passengers',
            'Start Time',
            'End Time',
            'Pickup Location',
            'Destination',
            'Status',
        ];
    }

    public function map($row): array
    {
        return [
            $row->getEmployee?->system_id ?? 'N/A',
            $row->getEmployee?->full_name ?? 'N/A',
            $row->trip_type ?? 'N/A',
            $row->trip_mode ?? 'N/A',
            $row->vehicle_type_required ?? 'N/A',
            $row->no_of_passengers ?? 0,
            $row->start_date_time ? Carbon::parse($row->start_date_time)->format('Y-m-d H:i') : '—',
            $row->end_date_time ? Carbon::parse($row->end_date_time)->format('Y-m-d H:i') : '—',
            $row->pickup_location ?? '',
            $row->destination ?? '',
            ucfirst($row->approval_status ?? 'pending'),
        ];
    }
}
