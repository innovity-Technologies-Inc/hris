<?php

namespace App\Imports\Employee;

use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeSalaryBreakdown;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class SalaryBreakdownImport implements ToCollection
{
    public function collection(Collection $collection)
    {
        // Skip the first row (header)
        $collection->skip(1)->each(function ($row, $index) {
            // Convert all values to string to avoid numeric loss
            $row = $row->map(fn($cell) => $this->toString($cell));

            // Skip completely empty rows
            if ($row->filter()->isEmpty()) {
                return;
            }

            EmployeeSalaryBreakdown::create([
                'employee_id'           => $this->getEmployeeId(Employee::class, $row[0]),
                'pay_scale_id'          => $this->getPayScaleId($row[1]),
                'basic_salary'          => $row[2] ?? null,
                'house_allowance'       => $row[3] ?? null,
                'transport_allowance'   => $row[4] ?? null,
                'food_allowance'        => $row[5] ?? null,
                'medical_allowance'     => $row[6] ?? null,
                'other_earnings'        => $row[7] ?? null,
                'gross_salary'          => $row[8] ?? null,
            ]);
        });
    }

    private function getPayScaleId($title)
    {
        if (empty($title)) return null;
        $title = trim(strtolower($title));
        $record = \App\Models\Company\PayScale::whereRaw('LOWER(title) LIKE ?', ["%{$title}%"])->first();
        return $record ? $record->id : null;
    }

    private function getEmployeeId($model, $employee_id)
    {
        if (empty($employee_id)) return null;
        $record = $model::where('applicant_id', $employee_id)->first();
        return $record ? $record->id : null;
    }

    private function toString($value)
    {
        if (is_null($value)) return null;

        if (is_numeric($value)) {
            if (floor($value) == $value) {
                return (string) $value;
            }
            return number_format($value, 0, '', '');
        }

        return trim((string) $value);
    }
}

