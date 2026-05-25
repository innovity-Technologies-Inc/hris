# Task: Employee Detailed View Modal and PDF Export

Implement a "Detailed View" button in the employee profile that opens a modal with all employee information and provides a PDF download option.

## Tasks

### 1. Research & Planning
- [x] Identify all fields in the `employees` table to be displayed.
- [x] Plan the modal layout (Glassmorphism, Modern-Clean).
- [x] Plan the PDF template.

### 2. Backend Implementation (Request -> Service -> Controller)
- [x] Create/Update `EmployeeServices` method to fetch full employee details. (Using existing `getEmployeeById`)
- [x] Create an API endpoint in `EmployeeProfileController` to return full employee details as JSON.
- [x] Implement PDF generation logic in a new service `EmployeeProfilePdfService`.
- [x] Create a route and controller action for PDF download.

### 3. Frontend Implementation
- [x] Add "Detailed View" button to `resources/views/employee/partials/profile_view/profile_header.blade.php`.
- [x] Create a new modal partial `resources/views/employee/partials/modal/detailed_view_modal.blade.php`.
- [x] Include the modal in `resources/views/employee/partials/profile_view/profile_header.blade.php`.
- [x] Add Vanilla JS/Axios logic to:
    - [x] Fetch employee data when the button is clicked.
    - [x] Populate the modal with the data (conditionally show fields).
    - [x] Handle the PDF download button click.
    - [x] **Fix**: Added Axios CDN and moved scripts to `@push('scripts')` to resolve `ReferenceError: axios is not defined`.

### 4. Verification
- [x] Create Pest tests for:
    - [x] API endpoint returning correct employee data.
    - [x] PDF download route returning a PDF response.
- [x] Manual verification of the UI and PDF layout.
- [x] Update `TEST_LOG.md`.

### 5. Finalization
- [x] Update `project_doc.md`.
- [x] Run `php artisan optimize`.
- [x] Commit changes.
