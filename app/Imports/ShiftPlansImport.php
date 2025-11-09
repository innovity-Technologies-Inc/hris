<?php

namespace App\Imports;

use App\Models\ShiftPlan;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ShiftPlansImport implements ToCollection
{
    /**
     * @param Collection $collection
     */
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

            ShiftPlan::create([
                'name' => $row[0],
                'clock_in_time' => $this->parseTime($row[1] ?? null),
                'clock_out_time' => $this->parseTime($row[2] ?? null),
                'treat_as_full_day_minutes' => $row[3] ?? null,
                'treat_as_half_day_minutes' => $row[4] ?? null,
                'grace_time' => $row[5] ?? null,
                'late_after_minutes' => $row[6] ?? null,
                'excessive_late_after_minutes' => $row[7] ?? null,
                'early_out_grace_minutes' => $row[8] ?? 5,
                'early_out_before' => $this->parseTime($row[9] ?? null),

                // Breakfast
                'breakfast_status' => strtolower($row[10] ?? 'inactive'),
                'breakfast_start_time' => $this->parseTime($row[11] ?? null),
                'breakfast_end_time' => $this->parseTime($row[12] ?? null),

                // Lunch
                'lunch_status' => strtolower($row[13] ?? 'inactive'),
                'lunch_start_time' => $this->parseTime($row[14] ?? null),
                'lunch_end_time' => $this->parseTime($row[15] ?? null),

                // Snacks
                'snacks_status' => strtolower($row[16] ?? 'inactive'),
                'snacks_start_time' => $this->parseTime($row[17] ?? null),
                'snacks_end_time' => $this->parseTime($row[18] ?? null),

                // Dinner
                'dinner_status' => strtolower($row[19] ?? 'inactive'),
                'dinner_start_time' => $this->parseTime($row[20] ?? null),
                'dinner_end_time' => $this->parseTime($row[21] ?? null),

                'active_ind' => strtolower($row[22] ?? 'active'),
            ]);
        });
    }

    /**
     * Parse and safely format time columns
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
     * Convert all values to strings to prevent numeric loss
     */
    private function toString($value)
    {
        if (is_null($value)) return null;

        if (is_numeric($value)) {
            if (floor($value) == $value) {
                return (string)$value;
            }
            return number_format($value, 0, '', '');
        }

        return trim((string)$value);
    }


}
