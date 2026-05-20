<?php

namespace App\Imports\Plan;

use App\Models\Plan\ShiftPlan;
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

            // Validate minimum required fields
            if (empty($row[0]) || empty($row[1]) || empty($row[2])) {
                return; // Skip rows without name or times
            }

            // Parse times for calculation
            $clockInTime = $this->parseTime($row[1] ?? null);
            $clockOutTime = $this->parseTime($row[2] ?? null);

            // Skip if times are invalid
            if (!$clockInTime || !$clockOutTime) {
                return;
            }

            $graceTime = (int)($row[3] ?? 0);
            $excessiveLate = (int)($row[4] ?? 0);
            $earlyOutGrace = (int)($row[5] ?? 5);

            // Calculate treat_as_full_day_minutes and treat_as_half_day_minutes
            $shiftStart = Carbon::parse($clockInTime);
            $shiftEnd = Carbon::parse($clockOutTime);

            // If clock out is before clock in, assume next day
            if ($shiftEnd->lt($shiftStart)) {
                $shiftEnd->addDay();
            }

            // Calculate: clock_out - clock_in - (grace_time + early_out_grace_minutes)
            $treatAsFullDayMinutes = $shiftStart->diffInMinutes($shiftEnd) - ($graceTime + $earlyOutGrace);
            $treatAsHalfDayMinutes = intdiv($treatAsFullDayMinutes, 2);

            ShiftPlan::create([
                'name' => $row[0],
                'clock_in_time' => $clockInTime,
                'clock_out_time' => $clockOutTime,
                'treat_as_full_day_minutes' => $treatAsFullDayMinutes,
                'treat_as_half_day_minutes' => $treatAsHalfDayMinutes,
                'grace_time' => $graceTime,
                'excessive_late_after_minutes' => $excessiveLate,
                'early_out_grace_minutes' => $earlyOutGrace,

                // Breakfast
                'breakfast_status' => strtolower($row[6] ?? 'inactive'),
                'breakfast_start_time' => $this->parseTime($row[7] ?? null),
                'breakfast_end_time' => $this->parseTime($row[8] ?? null),

                // Lunch
                'lunch_status' => strtolower($row[9] ?? 'inactive'),
                'lunch_start_time' => $this->parseTime($row[10] ?? null),
                'lunch_end_time' => $this->parseTime($row[11] ?? null),

                // Snacks
                'snacks_status' => strtolower($row[12] ?? 'inactive'),
                'snacks_start_time' => $this->parseTime($row[13] ?? null),
                'snacks_end_time' => $this->parseTime($row[14] ?? null),

                // Dinner
                'dinner_status' => strtolower($row[15] ?? 'inactive'),
                'dinner_start_time' => $this->parseTime($row[16] ?? null),
                'dinner_end_time' => $this->parseTime($row[17] ?? null),

                'active_ind' => strtolower($row[18] ?? 'active'),
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

