# Task: Dashboard and Transport Module Migration

Migrate `DashboardController` and `TransportService` to their respective module-specific directories and update namespaces and references.

## 📝 Requirements
- Move `app/Http/Controllers/DashboardController.php` to `app/Http/Controllers/Dashboard/DashboardController.php`.
- Update namespace for `DashboardController`.
- Move `app/Services/TransportService.php` to `app/Services/Transport/TransportService.php`.
- Update namespace for `TransportService`.
- Update all references in the codebase.
- Optimize application.

## 🛠️ Tasks
- [ ] Move `DashboardController.php` to `app/Http/Controllers/Dashboard/` and update namespace.
- [ ] Move `TransportService.php` to `app/Services/Transport/` and update namespace.
- [ ] Update references to `DashboardController` in `routes/web.php` and other files.
- [ ] Update references to `TransportService` in controllers and other services.
- [ ] Run `php artisan optimize`.
- [ ] Verify functionality with tests.

## 🧪 Verification
- Run existing tests to ensure no regressions.
- Manually verify dashboard and transport routes if possible (though I'm in a CLI, I'll rely on tests and code audit).
