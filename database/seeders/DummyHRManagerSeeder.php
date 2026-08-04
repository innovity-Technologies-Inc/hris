<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DummyHRManagerSeeder extends Seeder
{
    public function run(): void
    {
        $companyId     = 1;
        $locationId    = 1;
        $divisionId    = 1;
        $departmentId  = 1;
        $sectionId     = 561;
        $designationId = 601;

        $hrManagerRole = Role::where('name', 'HR Manager')->first();

        // 1. Create user
        $userId = DB::table('users')->insertGetId([
            'name'       => 'HR Manager',
            'email'      => 'hrmanager@example.com',
            'user_type'  => 'company',
            'status'     => 'active',
            'password'   => Hash::make('Password@123'),
            'created_at' => now(),
            'updated_at' => now(),
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        // 2. Create employee
        $employeeId = DB::table('employees')->insertGetId([
            'user_id'             => $userId,
            'applicant_id'        => 'APP-HRM-001',
            'system_id'           => 'SYS-HRM-001',
            'punch_card_no'       => 'PC-HRM-001',
            'first_name'          => 'HR',
            'last_name'           => 'Manager',
            'full_name'           => 'HR Manager',
            'father_name'         => 'Father of HR',
            'mother_name'         => 'Mother of HR',
            'gender'              => 'Male',
            'religion'            => 'Islam',
            'nationality'         => 'Bangladeshi',
            'date_of_birth'       => '1988-05-15',
            'personal_mobile'     => '01800000000',
            'work_email'          => 'hrmanager@example.com',
            'status'              => 'active',
            'general_info_status' => 'active',
            'present_address'     => json_encode([
                'line_1'      => '456 HR Avenue',
                'village'     => 'HR Village',
                'post_office' => 'Gulshan PO',
                'district'    => 'Dhaka',
                'division'    => 'Dhaka',
                'zip_code'    => '1212',
                'state'       => 'Dhaka',
                'country'     => 'Bangladesh',
            ]),
            'created_at' => now(),
            'updated_at' => now(),
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        // 3. Link employee back to user
        DB::table('users')->where('id', $userId)->update(['employee_id' => $employeeId]);

        // 4. Office info
        DB::table('employee_office_infos')->insert([
            'employee_id'              => $employeeId,
            'emp_type'                 => 'permanent',
            'joining_company_id'       => $companyId,
            'joining_business_unit_id' => $locationId,
            'joining_division_id'      => $divisionId,
            'joining_department_id'    => $departmentId,
            'joining_section_id'       => $sectionId,
            'joining_designation_id'   => $designationId,
            'date_of_join'             => '2022-06-01',
            'current_company_id'       => $companyId,
            'current_business_unit_id' => $locationId,
            'current_division_id'      => $divisionId,
            'current_department_id'    => $departmentId,
            'current_section_id'       => $sectionId,
            'current_designation_id'   => $designationId,
            'created_at' => now(),
            'updated_at' => now(),
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        // 5. Salary breakdown
        DB::table('employee_salary_breakdowns')->insert([
            'employee_id'                    => $employeeId,
            'basic_salary'                   => 50000,
            'house_allowance'                => 15000,
            'transport_allowance'            => 5000,
            'food_allowance'                 => 3000,
            'medical_allowance'              => 3000,
            'other_earnings'                 => 2000,
            'basic_salary_percentage'        => 64,
            'house_allowance_percentage'     => 19,
            'transport_allowance_percentage' => 6,
            'food_allowance_percentage'      => 4,
            'medical_allowance_percentage'   => 4,
            'other_earnings_percentage'      => 3,
            'gross_salary'                   => 78000,
            'created_at' => now(),
            'updated_at' => now(),
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        // 6. Assign HR Manager role
        if ($hrManagerRole) {
            DB::table('model_has_roles')->insert([
                'role_id'    => $hrManagerRole->id,
                'model_type' => 'App\Models\User',
                'model_id'   => $userId,
            ]);
        }

        $this->command->info("Created: HR Manager (User ID: {$userId}, Employee ID: {$employeeId})");
    }
}
