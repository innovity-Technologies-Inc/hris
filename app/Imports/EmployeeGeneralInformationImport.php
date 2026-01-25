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
                'blood_group' => $row[14] ?? null,
                'height_feet' => $row[15] ?? null,
                'height_inches' => $row[16] ?? null,
                'children_count' => $row[17] ?? null,
                'present_address' => [
                    'line_1' => $row[18] ?? null,
                    'village' => $row[19] ?? null,
                    'post_office' => $row[20] ?? null,
                    'zip_code' => $row[21] ?? null,
                    'district' => $row[22] ?? null,
                    'division' => $row[23] ?? null,
                    'state' => $row[24] ?? null,
                    'country' => $row[25] ?? null,
                ],

                'permanent_address' => [
                    'line_1' => $row[26] ?? null,
                    'village' => $row[27] ?? null,
                    'post_office' => $row[28] ?? null,
                    'zip_code' => $row[29] ?? null,
                    'district' => $row[30] ?? null,
                    'division' => $row[31] ?? null,
                    'state' => $row[32] ?? null,
                    'country' => $row[33] ?? null,
                ],

                'reference_address' => [
                    'emp_id' => $row[34] ?? null,
                    'reference_name' => $row[35] ?? null,
                    'reference_designation' => $row[36] ?? null,
                    'email' => $row[37] ?? null,
                    'phone' => $row[38] ?? null,
                    'mobile' => $row[39] ?? null,
                    'line_1' => $row[40] ?? null,
                    'village' => $row[41] ?? null,
                    'post_office' => $row[42] ?? null,
                    'zip_code' => $row[43] ?? null,
                    'district' => $row[44] ?? null,
                    'division' => $row[45] ?? null,
                    'state' => $row[46] ?? null,
                    'country' => $row[47] ?? null,
                ],

                // Document Information
                'tin' => $row[48] ?? null,
                'passport_no' => $row[49] ?? null,
                'passport_expiry' => $this->parseDate($row[50] ?? null),
                'license_no' => $row[51] ?? null,
                'license_expiry' => $this->parseDate($row[52] ?? null),
                'visa_expiry' => $this->parseDate($row[53] ?? null),
                'work_expiry' => $this->parseDate($row[54] ?? null),
                'residency_id_number' => $row[55] ?? null,

                // Birth Information

                'date_of_birth' => $this->parseDate($row[56] ?? null),
                'birth_country' => $row[57] ?? null,
                'birth_reg_no' => $row[58] ?? null,

                // Contact Information
                'personal_mobile' => $row[59] ?? null,
                'home_phone' => $row[60] ?? null,
                'work_mobile' => $row[61] ?? null,
                'work_phone' => $row[62] ?? null,
                'work_email' => $row[63] ?? null,
                'personal_email' => $row[64] ?? null,
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
