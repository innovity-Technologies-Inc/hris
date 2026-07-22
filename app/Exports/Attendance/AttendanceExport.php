<?php

namespace App\Exports\Attendance;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class AttendanceExport implements FromCollection, WithHeadings, WithMapping
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
            'Shift Type',
            'Clock In',
            'Clock In Status',
            'Clock Out',
            'Clock Out Status',
            'Attendance Status',
            'Workstation',
            'Working Time (Hours)',
            'Overtime (Hours)',
        ];
    }

    public function map($row): array
    {
        return [
            $row->getEmployee?->system_id ?? 'N/A',
            $row->getEmployee?->applicant_id ?? 'N/A',
            $row->getEmployee?->full_name ?? 'N/A',
            $row->shift_type ?? 'N/A',
            $row->in_time ? Carbon::parse($row->in_time)->format('Y-m-d h:i A') : '—',
            $row->in_status ?? 'N/A',
            $row->out_time ? Carbon::parse($row->out_time)->format('Y-m-d h:i A') : '—',
            $row->out_status ?? 'N/A',
            $row->attendance_status ?? 'N/A',
            $row->workstation ?? 'N/A',
            $row->working_time ?? '0',
            $row->overtime ?? '0',
        ];
    }
}
