<?php

namespace App\Exports\Leave;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class LeaveExport implements FromCollection, WithHeadings, WithMapping
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
            'Employee ID',
            'Employee Name',
            'Leave Plan / Category',
            'Days',
            'From',
            'To',
            'Reason',
            'Status',
        ];
    }

    public function map($row): array
    {
        return [
            $row->getEmployee?->system_id ?? 'N/A',
            $row->getEmployee?->applicant_id ?? 'N/A',
            $row->getEmployee?->full_name ?? 'N/A',
            $row->leave_category_type === 'compensatory' ? 'Compensatory Leave' : ($row->getPlan?->name ?? 'N/A'),
            $row->leave_count ?? '0',
            $row->from ? Carbon::parse($row->from)->format('Y-m-d') : '—',
            $row->to ? Carbon::parse($row->to)->format('Y-m-d') : '—',
            $row->reason ?? '',
            ucfirst($row->status ?? 'pending'),
        ];
    }
}
