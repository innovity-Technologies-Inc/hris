<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class SyncExistingEmployeesToUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ensure "Employee" role exists
        $employeeRole = Role::firstOrCreate(['name' => 'Employee', 'guard_name' => 'web']);
        $superAdminRole = Role::where('name', 'Super Admin')->first();

        // 2. Get all employees
        $employees = Employee::all();

        foreach ($employees as $index => $employee) {
            // Skip if employee already has a user link
            if ($employee->user_id && User::find($employee->user_id)) {
                continue;
            }

            // Fallback for missing email
            $email = $employee->work_email ?? 'employee' . $employee->id . '@example.com';
            
            // Check if user already exists by email
            $user = User::where('email', $email)->first();

            if (!$user) {
                // Determine type and role
                // For the first employee, make them Super Admin / Group
                if ($index === 0) {
                    $userType = 'Group';
                    $targetRole = $superAdminRole;
                } else {
                    $userType = 'Employee';
                    $targetRole = $employeeRole;
                }

                $user = User::create([
                    'name' => $employee->full_name,
                    'email' => $email,
                    'password' => Hash::make('password123'), // Default password
                    'user_type' => $userType,
                    'employee_id' => $employee->id,
                    'status' => 'active',
                ]);

                if ($targetRole) {
                    $user->assignRole($targetRole);
                }
            }

            // Link employee to user
            $employee->update(['user_id' => $user->id]);
        }
    }
}
