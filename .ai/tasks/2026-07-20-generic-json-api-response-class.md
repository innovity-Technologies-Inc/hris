# Task: Implement Generic ApiResponse Class & Trait

## Solution Architecture
1. Create `App\Http\Responses\ApiResponse.php` with static helper methods conforming to `GEMINI.md` API standards.
2. Create `App\Traits\ApiResponse.php` trait wrapping `ApiResponse` helper methods.
3. Include `App\Traits\ApiResponse` in base `App\Http\Controllers\Controller.php`.
4. Refactor controllers (`DataController.php`, `LeavesController.php`, `LeavePlanController.php`) to utilize the generic `ApiResponse`.
5. Run `php artisan optimize`.
6. Run Pest feature tests.
7. Commit changes.
