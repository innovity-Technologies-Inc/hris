<?php

namespace App\Imports;

use App\Models\RosterPlan;
use App\Models\ShiftPlan;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class RosterPlansImport implements ToCollection
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

            RosterPlan::create([
                'name' => $row[0],
                'short_name' => $row[1],
                'swapping' => $row[2],
                'description' => $row[2],
                'status' => strtolower($row[3] ?? 'active'),
                'first_shift_id' => $this->getId(ShiftPlan::class, $row[4]),
                'second_shift_id' => $this->getId(ShiftPlan::class, $row[5]),
            ]);
        });
    }

    /**
     * Parse and safely format time columns
     */

    private function getId($model, $name)
    {
        if (empty($name)) return null;
        $name = trim(strtolower($name));
        $record = $model::whereRaw('LOWER(name) LIKE ?', ["%{$name}%"])->first();
        return $record ? $record->id : null;
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
