<?php

namespace App\Exports\Payroll;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SalaryDetailExport implements FromCollection, WithHeadings, WithMapping
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
            'Basic/Gross Salary',
            'Overtime Amount',
            'Bonus Amount',
            'Deduction Amount',
            'Net Payable Salary',
        ];
    }

    public function map($row): array
    {
        static $index = 0;
        $index++;

        $netPayable = ($row->salary ?? 0.00) + ($row->overtime_amount ?? 0.00) + ($row->bonus_amount ?? 0.00) - ($row->deduction_amount ?? 0.00);

        return [
            $index,
            $row->getEmployee?->full_name ?? 'N/A',
            $row->getEmployee?->applicant_id ?? 'N/A',
            $row->getEmployee?->system_id ?? 'N/A',
            $row->salary ?? '0.00',
            $row->overtime_amount ?? '0.00',
            $row->bonus_amount ?? '0.00',
            $row->deduction_amount ?? '0.00',
            number_format($netPayable, 2, '.', ''),
        ];
    }
}
