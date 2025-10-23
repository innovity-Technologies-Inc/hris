<?php

namespace App\Imports;

use App\HelperClass;
use App\Models\Employee;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class EmployeeGeneralInformationImport implements ToCollection
{
    public function collection(Collection $collection)
    {
        // Skip the first row (header)
        $collection->skip(1)->each(function ($row, $index) {

            // Convert all values to string to avoid numeric loss
            $row = $row->map(fn($cell) => $this->toString($cell));

            // Skip empty rows
            if (empty($row[0]) && empty($row[1]) && empty($row[3])) {
                return;
            }

            Employee::create([
                // System Identifiers
                'applicant_id' => $row[0] ?? null,
                'system_id' => $row[1] ?? null,
                'punch_card_no' => $row[2] ?? null,

                // Personal Information
                'first_name' => $row[3] ?? null,
                'last_name' => $row[4] ?? null,
                'middle_name' => $row[5] ?? null,
                'full_name' => $row[6] ?? null,
                'father_name' => $row[7] ?? null,
                'mother_name' => $row[8] ?? null,
                'spouse_name' => $row[9] ?? null,
                'marital_status' => $row[10] ?? null,
                'gender' => $row[11] ?? null,
                'religion' => $row[12] ?? null,
                'nationality' => $row[13] ?? null,
                'height_feet' => $row[14] ?? null,
                'height_inches' => $row[15] ?? null,
                'children_count' => $row[16] ?? null,
                'present_address' => [
                    'line_1' => $row[17] ?? null,
                    'village' => $row[18] ?? null,
                    'post_office' => $row[19] ?? null,
                    'zip_code' => $row[20] ?? null,
                    'district' => $row[21] ?? null,
                    'division' => $row[22] ?? null,
                    'state' => $row[23] ?? null,
                    'country' => $row[24] ?? null,
                ],

                'permanent_address' => [
                    'line_1' => $row[25] ?? null,
                    'village' => $row[26] ?? null,
                    'post_office' => $row[27] ?? null,
                    'zip_code' => $row[28] ?? null,
                    'district' => $row[29] ?? null,
                    'division' => $row[30] ?? null,
                    'state' => $row[31] ?? null,
                    'country' => $row[32] ?? null,
                ],

                'reference_address' => [
                    'emp_id' => $row[33] ?? null,
                    'reference_name' => $row[34] ?? null,
                    'reference_designation' => $row[35] ?? null,
                    'email' => $row[36] ?? null,
                    'phone' => $row[37] ?? null,
                    'mobile' => $row[38] ?? null,
                    'line_1' => $row[39] ?? null,
                    'village' => $row[40] ?? null,
                    'post_office' => $row[41] ?? null,
                    'zip_code' => $row[42] ?? null,
                    'district' => $row[43] ?? null,
                    'division' => $row[44] ?? null,
                    'state' => $row[45] ?? null,
                    'country' => $row[46] ?? null,
                ],

                // Document Information
                'tin' => $row[47] ?? null,
                'passport_no' => $row[48] ?? null,
                'passport_expiry' => $this->parseDate($row[49] ?? null),
                'license_no' => $row[50] ?? null,
                'license_expiry' => $this->parseDate($row[51] ?? null),
                'visa_expiry' => $this->parseDate($row[52] ?? null),
                'work_expiry' => $this->parseDate($row[53] ?? null),
                'residency_id_number' => $row[54] ?? null,

                // Birth Information

                'date_of_birth' => $this->parseDate($row[55] ?? null),
                'birth_country' => $row[56] ?? null,
                'birth_reg_no' => $row[57] ?? null,

                // Contact Information
                'personal_mobile' => $row[58] ?? null,
                'home_phone' => $row[59] ?? null,
                'work_mobile' => $row[60] ?? null,
                'work_phone' => $row[61] ?? null,
                'work_email' => $row[62] ?? null,
                'personal_email' => $row[63] ?? null,
            ]);
        });
    }

    private function parseDate($value)
    {
        if (empty($value)) return null;

        try {
            // Check if numeric Excel serial date (e.g., 32978)
            if (is_numeric($value)) {
                return Carbon::instance(Date::excelToDateTimeObject($value))->format('Y-m-d');
            }

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
            // If it's an integer-like number, keep it as string
            if (floor($value) == $value) {
                return (string)$value;
            }
            // Convert float to precise string
            return number_format($value, 0, '', '');
        }

        return trim((string)$value);
    }


}
