<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\EmployeeNominee;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class EmployeeNomineeImport implements ToCollection
{
    public function collection(Collection $collection)
    {
        // Skip the first row (header)
        $collection->skip(1)->each(function ($row, $index) {

            // Convert all values to string to avoid numeric loss
            $row = $row->map(fn($cell) => $this->toString($cell));

            // Skip empty rows
            if (empty($row[0]) && empty($row[1])) {
                return;
            }

            EmployeeNominee::create([
                // Foreign Key
                'employee_id' => $this->getEmployeeId(Employee::class, $row[0]),

                // Personal Details
                'nominee_name' => $row[1] ?? null,
                'father_name' => $row[2] ?? null,
                'mother_name' => $row[3] ?? null,
                'spouse_name' => $row[4] ?? null,
                'gender' => $row[5] ?? null,
                'date_of_birth' => $this->parseDate($row[6] ?? null),
                'religion' => $row[7] ?? null,
                'marital_status' => $row[8] ?? null,
                'nationality' => $row[9] ?? null,
                'blood_group' => $row[10] ?? null,
                'photo_path' => $row[11] ?? null,

                // Identification
                'nid' => $row[12] ?? null,
                'birth_reg_no' => $row[13] ?? null,

                // Financial
                'bank_account_no' => $row[14] ?? null,
                'ratio' => $row[15] ?? null,

                // Contact & Address
                'phone' => $row[16] ?? null,
                'mobile' => $row[17] ?? null,
                'present_address_line' => $row[18] ?? null,
                'village' => $row[19] ?? null,
                'post_office' => $row[20] ?? null,
                'thana' => $row[21] ?? null,
                'district' => $row[22] ?? null,
                'state' => $row[23] ?? null,
                'zip_code' => $row[24] ?? null,
                'country' => $row[25] ?? null,
            ]);
        });
    }

    //Match Full Value
    private function getEmployeeId($model, $employee_id)
    {
        if (empty($employee_id)) return null;
        $record = $model::where('applicant_id', $employee_id)->first();
        return $record ? $record->id : null;
    }


    private function parseDate($value)
    {
        if (empty($value)) return null;

        try {
            // If it's a numeric Excel serial date
            if (is_numeric($value)) {
                return Carbon::instance(Date::excelToDateTimeObject($value))->format('Y-m-d');
            }

            // Otherwise try parsing normally
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    private function toString($value)
    {
        if (is_null($value)) return null;

        // If numeric (e.g., scientific), cast to string without losing zeros
        if (is_numeric($value)) {
            // If integer-like, keep it as string
            if (floor($value) == $value) {
                return (string)$value;
            }
            // Convert float to precise string
            return number_format($value, 0, '', '');
        }

        return trim((string)$value);
    }
}
