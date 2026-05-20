<?php

namespace App\Imports\Plan;

use App\Http\Controllers\Plan\MealPlansController;
use App\Models\Plan\MealPlan;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class MealPlansImport implements ToCollection
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

            MealPlan::create([
                'name'           => $row[0],
                'type'          => $row[1] ?? null,
                'description'       => $row[2] ?? null,
                'start_time'   => $this->parseTime($row[3] ?? null),
                'end_time'        => $this->parseTime($row[4] ?? null),
                'cost'     => $row[5] ?? null,
                'status'        => $row[6] ?? null,
            ]);
        });
    }

    private function parseTime($value)
    {
        try {
            return $value ? Carbon::parse($value)->format('H:i:s') : null;
        } catch (\Exception $e) {
            return null;
        }
    }


    private function toString($value)
    {
        if (is_null($value)) return null;

        if (is_numeric($value)) {
            if (floor($value) == $value) {
                return (string) $value;
            }
            return number_format($value, 0, '', '');
        }

        return trim((string) $value);
    }

}

