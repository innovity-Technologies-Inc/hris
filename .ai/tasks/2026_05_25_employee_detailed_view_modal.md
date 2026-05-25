# Task: Employee Detailed View Modal and PDF Export

Implement a "Detailed View" button in the employee profile that opens a modal with all employee information and provides a PDF download option.

## Tasks

### 1. Research & Planning
- [ ] Identify all fields in the `employees` table to be displayed.
- [ ] Plan the modal layout (Glassmorphism, Modern-Clean).
- [ ] Plan the PDF template.

### 2. Backend Implementation (Request -> Service -> Controller)
- [ ] Create/Update `EmployeeServices` method to fetch full employee details.
- [ ] Create an API endpoint in `EmployeeProfileController` to return full employee details as JSON.
- [ ] Implement PDF generation logic in a new service `EmployeeProfilePdfService` or within `EmployeeServices`.
- [ ] Create a route and controller action for PDF download.

### 3. Frontend Implementation
- [ ] Add "Detailed View" button to `resources/views/employee/partials/profile_view/profile_header.blade.php`.
- [ ] Create a new modal partial `resources/views/employee/partials/modal/detailed_view_modal.blade.php`.
- [ ] Include the modal in `resources/views/employee/partials/profile_view/profile_header.blade.php`.
- [ ] Add Vanilla JS/Axios logic to:
    - [ ] Fetch employee data when the button is clicked.
    - [ ] Populate the modal with the data (conditionally show fields).
    - [ ] Handle the PDF download button click.

### 4. Verification
- [ ] Create Pest tests for:
    - [ ] API endpoint returning correct employee data.
    - [ ] PDF download route returning a PDF response.
- [ ] Manual verification of the UI and PDF layout.
- [ ] Update `TEST_LOG.md`.

### 5. Finalization
- [ ] Update `project_doc.md`.
- [ ] Run `php artisan optimize`.
- [ ] Commit changes.
