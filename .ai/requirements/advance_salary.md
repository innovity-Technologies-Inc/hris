# Implementation Plan: Advance Salary Module

## Objective
Implement an Advance Salary module that allows HR to process advance payments for employees (individually or in batches by organizational filters and pay groups). The module will automatically deduct the advance from the employee's future salary.

## Scope & Impact
- **Database**:
  - Update `payroll_process` table to support `type = 'advance'`.
  - Create `advance_salaries` table to store individual employee advances.
  - Update `payrolls` table to include `advance_deduction_amount`.
- **Backend (SOA Pattern)**:
  - Create `AdvanceSalaryController` (API-driven).
  - Update `PayrollServices` (or create `AdvanceSalaryServices`) to handle calculation, processing, and deletion.
  - Update `PayrollServices@salaryProcess` to automatically detect and deduct approved advances for the current salary month.
- **Frontend**:
  - Create `advance_salary/index.blade.php` to list processes.
  - Create `advance_salary/create.blade.php` mimicking the existing `salary/create.blade.php` structure but with Advance-specific fields (`amount_type`, `amount_value`, `percentage_base`, `deduction_month`, `reason`).

## Proposed Solution & Implementation Steps

### Phase 1: Database Migrations & Models
1. **Migrations**:
   - `alter_type_in_payroll_process_table`: `DB::statement("ALTER TABLE payroll_process MODIFY COLUMN type ENUM('salary', 'bonus', 'advance') NOT NULL");`
   - `create_advance_salaries_table`: Columns for `process_id`, `employee_id`, `batch_id`, `amount`, `deduction_month`, `reason`, `status` (`pending`, `approved`, `deducted`).
   - `add_advance_deduction_to_payrolls_table`: Add `advance_deduction_amount` to `payrolls`.
2. **Models**:
   - Create `App\Models\Payroll\AdvanceSalary`.
   - Update `App\Models\Payroll\PayrollProcess` to handle the new type.

### Phase 2: Services & Logic
1. **Advance Salary Processing**:
   - Add logic in `PayrollServices` (e.g., `advanceProcess` method) to calculate the advance amount for filtered employees based on `amount_type` (fixed/percentage) and `percentage_base` (basic/gross).
   - Add `advanceDelete` method to handle rollback/deletion of pending advances.
2. **Deduction Logic in `salaryProcess`**:
   - Update `PayrollServices@salaryProcess` to find `advance_salaries` where `deduction_month == $salary_month`, `status == 'approved'`, and `employee_id == $employee->id`.
   - Sum the advance amounts, set `advance_deduction_amount` on the `Payroll` record, and subtract from the `total_salary`.
   - Update the `advance_salaries` status to `deducted`.
   - Handle rollback in `salaryDelete` to set `deducted` advances back to `approved`.

### Phase 3: Controller & Routes
1. **Controller**:
   - Create `AdvanceSalaryController` with standard resource methods (`index`, `create`, `store`, `edit`, `update`, `destroy`, `statusUpdate`).
2. **Routes**:
   - Register routes in `routes/web.php` under the payroll group.

### Phase 4: Frontend UI
1. **Views**:
   - `resources/views/payroll/advance_salary/index.blade.php`: DataTable with FlexSearch, similar to Salary list.
   - `resources/views/payroll/advance_salary/create.blade.php`: Form with cascading selects (Company -> Branch -> ... -> Employee), Pay Group, Salary Month (for reference), Deduction Month, Amount Type (Fixed/Percentage), Percentage Base (Gross/Basic), Amount, and Reason.
2. **JavaScript**:
   - Implement Axios for dynamic UI updates (loading organizational units) mirroring the salary view.

## Alternatives Considered
- *Separate UI for Individual vs. Batch*: Opted to consolidate into a single UI mimicking Salary Generation to maintain consistency and fulfill the user's explicit request.

## Verification & Testing
- Create a test file `tests/Feature/PayrollAdvanceSalaryTest.php` using Pest PHP.
- Ensure SQLite in-memory database is used.
- Test Cases:
  - Advance calculation (fixed and percentage).
  - Batch creation of advances.
  - Automatic deduction during salary generation.
  - Rollback of deduction if salary process is deleted.
- Log results in `TEST_LOG.md`.
