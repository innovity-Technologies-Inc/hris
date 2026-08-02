<?php

namespace Database\Seeders;

use App\Models\Employee\Employee;
use App\Models\User;
use App\Models\Employee\EmployeeOfficeInfo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Enums\UserType;

class UserRoleProvisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ensure Permissions and Basic Roles exist
        $this->call(PermissionSeeder::class);

        $superAdminRole = Role::where('name', 'Super Admin')->first();
        $hrManagerRole = Role::firstOrCreate(['name' => 'HR Manager', 'guard_name' => 'web']);
        $deptManagerRole = Role::firstOrCreate(['name' => 'Department Manager', 'guard_name' => 'web']);
        $managerRole = Role::firstOrCreate(['name' => 'Manager', 'guard_name' => 'web']);
        $employeeRole = Role::firstOrCreate(['name' => 'Employee', 'guard_name' => 'web']);

        // 2. Create/Update the "Group" Super Admin User
        $adminUser = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('12345678'),
                'user_type' => UserType::Group,
                'status' => 'active',
                'employee_id' => 201,
            ]
        );
        $adminUser->assignRole($superAdminRole);

        // Link System Administrator employee to the admin user
        $adminEmployee = Employee::where('work_email', 'admin@example.com')->first();
        if ($adminEmployee) {
            $adminEmployee->update(['user_id' => $adminUser->id]);
        }
 
        $this->command->info('Super Admin user created with user_type: Group');

        // Mappings for employee IDs 192 to 200 (including User IDs 194, 195, 196)
        $specificMappings = [
            192 => [
                'user_type' => UserType::Department,
                'role' => 'HR Manager',
                'office_info' => [
                    'current_company_id' => 101,
                    'current_business_unit_id' => 211,
                    'current_division_id' => 311,
                    'current_department_id' => 411,
                    'current_section_id' => 551,
                ]
            ],
            193 => [
                'user_type' => UserType::Employee,
                'role' => 'Manager',
                'office_info' => [
                    'current_company_id' => 110,
                    'current_business_unit_id' => 210,
                    'current_division_id' => 310,
                    'current_department_id' => 410,
                    'current_section_id' => 550,
                ]
            ],
            194 => [
                'user_type' => UserType::Company,
                'role' => 'HR Manager',
                'office_info' => [
                    'current_company_id' => 105,
                    'current_business_unit_id' => 205,
                    'current_division_id' => 305,
                    'current_department_id' => 405,
                    'current_section_id' => 545,
                ]
            ],
            195 => [
                'user_type' => UserType::Company,
                'role' => 'Manager',
                'office_info' => [
                    'current_company_id' => 107,
                    'current_business_unit_id' => 207,
                    'current_division_id' => 307,
                    'current_department_id' => 437,
                    'current_section_id' => 537,
                ]
            ],
            196 => [
                'user_type' => UserType::BusinessUnit,
                'role' => 'Manager',
                'office_info' => [
                    'current_company_id' => 105,
                    'current_business_unit_id' => 205,
                    'current_division_id' => 325,
                    'current_department_id' => 425,
                    'current_section_id' => 525,
                ]
            ],
            197 => [
                'user_type' => UserType::Division,
                'role' => 'Manager',
                'office_info' => [
                    'current_company_id' => 101,
                    'current_business_unit_id' => 201,
                    'current_division_id' => 301,
                    'current_department_id' => null,
                    'current_section_id' => null,
                ]
            ],
            198 => [
                'user_type' => UserType::Department,
                'role' => 'Manager',
                'office_info' => [
                    'current_company_id' => 101,
                    'current_business_unit_id' => 201,
                    'current_division_id' => 301,
                    'current_department_id' => 401,
                    'current_section_id' => null,
                ]
            ],
            199 => [
                'user_type' => UserType::Section,
                'role' => 'Manager',
                'office_info' => [
                    'current_company_id' => 101,
                    'current_business_unit_id' => 201,
                    'current_division_id' => 301,
                    'current_department_id' => 401,
                    'current_section_id' => 501,
                ]
            ],
            200 => [
                'user_type' => UserType::Employee,
                'role' => 'Employee',
                'office_info' => [
                    'current_company_id' => 101,
                    'current_business_unit_id' => 201,
                    'current_division_id' => 301,
                    'current_department_id' => 401,
                    'current_section_id' => 501,
                ]
            ],
        ];
 
        // 3. Provision Login for All Employees
        $employees = Employee::with('officeInfo.getCurrentDepartment')->get();
 
        foreach ($employees as $index => $employee) {
            $email = $employee->work_email ?? 'employee' . $employee->id . '@example.com';
            
            // Skip the default System Administrator email to prevent overwriting their Super Admin role
            if ($email === 'admin@example.com') {
                continue;
            }
            
            // Determine Role based on Department
            $targetRoleName = $employeeRole->name;
            $userType = UserType::Employee;

            if (array_key_exists($employee->id, $specificMappings)) {
                $mapping = $specificMappings[$employee->id];
                $userType = $mapping['user_type'];
                $targetRoleName = $mapping['role'];

                // Update Employee Office Info in the Database
                EmployeeOfficeInfo::updateOrCreate(
                    ['employee_id' => $employee->id],
                    $mapping['office_info']
                );
            } else {
                $deptName = $employee->officeInfo?->getCurrentDepartment?->department_name ?? '';
                
                if (stripos($deptName, 'Recruitment') !== false || stripos($deptName, 'Payroll') !== false) {
                    $targetRoleName = $hrManagerRole->name;
                    $userType = UserType::Department; // Example assignment
                } elseif ($index < 5) {
                    // Make the first 5 employees "Company" level users for variety
                    $userType = UserType::Company;
                }
            }
 
            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $employee->full_name,
                    'password' => Hash::make('12345678'),
                    'user_type' => $userType,
                    'employee_id' => $employee->id,
                    'status' => 'active',
                ]
            );
 
            if ($targetRoleName) {
                $user->syncRoles([$targetRoleName]);
            }
 
            // Link employee to user
            $employee->update(['user_id' => $user->id]);
        }

        $this->command->info('Provisioned ' . $employees->count() . ' employees with login info and roles.');
    }
}

