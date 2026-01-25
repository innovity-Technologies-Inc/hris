<?php

namespace App\Imports;

use App\Models\OTPlan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class OTPlansImport implements ToCollection
{
    public function collection(Collection $collection)
    {
        // Skip the first row (header)
        $collection->skip(1)->each(function ($row, $index) {

            // Convert all values to string to avoid numeric loss
            $row = $row->map(fn($cell) => $this->toString($cell));

            // Skip empty rows
            if ($row->filter()->isEmpty()) {
                return;
            }

            OTPlan::create([
                'name'                       => $row[0],
                'description'                => $row[1] ?? null,

                // Configurations
                'ot_config_type'             => $row[2] ?? 'Salary Based',
                'salary_rate_type'           => $row[3] ?? 'Basic Rate',
                'overtime_multiplier'        => $this->toDecimal($row[4] ?? null),
                'custom_overtime_rate'       => $this->toDecimal($row[5] ?? null),

                // Hours
                'maximum_overtime'           => $this->toDecimal($row[6] ?? null),

                // Status
                'status'                     => strtolower($row[7] ?? 'active'),
            ]);
        });
    }

    /**
     * Convert numeric to decimal string
     */
    private function toDecimal($value)
    {
        if (is_null($value)) return null;

        if (is_numeric($value)) {
            return number_format((float)$value, 2, '.', '');
        }

        return $value;
    }

    /**
     * Convert all cell values to strings to prevent numeric loss
     */
    private function toString($value)
    {
        if (is_null($value)) return null;

        if (is_numeric($value)) {
            if (floor($value) == $value) {
                return (string) $value;
            }
            return number_format($value, 2, '.', '');
        }

        return trim((string)$value);
    }
}
