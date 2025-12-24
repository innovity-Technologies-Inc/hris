<?php

namespace App\Imports;

use App\Models\Attendance;
use App\Models\ShiftPlan;
use App\Services\AttendanceServices;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;


class AttendanceImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    protected AttendanceServices $attendanceServices;
    public function __construct(AttendanceServices $attendanceServices)
    {
        $this->attendanceServices = $attendanceServices;
    }

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

            $items = [
                'employee_id' => $row[0],
                'clock_in' => $this->parseDateTime($row[1]),
                'clock_out' => $this->parseDateTime($row[2]),
                'workstation' => $row[3] ?? null,
            ];

            $this->attendanceServices->singleAttendanceStore($items);
        });

    }

    private function parseDateTime($value)
    {
        if (empty($value)) {
            return null;
        }

        try {
            // Try to parse the datetime using Carbon
            // This handles multiple formats like:
            // - 2024-12-23 09:00:00
            // - 12/23/2025 9:00
            // - 23-12-2025 09:00
            $date = Carbon::parse($value);
            return $date->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            // If parsing fails, return the original value
            return $value;
        }
    }

    private function toString($cell)
    {
        if ($cell === null) {
            return '';
        }
        return (string) $cell;
    }

}




