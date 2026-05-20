# Task: Payroll Service Migration

Migrate Payroll module services to a dedicated sub-directory and update all references to follow the modular reorganization plan.

## 🛠️ Tasks
- [ ] Create `app/Services/Payroll` directory if it doesn't exist.
- [ ] Move services from `app/Services/` to `app/Services/Payroll/`:
    - `PayrollServices.php`
    - `PayslipService.php`
    - `SalaryCertificateService.php`
- [ ] Update internal namespaces in the moved files to `namespace App\Services\Payroll;`.
- [ ] Update all references across the codebase:
    - `App\Services\PayrollServices` -> `App\Services\Payroll\PayrollServices`
    - `App\Services\PayslipService` -> `App\Services\Payroll\PayslipService`
    - `App\Services\SalaryCertificateService` -> `App\Services\Payroll\SalaryCertificateService`
- [ ] Run `php artisan optimize` to refresh caches.

## ✅ Verification
- [ ] Verify that the application still loads correctly.
- [ ] (Optional) Run existing tests related to Payroll to ensure no breakage.
- [ ] Update `TEST_LOG.md` with results.
