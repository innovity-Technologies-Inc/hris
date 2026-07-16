# Task: Center-align the region select name and clock display

## Problem
The default select dropdown styling has a dropdown arrow on the right side which offsets the text and breaks perfect horizontal center alignment.

## Proposed Solution
Update `@push('styles')` block inside `clock_in_out.blade.php` to target `#mainClockTzSelect`, centering it, removing its default select arrow, and setting its styles to be bold and centered.

## Execution Steps
1. **View Style Update**:
   - Update custom styling block in `resources/views/attendance/clock_in_out.blade.php` to define alignment rules for `#mainClockTzSelect`.
2. **Verification**:
   - Run tests and commit.
