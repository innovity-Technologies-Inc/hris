<?php

namespace Database\Seeders;

use App\Enums\UserType;
use App\Models\Employee\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserAndRoleSeeder extends Seeder
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
        $employeeRole = Role::firstOrCreate(['name' => 'Employee', 'guard_name' => 'web']);

        // 2. Create/Update the "Group" Super Admin Employee Profile
        $adminEmployee = Employee::updateOrCreate(
            ['work_email' => 'admin@example.com'],
            [
                'applicant_id' => 'ADMIN001',
                'system_id' => 'SYSADMIN001',
                'punch_card_no' => 'PADMIN001',
                'first_name' => 'System',
                'last_name' => 'Administrator',
                'full_name' => 'System Administrator',
                'father_name' => 'N/A',
                'mother_name' => 'N/A',
                'gender' => 'Male',
                'religion' => 'N/A',
                'nationality' => 'N/A',
                'present_address' => json_encode(['address' => 'N/A']),
                'date_of_birth' => '1980-01-01',
                'personal_mobile' => '0000000000',
                'status' => 'active',
            ]
        );

        // 3. Create/Update the "Group" Super Admin User and link to Employee
        $adminUser = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('12345678'),
                'user_type' => UserType::Group,
                'employee_id' => $adminEmployee->id,
                'status' => 'active',
            ]
        );
        $adminUser->assignRole($superAdminRole);
        $adminEmployee->update(['user_id' => $adminUser->id]);

        // 4. Create/Update Office Info for Super Admin
        \App\Models\Employee\EmployeeOfficeInfo::updateOrCreate(
            ['employee_id' => $adminEmployee->id],
            [
                'emp_type' => 'permanent',
                'date_of_join' => '2020-01-01',
                'current_company_id' => \App\Models\Company\Company::first()?->id,
                'current_business_unit_id' => \App\Models\Company\CompanyLocation::first()?->id,
            ]
        );

        $this->command->info('Super Admin user created and linked to Employee profile.');

        // 3. Create Specific Test User
        $testEmployee = Employee::first(); // Use the first seeded employee
        if ($testEmployee) {
            $testUser = User::updateOrCreate(
                ['email' => 'test@example.com'],
                [
                    'name' => $testEmployee->full_name,
                    'password' => Hash::make('12345678'),
                    'user_type' => UserType::Employee,
                    'employee_id' => $testEmployee->id,
                    'status' => 'active',
                ]
            );
            $testUser->syncRoles(['Employee']);
            $testEmployee->update(['user_id' => $testUser->id]);
        }

        $defaultPassword = Hash::make('12345678');

        // 4. Provision Login for All Employees
        // Using chunking for performance with ~2000 records
        Employee::with('officeInfo.getCurrentDepartment')->chunk(200, function ($employees) use ($hrManagerRole, $deptManagerRole, $employeeRole, $defaultPassword) {
            foreach ($employees as $employee) {
                // SKIP Super Admin to prevent overwriting roles/type
                if ($employee->work_email === 'admin@example.com') {
                    continue;
                }

                $email = $employee->work_email ?? 'employee' . $employee->id . '@example.com';

                // Determine Role based on Department or other logic
                $targetRole = $employeeRole;
                $userType = UserType::Employee;

                $deptName = $employee->officeInfo?->getCurrentDepartment?->department_name ?? '';

                if (stripos($deptName, 'HR') !== false || stripos($deptName, 'Human Resource') !== false || stripos($deptName, 'Payroll') !== false) {
                    $targetRole = $hrManagerRole;
                    $userType = UserType::Department;
                } elseif (stripos($deptName, 'Manager') !== false || stripos($deptName, 'Head') !== false) {
                    $targetRole = $deptManagerRole;
                    $userType = UserType::Department;
                }

                $user = User::updateOrCreate(
                    ['email' => $email],
                    [
                        'name' => $employee->full_name,
                        'password' => $defaultPassword,
                        'user_type' => $userType,
                        'employee_id' => $employee->id,
                        'status' => 'active',
                    ]
                );

                $user->syncRoles([$targetRole->name]);

                // Link employee to user
                if ($employee->user_id !== $user->id) {
                    $employee->update(['user_id' => $user->id]);
                }
            }
        });

        // 5. Explicitly restore Admin user_type and role if it was overwritten
        $finalAdmin = User::where('email', 'admin@example.com')->first();
        if ($finalAdmin) {
            $finalAdmin->update(['user_type' => UserType::Group]);
            $finalAdmin->syncRoles(['Super Admin']);
        }

        $this->command->info('Provisioned employees with login info and roles.');
    }
}

