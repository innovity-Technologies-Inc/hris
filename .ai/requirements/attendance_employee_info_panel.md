# Requirements for Attendance Employee Info Panel

## 1. Background
To improve user experience and simplify clock-in, the employee selection dropdown should be replaced with a read-only employee information panel containing the logged-in user's Name, Employee ID, and Branch Location.

## 2. Functional Requirements
- **Display Panel**:
    - If the logged-in user is mapped to an employee record, render a read-only panel showing their Name, Employee ID, and Branch Location.
    - Hide or remove the interactive select dropdown.
- **Data Compatibility**:
    - Render hidden inputs containing the selected employee ID, preserving references for existing Axios/AJAX triggers.
