<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Enums\UserType;
use Spatie\Permission\Models\Role;

class DummyManagerEmployeesSeeder extends Seeder
{
    public function run(): void
    {
        // Shared org IDs from existing data
        $companyId    = 1;  // Digicon Technologies
        $locationId   = 1;  // existing location/business unit
        $divisionId   = 1;
        $departmentId = 1;  // Software Development
        $sectionId    = 561; // Frontend Development
        $designationId = 601; // Entry Level - Software Engineer

        $managerRole = Role::where('name', 'Manager')->first();

        $employees = [
            [
                'user_type'   => 'department',
                'name'        => 'Department Manager',
                'email'       => 'department@example.com',
                'applicant_id'=> 'APP-DEPT-001',
                'system_id'   => 'SYS-DEPT-001',
                'punch_card'  => 'PC-DEPT-001',
                'first_name'  => 'Department',
                'last_name'   => 'Manager',
                'full_name'   => 'Department Manager',
            ],
            [
                'user_type'   => 'section',
                'name'        => 'Section Manager',
                'email'       => 'section@example.com',
                'applicant_id'=> 'APP-SECT-001',
                'system_id'   => 'SYS-SECT-001',
                'punch_card'  => 'PC-SECT-001',
                'first_name'  => 'Section',
                'last_name'   => 'Manager',
                'full_name'   => 'Section Manager',
            ],
        ];

        foreach ($employees as $data) {
            // 1. Create user
            $userId = DB::table('users')->insertGetId([
                'name'       => $data['name'],
                'email'      => $data['email'],
                'user_type'  => $data['user_type'],
                'status'     => 'active',
                'password'   => Hash::make('Password@123'),
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
            ]);

            // 2. Create employee
            $employeeId = DB::table('employees')->insertGetId([
                'user_id'      => $userId,
                'applicant_id' => $data['applicant_id'],
                'system_id'    => $data['system_id'],
                'punch_card_no'=> $data['punch_card'],
                'first_name'   => $data['first_name'],
                'last_name'    => $data['last_name'],
                'full_name'    => $data['full_name'],
                'father_name'  => 'Father of ' . $data['first_name'],
                'mother_name'  => 'Mother of ' . $data['first_name'],
                'gender'       => 'Male',
                'religion'     => 'Islam',
                'nationality'  => 'Bangladeshi',
                'date_of_birth'=> '1990-01-01',
                'personal_mobile' => '01700000000',
                'work_email'   => $data['email'],
                'status'       => 'active',
                'general_info_status' => 'active',
                'present_address' => json_encode([
                    'line_1'      => '123 Example Street',
                    'village'     => 'Example Village',
                    'post_office' => 'Example PO',
                    'district'    => 'Dhaka',
                    'division'    => 'Dhaka',
                    'zip_code'    => '1000',
                    'state'       => 'Dhaka',
                    'country'     => 'Bangladesh',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
            ]);

            // 3. Update user with employee_id
            DB::table('users')->where('id', $userId)->update(['employee_id' => $employeeId]);

            // 4. Create employee office info
            DB::table('employee_office_infos')->insert([
                'employee_id'             => $employeeId,
                'emp_type'                => 'permanent',
                'joining_company_id'      => $companyId,
                'joining_business_unit_id'=> $locationId,
                'joining_division_id'     => $divisionId,
                'joining_department_id'   => $departmentId,
                'joining_section_id'      => $sectionId,
                'joining_designation_id'  => $designationId,
                'date_of_join'            => '2024-01-01',
                'current_company_id'      => $companyId,
                'current_business_unit_id'=> $locationId,
                'current_division_id'     => $divisionId,
                'current_department_id'   => $departmentId,
                'current_section_id'      => $sectionId,
                'current_designation_id'  => $designationId,
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
            ]);

            // 5. Create salary breakdown
            DB::table('employee_salary_breakdowns')->insert([
                'employee_id'                    => $employeeId,
                'basic_salary'                   => 30000,
                'house_allowance'                => 10000,
                'transport_allowance'            => 3000,
                'food_allowance'                 => 2000,
                'medical_allowance'              => 2000,
                'other_earnings'                 => 0,
                'basic_salary_percentage'        => 50,
                'house_allowance_percentage'     => 30,
                'transport_allowance_percentage' => 10,
                'food_allowance_percentage'      => 5,
                'medical_allowance_percentage'   => 5,
                'other_earnings_percentage'      => 0,
                'gross_salary'                   => 47000,
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => 1,
                'updated_by' => 1,
            ]);

            // 6. Assign Manager role
            if ($managerRole) {
                DB::table('model_has_roles')->insert([
                    'role_id'    => $managerRole->id,
                    'model_type' => 'App\Models\User',
                    'model_id'   => $userId,
                ]);
            }

            $this->command->info("Created: {$data['name']} (User ID: {$userId}, Employee ID: {$employeeId})");
        }
    }
}
