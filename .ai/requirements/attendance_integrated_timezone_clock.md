# Requirements for Attendance Integrated Timezone Clock

## 1. Background
Rather than having a separate world clock widget section, the user wants the main clock itself to support region selection, defaulting to Bangladesh time, and allowing live updates for the selected timezone.

## 2. Functional Requirements
- **Integrated Selection**:
    - Place a timezone selector dropdown directly inside the main glowing time display card.
    - Default the selector's value to Bangladesh (Dhaka / `Asia/Dhaka`).
- **Dynamic Time & Date Updates**:
    - Update both the digital time display (`#currentTime`) and date string (`#currentDate`) dynamically based on the chosen timezone from the dropdown.
    - Remove the separate world clock container panel from the page layout.
