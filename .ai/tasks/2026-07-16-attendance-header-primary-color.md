# Task: Align attendance header and widgets with primary red brand color

## Problem
The current page header and clock widget highlight styles use default indigo/blue shades, whereas the project's primary brand color is a dark red/rose shade (`#974063`).

## Proposed Solution
Update `resources/views/attendance/clock_in_out.blade.php` style declarations to:
- Use `linear-gradient(135deg, var(--primary-color, #974063), #6b2543)` for `.page-header`.
- Update clock panel glow colors and time shadows to red shade rgb elements (`rgba(151, 64, 99, ...)`).

## Execution Steps
1. **View Style Update**:
   - Edit custom styles inside `clock_in_out.blade.php` to define the red-shade gradient and glow.
2. **Verification**:
   - Run tests and commit.
