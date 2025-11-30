<div class="container-fluid px-4 py-5">
    <div class="row g-4">
        {{-- Leave Plan List Panel --}}
        <div class="col-lg-4 col-md-5">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-3 fw-semibold">📋 Leave Plan List</h5>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="selectAll">
                        <label class="form-check-label" for="selectAll">
                            Select all
                        </label>
                    </div>
                </div>

                <div class="card-body p-3" style="max-height: 550px; overflow-y: auto;" id="leavePlanList">
                    {{-- List items will be populated here --}}
                </div>

                <div class="card-footer bg-light">
                    <button class="btn btn-primary w-100 py-2" id="submitBtn">
                        <i class="bi bi-check-circle me-2"></i>Submit Selected
                    </button>
                </div>
            </div>
        </div>

        {{-- Details Panel --}}
        <div class="col-lg-8 col-md-7">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-info-circle me-2"></i>Details
                    </h5>
                </div>
                <div class="card-body" style="min-height: 400px;">
                    {{-- Empty State --}}
                    <div id="emptyState" class="d-flex flex-column align-items-center justify-content-center" style="height: 350px;">
                        <i class="bi bi-file-earmark-text text-muted" style="font-size: 3rem; opacity: 0.3;"></i>
                        <p class="text-muted mt-3">Select a leave plan to view details</p>
                    </div>

                    {{-- Details Content (Hidden by default) --}}
                    <div id="detailsContent" class="d-none">
                        <div class="pb-3 border-bottom border-primary border-2 mb-3">
                            <h4 class="fw-bold mb-1" id="planName"></h4>
                            <div class="text-muted">
                                <span class="me-3"><i class="bi bi-tag me-1"></i><span id="planShortName"></span></span>
                                <span id="planStatusBadge"></span>
                            </div>
                        </div>

                        {{-- Basic Information --}}
                        <div class="mb-3 p-3 bg-light rounded border">
                            <h6 class="text-uppercase fw-bold mb-3" style="font-size: 0.813rem; letter-spacing: 0.8px;">
                                <i class="bi bi-info-square me-2 text-primary"></i>Basic Information
                            </h6>
                            <div class="row g-2">
                                <div class="col-md-3">
                                    <label class="text-secondary text-uppercase fw-semibold mb-1" style="font-size: 0.688rem;">Leave Type</label>
                                    <div class="fw-semibold" id="leaveType"></div>
                                </div>
                                <div class="col-md-3">
                                    <label class="text-secondary text-uppercase fw-semibold mb-1" style="font-size: 0.688rem;">Applicable Gender</label>
                                    <div class="fw-semibold" id="applicableGender"></div>
                                </div>
                                <div class="col-md-3">
                                    <label class="text-secondary text-uppercase fw-semibold mb-1" style="font-size: 0.688rem;">Day Type</label>
                                    <div class="fw-semibold" id="dayType"></div>
                                </div>
                                <div class="col-md-3">
                                    <label class="text-secondary text-uppercase fw-semibold mb-1" style="font-size: 0.688rem;">Display Serial</label>
                                    <div class="fw-semibold" id="displaySerial"></div>
                                </div>
                            </div>
                        </div>

                        {{-- Leave Allocation --}}
                        <div class="mb-3 p-3 bg-light rounded border">
                            <h6 class="text-uppercase fw-bold mb-3" style="font-size: 0.813rem; letter-spacing: 0.8px;">
                                <i class="bi bi-calendar-range me-2 text-primary"></i>Leave Allocation
                            </h6>
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <label class="text-secondary text-uppercase fw-semibold mb-1" style="font-size: 0.688rem;">Leave Limit</label>
                                    <div class="fw-bold text-primary" id="leaveLimit"></div>
                                </div>
                                <div class="col-md-4">
                                    <label class="text-secondary text-uppercase fw-semibold mb-1" style="font-size: 0.688rem;">Max No. of Days</label>
                                    <div class="fw-bold text-primary" id="maxDays"></div>
                                </div>
                                <div class="col-md-4">
                                    <label class="text-secondary text-uppercase fw-semibold mb-1" style="font-size: 0.688rem;">Apply Limit</label>
                                    <div class="fw-bold text-primary" id="applyLimit"></div>
                                </div>
                            </div>
                        </div>

                        {{-- Configuration --}}
                        <div class="mb-3 p-3 bg-light rounded border">
                            <h6 class="text-uppercase fw-bold mb-3" style="font-size: 0.813rem; letter-spacing: 0.8px;">
                                <i class="bi bi-gear me-2 text-primary"></i>Configuration
                            </h6>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label class="text-secondary text-uppercase fw-semibold mb-1" style="font-size: 0.688rem;">Allow Fractional Leave</label>
                                    <div id="fractionalLeave"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-secondary text-uppercase fw-semibold mb-1" style="font-size: 0.688rem;">Include Off Days</label>
                                    <div class="fw-semibold" id="offDayInclude"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <script>
        // Dummy data matching migration structure
        const leavePlans = [{
                id: 1,
                name: 'Annual Leave',
                short_name: 'AL',
                applicable_gender: 'Both',
                day_type: 'Calculative',
                leave_type: 'Paid',
                leave_limit: 20,
                max_no_of_days: 30,
                display_serial: 1,
                apply_limit: 2,
                allow_fractional_leave: 'active',
                off_day_include: 0,
                active_ind: 'active'
            },
            {
                id: 2,
                name: 'Maternity Leave',
                short_name: 'ML',
                applicable_gender: 'Female',
                day_type: 'Fixed',
                leave_type: 'Paid',
                leave_limit: 120,
                max_no_of_days: 120,
                display_serial: 2,
                apply_limit: 1,
                allow_fractional_leave: 'inactive',
                off_day_include: 1,
                active_ind: 'active'
            },
            {
                id: 3,
                name: 'Sick Leave',
                short_name: 'SL',
                applicable_gender: 'Both',
                day_type: 'Calculative',
                leave_type: 'Paid',
                leave_limit: 15,
                max_no_of_days: 20,
                display_serial: 3,
                apply_limit: 3,
                allow_fractional_leave: 'active',
                off_day_include: 0,
                active_ind: 'active'
            },
            {
                id: 4,
                name: 'Casual Leave',
                short_name: 'CL',
                applicable_gender: 'Both',
                day_type: 'Fixed',
                leave_type: 'Paid',
                leave_limit: 10,
                max_no_of_days: 10,
                display_serial: 4,
                apply_limit: 5,
                allow_fractional_leave: 'inactive',
                off_day_include: 0,
                active_ind: 'active'
            },
            {
                id: 5,
                name: 'Paternity Leave',
                short_name: 'PL',
                applicable_gender: 'Male',
                day_type: 'Fixed',
                leave_type: 'Paid',
                leave_limit: 15,
                max_no_of_days: 15,
                display_serial: 5,
                apply_limit: 1,
                allow_fractional_leave: 'inactive',
                off_day_include: 1,
                active_ind: 'active'
            }
        ];

    /**
     * Render leave plan list items
     */
    function renderLeavePlanList() {
        const listContainer = document.getElementById('leavePlanList');
        listContainer.innerHTML = '';

        leavePlans.forEach(plan => {
            const listItem = document.createElement('div');
            listItem.className = 'card mb-2 border hover-shadow';
            listItem.style.cursor = 'pointer';
            listItem.style.transition = 'all 0.3s ease';

            listItem.innerHTML = `
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="form-check">
                            <input class="form-check-input plan-checkbox" type="checkbox" value="${plan.id}" id="plan${plan.id}">
                            <label class="form-check-label fw-semibold" for="plan${plan.id}">
                                ${plan.name}
                            </label>
                        </div>
                        <button class="btn btn-sm btn-outline-primary" onclick="showDetails(${plan.id})" title="View Details">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>
            `;

            listItem.addEventListener('mouseenter', function() {
                this.style.borderColor = 'var(--bs-primary)';
                this.style.transform = 'translateY(-2px)';
            });

            listItem.addEventListener('mouseleave', function() {
                this.style.borderColor = 'var(--bs-border-color)';
                this.style.transform = 'translateY(0)';
            });

            listContainer.appendChild(listItem);
        });
    }

    /**
     * Display details for selected leave plan
     */
    function showDetails(planId) {
        const plan = leavePlans.find(p => p.id === planId);
        if (!plan) return;

        // Hide empty state and show details
        document.getElementById('emptyState').classList.add('d-none');
        document.getElementById('detailsContent').classList.remove('d-none');

        // Populate details
        document.getElementById('planName').textContent = plan.name;
        document.getElementById('planShortName').textContent = plan.short_name;

        const statusBadge = plan.active_ind === 'active'
            ? '<span class="badge bg-success">Active</span>'
            : '<span class="badge bg-secondary">Inactive</span>';
        document.getElementById('planStatusBadge').innerHTML = statusBadge;

        document.getElementById('leaveType').textContent = plan.leave_type;
        document.getElementById('applicableGender').textContent = plan.applicable_gender;
        document.getElementById('dayType').textContent = plan.day_type;
        document.getElementById('displaySerial').textContent = plan.display_serial;

        document.getElementById('leaveLimit').textContent = plan.leave_limit + ' days';
        document.getElementById('maxDays').textContent = plan.max_no_of_days + ' days';
        document.getElementById('applyLimit').textContent = plan.apply_limit + ' times';

        const fractionalBadge = plan.allow_fractional_leave === 'active'
            ? '<span class="badge bg-info">Enabled</span>'
            : '<span class="badge bg-secondary">Disabled</span>';
        document.getElementById('fractionalLeave').innerHTML = fractionalBadge;

        document.getElementById('offDayInclude').textContent = plan.off_day_include ? 'Yes' : 'No';
    }        /**
         * Initialize event listeners
         */
        document.addEventListener('DOMContentLoaded', function() {
            renderLeavePlanList();

            // Select All functionality
            const selectAllCheckbox = document.getElementById('selectAll');
            selectAllCheckbox.addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('.plan-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });

            // Update Select All based on individual selections
            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('plan-checkbox')) {
                    const checkboxes = document.querySelectorAll('.plan-checkbox');
                    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                    selectAllCheckbox.checked = allChecked;
                }
            });

            // Submit button handler
            document.getElementById('submitBtn').addEventListener('click', function() {
                const selectedIds = Array.from(document.querySelectorAll('.plan-checkbox:checked'))
                    .map(cb => cb.value);

                if (selectedIds.length === 0) {
                    alert('Please select at least one leave plan.');
                    return;
                }

                console.log('Selected Plan IDs:', selectedIds);
                alert('Selected Leave Plans: ' + selectedIds.join(', '));
            });
        });
    </script>
