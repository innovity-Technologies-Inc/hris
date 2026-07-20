# Requirement: Leave Module Architecture Refactoring (Request -> Service -> Controller)

## Overview
Refactor `LeavesController` to adhere strictly to the project's Request -> Service -> Controller pattern defined in `GEMINI.md`.

## Detailed Specifications

1. **Form Requests (`App\Http\Requests\Leave`)**:
   - `StoreLeaveRequest`: Validates leave submission parameters, incorporating `withValidator()` or `$this->after()` for leave limit, max days, and compensatory leave balance checks.
   - `CalculateEndDateRequest`: Validates end date calculation inputs (`employee_id`, `leave_category_type`, `plan_id`, `start_date`, `leave_count`).
   - `ImportLeavesRequest`: Validates file format for Excel/CSV import (`file`).

2. **Service Layer (`App\Services\Leave\LeaveServices.php`)**:
   - Move all business logic out of `LeavesController` into `LeaveServices`:
     - `getLeavesIndexData($flexsearch, $request)`
     - `getCreateFormData()`
     - `storeLeave($validatedData, $user)`
     - `deleteLeave($id, $user)`
     - `getLeaveData($id)`
     - `importLeaves($file)`
     - `getEmployeeLeaveInfo($id, $user)`
     - `calculateEndDate($validatedData)`

3. **Thin Controller (`App\Http\Controllers\Leave\LeavesController.php`)**:
   - Controller handles only request injection, service delegation, and JSON / Blade response returns.
