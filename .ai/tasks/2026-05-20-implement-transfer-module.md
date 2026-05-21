# Task: Implement Transfer Module

Implement a comprehensive Transfer Module following the strict Request -> Service -> API Controller pattern with Axios and Vanilla JS.

## 🛠️ Requirements
- **Permissions**: `transfers.create`, `transfers.view`, `transfers.edit`, `transfers.approve`.
- **Database**:
    - Migration for `transfers` table.
    - Migration for `transfer_approvals` table.
- **Backend (SOA)**:
    - Models: `App\Models\Transfer\Transfer`, `App\Models\Transfer\TransferApproval`.
    - Request: `App\Http\Requests\Transfer\StoreTransferRequest`.
    - Service: `App\Services\Transfer\TransferServices`.
    - API Controller: `App\Http\Controllers\Transfer\TransferAPIController`.
    - Web Controller: `App\Http\Controllers\Transfer\TransferController`.
- **Frontend (Axios + Vanilla JS)**:
    - `Application` view: Form with cascading dropdowns for organizational units.
    - `Logs` view: List of transfers with status tracking.
    - `View & Approval` view: Comparison of info, approval workflow with authority selection.
- **Notifications**: Email and In-app panel notifications.
- **Execution**: Automatic update of `EmployeeOfficeInfo` on transfer completion.

## 📝 Sub-tasks

### 1. Database & Permissions
- [x] Create migrations for `transfers` and `transfer_approvals`.
- [x] Update `PermissionSeeder` to include `transfers` permissions.
- [x] Re-seed permissions and create basic roles.

### 2. Models & Service Layer
- [x] Implement `Transfer` and `TransferApproval` models with relationships.
- [x] Implement `TransferServices` for business logic (creation, approval tracking, completion).
- [x] Implement `StoreTransferRequest` for validation.

### 3. API & Web Controllers
- [x] Implement `TransferAPIController` returning JSON for:
    - Employee list for dropdown.
    - Organizational unit data (Company -> Section chain).
    - Store/Update transfer.
    - Select and notify approvers.
    - Approve/Reject action.
- [x] Implement `TransferController` for loading Blade views.

### 4. Application (Create) View
- [x] Create `resources/views/transfer/application.blade.php`.
- [x] Implement Axios + Vanilla JS for cascading dropdowns.
- [x] Handle form submission via Axios.

### 5. Logs (List) View
- [x] Create `resources/views/transfer/logs.blade.php`.
- [x] Implement search and filtering using FlexSearch principles.

### 6. View & Approval Workflow
- [x] Create `resources/views/transfer/view.blade.php`.
- [x] Implement comparison between Current and Requested info.
- [x] Implement "Set Approvers" modal with authority selection filter.
- [x] Implement approval action buttons and modals.

### 7. Completion & Automation
- [x] Implement `completeTransfer` logic in `TransferServices`.
- [x] Ensure `EmployeeOfficeInfo` updates correctly on completion.
- [x] Implement Laravel Notifications (Email + Database).

### 8. Verification & Optimization
- [x] Write Pest tests for the full workflow (Application -> Approval -> Completion).
- [x] Run `php artisan optimize`.
- [x] Verify UI design (Bootstrap 5 + Glassmorphism).

## ✅ Verification Criteria
- [x] Transfers can be created for any employee.
- [x] Approvers are correctly assigned and notified.
- [x] Multi-level approval works as expected.
- [x] On completion, employee's office info is updated automatically.
- [x] No page reloads occur during data fetching or status updates (using Axios).
- [x] Permission checks correctly restrict access.

**Note:** The Pest tests for this module (`tests/Feature/TransferModuleTest.php`) have been successfully implemented and verified to pass. The module is fully functional and verified.
