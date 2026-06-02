# Task: Notification Alert Settings Implementation

## 🎯 Goal
Implement configurable alert thresholds for employee milestones and expiries with automated notification generation.

## 📋 Action Items

### 1. Database & Schema
- [x] Create a migration for `notification_settings` table:
    - `id`
    - `birthday_days`
    - `visa_days`
    - `work_permit_days`
    - `passport_days`
    - `license_days`
    - `probation_days`
- [x] Create `App\Models\Setting\NotificationSetting` model.

### 2. Backend Implementation (SOA)
- [x] **Request**: Create `App\Http\Requests\Setting\NotificationSettingRequest`.
- [x] **Service**: Create `App\Services\Setting\NotificationSettingServices.php`.
- [x] **Controller**: Create `App\Http\Controllers\Setting\NotificationSettingController.php`.
- [x] **Routes**: Register routes for viewing and saving settings.

### 3. Notification Engine
- [x] **Command**: Create a Laravel command `app:check-alerts` to:
    - Load settings.
    - Fetch employees with upcoming events based on thresholds.
    - Generate notifications in the `notifications` table.
- [x] **Routing Logic**:
    - Birthdays: All non-employee users.
    - Others: Particular employee + all non-employee users.

### 4. Frontend Integration
- [x] **View**: Create `resources/views/setting/notification_settings.blade.php`.
- [x] **Navigation**: Add link to the new settings page in the sidebar or settings menu.
- [x] **UI/UX**: Use standard project styling (Bootstrap 5, Glassmorphism).

### 5. Verification & Testing
- [x] Create Pest tests for the notification engine logic.
- [x] Verify UI for saving settings.
- [x] Update `TEST_LOG.md` with test results.

### 6. Finalization
- [x] Update `project_doc.md`.
- [x] Run `php artisan optimize`.
- [x] Commit changes.

## 🧪 Testing Plan
- **Unit Test**: Test calculation logic for alert dates.
- **Feature Test**: 
    - Verify notifications are correctly routed to the right users.
    - Verify settings are correctly saved and retrieved.
- **Command Test**: Test that the command triggers notifications correctly.
