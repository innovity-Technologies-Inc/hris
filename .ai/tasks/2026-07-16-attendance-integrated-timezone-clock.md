# Task: Integrate timezone selection inside the main Clock display

## Problem
Having a separate world clock widget section takes up layout space. The main clock itself should let the user switch between Bangladesh time (default) and other regions.

## Proposed Solution
1. Remove `.world-clock-container` section completely.
2. Embed the `<select id="mainClockTzSelect">` dropdown directly inside the main `.time-display` card.
3. Update JS clock handlers to format time and date using the value of `#mainClockTzSelect` (defaulting to `Asia/Dhaka`).

## Execution Steps
1. **View HTML Update**:
   - Edit `resources/views/attendance/clock_in_out.blade.php` to remove the separate world clock card layout and add the select option directly inside the main `.time-display` card.
2. **Javascript Update**:
   - Update `updateTime()` loop and event binding to render timezone-accurate times and dates.
3. **Verification**:
   - Run tests and commit.
