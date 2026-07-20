<?php

namespace App\Imports\Plan;

use App\Models\Plan\OffDayPlan;
use App\Models\Plan\ShiftPlan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class OffDayPlansImport implements ToCollection
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

            // Resolve shift_id from shift name or ID
            $shiftId = $this->resolveShiftId($row[2] ?? null);

            // Skip row if shift not found and shift reference was provided
            if (($row[2] ?? null) && !$shiftId) {
                \Log::warning("OffDayPlansImport: Shift not found for row " . ($index + 2) . ": " . ($row[2] ?? 'empty'));
                return;
            }

            OffDayPlan::create([
                'name'                   => $row[0],
                'short_name'             => $row[1] ?? null,
                'type'                   => (isset($row[8]) && strtolower($row[8]) === 'comp-off') ? 'comp-off' : 'Paid',
                'shift_id'               => $shiftId,

                // Configuration fields (refactored to match OT Plan)
                'offday_config_type'     => $row[3] ?? 'Custom',
                'salary_rate_type'       => $row[4] ?? null,
                'offday_multiplier'      => $this->toDecimal($row[5] ?? null),
                'custom_offday_rate'     => $this->toDecimal($row[6] ?? null),

                'status'                 => strtolower($row[7] ?? 'active'),
            ]);
        });
    }

    /**
     * Resolve shift ID from name or ID
     * Supports both numeric IDs and shift names for flexibility
     */
    private function resolveShiftId($value)
    {
        if (is_null($value) || $value === '') {
            return null;
        }

        // If numeric, treat as direct ID
        if (is_numeric($value)) {
            $shift = ShiftPlan::find((int) $value);
            return $shift ? $shift->id : null;
        }

        // Otherwise, search by name
        $shift = ShiftPlan::where('name', $value)->first();
        return $shift ? $shift->id : null;
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
                return (string)$value;
            }
            return number_format($value, 2, '.', '');
        }

        return trim((string)$value);
    }
}

