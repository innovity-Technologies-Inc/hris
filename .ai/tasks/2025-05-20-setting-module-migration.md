# Task: Migrate Setting Module

## Description
Migrate files related to the "Setting" module to their respective subdirectories and update all references to maintain system integrity and follow the modular organization plan.

## Sub-tasks
1. **Move Files**:
    - Move Controllers from `app/Http/Controllers/` to `app/Http/Controllers/Setting/`.
    - Move Models from `app/Models/` to `app/Models/Setting/`.
    - Move Services from `app/Services/` to `app/Services/Setting/`.
2. **Update Namespaces**:
    - Update namespaces in moved files.
    - Add `use App\Http\Controllers\Controller;` to controllers.
3. **Global Search and Replace**:
    - Update class references across `app/`, `routes/`, `database/`, `tests/`, and `resources/views/`.
4. **Verification**:
    - Run `php artisan optimize`.
    - Run tests to ensure no regressions.
5. **Documentation**:
    - Update `project_doc.md`.

## Files to Move
### Controllers
- `ApiKeyController.php`
- `IDCardDesignController.php`
- `NotificationController.php`
- `SettingsController.php`

### Models
- `ApiKey.php`
- `GeneralSetting.php`
- `IDCardDesign.php`
- `MailSetting.php`
- `Menu.php`
- `Notification.php`

### Services
- `IDCardService.php`
- `NotificationServices.php`
- `QrCodeService.php`
- `RoleServices.php`

## Verification Command
```powershell
php artisan optimize
$env:DB_CONNECTION='sqlite'; $env:DB_DATABASE=':memory:'; php artisan test
```
