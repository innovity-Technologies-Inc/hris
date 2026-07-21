<?php

namespace App\Exports\Employee;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EmployeeSearchExport implements FromCollection, WithHeadings, WithMapping
{
    protected $employees;

    public function __construct($employees)
    {
        $this->employees = $employees;
    }

    public function collection()
    {
        return $this->employees;
    }

    public function headings(): array
    {
        return [
            'System ID',
            'Employee ID',
            'Employee Name',
            'Email',
            'Mobile',
            'Gender',
            'Company',
            'Branch',
            'Division',
            'Department',
            'Section',
            'Employee Type',
        ];
    }

    public function map($row): array
    {
        $officeInfo = $row->officeInfo;
        return [
            $row->system_id ?? '',
            $row->applicant_id ?? '',
            $row->full_name ?? '',
            $row->work_email ?? '',
            $row->personal_mobile ?? '',
            $row->gender ?? '',
            $officeInfo?->getCurrentCompany?->name ?? 'N/A',
            $officeInfo?->getCurrentBusinessUnit?->name ?? 'N/A',
            $officeInfo?->getCurrentDivision?->division_name ?? 'N/A',
            $officeInfo?->getCurrentDepartment?->department_name ?? 'N/A',
            $officeInfo?->getCurrentSection?->section_name ?? 'N/A',
            ucfirst($officeInfo?->emp_type ?? 'N/A'),
        ];
    }
}
