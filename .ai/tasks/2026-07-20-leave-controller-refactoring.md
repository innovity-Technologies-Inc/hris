# Task: Refactor LeavesController to Request -> Service -> Thin Controller Architecture

## Execution Plan
1. Create `App\Http\Requests\Leave\StoreLeaveRequest.php`.
2. Create `App\Http\Requests\Leave\CalculateEndDateRequest.php`.
3. Create `App\Http\Requests\Leave\ImportLeavesRequest.php`.
4. Create `App\Services\Leave\LeaveServices.php`.
5. Refactor `App\Http\Controllers\Leave\LeavesController.php` to delegate all work to `LeaveServices`.
6. Run `php artisan optimize`.
7. Commit changes.
