# Task: Implement Industry Standard PDF Payslip

Generate a professional PDF payslip for employees from their payroll details.

## 📝 Sub-tasks

### 1. Service Layer Implementation
- [ ] Create `App\Services\PayslipService`.
- [ ] Implement `generatePayslip($payrollId)` method.
- [ ] Use `Browsershot` for PDF generation, following the pattern in `IDCardService`.
- [ ] Fetch Company details from `EmployeeOfficeInfo` -> `getCurrentCompany`.

### 2. Controller Update
- [ ] Add `generatePayslip($id)` method to `App\Http\Controllers\Payroll\SalaryController`.
- [ ] Inject `PayslipService` into the controller.

### 3. Route Definition
- [ ] Add `Route::get('generate-payslip/{id}', 'generatePayslip')->name('payroll.payslip');` to the `salary-process` group in `routes/web.php`.

### 4. View Creation
- [ ] Create `resources/views/payroll/salary/payslip_pdf.blade.php`.
- [ ] Design an industry-standard payslip layout (Professional, clean, includes branding and tables).

### 5. UI Integration
- [ ] Add "Generate Payslip" button in `resources/views/payroll/salary/payroll_view.blade.php`.
- [ ] Use a professional icon (e.g., `fas fa-file-pdf`).

### 6. Finalization
- [ ] Update `project_doc.md`.
- [ ] Run `php artisan optimize`.
- [ ] Commit changes.

## 🧪 Verification
- [ ] Navigate to an employee's payroll detail page.
- [ ] Click "Generate Payslip".
- [ ] Verify the PDF is generated correctly with all details (Company logo, Earnings, Deductions, etc.).
