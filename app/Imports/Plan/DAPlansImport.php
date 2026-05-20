<?php

namespace App\Imports\Plan;

use App\Models\Plan\DAPlan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class DAPlansImport implements ToCollection
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

            DAPlan::create([
                'name'          => $row[0],
                'short_name'    => $row[1] ?? null,
                'remuneration'  => $this->toDecimal($row[2] ?? 0),
                'status'        => strtolower($row[3] ?? 'active'),
            ]);
        });
    }

    /**
     * Convert numeric values to decimal
     */
    private function toDecimal($value)
    {
        if (is_null($value)) return 0;

        return (float) $value;
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

