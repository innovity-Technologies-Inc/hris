<?php

namespace App\Exports\Payroll;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class BonusDetailExport implements FromCollection, WithHeadings, WithMapping
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
            'Bonus Amount',
            'Disbursement Status',
        ];
    }

    public function map($row): array
    {
        static $index = 0;
        $index++;

        return [
            $index,
            $row->getEmployee?->full_name ?? 'N/A',
            $row->getEmployee?->applicant_id ?? 'N/A',
            $row->getEmployee?->system_id ?? 'N/A',
            $row->amount ?? '0.00',
            ucfirst($row->disbursement_status ?? 'Pending'),
        ];
    }
}
