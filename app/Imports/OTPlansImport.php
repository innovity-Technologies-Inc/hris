<?php

namespace App\Imports;

use App\Models\OTPlan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Carbon\Carbon;

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

                // OT Type
                'ot_type'                    => strtolower($row[2] ?? 'regular'),

                // Configurations
                'ot_config_type'             => strtolower($row[3] ?? 'salary_based'),
                'salary_rate_type'           => strtolower($row[4] ?? 'basic_rate'),
                'overtime_multiplier'        => $this->toDecimal($row[5] ?? null),
                'custom_overtime_rate'       => $this->toDecimal($row[6] ?? null),

                // Hours
                'minimum_overtime_hours'     => $this->toDecimal($row[7] ?? 0.00),
                'maximum_overtime_hours'     => $this->toDecimal($row[8] ?? null),

                // Time Range
                'overtime_start_time'        => $this->parseTime($row[9] ?? null),
                'overtime_end_time'          => $this->parseTime($row[10] ?? null),

                // Status
                'active_ind'                 => strtolower($row[11] ?? 'active'),
            ]);
        });
    }

    /**
     * Parse time safely
     */
    private function parseTime($value)
    {
        try {
            return $value ? Carbon::parse($value)->format('H:i:s') : null;
        } catch (\Exception $e) {
            return null;
        }
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
