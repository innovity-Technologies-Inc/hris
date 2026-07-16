# Task: Add Country tag and World Clock to Attendance page

## Problem
Currently, the clock-in time widget only displays the user's raw local time without showing the resolved country location or key international region clocks.

## Proposed Solution
1. Update `clock_in_out.blade.php` to include:
   - A country badge label next to the primary current time header.
   - A grid of world clocks below the primary clock display.
2. Extend javascript clock timer to resolve the local country using client-side timezone mapping and update the world clock values (London, New York, Tokyo, Dubai) every second.

## Execution Steps
1. **View HTML Update**:
   - Update `resources/views/attendance/clock_in_out.blade.php` to add the country badge and the world clock grid.
2. **View Style Update**:
   - Add styling rules for `.world-clock-card` in the view's `@push('styles')` block.
3. **Javascript Logic Update**:
   - Implement timezone-based formatting and update clock values inside the `updateTime()` handler.
4. **Verification**:
   - Verify tests and commit.
