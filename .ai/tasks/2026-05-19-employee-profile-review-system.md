# Task: Employee Profile Review System & Hierarchical Notifications

## 1. Database & Models
- [ ] Create migration for `notifications` table:
    - `id`, `user_type` (string), `user_id` (unsignedBigInteger, nullable), `title` (string), `message` (text), `data` (json, nullable), `read_at` (timestamp, nullable).
- [ ] Create migration to add `review_cause` (text, nullable) to `employees` table.
- [ ] Create `Notification` model.
- [ ] Update `Employee` model relationships.

## 2. Notification System Logic
- [ ] Create `NotificationServices.php`:
    - Method `createNotification(string $userType, ?int $userId, string $title, string $message, array $data = [])`.
    - Method `getVisibleNotifications($user)`: Implement hierarchical visibility logic based on user type levels (`Employee` -> `Section` -> `Department` -> `Division` -> `Business Unit` -> `Company` -> `Group`).
- [ ] Create `NotificationController.php` for fetching and marking notifications as read.

## 3. Profile Review Module
- [ ] Create `EmployeeReviewController.php`:
    - `index()`: List employees with `pending` status using FlexSearch.
    - `review(Request $request, $id)`: Handle the review submission logic.
- [ ] Create `ProfileIncompleteMail` and `ProfileActiveMail` mailable classes.
- [ ] Update `EmployeeServices.php`:
    - Add `reviewProfile(Employee $employee, string $status, ?string $cause)`:
        - Update employee status.
        - Log review cause if incomplete.
        - Send appropriate Email.
        - Create appropriate Notifications.

## 4. UI/UX Implementation
- [ ] Create `resources/views/employees/review/index.blade.php`: List pending employees.
- [ ] Update `resources/views/employees/profile.blade.php`:
    - Add "Review" button (visible only if status is `pending`).
    - Add Review Modal with `status` dropdown and conditional `cause` textarea.
- [ ] Header Notification Component:
    - Update the top navigation notification icon to fetch and display notifications based on the new hierarchical logic.

## 5. Verification
- [ ] Create Feature test `tests/Feature/EmployeeProfileReviewTest.php`:
    - Test pending list visibility.
    - Test review submission (Accept/Incomplete).
    - Test notification creation and hierarchical visibility.
    - Test email dispatching.
- [ ] Update `TEST_LOG.md`.
