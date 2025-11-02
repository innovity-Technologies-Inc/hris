<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\EmployeeEligiblePlan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class EmployeeEligiblePlanImport implements ToCollection
{
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

            EmployeeEligiblePlan::create([
                'employee_id' => $this->getEmployeeId(Employee::class, $row[0]),

// Shift Plan
                'shift_plan_status' => $row[1] ?? null,
                'shift_plan_from' => $this->parseDate($row[2] ?? null),
                'shift_plan_to' => $this->parseDate($row[3] ?? null),

// Leave Plan
                'leave_plan_status' => $row[4] ?? null,
                'leave_plan_from' => $this->parseDate($row[5] ?? null),
                'leave_plan_to' => $this->parseDate($row[6] ?? null),

// OT Plan
                'ot_plan_status' => $row[7] ?? null,
                'ot_plan_from' => $this->parseDate($row[8] ?? null),
                'ot_plan_to' => $this->parseDate($row[9] ?? null),

// Attendance Bonus Plan
                'attendance_bonus_plan_status' => $row[10] ?? null,
                'attendance_bonus_plan_from' => $this->parseDate($row[11] ?? null),
                'attendance_bonus_plan_to' => $this->parseDate($row[12] ?? null),

// Day Off Work Plan
                'day_off_work_plan_status' => $row[13] ?? null,
                'day_off_work_plan_from' => $this->parseDate($row[14] ?? null),
                'day_off_work_plan_to' => $this->parseDate($row[15] ?? null),

// Roster Plans
                'roster_plans_status' => $row[16] ?? null,
                'roster_plans_from' => $this->parseDate($row[17] ?? null),
                'roster_plans_to' => $this->parseDate($row[18] ?? null),

// Bonus Plan
                'bonus_plan_status' => $row[19] ?? null,
                'bonus_plan_from' => $this->parseDate($row[20] ?? null),
                'bonus_plan_to' => $this->parseDate($row[21] ?? null),

// Allowance Plan
                'allowance_plan_status' => $row[22] ?? null,
                'allowance_plan_from' => $this->parseDate($row[23] ?? null),
                'allowance_plan_to' => $this->parseDate($row[24] ?? null),

// Late Deduction Plan
                'late_deduction_plan_status' => $row[25] ?? null,
                'late_deduction_plan_from' => $this->parseDate($row[26] ?? null),
                'late_deduction_plan_to' => $this->parseDate($row[27] ?? null),

// Production Plan
                'production_plan_status' => $row[28] ?? null,
                'production_plan_from' => $this->parseDate($row[29] ?? null),
                'production_plan_to' => $this->parseDate($row[30] ?? null),

// Early Out Deduction Plan
                'early_out_deduction_plan_status' => $row[31] ?? null,
                'early_out_deduction_plan_from' => $this->parseDate($row[32] ?? null),
                'early_out_deduction_plan_to' => $this->parseDate($row[33] ?? null),

// Salary Breakdown Plan
                'salary_breakdown_plan_status' => $row[34] ?? null,
                'salary_breakdown_plan_from' => $this->parseDate($row[35] ?? null),
                'salary_breakdown_plan_to' => $this->parseDate($row[36] ?? null),

// Medical Plan
                'medical_plan_status' => $row[37] ?? null,
                'medical_plan_from' => $this->parseDate($row[38] ?? null),
                'medical_plan_to' => $this->parseDate($row[39] ?? null),

// Night Bill Plan
                'night_bill_plan_status' => $row[40] ?? null,
                'night_bill_plan_from' => $this->parseDate($row[41] ?? null),
                'night_bill_plan_to' => $this->parseDate($row[42] ?? null),

// Tiffin Plan
                'tiffin_plan_status' => $row[43] ?? null,
                'tiffin_plan_from' => $this->parseDate($row[44] ?? null),
                'tiffin_plan_to' => $this->parseDate($row[45] ?? null),

// Dinner Plan
                'dinner_plan_status' => $row[46] ?? null,
                'dinner_plan_from' => $this->parseDate($row[47] ?? null),
                'dinner_plan_to' => $this->parseDate($row[48] ?? null),

// Breakfast Plan
                'breakfast_plan_status' => $row[49] ?? null,
                'breakfast_plan_from' => $this->parseDate($row[50] ?? null),
                'breakfast_plan_to' => $this->parseDate($row[51] ?? null),

// Food Com Plan
                'food_com_plan_status' => $row[52] ?? null,
                'food_com_plan_from' => $this->parseDate($row[53] ?? null),
                'food_com_plan_to' => $this->parseDate($row[54] ?? null),

// Excessive Late Plan
                'excessive_late_plan_status' => $row[55] ?? null,
                'excessive_late_plan_from' => $this->parseDate($row[56] ?? null),
                'excessive_late_plan_to' => $this->parseDate($row[57] ?? null),

// Lunch Plan
                'lunch_plan_status' => $row[58] ?? null,
                'lunch_plan_from' => $this->parseDate($row[59] ?? null),
                'lunch_plan_to' => $this->parseDate($row[60] ?? null),

// Snacks Plan
                'snacks_plan_status' => $row[61] ?? null,
                'snacks_plan_from' => $this->parseDate($row[62] ?? null),
                'snacks_plan_to' => $this->parseDate($row[63] ?? null),

            ]);
        });
    }

    private function getEmployeeId($model, $employee_id)
    {
        if (empty($employee_id)) return null;
        $record = $model::where('applicant_id', $employee_id)->first();
        return $record ? $record->id : null;
    }

    private function parseDate($value)
    {
        if (empty($value)) return null;

        try {
            if (is_numeric($value)) {
                return Carbon::instance(Date::excelToDateTimeObject($value))->format('Y-m-d');
            }

            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

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
