<?php

namespace App\Imports\Plan;

use App\Models\Plan\LeavePlan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class LeavePlansImport implements ToCollection
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

            LeavePlan::create([
                'name'                    => $row[0],
                'short_name'              => $row[1],

                'applicable_gender'       => $row[2] ?? 'Both',
                'leave_type'              => $row[4] ?? 'Casual Leave',

                'leave_limit'             => $this->toInt($row[5] ?? 0),
                'max_no_of_days'          => $this->toInt($row[6] ?? 0),
                'display_serial'          => $this->toInt($row[7] ?? 0),

                'apply_limit'             => $this->toInt($row[8] ?? 0),
                'allow_fractional_leave'  => strtolower($row[9] ?? 'inactive'),

                'off_day_include'         => $this->toInt($row[10] ?? 0),

                'active_ind'              => strtolower($row[11] ?? 'active'),
            ]);
        });
    }

    /**
     * Convert numeric values to integer
     */
    private function toInt($value)
    {
        if (is_null($value)) return null;

        return (int) floatval($value);
    }

    /**
     * Convert any cell value to safe string
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

