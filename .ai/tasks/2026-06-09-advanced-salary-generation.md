# Task: Advanced Salary Generation (Pay Groups & Penalties)

## Objectives
- Integrate Pay Group selection into the salary generation process.
- Handle different Pay Group frequencies (Monthly, Weekly, Hourly/Daily).
- Calculate gross salary dynamically for 'Hourly' based on working hours.
- Prevent duplicate salary generation for the same Pay Group and Date Range.
- Integrate Employee Penalties: deduct approved penalties within the date range and update their status to 'deducted'.

## Steps
1. **Migration**:
   - Update `payroll_process` table to add `pay_group_id` (foreign key to `pay_groups`), `start_date` (date), and `end_date` (date).
   - Drop or make `salary_month` nullable if we transition fully to start/end dates, though keeping it for display is fine.
2. **Models**:
   - Update `PayrollProcess` model fillable properties and relationships (`PayGroup`).
3. **Views (`resources/views/payroll/salary/create.blade.php`)**:
   - Add Pay Group dropdown.
   - Use AJAX/JavaScript to change date selection inputs (month picker for monthly, date range picker for weekly/hourly) based on selected Pay Group's frequency.
4. **Services (`App\Services\Payroll\PayrollServices.php`)**:
   - Update `payrollProcessDataValidation` to validate `pay_group_id`, `start_date`, `end_date`.
   - Update `findEmployees` to filter by the selected `pay_group_id` via `employeeSalaryBreakdown.payScale.pay_group_id`.
   - Update duplicate check: Instead of checking just `salary_month`, check `pay_group_id`, `start_date`, and `end_date`.
   - Update `salaryProcess`:
     - If frequency is Hourly, calculate gross salary: `gross_salary = (basic_rate_per_hour * total_working_hours)`.
     - Fetch `approved` penalties from `employee_penalties` between `start_date` and `end_date`. Deduct this from the total salary.
     - Upon successful creation, update the fetched penalties' status to `deducted`.
5. **Testing**:
   - Add/update tests in Pest to verify the new constraints and calculations.