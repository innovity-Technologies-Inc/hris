@extends('structure.master')
@section('content')
<div class="container-fluid mt-4">
    {{-- Page Header --}}
    <div class="mb-4">
        <h4 class="fw-semibold">
            <i class="mdi mdi-calendar-clock text-primary me-2"></i>Create Roster Plan
        </h4>
        <p class="text-muted small mb-0">Define a roster plan with shifts and repetition settings</p>
    </div>

    <form method="POST" action="#" id="rosterPlanForm">
        @csrf

        {{-- Basic Roster Information --}}
        <div class="card border mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0 fw-semibold">
                    <i class="mdi mdi-information-outline text-primary me-2"></i>Basic Roster Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    {{-- Plan Name --}}
                    <div class="col-md-6 mb-3">
                        <label for="plan_name" class="form-label fw-semibold">
                            Plan Name <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            class="form-control"
                            id="plan_name"
                            name="plan_name"
                            placeholder="E.g., Weekly Day-Night Rotation"
                            value="{{ old('plan_name') }}"
                            required>
                        @error('plan_name')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Short Name --}}
                    <div class="col-md-6 mb-3">
                        <label for="short_name" class="form-label fw-semibold">
                            Short Name
                        </label>
                        <input
                            type="text"
                            class="form-control"
                            id="short_name"
                            name="short_name"
                            placeholder="E.g., WDN"
                            value="{{ old('short_name') }}"
                            maxlength="10">
                        <small class="text-muted">Optional abbreviated name (max 10 characters)</small>
                        @error('short_name')
                            <span class="text-danger small d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    {{-- Repetition Days --}}
                    <div class="col-md-6 mb-3">
                        <label for="repetition_days" class="form-label fw-semibold">
                            Repetition Days <span class="text-danger">*</span>
                        </label>
                        <input
                            type="number"
                            class="form-control"
                            id="repetition_days"
                            name="repetition_days"
                            placeholder="E.g., 7"
                            value="{{ old('repetition_days') }}"
                            min="1"
                            required>
                        <small class="text-muted">Number of days before the roster pattern repeats</small>
                        @error('repetition_days')
                            <span class="text-danger small d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div class="col-md-6 mb-3">
                        <label for="status" class="form-label fw-semibold">
                            Status <span class="text-danger">*</span>
                        </label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="">Select Status</option>
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    {{-- Description --}}
                    <div class="col-md-12 mb-3">
                        <label for="description" class="form-label fw-semibold">
                            Description
                        </label>
                        <textarea
                            class="form-control"
                            id="description"
                            name="description"
                            rows="3"
                            placeholder="Optional notes about this roster plan">{{ old('description') }}</textarea>
                        @error('description')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Shift Assignment --}}
        <div class="card border mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0 fw-semibold">
                    <i class="mdi mdi-clock-outline text-success me-2"></i>Shift Assignment
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    {{-- Shift 1 Selection --}}
                    <div class="col-md-6 mb-3">
                        <label for="shift_1_id" class="form-label fw-semibold">
                            Shift 1 <span class="text-danger">*</span>
                        </label>
                        <select class="form-select shift-select" id="shift_1_id" name="shift_1_id" data-target="shift_1_details" required>
                            <option value="">Select Shift 1</option>
                            <option value="1">Morning Shift</option>
                            <option value="2">Day Shift</option>
                            <option value="3">Evening Shift</option>
                            <option value="4">Night Shift</option>
                        </select>
                        @error('shift_1_id')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror

                        {{-- Dynamic Shift 1 Details Container --}}
                        <div id="shift_1_details" class="shift-details mt-3 d-none">
                            <div class="border rounded p-3 bg-light">
                                <h6 class="fw-semibold mb-3 text-primary">
                                    <i class="mdi mdi-information me-1"></i>Shift Details
                                </h6>
                                <div class="shift-info">
                                    {{-- JavaScript will populate this --}}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Shift 2 Selection --}}
                    <div class="col-md-6 mb-3">
                        <label for="shift_2_id" class="form-label fw-semibold">
                            Shift 2
                        </label>
                        <select class="form-select shift-select" id="shift_2_id" name="shift_2_id" data-target="shift_2_details">
                            <option value="">Select Shift 2 (Optional)</option>
                            <option value="1">Morning Shift</option>
                            <option value="2">Day Shift</option>
                            <option value="3">Evening Shift</option>
                            <option value="4">Night Shift</option>
                        </select>
                        @error('shift_2_id')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror

                        {{-- Dynamic Shift 2 Details Container --}}
                        <div id="shift_2_details" class="shift-details mt-3 d-none">
                            <div class="border rounded p-3 bg-light">
                                <h6 class="fw-semibold mb-3 text-primary">
                                    <i class="mdi mdi-information me-1"></i>Shift Details
                                </h6>
                                <div class="shift-info">
                                    {{-- JavaScript will populate this --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Form Actions --}}
        <div class="d-flex justify-content-end gap-2 mb-4">
            <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                <i class="mdi mdi-arrow-left me-1"></i>Cancel
            </button>
            <button type="submit" class="btn btn-warning">
                <i class="mdi mdi-content-save me-1"></i>Save Roster Plan
            </button>
        </div>
    </form>
</div>

{{-- Inline JavaScript for Dynamic Shift Details --}}
<script>
    // Dummy shift data - In a real application, this would come from the backend
    const shifts = {
        1: {
            id: 1,
            name: 'Morning Shift',
            start_time: '06:00 AM',
            end_time: '02:00 PM',
            notes: 'Standard morning shift with 8-hour duration'
        },
        2: {
            id: 2,
            name: 'Day Shift',
            start_time: '09:00 AM',
            end_time: '05:00 PM',
        },
        3: {
            id: 3,
            name: 'Evening Shift',
            start_time: '02:00 PM',
            end_time: '10:00 PM',
        },
        4: {
            id: 4,
            name: 'Night Shift',
            start_time: '10:00 PM',
            end_time: '06:00 AM',
        }
    };

    /**
     * Initialize shift selection listeners when the page loads
     * This ensures the dynamic behavior is ready as soon as the DOM is loaded
     */
    document.addEventListener('DOMContentLoaded', function() {
        // Get all shift select dropdowns
        const shiftSelects = document.querySelectorAll('.shift-select');

        // Attach change event listener to each shift dropdown
        shiftSelects.forEach(function(select) {
            select.addEventListener('change', handleShiftChange);
        });
    });

    /**
     * Handle shift selection changes
     * This function is called whenever a user selects a shift from the dropdown
     * @param {Event} event - The change event from the select element
     */
    function handleShiftChange(event) {
        // Get the select element that triggered the change
        const selectElement = event.target;

        // Get the selected shift ID
        const shiftId = selectElement.value;

        // Get the target details container ID from the data-target attribute
        const targetId = selectElement.getAttribute('data-target');
        const detailsContainer = document.getElementById(targetId);

        // If no shift is selected, hide the details container using Bootstrap's d-none class
        if (!shiftId || shiftId === '') {
            detailsContainer.classList.add('d-none');
            return;
        }

        // Get the shift data from our dummy data object
        const shiftData = shifts[shiftId];

        // If shift data exists, populate and display the details
        if (shiftData) {
            displayShiftDetails(detailsContainer, shiftData);
            detailsContainer.classList.remove('d-none');
        } else {
            // If shift data doesn't exist, hide the container
            detailsContainer.classList.add('d-none');
        }
    }

    /**
     * Display shift details in the target container
     * This function creates the HTML to show shift information
     * @param {HTMLElement} container - The container element to populate
     * @param {Object} shift - The shift data object
     */
    function displayShiftDetails(container, shift) {
        // Find the shift-info div inside the container
        const infoDiv = container.querySelector('.shift-info');

        // Build the HTML content with shift details using Bootstrap classes
        const detailsHTML = `
            <div class="row">
                <div class="col-6">
                    <p class="text-muted mb-1 small">Shift Name</p>
                    <p class="fw-semibold mb-0">${shift.name}</p>
                </div>
                <div class="col-6">
                    <p class="text-muted mb-1 small">Duration</p>
                    <p class="fw-semibold mb-0">${shift.start_time} - ${shift.end_time}</p>
                </div>
            </div>
        `;

        // Insert the HTML into the container
        infoDiv.innerHTML = detailsHTML;
    }

    /**
     * Optional: Form validation before submission
     * This function runs when the form is submitted
     */
    document.getElementById('rosterPlanForm').addEventListener('submit', function(event) {
        // Prevent default form submission for demonstration
        // In a real application, remove this line to allow normal form submission
        // event.preventDefault();

        // You can add custom validation logic here
        const planName = document.getElementById('plan_name').value.trim();
        const status = document.getElementById('status').value;
        const repetitionDays = document.getElementById('repetition_days').value;
        const shift1 = document.getElementById('shift_1_id').value;

        // Example: Check if required fields are filled
        if (!planName || !status || !repetitionDays || !shift1) {
            alert('Please fill in all required fields.');
            event.preventDefault();
            return false;
        }

        // If validation passes, the form will submit normally
        return true;
    });
</script>
@endsection
