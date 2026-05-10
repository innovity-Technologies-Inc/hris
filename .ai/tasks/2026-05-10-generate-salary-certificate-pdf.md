# Task: Implement Salary Certificate PDF Generation

Generate a professional Salary Certificate PDF for employees.

## 📝 Sub-tasks

### 1. Service Layer Implementation
- [ ] Create `App\Services\SalaryCertificateService`.
- [ ] Implement `generateSalaryCertificate($payrollId)` method.
- [ ] Use `Browsershot` for PDF generation.
- [ ] Pull data from `Payroll`, `Employee`, `EmployeeOfficeInfo`, and `Company`.

### 2. Controller Update
- [ ] Add `generateSalaryCertificate($id)` method to `App\Http\Controllers\Payroll\SalaryController`.
- [ ] Inject `SalaryCertificateService` into the controller.

### 3. Route Definition
- [ ] Add `Route::get('generate-salary-certificate/{id}', 'generateSalaryCertificate')->name('payroll.certificate');` to the `salary-process` group in `routes/web.php`.

### 4. View Creation
- [ ] Create `resources/views/payroll/salary/salary_certificate_pdf.blade.php`.
- [ ] Design a professional salary certificate layout:
    - Company Letterhead.
    - "TO WHOM IT MAY CONCERN" title.
    - Statement of employment and salary details.
    - Signature blocks.

### 5. UI Integration
- [ ] Add "Generate Salary Certificate" button in `resources/views/payroll/salary/payroll_view.blade.php` next to the Payslip button.
- [ ] Use a professional icon (e.g., `fas fa-certificate`).

### 6. Finalization
- [ ] Update `project_doc.md`.
- [ ] Run `php artisan optimize`.
- [ ] Commit changes.

## 🧪 Verification
- [ ] Navigate to an employee's payroll detail page.
- [ ] Click "Generate Salary Certificate".
- [ ] Verify the PDF is generated correctly with a professional layout and accurate data.
