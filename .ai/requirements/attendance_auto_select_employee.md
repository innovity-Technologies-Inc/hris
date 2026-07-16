# Requirements for Attendance Auto-Select Employee

## 1. Background
When accessing the Clock In / Out page, if the logged-in user is an employee, their record should be automatically selected in the Employee Name dropdown.

## 2. Functional Requirements
- **Auto-Selection**:
    - Ensure the logged-in user's employee record is always included in the dropdown list, even if they don't have an active shift.
    - Select the logged-in user's employee record by default on page load.
- **AJAX and Form Compatibility**:
    - Ensure the JavaScript correctly resolves the selected employee ID (falling back to a hidden input if the select is disabled or option is empty).
