{{-- resources/views/leave-plans/index.blade.php --}}
@extends('structure.master')

@section('content')
    <div class="container-fluid px-4 py-5">
        <div class="row g-4">
            {{-- Leave Plan List Panel - Box Style (Wider) --}}
            <div class="col-lg-4 col-md-5">
                <div class="list-container">
                    <div class="list-header">
                        <h5 class="mb-3 fw-semibold">Leave Plan List</h5>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="selectAll">
                            <label class="form-check-label" for="selectAll">
                                Select all
                            </label>
                        </div>
                    </div>

                    <div class="list-body" id="leavePlanList">
                        {{-- List items rendered by JavaScript --}}
                    </div>

                    <div class="list-footer">
                        <button class="btn btn-primary w-100 py-2" id="submitBtn">
                            <i class="bi bi-check-circle me-2"></i>Submit Selected
                        </button>
                    </div>
                </div>
            </div>

            {{-- Details Panel - Card Style (Compact) --}}
            <div class="col-lg-8 col-md-7">
                <div class="card details-card">
                    <div class="card-header">
                        <h5 class="mb-0 fw-semibold">
                            <i class="bi bi-info-circle me-2"></i>Details
                        </h5>
                    </div>
                    <div class="card-body" id="detailsPanel">
                        <div class="empty-state">
                            <i class="bi bi-file-earmark-text"></i>
                            <p>Select a leave plan to view details</p>
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
                listItem.className = 'plan-item';
                listItem.innerHTML = `
            <div class="plan-item-content">
                <div class="form-check">
                    <input class="form-check-input plan-checkbox" type="checkbox" value="${plan.id}" id="plan${plan.id}">
                    <label class="form-check-label" for="plan${plan.id}">
                        <span class="plan-name">${plan.name}</span>
                    </label>
                </div>
                <button class="btn-view" onclick="showDetails(${plan.id})" title="View Details">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
        `;
                listContainer.appendChild(listItem);
            });
        }

        /**
         * Display details for selected leave plan
         */
        function showDetails(planId) {
            const plan = leavePlans.find(p => p.id === planId);
            if (!plan) return;

            const detailsPanel = document.getElementById('detailsPanel');

            detailsPanel.innerHTML = `
        <div class="details-header">
            <h4 class="fw-bold mb-1">${plan.name}</h4>
            <div class="text-muted">
                <span class="me-3"><i class="bi bi-tag me-1"></i>${plan.short_name}</span>
                <span class="badge ${plan.active_ind === 'active' ? 'badge-active' : 'badge-inactive'}">
                    ${plan.active_ind === 'active' ? 'Active' : 'Inactive'}
                </span>
            </div>
        </div>

        <div class="details-section">
            <h6 class="section-title">
                <i class="bi bi-info-square me-2"></i>Basic Information
            </h6>
            <div class="row g-2">
                <div class="col-md-3">
                    <div class="detail-field">
                        <label>Leave Type</label>
                        <div class="detail-value">${plan.leave_type}</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="detail-field">
                        <label>Applicable Gender</label>
                        <div class="detail-value">${plan.applicable_gender}</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="detail-field">
                        <label>Day Type</label>
                        <div class="detail-value">${plan.day_type}</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="detail-field">
                        <label>Display Serial</label>
                        <div class="detail-value">${plan.display_serial}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="details-section">
            <h6 class="section-title">
                <i class="bi bi-calendar-range me-2"></i>Leave Allocation
            </h6>
            <div class="row g-2">
                <div class="col-md-4">
                    <div class="detail-field">
                        <label>Leave Limit</label>
                        <div class="detail-value highlight">${plan.leave_limit} days</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="detail-field">
                        <label>Max No. of Days</label>
                        <div class="detail-value highlight">${plan.max_no_of_days} days</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="detail-field">
                        <label>Apply Limit</label>
                        <div class="detail-value highlight">${plan.apply_limit} times</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="details-section">
            <h6 class="section-title">
                <i class="bi bi-gear me-2"></i>Configuration
            </h6>
            <div class="row g-2">
                <div class="col-md-6">
                    <div class="detail-field">
                        <label>Allow Fractional Leave</label>
                        <div class="detail-value">
                            <span class="badge ${plan.allow_fractional_leave === 'active' ? 'badge-enabled' : 'badge-disabled'}">
                                ${plan.allow_fractional_leave === 'active' ? 'Enabled' : 'Disabled'}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="detail-field">
                        <label>Include Off Days</label>
                        <div class="detail-value">${plan.off_day_include ? 'Yes' : 'No'}</div>
                    </div>
                </div>
            </div>
        </div>
    `;
        }

        /**
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

    <style>
        /* CSS Variables for Theme Support */
        :root {
            --leave-card-bg: #ffffff;
            --leave-card-border: #e1e4e8;
            --leave-text-primary: #24292e;
            --leave-text-secondary: #6a737d;
            --leave-text-muted: #959da5;
            --leave-hover-bg: rgba(3, 102, 214, 0.06);
            --leave-active-bg: rgba(3, 102, 214, 0.15);
            --leave-accent: #108dff;
            --leave-accent-hover: #0366d6;
            --leave-gradient-start: #fafbfc;
            --leave-gradient-end: #f6f8fa;
            --leave-shadow: rgba(0, 0, 0, 0.04);
            --leave-shadow-hover: rgba(3, 102, 214, 0.1);
            --leave-border-radius: 8px;
            --leave-scrollbar-track: #f6f8fa;
            --leave-scrollbar-thumb: #d1d5da;
            --leave-badge-success-bg: #dcffe4;
            --leave-badge-success-text: #176f2c;
            --leave-badge-success-border: #9ce5a8;
            --leave-badge-info-bg: #dbedff;
            --leave-badge-info-text: #005cc5;
            --leave-badge-info-border: #a8d4ff;
            --leave-badge-inactive-bg: #f1f1f1;
            --leave-badge-inactive-text: #586069;
            --leave-badge-inactive-border: #d1d5da;
        }

        /* Dark Mode Variables */
        html[data-bs-theme='dark'] {
            --leave-card-bg: #1e293b;
            --leave-card-border: rgba(255, 255, 255, 0.1);
            --leave-text-primary: #f1f5f9;
            --leave-text-secondary: #cbd5e1;
            --leave-text-muted: #94a3b8;
            --leave-hover-bg: rgba(16, 141, 255, 0.1);
            --leave-active-bg: rgba(16, 141, 255, 0.2);
            --leave-accent: #108dff;
            --leave-accent-hover: #38a3ff;
            --leave-gradient-start: #0f172a;
            --leave-gradient-end: #1e293b;
            --leave-shadow: rgba(0, 0, 0, 0.3);
            --leave-shadow-hover: rgba(16, 141, 255, 0.2);
            --leave-scrollbar-track: #0f172a;
            --leave-scrollbar-thumb: #475569;
            --leave-badge-success-bg: rgba(34, 197, 94, 0.15);
            --leave-badge-success-text: #4ade80;
            --leave-badge-success-border: rgba(34, 197, 94, 0.3);
            --leave-badge-info-bg: rgba(16, 141, 255, 0.15);
            --leave-badge-info-text: #60a5fa;
            --leave-badge-info-border: rgba(16, 141, 255, 0.3);
            --leave-badge-inactive-bg: rgba(100, 116, 139, 0.15);
            --leave-badge-inactive-text: #94a3b8;
            --leave-badge-inactive-border: rgba(100, 116, 139, 0.3);
        }

        /* List Container - Box Style */
        .list-container {
            background: var(--leave-card-bg);
            border: 1px solid var(--leave-card-border);
            border-radius: var(--leave-border-radius);
            box-shadow: 0 4px 6px -1px var(--leave-shadow), 0 2px 4px -1px var(--leave-shadow);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .list-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--leave-card-border);
            background: linear-gradient(to bottom, var(--leave-gradient-start), var(--leave-gradient-end));
        }

        .list-header h5 {
            color: var(--leave-text-primary);
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
        }

        .list-header h5::before {
            content: '📋';
            margin-right: 0.5rem;
            font-size: 1.25rem;
        }

        .list-body {
            padding: 1rem;
            max-height: 550px;
            overflow-y: auto;
            background: var(--leave-card-bg);
        }

        .list-footer {
            padding: 1.25rem;
            border-top: 1px solid var(--leave-card-border);
            background: linear-gradient(to top, var(--leave-gradient-start), var(--leave-gradient-end));
        }

        /* Plan Item - Box Style */
        .plan-item {
            background: var(--leave-card-bg);
            border: 1px solid var(--leave-card-border);
            border-radius: 6px;
            margin-bottom: 0.75rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
        }

        .plan-item:hover {
            border-color: var(--leave-accent);
            box-shadow: 0 4px 12px var(--leave-shadow-hover);
            transform: translateY(-2px);
            background: var(--leave-hover-bg);
        }

        .plan-item-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.25rem;
        }

        .plan-name {
            display: block;
            font-weight: 600;
            color: var(--leave-text-primary);
            font-size: 0.938rem;
            margin-bottom: 0.25rem;
            letter-spacing: 0.01em;
        }

        .plan-code {
            display: inline-block;
            font-size: 0.688rem;
            color: var(--leave-accent);
            background: var(--leave-active-bg);
            padding: 0.25rem 0.625rem;
            border-radius: 4px;
            margin-left: 0.5rem;
            font-weight: 600;
            border: 1px solid var(--leave-accent);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-view {
            background: var(--leave-card-bg);
            border: 1px solid var(--leave-card-border);
            border-radius: 6px;
            padding: 0.5rem 0.875rem;
            cursor: pointer;
            transition: all 0.3s ease;
            color: var(--leave-text-secondary);
        }

        .btn-view:hover {
            background: var(--leave-accent);
            color: #ffffff;
            border-color: var(--leave-accent);
            transform: scale(1.05);
        }

        .btn-view i {
            font-size: 1rem;
        }

        /* Details Card - Compact */
        .details-card {
            border: 1px solid var(--leave-card-border);
            border-radius: var(--leave-border-radius);
            box-shadow: 0 4px 6px -1px var(--leave-shadow), 0 2px 4px -1px var(--leave-shadow);
            overflow: hidden;
            background: var(--leave-card-bg);
        }

        .details-card .card-header {
            background: linear-gradient(135deg, var(--leave-accent), var(--leave-accent-hover));
            border-bottom: 1px solid var(--leave-card-border);
            padding: 1.25rem 1.5rem;
        }

        .details-card .card-header h5 {
            color: #ffffff;
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        .details-card .card-header h5 i {
            margin-right: 0.5rem;
            font-size: 1.25rem;
        }

        .details-card .card-body {
            padding: 1rem;
            min-height: 400px;
            background: var(--leave-card-bg);
        }

        /* Empty State */
        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 350px;
            color: var(--leave-text-muted);
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.3;
            color: var(--leave-text-muted);
        }

        .empty-state p {
            font-size: 1rem;
            margin: 0;
            font-weight: 500;
        }

        /* Details Content - Compact */
        .details-header {
            padding-bottom: 0.75rem;
            border-bottom: 2px solid var(--leave-accent);
            margin-bottom: 1rem;
        }

        .details-header h4 {
            font-size: 1.25rem;
            color: var(--leave-text-primary);
            font-weight: 700;
            margin-bottom: 0.375rem;
        }

        .details-header .text-muted {
            color: var(--leave-text-secondary) !important;
        }

        .details-section {
            margin-bottom: 1rem;
            padding: 0.875rem;
            background: var(--leave-hover-bg);
            border-radius: 6px;
            border: 1px solid var(--leave-card-border);
        }

        .section-title {
            font-size: 0.813rem;
            font-weight: 700;
            color: var(--leave-text-primary);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 0.75rem;
            padding-bottom: 0.375rem;
            border-bottom: 2px solid var(--leave-card-border);
            display: flex;
            align-items: center;
        }

        .section-title i {
            color: var(--leave-accent);
            margin-right: 0.375rem;
            font-size: 1rem;
        }

        .detail-field {
            margin-bottom: 0.5rem;
        }

        .detail-field label {
            display: block;
            font-size: 0.688rem;
            color: var(--leave-text-secondary);
            font-weight: 600;
            margin-bottom: 0.25rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .detail-value {
            font-size: 0.875rem;
            color: var(--leave-text-primary);
            font-weight: 600;
        }

        .detail-value.highlight {
            font-size: 1rem;
            color: var(--leave-accent);
            font-weight: 700;
        }

        /* Badges */
        .badge {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.375rem 0.75rem;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-active {
            background-color: var(--leave-badge-success-bg);
            color: var(--leave-badge-success-text);
            border: 1px solid var(--leave-badge-success-border);
        }

        .badge-inactive {
            background-color: var(--leave-badge-inactive-bg);
            color: var(--leave-badge-inactive-text);
            border: 1px solid var(--leave-badge-inactive-border);
        }

        .badge-enabled {
            background-color: var(--leave-badge-info-bg);
            color: var(--leave-badge-info-text);
            border: 1px solid var(--leave-badge-info-border);
        }

        .badge-disabled {
            background-color: var(--leave-badge-inactive-bg);
            color: var(--leave-badge-inactive-text);
            border: 1px solid var(--leave-badge-inactive-border);
        }

        /* Submit Button */
        #submitBtn {
            background: linear-gradient(135deg, var(--leave-accent), var(--leave-accent-hover));
            border: 1px solid var(--leave-accent);
            color: #ffffff;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px var(--leave-shadow);
            text-transform: uppercase;
        }

        #submitBtn:hover {
            background: linear-gradient(135deg, var(--leave-accent-hover), #044289);
            border-color: var(--leave-accent-hover);
            box-shadow: 0 4px 12px var(--leave-shadow-hover);
            transform: translateY(-2px);
        }

        #submitBtn:active {
            transform: translateY(0);
        }

        /* Custom Checkbox */
        .form-check-input {
            border-color: var(--leave-card-border);
            background-color: var(--leave-card-bg);
        }

        .form-check-input:checked {
            background-color: var(--leave-accent);
            border-color: var(--leave-accent);
        }

        .form-check-input:focus {
            border-color: var(--leave-accent);
            box-shadow: 0 0 0 0.2rem var(--leave-shadow-hover);
        }

        .form-check-label {
            color: var(--leave-text-primary);
            font-weight: 500;
        }

        /* Scrollbar Styling */
        .list-body::-webkit-scrollbar {
            width: 8px;
        }

        .list-body::-webkit-scrollbar-track {
            background: var(--leave-scrollbar-track);
            border-radius: 4px;
        }

        .list-body::-webkit-scrollbar-thumb {
            background: var(--leave-scrollbar-thumb);
            border-radius: 4px;
        }

        .list-body::-webkit-scrollbar-thumb:hover {
            background: var(--leave-accent);
        }

        /* Page Container */
        .container-fluid {
            background: transparent;
        }

        /* Responsive Design */
        @media (max-width: 991px) {
            .list-container {
                margin-bottom: 1.5rem;
            }

            .list-body {
                max-height: 350px;
            }

            .details-card .card-body {
                min-height: 300px;
            }

            .details-section {
                padding: 1rem;
            }

            .list-header h5,
            .details-header h4 {
                font-size: 1.125rem;
            }
        }

        @media (max-width: 767px) {
            .container-fluid {
                padding: 1rem !important;
            }

            .list-header,
            .list-footer {
                padding: 1rem;
            }

            .list-body {
                max-height: 300px;
                padding: 0.75rem;
            }

            .plan-item-content {
                padding: 0.875rem;
            }

            .details-card .card-body {
                padding: 1rem;
                min-height: 250px;
            }

            .details-section {
                margin-bottom: 1rem;
                padding: 0.875rem;
            }

            .detail-field {
                margin-bottom: 0.75rem;
            }

            .btn-view {
                padding: 0.375rem 0.625rem;
            }
        }

        /* Smooth Transitions */
        * {
            transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease;
        }

        /* Animation for Items */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .plan-item {
            animation: fadeInUp 0.3s ease-out backwards;
        }

        .plan-item:nth-child(1) {
            animation-delay: 0.05s;
        }

        .plan-item:nth-child(2) {
            animation-delay: 0.1s;
        }

        .plan-item:nth-child(3) {
            animation-delay: 0.15s;
        }

        .plan-item:nth-child(4) {
            animation-delay: 0.2s;
        }

        .plan-item:nth-child(5) {
            animation-delay: 0.25s;
        }
    </style>
@endsection

