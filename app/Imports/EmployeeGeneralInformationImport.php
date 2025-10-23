<?php

namespace App\Imports;

use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmployeeGeneralInformationImport implements ToCollection, withHeadingRow
{

    public function collection(Collection $collection)
    {
        $collection->skip(1)->each(function ($row) {

            Employee::create([
                // System Identifiers
                'applicant_id' => $row[0],
                'system_id' => $row[1],
                'punch_card_no' => $row[2],

                // Personal Information
                'first_name' => $row[3],
                'last_name' => $row[4],
                'middle_name' => $row[5],
                'full_name' => $row[6],
                'father_name' => $row[7],
                'mother_name' => $row[8],
                'spouse_name' => $row[9],
                'marital_status' => $row[10],
                'gender' => $row[11],
                'religion' => $row[12],
                'nationality' => $row[13],
                'height_feet' => $row[14],
                'height_inches' => $row[15],
                'children_count' => $row[16],

                // Address Fields (assumed JSON)
                'present_address' => json_encode([
                    'line_1' => $row[17] ?? null,
                    'village' => $row[18] ?? null,
                    'post_office' => $row[19] ?? null,
                    'zip_code' => $row[20] ?? null,
                    'district' => $row[21] ?? null,
                    'division' => $row[22] ?? null,
                    'state' => $row[23] ?? null,
                    'country' => $row[24] ?? null,
                ]),
                'permanent_address' => json_encode([
                    'line_1' => $row[25] ?? null,
                    'village' => $row[26] ?? null,
                    'post_office' => $row[27] ?? null,
                    'zip_code' => $row[28] ?? null,
                    'district' => $row[29] ?? null,
                    'division' => $row[30] ?? null,
                    'state' => $row[31] ?? null,
                    'country' => $row[32] ?? null,
                ]),
                'reference_address' => json_encode([
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
                ]),

                // Document Information
                'tin' => $row[47],
                'passport_no' => $row[48],
                'passport_expiry' => $this->parseDate($row[49]),
                'license_no' => $row[50],
                'license_expiry' => $this->parseDate($row[51]),
                'visa_expiry' => $this->parseDate($row[52]),
                'work_expiry' => $this->parseDate($row[53]),
                'residency_id_number' => $row[54],

                // Birth Information
                'date_of_birth' => $this->parseDate($row[55]),
                'birth_country' => $row[56],
                'birth_reg_no' => $row[57],

                // Contact Information
                'personal_mobile' => $row[58],
                'home_phone' => $row[59],
                'work_mobile' => $row[60],
                'work_phone' => $row[61],
                'work_email' => $row[62],
                'personal_email' => $row[63],
            ]);
        });
    }

        private function parseDate($value)
    {
        // Try to convert Excel date or string date safely
        try {
            return Carbon::parse(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value))->format('Y-m-d');
        } catch (\Exception $e) {
            try {
                return Carbon::parse($value)->format('Y-m-d');
            } catch (\Exception $e) {
                return null;
            }
        }
    }
}
