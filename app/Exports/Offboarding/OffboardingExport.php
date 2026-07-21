<?php

namespace App\Exports\Offboarding;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OffboardingExport implements FromCollection, WithHeadings, WithMapping
{
    protected $offboardings;
    protected $type;

    public function __construct($offboardings, string $type)
    {
        $this->offboardings = $offboardings;
        $this->type = $type;
    }

    public function collection()
    {
        return $this->offboardings;
    }

    public function headings(): array
    {
        return [
            'System ID',
            'Employee ID',
            'Employee Name',
            'Company',
            'Branch',
            'Department',
            $this->type === 'resignation' ? 'Resignation Date' : 'Termination Date',
            'Reason',
            'Status',
        ];
    }

    public function map($row): array
    {
        $officeInfo = $row->employee?->officeInfo;
        return [
            $row->employee?->system_id ?? '',
            $row->employee?->applicant_id ?? '',
            $row->employee?->full_name ?? '',
            $officeInfo?->getCurrentCompany?->name ?? 'N/A',
            $officeInfo?->getCurrentBusinessUnit?->name ?? 'N/A',
            $officeInfo?->getCurrentDepartment?->department_name ?? 'N/A',
            $row->resignation_date ?? '',
            $row->reason ?? '',
            ucfirst($row->status ?? ''),
        ];
    }
}
