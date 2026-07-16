# Task: Replace static world clocks with Select Region option

## Problem
The previous implementation displayed static cards for predefined regions. The user wants to select the region/timezone from a dropdown and view the time for that specific region in the clock panel.

## Proposed Solution
1. Replace the world clock card grid in `clock_in_out.blade.php` with a dropdown selection option and a single dedicated digital clock time display panel.
2. Add a JS handler `updateWorldClock()` to update this display timezone dynamically based on the dropdown selection, and call it inside the 1-second interval timer.

## Execution Steps
1. **View HTML Update**:
   - Edit `resources/views/attendance/clock_in_out.blade.php` to replace the static card layout with a dropdown select and a digital clock display box.
2. **Javascript Update**:
   - Define `updateWorldClock()` method and update the change event listener and interval loop.
3. **Verification**:
   - Run tests and commit.
