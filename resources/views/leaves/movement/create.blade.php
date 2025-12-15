@extends('structure.master')

@section('content')
    @php
        // ========== SAMPLE EMPLOYEE DATA ==========
        $employees = [
            (object) [
                'id' => 1,
                'employee_id' => 'EMP-2024-001',
                'name' => 'Mohammad Rahman',
                'designation' => 'Senior Software Engineer',
                'department' => 'Engineering',
            ],
            (object) [
                'id' => 2,
                'employee_id' => 'EMP-2024-002',
                'name' => 'Fatima Ahmed',
                'designation' => 'HR Manager',
                'department' => 'Human Resources',
            ],
            (object) [
                'id' => 3,
                'employee_id' => 'EMP-2024-003',
                'name' => 'Karim Hassan',
                'designation' => 'Financial Analyst',
                'department' => 'Finance',
            ],
            (object) [
                'id' => 4,
                'employee_id' => 'EMP-2024-004',
                'name' => 'Ayesha Khan',
                'designation' => 'Marketing Executive',
                'department' => 'Marketing',
            ],
            (object) [
                'id' => 5,
                'employee_id' => 'EMP-2024-005',
                'name' => 'Abdullah Islam',
                'designation' => 'Sales Manager',
                'department' => 'Sales',
            ],
            (object) [
                'id' => 6,
                'employee_id' => 'EMP-2024-006',
                'name' => 'Nadia Sultana',
                'designation' => 'Project Manager',
                'department' => 'Operations',
            ],
            (object) [
                'id' => 7,
                'employee_id' => 'EMP-2024-007',
                'name' => 'Mahmudul Hasan',
                'designation' => 'Business Analyst',
                'department' => 'Business Development',
            ],
            (object) [
                'id' => 8,
                'employee_id' => 'EMP-2024-008',
                'name' => 'Rukhsana Begum',
                'designation' => 'Quality Assurance',
                'department' => 'Engineering',
            ],
        ];

        // ========== SAMPLE TA PLAN DATA ==========
        $taPlans = [
            (object) [
                'id' => 1,
                'name' => 'Basic TA Plan',
                'rate_per_km' => 10.0,
                'description' => 'Standard travel allowance for local travel',
            ],
            (object) [
                'id' => 2,
                'name' => 'Executive TA Plan',
                'rate_per_km' => 15.0,
                'description' => 'Enhanced travel allowance for executives',
            ],
            (object) [
                'id' => 3,
                'name' => 'Premium TA Plan',
                'rate_per_km' => 20.0,
                'description' => 'Premium travel allowance for senior management',
            ],
            (object) [
                'id' => 4,
                'name' => 'Field Staff TA Plan',
                'rate_per_km' => 12.5,
                'description' => 'Travel allowance for field operations staff',
            ],
        ];

        // ========== SAMPLE DA PLAN DATA ==========
        $daPlans = [
            (object) [
                'id' => 1,
                'name' => 'Standard DA Plan',
                'daily_rate' => 500.0,
                'description' => 'Standard daily allowance for business trips',
            ],
            (object) [
                'id' => 2,
                'name' => 'Executive DA Plan',
                'daily_rate' => 1000.0,
                'description' => 'Enhanced daily allowance for executives',
            ],
            (object) [
                'id' => 3,
                'name' => 'Premium DA Plan',
                'daily_rate' => 1500.0,
                'description' => 'Premium daily allowance for senior management',
            ],
            (object) [
                'id' => 4,
                'name' => 'Field Staff DA Plan',
                'daily_rate' => 750.0,
                'description' => 'Daily allowance for field operations staff',
            ],
        ];

        // ========== SAMPLE STATUS OPTIONS ==========
        $statusOptions = [
            (object) [
                'value' => 'pending',
                'label' => 'Pending',
                'badge_class' => 'bg-warning',
            ],
            (object) [
                'value' => 'approved',
                'label' => 'Approved',
                'badge_class' => 'bg-success',
            ],
            (object) [
                'value' => 'rejected',
                'label' => 'Rejected',
                'badge_class' => 'bg-danger',
            ],
            (object) [
                'value' => 'completed',
                'label' => 'Completed',
                'badge_class' => 'bg-info',
            ],
        ];
    @endphp

    <div class="container-fluid">
        <!-- Page Title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">HRMS</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Leaves</a></li>
                            <li class="breadcrumb-item active">Employee Movement</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Employee Movement Entry</h4>
                </div>
            </div>
        </div>

        <!-- Main Card -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border">
                    <!-- Card Header -->
                    <div class="card-header border-bottom py-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 rounded p-2 me-3">
                                    <i class="bi bi-geo-alt-fill fs-3 text-primary"></i>
                                </div>
                                <div>
                                    <h4 class="mb-1 fw-bold">Employee Movement Form</h4>
                                    <p class="mb-0 text-muted small">Record employee travel and movement details with
                                        allowances</p>
                                </div>
                            </div>
                            <div>
                                <button type="button" class="btn btn-outline-secondary me-2">
                                    <i class="bi bi-arrow-left me-2"></i>Back
                                </button>
                                <button type="button" class="btn btn-outline-primary">
                                    <i class="bi bi-clock-history me-2"></i>Movement History
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body p-4">
                        <form id="employeeMovementForm" action="#" method="POST">
                            @csrf

                            <div class="row g-4">
                                <!-- Employee Name - Full Width -->
                                <div class="col-12">
                                    <label for="employee_id" class="form-label fw-semibold">
                                        <i class="bi bi-person-badge text-primary me-2"></i>
                                        Employee Name <span class="text-danger">*</span>
                                    </label>
                                    <select name="employee_id" id="employee_id" class="form-select select2_list" required>
                                        <option value="">Select Employee</option>
                                        @foreach ($employees as $employee)
                                            <option value="{{ $employee->id }}"
                                                data-employee-id="{{ $employee->employee_id }}"
                                                data-designation="{{ $employee->designation }}"
                                                data-department="{{ $employee->department }}">
                                                {{ $employee->name }} ({{ $employee->employee_id }}) -
                                                {{ $employee->designation }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">Please select an employee.</div>
                                </div>

                                <!-- From Date -->
                                <div class="col-md-6">
                                    <label for="from_date" class="form-label fw-semibold">
                                        <i class="bi bi-calendar-check text-success me-2"></i>
                                        From Date & Time <span class="text-danger">*</span>
                                    </label>
                                    <input type="datetime-local" name="from_date" id="from_date" class="form-control"
                                        required>
                                    <div class="invalid-feedback">Please enter from date and time.</div>
                                </div>

                                <!-- To Date -->
                                <div class="col-md-6">
                                    <label for="to_date" class="form-label fw-semibold">
                                        <i class="bi bi-calendar-x text-danger me-2"></i>
                                        To Date & Time <span class="text-danger">*</span>
                                    </label>
                                    <input type="datetime-local" name="to_date" id="to_date" class="form-control"
                                        required>
                                    <div class="invalid-feedback">Please enter to date and time.</div>
                                </div>

                                <!-- Source Address -->
                                <div class="col-md-4">
                                    <label for="source_address" class="form-label fw-semibold">
                                        <i class="bi bi-pin-map text-info me-2"></i>
                                        Source Address <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="source_address" id="source_address" class="form-control"
                                        placeholder="Enter starting location address" required>
                                    <div class="invalid-feedback">Please enter source address.</div>
                                </div>

                                <!-- Destination Address -->
                                <div class="col-md-4">
                                    <label for="destination_address" class="form-label fw-semibold">
                                        <i class="bi bi-geo-alt text-warning me-2"></i>
                                        Destination Address <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="destination_address" id="destination_address"
                                        class="form-control" placeholder="Enter destination address" required>
                                    <div class="invalid-feedback">Please enter destination address.</div>
                                </div>

                                <!-- Covered Distance -->
                                <div class="col-md-4">
                                    <label for="covered_distance" class="form-label fw-semibold">
                                        <i class="bi bi-speedometer2 text-primary me-2"></i>
                                        Covered Distance (KM) <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" name="covered_distance" id="covered_distance" class="form-control"
                                        step="0.01" min="0" placeholder="0.00" required>
                                    <div class="invalid-feedback">Please enter covered distance in kilometers.</div>
                                </div>

                                <!-- TA Plan -->
                                <div class="col-md-4">
                                    <label for="ta_plan_id" class="form-label fw-semibold">
                                        <i class="bi bi-cash-coin text-success me-2"></i>
                                        TA Plan <span class="text-danger">*</span>
                                    </label>
                                    <select name="ta_plan_id" id="ta_plan_id" class="form-select select2_list" required>
                                        <option value="">Select TA Plan</option>
                                        @foreach ($taPlans as $plan)
                                            <option value="{{ $plan->id }}" data-rate="{{ $plan->rate_per_km }}"
                                                data-description="{{ $plan->description }}">
                                                {{ $plan->name }} (৳{{ number_format($plan->rate_per_km, 2) }}/KM)
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">Please select a TA plan.</div>
                                    <small class="text-muted d-block mt-1" id="ta_plan_info"></small>
                                </div>

                                <!-- DA Plan -->
                                <div class="col-md-4">
                                    <label for="da_plan_id" class="form-label fw-semibold">
                                        <i class="bi bi-wallet2 text-warning me-2"></i>
                                        DA Plan <span class="text-danger">*</span>
                                    </label>
                                    <select name="da_plan_id" id="da_plan_id" class="form-select select2_list" required>
                                        <option value="">Select DA Plan</option>
                                        @foreach ($daPlans as $plan)
                                            <option value="{{ $plan->id }}" data-rate="{{ $plan->daily_rate }}"
                                                data-description="{{ $plan->description }}">
                                                {{ $plan->name }} (৳{{ number_format($plan->daily_rate, 2) }}/Day)
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">Please select a DA plan.</div>
                                    <small class="text-muted d-block mt-1" id="da_plan_info"></small>
                                </div>

                                <!-- Calculated Allowance Summary -->
                                <div class="col-12">
                                    <div class="card bg-light border-0">
                                        <div class="card-body p-3">
                                            <h6 class="mb-3 fw-semibold">
                                                <i class="bi bi-calculator text-primary me-2"></i>
                                                Calculated Allowance Summary
                                            </h6>
                                            <div class="row g-3">
                                                <div class="col-md-3">
                                                    <div class="d-flex flex-column">
                                                        <small class="text-muted">TA Amount</small>
                                                        <h5 class="mb-0 text-success" id="ta_amount">৳0.00</h5>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="d-flex flex-column">
                                                        <small class="text-muted">DA Amount</small>
                                                        <h5 class="mb-0 text-warning" id="da_amount">৳0.00</h5>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="d-flex flex-column">
                                                        <small class="text-muted">Total Days</small>
                                                        <h5 class="mb-0 text-info" id="total_days">0</h5>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="d-flex flex-column">
                                                        <small class="text-muted">Total Allowance</small>
                                                        <h5 class="mb-0 text-primary fw-bold" id="total_allowance">৳0.00
                                                        </h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Reason -->
                                <div class="col-12">
                                    <label for="reason" class="form-label fw-semibold">
                                        <i class="bi bi-chat-left-text text-secondary me-2"></i>
                                        Reason / Purpose of Movement <span class="text-danger">*</span>
                                    </label>
                                    <textarea name="reason" id="reason" rows="4" class="form-control"
                                        placeholder="Describe the purpose of this movement..." required></textarea>
                                    <div class="invalid-feedback">Please enter the reason for movement.</div>
                                </div>

                                <!-- Status -->
                                <div class="col-md-6">
                                    <label for="status" class="form-label fw-semibold">
                                        <i class="bi bi-shield-check text-info me-2"></i>
                                        Status <span class="text-danger">*</span>
                                    </label>
                                    <select name="status" id="status" class="form-select" required>
                                        <option value="">Select Status</option>
                                        @foreach ($statusOptions as $status)
                                            <option value="{{ $status->value }}" data-badge="{{ $status->badge_class }}"
                                                {{ $status->value == 'pending' ? 'selected' : '' }}>
                                                {{ $status->label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">Please select a status.</div>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="d-flex justify-content-end gap-2 pt-4 mt-4 border-top">
                                <button type="button" class="btn btn-outline-secondary"
                                    onclick="window.history.back();">
                                    <i class="bi bi-x-circle me-2"></i>Cancel
                                </button>
                                <button type="reset" class="btn btn-outline-warning">
                                    <i class="bi bi-arrow-counterclockwise me-2"></i>Reset
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-2"></i>Submit Movement
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Calculate allowances when inputs change
            function calculateAllowances() {
                const distance = parseFloat($('#covered_distance').val()) || 0;
                const taRate = parseFloat($('#ta_plan_id option:selected').data('rate')) || 0;
                const daRate = parseFloat($('#da_plan_id option:selected').data('rate')) || 0;

                // Calculate days difference
                const fromDate = new Date($('#from_date').val());
                const toDate = new Date($('#to_date').val());
                let days = 0;

                if (fromDate && toDate && toDate >= fromDate) {
                    const diffTime = Math.abs(toDate - fromDate);
                    days = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) || 1; // At least 1 day
                }

                // Calculate amounts
                const taAmount = distance * taRate;
                const daAmount = days * daRate;
                const totalAllowance = taAmount + daAmount;

                // Update display
                $('#ta_amount').text('৳' + taAmount.toFixed(2));
                $('#da_amount').text('৳' + daAmount.toFixed(2));
                $('#total_days').text(days);
                $('#total_allowance').text('৳' + totalAllowance.toFixed(2));
            }

            // Show TA Plan info
            $('#ta_plan_id').on('change', function() {
                const description = $(this).find('option:selected').data('description');
                $('#ta_plan_info').text(description || '');
                calculateAllowances();
            });

            // Show DA Plan info
            $('#da_plan_id').on('change', function() {
                const description = $(this).find('option:selected').data('description');
                $('#da_plan_info').text(description || '');
                calculateAllowances();
            });

            // Recalculate on distance change
            $('#covered_distance').on('input', function() {
                calculateAllowances();
            });

            // Recalculate on date change
            $('#from_date, #to_date').on('change', function() {
                calculateAllowances();

                // Validate that to_date is after from_date
                const fromDate = new Date($('#from_date').val());
                const toDate = new Date($('#to_date').val());

                if (fromDate && toDate && toDate < fromDate) {
                    toastr.warning('To date must be after or equal to from date.', 'Invalid Date Range');
                    $('#to_date').val('');
                }
            });

            // Form Submission
            $('#employeeMovementForm').on('submit', function(e) {
                e.preventDefault();

                if (this.checkValidity()) {
                    // Validate date range
                    const fromDate = new Date($('#from_date').val());
                    const toDate = new Date($('#to_date').val());

                    if (toDate < fromDate) {
                        toastr.error('To date must be after or equal to from date.', 'Validation Error');
                        return;
                    }

                    const formData = $(this).serialize();
                    console.log('Form Data:', formData);

                    // Get all form values for display
                    const employeeName = $('#employee_id option:selected').text();
                    const taAmount = $('#ta_amount').text();
                    const daAmount = $('#da_amount').text();
                    const totalAllowance = $('#total_allowance').text();

                    // Show success message with details
                    toastr.success(
                        `Employee: ${employeeName}<br>
                        TA: ${taAmount}<br>
                        DA: ${daAmount}<br>
                        Total: ${totalAllowance}`,
                        'Movement Record Submitted Successfully!', {
                            timeOut: 5000,
                            extendedTimeOut: 3000,
                            closeButton: true,
                            progressBar: true,
                            escapeHtml: false
                        }
                    );

                    // Reset form after successful submission (optional)
                    // setTimeout(() => {
                    //     this.reset();
                    //     $('.select2_list').val('').trigger('change');
                    //     calculateAllowances();
                    // }, 2000);
                } else {
                    this.classList.add('was-validated');
                    toastr.error('Please fill all required fields correctly.', 'Validation Error');
                }
            });

            // Initialize calculations on page load
            calculateAllowances();
        });
    </script>
@endpush
