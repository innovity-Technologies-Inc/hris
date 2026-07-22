<?php

namespace App\Imports\Employee;

use App\Models\Company\Company;
use App\Models\Company\CompanyLocation;
use App\Models\Company\Department;
use App\Models\Company\Designation;
use App\Models\Company\Division;
use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeOfficeInfo;
use App\Models\Company\SalaryGrade;
use App\Models\Company\Section;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\ToCollection;

class EmployeeOfficeInformationImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        // Skip header
        $collection->skip(1)->each(function ($row) {

            $row = $row->map(fn($cell) => trim((string) $cell));

            // Skip if no employee_id or emp_type
            if (empty($row[0]) && empty($row[1])) return;

            EmployeeOfficeInfo::create([
                'employee_id' => $this->getEmployeeId(Employee::class, $row[0]),
                'emp_type' => $row[1] ?? null,
                'grade_id' => $this->getId(SalaryGrade::class, $row[2]),
                'hr_file_no' => $row[3] ?? null,
                'file_note' => $row[5] ?? null,

                // Joining Info (by name lookup)
                'joining_company_id' => $this->getId(Company::class, $row[6]),
                'joining_business_unit_id' => $this->getUnitId(CompanyLocation::class, $row[7]),
                'joining_division_id' => $this->getDivisionId(Division::class, $row[8]),
                'joining_department_id' => $this->getDepartmentId(Department::class, $row[9]),
                'joining_section_id' => $this->getSectionId(Section::class, $row[10]),
                'joining_designation_id' => $this->getDesignationId(Designation::class, $row[11]),
                'date_of_join' => $this->parseDate($row[12]),

                // Current Info
                'current_company_id' => $this->getId(Company::class, $row[13]),
                'current_business_unit_id' => $this->getUnitId(CompanyLocation::class, $row[14]),
                'current_division_id' => $this->getDivisionId(Division::class, $row[15]),
                'current_department_id' => $this->getDepartmentId(Department::class, $row[16]),
                'current_section_id' => $this->getSectionId(Section::class, $row[17]),
                'current_designation_id' => $this->getDesignationId(Designation::class, $row[18]),

                // Orientation and other info
                'orientation_required' => $row[19] ?? null,
                'orientation_from' => $this->parseDate($row[20]),
                'orientation_to' => $this->parseDate($row[21]),
                'orientation_type' => $row[22] ?? null,
                'orientation_days' => $row[23] ?? null,
                'confirmation_date' => $this->parseDate($row[24]),
                'probation_duration' => $row[25] ?? null,
                'next_promotion_date' => $this->parseDate($row[26]),
                'promotion_cycle' => $row[27] ?? null,
                'increment_cycle' => $row[28] ?? null,

                'weekends' => $this->parseArray($row[29]),
                'alternate_off_day' => $this->parseArray($row[30]),
                'ot_allowed' => $row[31] ?? null,
                'pf_eligible' => $row[32] ?? null,
                'salary_type' => $row[33] ?? null,
                'transport_eligible' => $row[34] ?? null,
                'can_apply_loan' => $row[35] ?? null,
                'pf_effective_date' => $this->parseDate($row[36]),
                'can_apply_advance' => $row[37] ?? null,
                'gratuity_eligible' => $row[38] ?? null,
            ]);
        });
    }

    //Match Full Value
    private function getEmployeeId($model, $employee_id)
    {
        if (empty($employee_id)) return null;
        $record = $model::where('applicant_id', $employee_id)->first();
        return $record ? $record->id : null;
    }

    //Match Partially matched Value

    private function getId($model, $name)
    {
        if (empty($name)) return null;
        $name = trim(strtolower($name));
        $record = $model::whereRaw('LOWER(name) LIKE ?', ["%{$name}%"])->first();
        return $record ? $record->id : null;
    }

    private function getUnitId($model, $name)
    {
        if (empty($name)) return null;
        $name = trim(strtolower($name));
        $record = $model::whereRaw('LOWER(name) LIKE ?', ["%{$name}%"])->first();
        return $record ? $record->id : null;
    }

   private function getDivisionId($model, $name)
    {
        if (empty($name)) return null;
        $name = trim(strtolower($name));
        $record = $model::whereRaw('LOWER(name) LIKE ?', ["%{$name}%"])->first();
        return $record ? $record->id : null;
    }

private function getDepartmentId($model, $department_name)
    {
        if (empty($department_name)) return null;
        $department_name = trim(strtolower($department_name));
        $record = $model::whereRaw('LOWER(department_name) LIKE ?', ["%{$department_name}%"])->first();
        return $record ? $record->id : null;
    }
    private function getSectionId($model, $name)
    {
        if (empty($name)) return null;
        $name = trim(strtolower($name));
        $record = $model::whereRaw('LOWER(name) LIKE ?', ["%{$name}%"])->first();
        return $record ? $record->id : null;
    }
    private function getDesignationId($model, $company_designation)
    {
        if (empty($company_designation)) return null;
        $company_designation = trim(strtolower($company_designation));
        $record = $model::whereRaw('LOWER(company_designation) LIKE ?', ["%{$company_designation}%"])->first();
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

    private function parseArray($value)
    {
        if (empty($value)) return [];

        // Try to decode JSON
        $decoded = json_decode($value, true);

        // If valid JSON, return as array
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }

        // Fallback: split by comma if it's just a plain CSV string
        return array_map('trim', explode(',', $value));
    }

}

