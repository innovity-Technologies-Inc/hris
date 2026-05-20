# Task: Modular Reorganization

Reorganize the project codebase into logical modules based on the sidebar menu structure to improve maintainability and scalability.

## 📋 Requirements
- Create module-specific directories in Controllers, Models, Services, Requests, and Views.
- Defined Modules: `Dashboard`, `Employee`, `Attendance`, `Leave`, `Movement`, `Payroll`, `Plan`, `Company`, `Structure`, `Transport`, `Setting`.

## 🛠️ Action Plan

### 1. Directory Preparation
- Create the following directories:
    - `app/Http/Controllers/Dashboard`
    - `app/Http/Controllers/Employee`
    - `app/Http/Controllers/Attendance`
    - `app/Http/Controllers/Leave`
    - `app/Http/Controllers/Movement`
    - `app/Http/Controllers/Payroll` (already exists, but verify)
    - `app/Http/Controllers/Plan`
    - `app/Http/Controllers/Company`
    - `app/Http/Controllers/Structure`
    - `app/Http/Controllers/Transport` (already exists, but verify)
    - `app/Http/Controllers/Setting` (already exists as Settings, maybe rename or unify)
- Repeat for `app/Models`, `app/Services`, and `app/Http/Requests`.
- Align `resources/views` directories.

### 2. File Migration (Incremental)
- Move existing files into their respective modules.
- Update namespaces in moved PHP files.
- Update imports in all files referencing the moved classes.
- Update routes in `routes/web.php` and other route files.
- Update view references in Controllers and Blade files.

### 3. Verification
- Run `php artisan optimize` to clear caches.
- Execute existing tests to ensure no regressions.
- Manually verify UI functionality.

## ✅ Verification Steps
1. [ ] Directories created.
2. [ ] Controllers migrated and namespaces updated.
3. [ ] Models migrated and namespaces updated.
4. [ ] Services migrated and namespaces updated.
5. [ ] Requests migrated and namespaces updated.
6. [ ] Views reorganized.
7. [ ] Routes updated.
8. [ ] All tests passing.
9. [ ] `php artisan optimize` successful.
