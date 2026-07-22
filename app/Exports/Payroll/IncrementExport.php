<?php

namespace App\Exports\Payroll;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class IncrementExport implements FromCollection, WithHeadings, WithMapping
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
            'Increment Base',
            'Increment Method',
            'Increase Amount',
            'Previous Gross Salary',
            'New Gross Salary',
            'Effective From',
            'Status',
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
            ucwords(str_replace('_', ' ', $row->increment_base ?? 'N/A')),
            ucfirst($row->increment_method ?? 'N/A'),
            $row->salary_increase_amount ?? '0.00',
            $row->previous_gross_salary ?? '0.00',
            $row->new_gross_salary ?? '0.00',
            $row->effective_from ? Carbon::parse($row->effective_from)->format('d M Y') : '—',
            ucfirst($row->status ?? 'Pending'),
        ];
    }
}
