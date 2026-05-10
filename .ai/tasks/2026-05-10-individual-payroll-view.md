# Task: Individual Payroll Detail View

Implement a detailed view for individual employee payroll records within a salary process batch.

## 📝 Sub-tasks

### 1. Route Definition
- [ ] Add `Route::get('payroll-detail/{id}', 'showPayroll')->name('payroll.show');` to the `salary-process` route group in `routes/web.php`.

### 2. Controller Implementation
- [ ] Implement `showPayroll($id)` in `App\Http\Controllers\Payroll\SalaryController`.
- [ ] Fetch the `Payroll` record with its related `Employee` and `PayrollProcess`.

### 3. View Creation
- [ ] Create `resources/views/payroll/salary/payroll_view.blade.php`.
- [ ] Design the view to show a comprehensive breakdown:
    - Employee Header (Name, ID, Photo).
    - Batch Information (Batch ID, Month).
    - Earnings (Basic Salary, Overtime, Bonus, Off-day Work).
    - Deductions (Late, Absent, etc.).
    - Attendance Stats (Late, Excessive Late, Early Exit, Absent Count).
    - Final Totals.
- [ ] Ensure "Glassmorphism" and "Modern-Clean" aesthetic is maintained.

### 4. Integration
- [ ] Update `resources/views/payroll/salary/view.blade.php` to add a "View" button for each row in the employees table.
- [ ] The button should link to `route('salary.payroll.show', $item->id)`.

### 5. Finalization
- [ ] Update `project_doc.md`.
- [ ] Run `php artisan optimize`.
- [ ] Commit changes.

## 🧪 Verification
- [ ] Navigate to a Salary Process -> View Eligible Employees.
- [ ] Click the "View" button for an employee.
- [ ] Verify that all payroll data (salary, deductions, counts) are displayed correctly.
