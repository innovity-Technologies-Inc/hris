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
        $employeeRole = Role::firstOrCreate(['name' => 'Employee', 'guard_name' => 'web']);

        // 2. Create/Update the "Group" Super Admin User
        $adminUser = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('12345678'),
                'user_type' => UserType::Group,
                'status' => 'active',
            ]
        );
        $adminUser->assignRole($superAdminRole);

        $this->command->info('Super Admin user created with user_type: Group');

        // 3. Provision Login for All Employees
        $employees = Employee::with('officeInfo.getCurrentDepartment')->get();

        foreach ($employees as $index => $employee) {
            $email = $employee->work_email ?? 'employee' . $employee->id . '@example.com';
            
            // Determine Role based on Department
            $targetRole = $employeeRole;
            $userType = UserType::Employee;

            $deptName = $employee->officeInfo?->getCurrentDepartment?->department_name ?? '';
            
            if (stripos($deptName, 'Recruitment') !== false || stripos($deptName, 'Payroll') !== false) {
                $targetRole = $hrManagerRole;
                $userType = UserType::Department; // Example assignment
            } elseif ($index < 5) {
                 // Make the first 5 employees "Company" level users for variety
                 $userType = UserType::Company;
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

            $user->syncRoles([$targetRole->name]);

            // Link employee to user
            $employee->update(['user_id' => $user->id]);
        }

        $this->command->info('Provisioned ' . $employees->count() . ' employees with login info and roles.');
    }
}

