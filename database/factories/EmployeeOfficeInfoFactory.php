<?php

namespace Database\Factories;

use App\Models\Employee\EmployeeOfficeInfo;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeOfficeInfoFactory extends Factory
{
    protected $model = EmployeeOfficeInfo::class;

    public function definition(): array
    {
        return [
            'employee_id' => 1,
            'current_company_id' => 1,
            'current_division_id' => 1,
            'current_department_id' => 1,
            'current_section_id' => 1,
        ];
    }
}

