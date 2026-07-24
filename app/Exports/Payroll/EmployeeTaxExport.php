<?php

namespace App\Exports\Payroll;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class EmployeeTaxExport implements FromCollection, WithHeadings, WithMapping
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
            'Annual Gross Salary',
            'Exemption Amount',
            'Taxable Income',
            'Total Calculated Tax',
            'Tax Payable (Annual)',
            'Tax Per Month',
            'Slabs Reached',
            'Calculation Date',
        ];
    }

    public function map($row): array
    {
        static $index = 0;
        $index++;

        return [
            $index,
            $row->employee?->full_name ?? 'N/A',
            $row->employee?->applicant_id ?? $row->employee?->system_id ?? 'N/A',
            number_format($row->gross_salary, 2, '.', ''),
            number_format($row->exemption_amount, 2, '.', ''),
            number_format($row->taxable_amount, 2, '.', ''),
            number_format($row->total_tax_amount, 2, '.', ''),
            number_format($row->tax_payable, 2, '.', ''),
            number_format($row->tax_per_month, 2, '.', ''),
            $row->slabs_reached,
            $row->updated_at ? Carbon::parse($row->updated_at)->format('d M Y, h:i A') : '—',
        ];
    }
}
