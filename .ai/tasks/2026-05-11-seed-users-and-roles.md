# Task: Seed Users and Roles for Employees

## Description
Initial setup of the database with standard user types ('group') and roles ('super_admin'), and provisioning login credentials for all existing employees.

## Requirements
- Create/Update 'Super Admin' role using Spatie Permission.
- Create a 'group' user type (this might be a field in `users` table or a specific role/flag).
- Iterate through all `employees` and create/update associated `User` accounts.
- Set all passwords to `12345678`.
- Assign roles to employees.

## Sub-tasks
1. [x] Research `User` and `Employee` model relationships and `user_type` field.
2. [x] Create `UserAndRoleSeeder`.
3. [x] Implement logic to create 'Super Admin' role and 'Group' user (admin@example.com).
4. [x] Implement logic to create/update `User` accounts for all 1999 existing `Employee` records.
5. [x] Assign diverse roles (HR Manager, Dept Manager, Employee) to employees.
6. [x] Set all passwords to `12345678`.
7. [x] Verify that all employees can log in.

## Verification Plan
- Run the seeder: `php artisan db:seed --class=UserRoleSeeder` (or appropriate name).
- Check `users` table for new records.
- Check `model_has_roles` table for role assignments.
- Test login with a few employee emails and the standard password.
