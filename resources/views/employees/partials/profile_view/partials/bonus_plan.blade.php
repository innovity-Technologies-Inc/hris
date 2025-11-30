<div class="container-fluid px-4 py-5">
    <div class="row g-4">
        {{-- Bonus Plan List Panel --}}
        <div class="col-lg-4 col-md-5">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-3 fw-semibold">💰 Bonus Plan List</h5>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="selectAll">
                        <label class="form-check-label" for="selectAll">
                            Select all
                        </label>
                    </div>
                </div>

                <div class="card-body p-3" style="max-height: 550px; overflow-y: auto;" id="bonusPlanList">
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
                    <div id="emptyState" class="d-flex flex-column align-items-center justify-content-center"
                        style="height: 350px;">
                        <i class="bi bi-file-earmark-text text-muted" style="font-size: 3rem; opacity: 0.3;"></i>
                        <p class="text-muted mt-3">Select a bonus plan to view details</p>
                    </div>

                    {{-- Details Content (Hidden by default) --}}
                    <div id="detailsContent" class="d-none">
                        <div class="pb-3 border-bottom border-primary border-2 mb-3">
                            <h4 class="fw-bold mb-1" id="planName"></h4>
                            <div class="text-muted">
                                <span class="me-3"><i class="bi bi-tag me-1"></i><span id="planType"></span></span>
                                <span id="planStatusBadge"></span>
                            </div>
                        </div>

                        {{-- Description (if available) --}}
                        <div id="descriptionSection" class="mb-3 p-3 bg-light rounded border d-none">
                            <h6 class="text-uppercase fw-bold mb-3" style="font-size: 0.813rem; letter-spacing: 0.8px;">
                                <i class="bi bi-file-text me-2 text-primary"></i>Description
                            </h6>
                            <div class="fw-semibold" id="planDescription"></div>
                        </div>

                        {{-- Bonus Information --}}
                        <div class="mb-3 p-3 bg-light rounded border">
                            <h6 class="text-uppercase fw-bold mb-3" style="font-size: 0.813rem; letter-spacing: 0.8px;">
                                <i class="bi bi-info-square me-2 text-primary"></i>Bonus Information
                            </h6>
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <label class="text-secondary text-uppercase fw-semibold mb-1"
                                        style="font-size: 0.688rem;">Bonus Type</label>
                                    <div class="fw-semibold" id="bonusType"></div>
                                </div>
                                <div class="col-md-4">
                                    <label class="text-secondary text-uppercase fw-semibold mb-1"
                                        style="font-size: 0.688rem;">Config Type</label>
                                    <div class="fw-semibold" id="configType"></div>
                                </div>
                                <div class="col-md-4">
                                    <label class="text-secondary text-uppercase fw-semibold mb-1"
                                        style="font-size: 0.688rem;">Status</label>
                                    <div id="statusBadge"></div>
                                </div>
                            </div>
                        </div>

                        {{-- Configuration Details --}}
                        <div class="mb-3 p-3 bg-light rounded border">
                            <h6 class="text-uppercase fw-bold mb-3" style="font-size: 0.813rem; letter-spacing: 0.8px;">
                                <i class="bi bi-gear me-2 text-primary"></i>Configuration Details
                            </h6>
                            <div class="row g-2" id="configDetails">
                                {{-- Populated dynamically --}}
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
    const bonusPlans = [{
            id: 1,
            name: 'Eid Bonus',
            description: 'Annual Eid festival bonus for all employees',
            bonus_type: 'festival',
            bonus_config_type: 'Salary Based',
            salary_rate_type: 'Basic Rate',
            overtime_multiplier: null,
            custom_overtime_rate: null,
            status: 'active'
        },
        {
            id: 2,
            name: 'Performance Bonus Q4',
            description: 'Quarterly performance-based bonus for high achievers',
            bonus_type: 'performance',
            bonus_config_type: 'Salary Based',
            salary_rate_type: 'Multiplier',
            overtime_multiplier: 1.5,
            custom_overtime_rate: null,
            status: 'active'
        },
        {
            id: 3,
            name: 'Annual Excellence Award',
            description: 'Annual bonus for outstanding employee performance',
            bonus_type: 'annual',
            bonus_config_type: 'Custom',
            salary_rate_type: null,
            overtime_multiplier: null,
            custom_overtime_rate: 5000.00,
            status: 'active'
        },
        {
            id: 4,
            name: 'Sales Incentive',
            description: 'Special incentive for sales team meeting targets',
            bonus_type: 'incentive',
            bonus_config_type: 'Salary Based',
            salary_rate_type: 'Multiplier',
            overtime_multiplier: 2.0,
            custom_overtime_rate: null,
            status: 'active'
        },
        {
            id: 5,
            name: 'Retention Bonus 2025',
            description: 'Long-term employee retention bonus program',
            bonus_type: 'retention',
            bonus_config_type: 'Custom',
            salary_rate_type: null,
            overtime_multiplier: null,
            custom_overtime_rate: 10000.00,
            status: 'inactive'
        }
    ];

    /**
     * Render bonus plan list items
     */
    function renderBonusPlanList() {
        const listContainer = document.getElementById('bonusPlanList');
        listContainer.innerHTML = '';

        bonusPlans.forEach(plan => {
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
     * Display details for selected bonus plan
     */
    function showDetails(planId) {
        const plan = bonusPlans.find(p => p.id === planId);
        if (!plan) return;

        // Hide empty state and show details
        document.getElementById('emptyState').classList.add('d-none');
        document.getElementById('detailsContent').classList.remove('d-none');

        // Populate details
        document.getElementById('planName').textContent = plan.name;

        const formatBonusType = (type) => type.charAt(0).toUpperCase() + type.slice(1);
        document.getElementById('planType').textContent = formatBonusType(plan.bonus_type);

        const statusBadge = plan.status === 'active' ?
            '<span class="badge bg-success">Active</span>' :
            '<span class="badge bg-secondary">Inactive</span>';
        document.getElementById('planStatusBadge').innerHTML = statusBadge;

        // Description
        if (plan.description) {
            document.getElementById('descriptionSection').classList.remove('d-none');
            document.getElementById('planDescription').textContent = plan.description;
        } else {
            document.getElementById('descriptionSection').classList.add('d-none');
        }

        // Bonus Information
        document.getElementById('bonusType').textContent = formatBonusType(plan.bonus_type);
        document.getElementById('configType').textContent = plan.bonus_config_type;

        const enabledBadge = plan.status === 'active' ?
            '<span class="badge bg-info">Active</span>' :
            '<span class="badge bg-secondary">Inactive</span>';
        document.getElementById('statusBadge').innerHTML = enabledBadge;

        // Configuration Details
        let configDetailsHTML = '';
        if (plan.bonus_config_type === 'Salary Based') {
            if (plan.salary_rate_type === 'Basic Rate') {
                configDetailsHTML = `
                        <div class="col-md-6">
                            <label class="text-secondary text-uppercase fw-semibold mb-1" style="font-size: 0.688rem;">Salary Rate Type</label>
                            <div class="fw-semibold">${plan.salary_rate_type}</div>
                        </div>
                    `;
            } else if (plan.salary_rate_type === 'Multiplier') {
                configDetailsHTML = `
                        <div class="col-md-6">
                            <label class="text-secondary text-uppercase fw-semibold mb-1" style="font-size: 0.688rem;">Salary Rate Type</label>
                            <div class="fw-semibold">${plan.salary_rate_type}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-secondary text-uppercase fw-semibold mb-1" style="font-size: 0.688rem;">Overtime Multiplier</label>
                            <div class="fw-bold text-primary">${plan.overtime_multiplier}x</div>
                        </div>
                    `;
            }
        } else if (plan.bonus_config_type === 'Custom') {
            configDetailsHTML = `
                    <div class="col-md-6">
                        <label class="text-secondary text-uppercase fw-semibold mb-1" style="font-size: 0.688rem;">Custom Rate</label>
                        <div class="fw-bold text-primary">৳${plan.custom_overtime_rate ? plan.custom_overtime_rate.toLocaleString() : '0.00'}</div>
                    </div>
                `;
        }

        document.getElementById('configDetails').innerHTML = configDetailsHTML;
    }

    /**
     * Initialize event listeners
     */
    document.addEventListener('DOMContentLoaded', function() {
        renderBonusPlanList();

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
                alert('Please select at least one bonus plan.');
                return;
            }

            console.log('Selected Plan IDs:', selectedIds);
            alert('Selected Bonus Plans: ' + selectedIds.join(', '));
        });
    });
</script>
