# Task: Update UserType Enum Checks in Blade Templates

Update Blade templates to use lowercase values for `UserType` enum checks, following the change of enum values to lowercase.

## Requirements
- Update `auth()->user()->user_type->value === 'Employee'` to `auth()->user()->user_type->value === 'employee'`.
- Update `auth()->user()->user_type->value !== 'Employee'` to `auth()->user()->user_type->value !== 'employee'`.
- Check for other types like 'Group', 'Company' and update them to lowercase if found.
- Target files:
    1. resources/views/movement/form.blade.php
    2. resources/views/leave/create.blade.php
    3. resources/views/structure/partials/sidebar.blade.php
    4. resources/views/employee/partials/profile_view/nominee_information.blade.php
    5. resources/views/employee/partials/profile_view/general_info.blade.php
    6. resources/views/employee/partials/profile_view/employment_history.blade.php
    7. resources/views/employee/partials/profile_view/education_info.blade.php
    8. resources/views/employee/partials/creation_button.blade.php

## Execution Plan
1. Search for uppercase `UserType` values in the specified files.
2. Replace uppercase values with lowercase values.
3. Verify the changes manually or by running relevant tests.

## Verification
- Manual inspection of the files.
- Ensure no broken logic in UI visibility based on user type.
