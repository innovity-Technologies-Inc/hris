# Task: NID Verification Module Implementation

## 🎯 Goal
Implement NID verification for employees with a modal-based workflow, dummy API integration, and access control.

## 📋 Action Items

### 1. Database & Schema
- [ ] Create a migration to add `nid` (string, nullable) and `is_nid_verified` (boolean, default false) to the `employees` table.
- [ ] Update `App\Models\Employee\Employee` model to include these fields in `$fillable`.
- [ ] Create a seeder or update `PermissionSeeder` to include the `nid verification` permission.

### 2. Backend Implementation (SOA)
- [ ] **Request**: Create `App\Http\Requests\Employee\NIDVerificationRequest` (optional if simple, but recommended).
- [ ] **Service**: Create `App\Services\Employee\NIDVerificationServices.php` with a `verify` method (dummy logic for now).
- [ ] **Controller**: Create `App\Http\Controllers\Employee\NIDVerificationController.php` to handle the verification API call and return JSON responses.
- [ ] **Routes**: Register the verification route in `routes/web.php`.

### 3. Frontend Integration
- [ ] **Profile Header**: 
    - [ ] Add the "NID Verification" button beside "Summary View".
    - [ ] Implement visibility logic (NOT Employee, has permission).
    - [ ] Add the "Verified" badge next to the employee name if `is_nid_verified` is true.
- [ ] **Modal**:
    - [ ] Create a modal in `profile_header.blade.php` or a separate partial.
    - [ ] Include a read-only input for NID.
    - [ ] Include a "Verify NID" button.
- [ ] **JavaScript**:
    - [ ] Implement Axios call to the verification endpoint.
    - [ ] Show loading state and success message using SweetAlert2 (if available) or standard Bootstrap alerts.
    - [ ] Refresh the page or update the UI dynamically on success.

### 4. Verification & Testing
- [ ] Create Pest tests for the `NIDVerificationServices` and `NIDVerificationController`.
- [ ] Verify permission-based access to the button and API.
- [ ] Update `TEST_LOG.md` with test results.

### 5. Finalization
- [ ] Update `project_doc.md`.
- [ ] Run `php artisan optimize`.
- [ ] Commit changes.

## 🧪 Testing Plan
- **Unit Test**: Test the dummy verification logic in the Service.
- **Feature Test**: 
    - Test the verification API endpoint with authorized and unauthorized users.
    - Test the UI visibility based on permissions and user types.
- **Database**: Ensure `nid` and `is_nid_verified` are correctly updated.
