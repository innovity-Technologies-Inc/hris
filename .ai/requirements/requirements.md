# Requirements for Granular Payroll Deductions and Penalty Fix

## 1. Background
The current payroll system calculates a total `deduction_amount` which is a sum of late, excessive late, absent, and early exit deductions. However, users need to see the breakdown of these deductions in the payroll details. Additionally, a bug was reported where penalties for employee 200 were not correctly picked up during salary generation.

## 2. Functional Requirements
- **Granular Deductions**: The `payrolls` table must store individual amounts for:
    - Late Deductions
    - Excessive Late Deductions
    - Absent Deductions
    - Early Exit Deductions
- **Penalty Fix**: Ensure that penalties are correctly picked up during salary generation, even if the process is re-run (i.e., status needs to be handled correctly).
- **Service Logic Update**: 
    - `PayrollServices::deductionAmount` should return detailed amounts.
    - `PayrollServices::salaryProcess` should store these detailed amounts.
    - `PayrollServices::salaryDelete` should reset `EmployeePenalty` status to `approved` if they were marked as `deducted` by the process being deleted.
- **UI Update**: The payroll details page (`payroll_view.blade.php`) should display these granular deductions.
- **Bonus Detail View**: Implement a detailed view for individual bonuses showing employee info, batch info, and applicable plans.
- **Data Integrity**: Run seeders to ensure test data is correct.

## 3. Technical Requirements
- **Database Migration**: Add columns to `payrolls` table.
- **Eloquent Model**: Update `Payroll` model with `$fillable`.
- **Type Safety**: Use proper PHP type hinting.
- **Service-Oriented Architecture**: Maintain logic within `PayrollServices`.
