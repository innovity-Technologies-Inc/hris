# Requirements for Attendance Clock Center Alignment

## 1. Background
The region name selector and the digital clock display inside the clock widget should be perfectly horizontally centered.

## 2. Functional Requirements
- **Horizontal Centering**:
    - Center the select element text using `text-align: center` and `text-align-last: center`.
    - Remove the default select arrow background image (`background-image: none`, `appearance: none`) to prevent it from offset-pushing the text to the left.
