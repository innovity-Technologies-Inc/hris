# Task: Pay Group Module Implementation

## 🎯 Goal
Implement the Pay Group management module under Company Info using an API-first approach with Axios and dynamic UI components.

## 📋 Action Items

### 1. Database & Schema
- [x] Create migration `create_pay_groups_table`:
    - `id`
    - `company_id` (foreign key, nullable)
    - `title` (string)
    - `payroll_frequency` (enum: Hourly, Monthly, Weekly)
    - `salary_processing_day` (string)
    - `status` (enum: active, inactive)
    - `timestamps`
- [x] Create `App\Models\Company\PayGroup` model with `OrganizationScoped` trait.

### 2. Backend Implementation (SOA)
- [x] **Request**: Create `App\Http\Requests\Company\PayGroupRequest`.
- [x] **Service**: Create `App\Services\Company\PayGroupServices.php` with CRUD logic.
- [x] **Controller**: Create `App\Http\Controllers\Company\PayGroupController.php` (returning JSON).
- [x] **Routes**: Register API-style routes in `routes/web.php`.

### 3. Frontend Implementation
- [x] **Index View**: `resources/views/company/pay_groups/index.blade.php`.
    - Integrated search using Axios.
    - Dynamic data table populated via Axios.
- [x] **Form View/Modal**: `resources/views/company/pay_groups/form.blade.php`.
    - Dynamic fields for `salary_processing_day` based on `payroll_frequency`.
    - Axios-based form submission (Create/Update).
- [x] **Navigation**: Add "Pay Groups" to the sidebar under Company Info.

### 4. Verification & Testing
- [x] Create Pest tests for `PayGroupServices`.
- [x] Create Pest tests for `PayGroupController` API endpoints.
- [x] Verify organizational scoping.
- [x] Update `TEST_LOG.md`.

### 5. Finalization
- [x] Update `project_doc.md`.
- [x] Run `php artisan optimize`.
- [x] Commit changes.

## 🧪 Testing Plan
- **Unit Test**: Test validation logic and frequency-based day selection.
- **Feature Test**: 
    - Test JSON API responses for listing, creating, and updating.
    - Verify that `OrganizationScoped` limits data based on user type.
- **Manual Test**: Verify the dynamic form fields in the browser.
