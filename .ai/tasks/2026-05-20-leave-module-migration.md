# Leave Module Migration

Migrate Leave module files to the new modular structure and update all references.

## Tasks
- [x] Move `app/Http/Controllers/LeavesController.php` to `app/Http/Controllers/Leave/` <!-- id: 0 -->
- [x] Move `app/Models/Leave.php` to `app/Models/Leave/` <!-- id: 1 -->
- [x] Move `app/Models/LeaveCount.php` to `app/Models/Leave/` <!-- id: 2 -->
- [x] Update namespace in `app/Http/Controllers/Leave/LeavesController.php` <!-- id: 3 -->
- [x] Update namespace in `app/Models/Leave/Leave.php` <!-- id: 4 -->
- [x] Update namespace in `app/Models/Leave/LeaveCount.php` <!-- id: 5 -->
- [x] Global search and replace references: <!-- id: 6 -->
    - `App\Models\Leave` (exact) -> `App\Models\Leave\Leave`
    - `App\Models\LeaveCount` -> `App\Models\Leave\LeaveCount`
    - `App\Http\Controllers\LeavesController` -> `App\Http\Controllers\Leave\LeavesController`
- [x] Run `php artisan optimize` <!-- id: 7 -->
- [x] Verify with tests <!-- id: 8 -->
