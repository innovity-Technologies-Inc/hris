<?php

namespace App\Imports\Plan;

use App\Models\Plan\BonusPlan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Carbon\Carbon;

class BonusPlansImport implements ToCollection
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

        // Skip empty rows
        if ($row->filter()->isEmpty()) {
            return;
        }

        BonusPlan::create([
            // Basic Information
            'name'                       => $row[0],
            'description'                => $row[1] ?? null,

            // Bonus Type (enum: festival, performance, annual, incentive, retention, other)
            'bonus_type'                 => $this->validateBonusType($row[2] ?? 'festival'),

            // Configuration Type (enum: 'Salary Based', 'Custom')
            'bonus_config_type'          => $this->validateConfigType($row[3] ?? 'Salary Based'),

            // Salary Rate Type (enum: 'Basic Rate', 'Multiplier')
            'salary_rate_type'           => $this->validateSalaryRateType($row[4] ?? 'Basic Rate'),

            // Rate Values
            'overtime_multiplier'        => $this->toDecimal($row[5] ?? null),
            'custom_overtime_rate'       => $this->toDecimal($row[6] ?? null),

            // Status (enum: active, inactive)
            'status'                     => $this->validateStatus($row[7] ?? 'active'),
        ]);
    });
}

/**
 * Validate and normalize bonus type enum
 *
 * @param string|null $value
 * @return string
 */
private function validateBonusType($value)
{
    $validTypes = ['festival', 'performance', 'annual', 'incentive', 'retention', 'other'];
    $normalized = strtolower(trim($value));

    return in_array($normalized, $validTypes) ? $normalized : 'festival';
}

/**
 * Validate and normalize configuration type enum
 * Matches exact casing from migration: 'Salary Based', 'Custom'
 *
 * @param string|null $value
 * @return string
 */
private function validateConfigType($value)
{
    $normalized = strtolower(trim($value));

    // Handle common variations
    $mapping = [
        'salary based'  => 'Salary Based',
        'salary_based'  => 'Salary Based',
        'salarybased'   => 'Salary Based',
        'custom'        => 'Custom',
    ];

    return $mapping[$normalized] ?? 'Salary Based';
}

/**
 * Validate and normalize salary rate type enum
 * Matches exact casing from migration: 'Basic Rate', 'Multiplier'
 *
 * @param string|null $value
 * @return string|null
 */
private function validateSalaryRateType($value)
{
    if (is_null($value) || trim($value) === '') {
        return null;
    }

    $normalized = strtolower(trim($value));

    // Handle common variations
    $mapping = [
        'basic rate'    => 'Basic Rate',
        'basic_rate'    => 'Basic Rate',
        'basicrate'     => 'Basic Rate',
        'multiplier'    => 'Multiplier',
    ];

    return $mapping[$normalized] ?? 'Basic Rate';
}

/**
 * Validate and normalize status enum
 *
 * @param string|null $value
 * @return string
 */
private function validateStatus($value)
{
    $normalized = strtolower(trim($value));

    return in_array($normalized, ['active', 'inactive']) ? $normalized : 'active';
}

/**
 * Convert numeric to decimal string with precision
 *
 * @param mixed $value
 * @return string|null
 */
private function toDecimal($value)
{
    if (is_null($value) || trim($value) === '') {
        return null;
    }

    // Remove any currency symbols or commas
    $cleaned = preg_replace('/[^\d.-]/', '', $value);

    if (is_numeric($cleaned)) {
        return number_format((float)$cleaned, 2, '.', '');
    }

    return null;
}

/**
 * Convert all cell values to strings to prevent numeric loss
 * Preserves leading zeros and large numbers
 *
 * @param mixed $value
 * @return string|null
 */
private function toString($value)
{
    if (is_null($value)) {
        return null;
    }

    if (is_numeric($value)) {
        // Preserve integers without decimals
        if (floor($value) == $value) {
            return (string) $value;
        }
        return number_format($value, 2, '.', '');
    }

    return trim((string)$value);
}

}

