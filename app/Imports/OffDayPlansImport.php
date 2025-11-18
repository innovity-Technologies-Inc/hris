<?php

namespace App\Imports;

use App\Models\OffDayPlan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Carbon\Carbon;

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

            OffDayPlan::create([
                'name' => $row[0],
                'short_name' => $row[1] ?? null,
                'start_time' => $this->parseTime($row[2] ?? null),
                'end_time' => $this->parseTime($row[3] ?? null),
                'grace_time' => $row[4],
                'grace_time_before' => $row[5] ?? null,
                'remuneration' => $this->toDecimal($row[6] ?? null),
                'active_ind' => strtolower($row[7] ?? 'active'),
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
                return (string)$value;
            }
            return number_format($value, 2, '.', '');
        }

        return trim((string)$value);
    }
}
