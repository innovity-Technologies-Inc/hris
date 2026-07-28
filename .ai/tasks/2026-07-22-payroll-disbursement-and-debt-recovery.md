# Implementation Plan: Payroll Disbursement & Debt Recovery Module

## Objective
Implement a Disbursement module to handle payments for generated Salary and Bonus processes, and automatically capture negative payroll values into a Debt Recovery (`previous_dues`) system.

## Background & Motivation
Currently, salaries and bonuses are generated but not explicitly "paid." We need a system to disburse these payments (partially or fully), attach payment proofs, and handle situations where an employee's deductions exceed their earnings, resulting in a negative net salary.

## Scope & Impact
- **Database**:
  - Update `payrolls` and `bonuses` to have a `disbursement_status` (`pending`, `paid`).
  - Create `previous_dues` table (employee_id, amount, salary_month, status).
  - Create `disbursements` table (process_id, payment_method, attachment_path).
  - Create `disbursement_items` table (disbursement_id, record_id, record_type - 'payroll' or 'bonus', amount).
- **Backend**:
  - `DisbursementController` for handling API requests and serving views.
  - `DisbursementServices` for logic.
  - Update `PayrollServices@salaryProcess` to:
    1. Check for `previous_dues` and add them to deductions.
    2. Check if final salary is negative, and if so, record a new `PreviousDue`.
- **Frontend**:
  - `disbursement/index.blade.php`: List generated processes (where total_amount > 0 and not fully disbursed).
  - `disbursement/process.blade.php`: Select employees, input payment method, upload attachments, and disburse.
- **API/Interaction**:
  - Axios for dynamic data loading and submission.

## Proposed Solution
1. **Debt Recovery (Previous Dues)**:
   - When generating a salary, if `$salary_amount < 0`, set `$salary_amount = 0` and insert a record into `previous_dues`.
   - When generating a salary, look for existing `pending` `previous_dues` and deduct them (up to the point where salary becomes 0, carrying over any remainder).

2. **Disbursement**:
   - HR selects a `PayrollProcess` (Salary or Bonus).
   - The UI lists all employees in that process whose `disbursement_status` is `pending`.
   - HR selects one, multiple, or all employees.
   - HR provides `payment_method` and `attachment`.
   - Backend creates a `Disbursement`, creates `DisbursementItems`, marks the respective `Payroll` or `Bonus` records as `paid`, and updates the `PayrollProcess` status if fully disbursed.

## Alternatives Considered
- Direct modification of `Payroll` table for proof of payment instead of a separate `Disbursements` table. Rejected because HR might disburse 10 employees today via Bank, and 5 tomorrow via Cash, requiring separate attachment tracking.

## Phased Implementation Plan

### Phase 1: Database Migrations
- Migration: Add `disbursement_status` to `payrolls` and `bonuses` tables.
- Migration: Create `previous_dues` table.
- Migration: Create `disbursements` and `disbursement_items` tables.

### Phase 2: Debt Recovery Logic
- Update `PayrollServices.php` to handle `previous_dues` deduction and creation during `salaryProcess`.
- Update `salaryDelete` to rollback `previous_dues`.

### Phase 3: Disbursement Services & Controllers
- Create `DisbursementServices.php` to handle the disbursement logic.
- Create `DisbursementController.php` with `index`, `showProcess`, and `store` methods.

### Phase 4: UI Implementation
- Add Sidebar menu and RBAC permissions.
- Create `disbursement/index.blade.php` (List of batches).
- Create `disbursement/process.blade.php` (Employee selection, file upload, form submission via Axios).

## Verification
- Test generating a salary that results in a negative amount to ensure `PreviousDue` is created.
- Test recovering a `PreviousDue` in a subsequent month.
- Test disbursing a batch partially and fully.
