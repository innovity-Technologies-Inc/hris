# Task: Implement Compensatory Leave Application & Comp-Off History Tracking

## Problem Statement
Currently, Leave Applications only support standard assigned leave plans. Employees cannot apply for compensatory leave earned by working on off-days, and there is no tracking table for comp-off history.

## Solution Architecture
1. **Database Schema & Migrations**:
   - Migration 1: Add `leave_category_type` enum (`standard`, `compensatory`) and make `plan_id` nullable on `leaves` table.
   - Migration 2: Create `employee_comp_off_histories` table for auditing earned and used comp-off days.
2. **Models**:
   - Create `App\Models\Employee\EmployeeCompOffHistory.php`.
   - Update `App\Models\Leave\Leave.php` `$fillable` array.
3. **DataController & API Routes**:
   - Add endpoint `/get-comp-off-details/{employee_id}` returning comp-off balance data.
4. **AttendanceServices**:
   - Record an `earned` entry in `employee_comp_off_histories` when `shift_type === 'comp-off'` is logged.
5. **LeavesController**:
   - Update `calculateEndDate()` to support compensatory leave (excluding weekends/holidays).
   - Update `store()` to handle compensatory leave validation against `EmployeeCompOff` balance.
   - Upon approval, update `EmployeeCompOff` used/balance days and create `used` history entry in `employee_comp_off_histories`.
6. **Blade UI (`leave/create.blade.php`)**:
   - Add Leave Category selection (`Standard Leave Plan` vs `Compensatory Leave`).
   - Add Axios/jQuery fetch for employee comp-off details.
   - Dynamically enable/disable `Compensatory Leave` selection based on balance.
   - Toggle `plan_id` visibility and required attributes based on selected category.

## Step-by-Step Execution Plan
- Step 1: Create and run database migrations.
- Step 2: Create `EmployeeCompOffHistory` model and update `Leave` model.
- Step 3: Update `DataController`, `LeavesController`, and `AttendanceServices`.
- Step 4: Update `leave/create.blade.php` view.
- Step 5: Run `php artisan optimize` and commit changes.
