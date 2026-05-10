# Task: Implement User Management & RBAC Module

Implement a comprehensive User Management system with organization-based data scoping and a Role-Based Access Control (RBAC) UI.

## 📝 Sub-tasks

### Phase 1: Authentication & Permissions Setup
- [ ] Install Laravel Breeze (Blade/Bootstrap).
- [ ] Install `spatie/laravel-permission`.
- [ ] Publish Spatie migrations and run `php artisan migrate`.

### Phase 2: Database & Model Refactoring
- [ ] Modify `users` table:
    - Add `user_type` (enum/string).
    - Add `employee_id` (nullable foreign key).
    - Add `status` (active/inactive).
- [ ] Modify `employees` table:
    - Add `user_id` (nullable foreign key).
- [ ] Update `User` and `Employee` models with relationships and fillables.

### Phase 3: Employee Registration Flow Integration
- [ ] Update `App\Http\Requests\EmployeeGeneralInformationRequest` (if exists) or create one to include user fields.
- [ ] Update `App\Services\EmployeeServices` to handle simultaneous `User` creation/update.
- [ ] Update `resources/views/employees/general_informations/form.blade.php`:
    - Add "Login Information" tab/section.
    - Fields: User Type (Dropdown), Roles (Multi-select), Password, Confirm Password.

### Phase 4: Role & Permission Management UI
- [ ] Create `App\Models\Menu` (to manage system menus for permissions).
- [ ] Create `App\Http\Controllers\Settings\RoleController`.
- [ ] Create `App\Services\RoleServices`.
- [ ] Create Views for Role Management:
    - List Roles.
    - Create/Edit Role (with menu-wise permission checkboxes: Create, Edit, View, Delete).
- [ ] Create `PermissionSeeder` to populate menus and basic permissions.

### Phase 5: Data Scoping (Global Scopes/Traits)
- [ ] Implement a Trait `OrganizationScoped` to handle data filtering based on `auth()->user()->user_type`.
- [ ] Apply the Trait to relevant models (Employee, Attendance, Payroll, etc.).
- [ ] Logic for:
    - `Employee` type: `where('id', auth()->user()->employee_id)`.
    - `Section` type: `where('section_id', auth()->user()->employee->officeInfo->section_id)`.
    - (Repeat for Department, Division, Company, etc.).

### Phase 6: Finalization
- [ ] Update `project_doc.md`.
- [ ] Run `php artisan optimize`.
- [ ] Commit changes.

## 🧪 Verification
- [ ] Successfully log in using credentials created via Employee form.
- [ ] Verify a "Department" type user cannot see employees from other departments.
- [ ] Verify an "Employee" type user can only see their own profile.
- [ ] Create a role with only "View" permission for "Attendance" and verify "Create/Delete" are hidden/blocked.
