# Task: Implement Offboarding Module (Resignation & Termination)

## Implementation Steps

1. **Config Update**: Register `'offboarding-resignation' => 'Offboarding Resignation'` and `'offboarding-termination' => 'Offboarding Termination'` in `config/approval-engine.php`.
2. **Permission Seeder**: Update `PermissionSeeder.php` with parent **Offboarding** menu and **Resignation** and **Termination** submenus. Run seeder.
3. **Database Migration**: Create migration `2026_07_20_000008_create_offboardings_table.php` (renaming or superseding `resignations` to `offboardings`).
4. **Model**: Create `App\Models\Offboarding\Offboarding.php` with `OrganizationScoped` and `Approvable` traits.
5. **Requests**: Create `StoreOffboardingRequest.php` and `UpdateOffboardingRequest.php`.
6. **Service**: Create `App\Services\Offboarding\OffboardingServices.php` (stores offboarding, updates employee status to `'resigned'` or `'terminated'`, initializes approval workflow).
7. **Controller**: Create `App\Http\Controllers\Offboarding\OffboardingController.php` with generic `ApiResponse` trait responses.
8. **Middleware**: Create `App\Http\Middleware\EnsureNotOffboarded.php` to restrict offboarded employees (`'resigned'`, `'terminated'`) to `/my-offboarding`. Register middleware in `bootstrap/app.php`.
9. **Web Routes**: Register `/offboarding/resignation` and `/offboarding/termination` routes in `routes/web.php`.
10. **Views**: Create Blade templates in `resources/views/offboarding/`:
    - `index.blade.php` & `search_results.blade.php` (shared index, dynamic type header, SweetAlert2 Axios delete)
    - `form.blade.php` (shared create/edit form, locked offboarding_type select, 5-tier hierarchy cascade, auto-calculating dates)
    - `show.blade.php` (offboarding details & approval workflow timeline)
    - `my_offboarding.blade.php` (portal page for offboarded employees)
11. **Testing**: Write Pest feature test `tests/Feature/OffboardingTest.php` testing resignation, termination, employee status update, and middleware portal restriction.
12. **Mandate Operations**: Run `php artisan optimize`, update `TEST_LOG.md`, and commit changes.
