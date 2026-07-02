<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        $tables = [
            'employees',
            'employee_office_infos',
            'employee_eligible_plans',
            'employee_education_experience_training',
            'employee_nominees',
            'employee_salary_breakdowns',
            'employee_bank_accounts',
            'employee_employment_histories',
            
            // Link tables
            'employee_shift_plans',
            'employee_roster_plans',
            'employee_ot_plans',
            'employee_offday_plans',
            'employee_meal_plans',
            'employee_leave_plans',
            'employee_bonus_plans'
        ];

        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->seedEmployees();
    }

    private function seedEmployees()
    {
        $tables = [
            'employees',
            'employee_office_infos',
            'employee_eligible_plans',
            'employee_education_experience_training',
            'employee_nominees',
            'employee_salary_breakdowns',
            'employee_bank_accounts',
            'employee_employment_histories'
        ];

        foreach ($tables as $table) {
            $path = database_path("seeders/data/{$table}.json");
            if (file_exists($path)) {
                $data = json_decode(file_get_contents($path), true);
                
                // Chunk inserting to prevent database query/parameter limits
                $chunks = array_chunk($data, 100);
                foreach ($chunks as $chunk) {
                    DB::table($table)->insert($chunk);
                }
            }
        }
        
        echo "Seeded employees from exported database json dumps successfully.\n";
    }
}
