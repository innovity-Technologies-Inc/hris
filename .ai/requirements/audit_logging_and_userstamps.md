# Implementation Plan: Audit Logging & Userstamps

## 1. Objective
Implement `created_by` and `updated_by` tracking across the database, integrate `spatie/laravel-activitylog` for comprehensive audit logging (capturing store, update, status change, approve, and delete actions), and build a UI in the Settings menu to view these logs.

## 2. Phase 1: Userstamps Implementation (created_by / updated_by)
- **Database Migration**: Create a dynamic migration that loops through all existing application tables (ignoring core system tables like `migrations`, `sessions`, etc.) and adds nullable `created_by` and `updated_by` foreign key columns.
- **Custom Trait (`App\Traits\Userstamps`)**: Create a trait that hooks into Eloquent's `creating` and `updating` lifecycle events to automatically populate these fields using `auth()->id()`.
- **Model Updates**: Bulk-apply the `Userstamps` trait to all 74 existing Eloquent models.

## 3. Phase 2: Spatie Activity Log Integration
- **Package Setup**: Install `spatie/laravel-activitylog`, publish its configuration, and run its database migrations.
- **Custom Trait (`App\Traits\Auditable`)**: Create a wrapper trait around Spatie's `LogsActivity` to standardise the configuration across the app (e.g., logging all fillable attributes, only logging dirty/changed values, and capturing the `causer_id`).
- **Model Updates**: Bulk-apply the `Auditable` trait to all relevant Eloquent models to ensure every module is tracked.

## 4. Phase 3: Audit Log UI Module
- **Controller**: Create `App\Http\Controllers\Setting\AuditLogController` to fetch records from the `activity_log` table.
- **Search & Filtering**: Utilize `daiyanmozumder/laravel-flexsearch` for filtering logs by User, Module (Subject), Event Type (created, updated, deleted), and Date Range.
- **Views**: Build `resources/views/setting/audit_logs/index.blade.php` to display a clean, readable table/timeline of the logs, including "Old Values" vs "New Values".
- **Sidebar Integration**: Add an "Audit Logs" menu item to the Settings section in the sidebar.

## 5. Verification & Testing
- Write a Feature Test (`AuditLogTest.php`) to verify that creating/updating a model correctly writes to the `activity_log` table and populates the `created_by`/`updated_by` columns.
- Manually verify the UI renders correctly and permissions are enforced.
