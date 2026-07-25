<?php

namespace App\Exports\Payroll;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class TaxDeductionExport implements FromCollection, WithHeadings, WithMapping
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
            'Salary Month',
            'Deduction Date',
            'Frequency',
            'Hours/Days Worked',
            'Annual Tax Payable',
            'Monthly Tax Rate',
            'Deducted Amount',
        ];
    }

    public function map($row): array
    {
        static $index = 0;
        $index++;

        $hoursOrDays = '—';
        if ($row->frequency === 'hourly') {
            $hoursOrDays = number_format($row->hours_worked, 2) . ' hrs';
        } elseif ($row->frequency === 'daily') {
            $hoursOrDays = number_format($row->days_worked, 1) . ' days';
        }

        return [
            $index,
            $row->employee?->full_name ?? 'N/A',
            $row->employee?->applicant_id ?? $row->employee?->system_id ?? 'N/A',
            $row->salary_month ? Carbon::parse($row->salary_month . '-01')->format('M Y') : 'N/A',
            $row->deduction_date ? Carbon::parse($row->deduction_date)->format('d M Y') : 'N/A',
            ucfirst($row->frequency),
            $hoursOrDays,
            number_format($row->annual_tax_payable, 2, '.', ''),
            number_format($row->monthly_tax_rate, 2, '.', ''),
            number_format($row->amount, 2, '.', ''),
        ];
    }
}
