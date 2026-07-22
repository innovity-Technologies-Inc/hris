<?php

namespace App\Exports\Transport;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class VehicleAllocationExport implements FromCollection, WithHeadings, WithMapping
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
            'Vehicle',
            'Model/Number',
            'Allocated To',
            'Allocation Type',
            'Reference Type',
            'Reference ID',
            'Start Date',
            'End Date',
            'Status',
            'Approved At',
        ];
    }

    public function map($row): array
    {
        $refLabel = match ($row->reference_type) {
            'App\\Models\\Transport\\EmployeeTransport' => 'Emp. Transport',
            'App\\Models\\Transport\\VehicleRequisition' => 'Requisition',
            default => '—',
        };

        return [
            $row->getVehicle?->vehicle_category ?? 'N/A',
            $row->getVehicle?->model_number ?? 'N/A',
            $row->name ?? 'N/A',
            $row->allocation_type ?? 'N/A',
            $refLabel,
            $row->reference_id ?? '—',
            $row->start_date ? Carbon::parse($row->start_date)->format('Y-m-d') : '—',
            $row->end_date ? Carbon::parse($row->end_date)->format('Y-m-d') : '—',
            ucfirst($row->status ?? 'pending'),
            $row->approved_at ? Carbon::parse($row->approved_at)->format('Y-m-d H:i') : '—',
        ];
    }
}
