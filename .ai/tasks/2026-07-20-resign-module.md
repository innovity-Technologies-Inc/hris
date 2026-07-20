# Task: Implement Resign Module with Approval Workflow & 5-Tier Cascade

## Solution Steps

1. **Approval Config Update**: Add `'resign' => 'Resignation'` in `config/approval-engine.php`.
2. **Permission Seeder**: Add `Resignations` menu and permissions in `PermissionSeeder.php` and run seeder.
3. **Database Migration**: Create `resignations` table (`2026_07_20_000007_create_resignations_table.php`).
4. **Eloquent Model**: Create `App\Models\Resignation\Resignation.php` with `OrganizationScoped` and `Approvable` traits.
5. **Form Requests**: Create `StoreResignationRequest.php` and `UpdateResignationRequest.php` in `App\Http\Requests\Resignation`.
6. **Service Class**: Create `App\Services\Resignation\ResignationServices.php` for database operations, workflow initialization, and flexsearch filtering.
7. **Controller**: Create `App\Http\Controllers\Resignation\ResignationController.php` using `ApiResponse` trait methods.
8. **Cascading Hierarchy Data API**: Add helper route in `DataController` or `ResignationController` to fetch employees by hierarchy filters.
9. **Web Routes**: Register `/resignation` web routes in `routes/web.php`.
10. **Views**: Create Blade templates in `resources/views/resignation/`:
    - `index.blade.php` & `search_results.blade.php` (FlexSearch, AJAX pagination, Axios delete confirmation)
    - `create.blade.php` (5-tier cascading dropdowns, auto-calculating dates, Axios post)
    - `edit.blade.php` (Axios put update)
    - `show.blade.php` (Resignation detail & approval workflow timeline view)
11. **Testing & Verification**: Create Pest feature test `tests/Feature/ResignationTest.php` and verify CRUD & approval workflow trigger.
12. **Mandate Operations**: Run `php artisan optimize`, log to `TEST_LOG.md`, and commit changes.
