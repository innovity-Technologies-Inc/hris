<?php

namespace App\Imports\Employee;

use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeEducationExperienceTraining;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class EmployeeEducationInfoImport implements ToCollection, WithHeadingRow
{
    protected $skipped = 0;
    protected $inserted = 0;

    public function collection(Collection $rows)
    {
        $groupedData = [];

        foreach ($rows as $index => $row) {
            // Get employee id safely with multiple key support
            $employeeId = $this->getEmployeeId(
                Employee::class,
                $row['applicant_id'] ?? $row['employee_id'] ?? $row['employee_name'] ?? null
            );

            // Skip if no employee found
            if (!$employeeId) {
                $this->skipped++;
                Log::warning("Skipped Row #{$index} - Missing/Invalid Employee ID", $row->toArray());
                continue;
            }

            if (!isset($groupedData[$employeeId])) {
                $groupedData[$employeeId] = [
                    'employee_id' => $employeeId,
                    'educations' => [],
                    'trainings' => [],
                ];
            }

            // --- Education ---
            $education = [
                'education_title' => $row['education_title'] ?? null,
                'institute' => $row['institute'] ?? null,
                'group_major' => $row['group_major'] ?? null,
                'board_university' => $row['board_university'] ?? null,
                'result_grade' => $row['result_grade'] ?? null,
                'passing_year' => $row['passing_year'] ?? null,
                'gpa_cgpa' => $row['gpa_cgpa'] ?? null,
            ];

            // --- Training ---
            $training = [
                'training_title' => $row['training_title'] ?? null,
                'course_name' => $row['course_name'] ?? null,
                'training_code' => $row['training_code'] ?? null,
                'institute' => $row['institute_1'] ?? $row['institute'] ?? null,
                'country' => $row['country'] ?? null,
                'location' => $row['location'] ?? null,
                'duration' => $row['duration_1'] ?? $row['duration'] ?? null,
                'from_date' => $this->parseDate($row['from_date'] ?? null),
                'to_date' => $this->parseDate($row['to_date'] ?? null),
            ];

            // Push into group
            $groupedData[$employeeId]['educations'][] = $education;
            $groupedData[$employeeId]['trainings'][] = $training;
        }

        // --- Save or update in DB ---
        foreach ($groupedData as $data) {
            EmployeeEducationExperienceTraining::updateOrCreate(
                ['employee_id' => $data['employee_id']],
                [
                    'educations' => $data['educations'],
                    'trainings' => $data['trainings'],
                    'status' => 'active',
                ]
            );
            $this->inserted++;
        }

        // Log summary
        Log::info("Import Summary: Inserted {$this->inserted}, Skipped {$this->skipped}");
    }

    private function parseDate($date)
    {
        if (empty($date)) return null;
        try {
            return \Carbon\Carbon::parse($date)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    private function getEmployeeId($model, $employee_id)
    {
        if (empty($employee_id)) return null;
        $record = $model::where('applicant_id', $employee_id)->first();
        return $record ? $record->id : null;
    }
}

