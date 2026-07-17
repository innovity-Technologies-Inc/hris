<style>
    .skeleton {
        background: linear-gradient(90deg, #ececec 25%, #f5f5f5 37%, #ececec 63%);
        animation: shimmer 1.4s infinite;
        background-size: 400% 100%;
        border-radius: 4px;
    }

    @keyframes shimmer {
        0% {
            background-position: -400px 0;
        }
        100% {
            background-position: 400px 0;
        }
    }
</style>

<div id="bonus-plan-container">
    <div class="container-fluid px-4 py-5">
        <div class="row g-4">

            {{-- ====================== --}}
            {{-- LEFT: BONUS PLAN LIST --}}
            {{-- ====================== --}}
            <div class="col-lg-4 col-md-5">
                <div class="card shadow-sm">

                    <div class="card-header bg-light">
                        <h5 class="mb-3 fw-semibold">💰 Bonus & Reward Plan List</h5>

                        @if(auth()->user()->user_type !== \App\Enums\UserType::Employee)
                            @can('employee-management.edit')
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="bonusSelectAll" onchange="handleSelectAll('bonus', this.checked)">
                                <label class="form-check-label" for="bonusSelectAll">Select all</label>
                            </div>
                            @endcan
                        @endif
                    </div>

                    {{-- FORM - Normal Submit --}}
                    <form id="" method="POST" action="{{route('employee.profile.plans.store', 'bonus-plans')}}">
                        @csrf

                        <div class="card-body p-3" style="max-height: 550px; overflow-y: auto;">
                            <input type="hidden" name="employee_id" value="{{ $employee->id }}">

                            @foreach($bonusPlans as $item)
                                <div class="d-flex align-items-center mb-2 bonus-plan-item">

                                    <input type="checkbox"
                                           class="form-check-input bonus-plan-checkbox me-2"
                                           name="plan_ids[]"
                                           value="{{ $item->id }}"
                                           id="bonus-plan-{{ $item->id }}"
                                           onchange="updateSelectAllState('bonus')"
                                           @if(isset($activeBonusPlans) && $activeBonusPlans->contains('plan_id', $item->id)) checked @endif
                                           @if(auth()->user()->user_type === \App\Enums\UserType::Employee || auth()->user()->cannot('employee-management.edit')) disabled @endif >

                                    <label for="bonus-plan-{{ $item->id }}" class="form-check-label flex-grow-1">
                                        {{ $item->name }}
                                    </label>
                                    <button type="button" class="btn btn-sm btn-outline-primary view-bonus-plan-details ms-2" onclick="viewBonusPlanDetails({{ $item->id }}, this)" title="View Details">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>

                        @if(auth()->user()->user_type !== \App\Enums\UserType::Employee)
                            @can('employee-management.edit')
                            <div class="card-footer bg-light">
                                <button class="btn btn-primary w-100 py-2" type="submit">
                                    <i class="bi bi-check-circle me-2"></i>Submit Selected
                                </button>
                            </div>
                            @endcan
                        @endif

                    </form>
                </div>
            </div>

            {{-- ====================== --}}
            {{-- RIGHT: DETAILS PANEL --}}
            {{-- ====================== --}}
            <div class="col-lg-8 col-md-7">
                <div class="card shadow-sm">

                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0 fw-semibold">
                            <i class="bi bi-info-circle me-2"></i>Details
                        </h5>
                    </div>

                    <div class="card-body" style="min-height: 400px;">

                        {{-- ====================== --}}
                        {{-- EMPTY STATE --}}
                        {{-- ====================== --}}
                        <div id="bonusPlanEmptyState"
                             class="d-flex flex-column align-items-center justify-content-center"
                             style="height: 350px;">
                            <i class="bi bi-file-earmark-text text-muted"
                               style="font-size: 3rem; opacity: 0.3;"></i>
                            <p class="text-muted mt-3">Select a bonus & reward plan to view details</p>
                        </div>

                        {{-- ====================== --}}
                        {{-- SKELETON LOADER --}}
                        {{-- ====================== --}}
                        <div id="bonusPlanSkeletonLoader" class="d-none">
                            <div class="skeleton mb-3" style="height: 30px; width: 50%;"></div>
                            <div class="skeleton mb-2" style="height: 20px; width: 30%;"></div>
                            <div class="skeleton mb-4" style="height: 20px; width: 20%;"></div>

                            <div class="row g-2">
                                <div class="col-md-4">
                                    <div class="skeleton" style="height: 70px;"></div>
                                </div>
                                <div class="col-md-4">
                                    <div class="skeleton" style="height: 70px;"></div>
                                </div>
                                <div class="col-md-4">
                                    <div class="skeleton" style="height: 70px;"></div>
                                </div>
                            </div>

                            <div class="row mt-3 g-2">
                                <div class="col-md-4">
                                    <div class="skeleton" style="height: 70px;"></div>
                                </div>
                                <div class="col-md-4">
                                    <div class="skeleton" style="height: 70px;"></div>
                                </div>
                                <div class="col-md-4">
                                    <div class="skeleton" style="height: 70px;"></div>
                                </div>
                            </div>
                        </div>

                        {{-- ====================== --}}
                        {{-- REAL DETAILS CONTENT --}}
                        {{-- ====================== --}}
                        <div id="bonusPlanDetailsContent" class="d-none">

                            <div class="pb-3 border-bottom border-primary border-2 mb-3">
                                <h4 class="fw-bold mb-1" id="bonusPlanName"></h4>
                                <div class="text-muted">
                                <span class="me-3">
                                    <i class="bi bi-tag me-1"></i>
                                    <span id="bonusPlanType"></span>
                                </span>
                                    <span id="bonusPlanStatusBadge"></span>
                                </div>
                            </div>

                            {{-- Description --}}
                            <div id="bonusDescriptionSection"
                                 class="mb-3 p-3 bg-light rounded border d-none">
                                <h6 class="text-uppercase fw-bold mb-3"
                                    style="font-size: 0.813rem; letter-spacing: 0.8px;">
                                    <i class="bi bi-file-text me-2 text-primary"></i>Description
                                </h6>
                                <div class="fw-semibold" id="bonusPlanDescription"></div>
                            </div>

                            {{-- Bonus & Reward Information --}}
                            <div class="mb-3 p-3 bg-light rounded border">
                                <h6 class="text-uppercase fw-bold mb-3"
                                    style="font-size: 0.813rem; letter-spacing: 0.8px;">
                                    <i class="bi bi-info-square me-2 text-primary"></i>Bonus & Reward Information
                                </h6>

                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <label class="text-secondary text-uppercase fw-semibold mb-1"
                                               style="font-size: 0.688rem;">Bonus & Reward Type</label>
                                        <div class="fw-semibold" id="bonusBonusType"></div>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="text-secondary text-uppercase fw-semibold mb-1"
                                               style="font-size: 0.688rem;">Config Type</label>
                                        <div class="fw-semibold" id="bonusConfigType"></div>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="text-secondary text-uppercase fw-semibold mb-1"
                                               style="font-size: 0.688rem;">Status</label>
                                        <div id="bonusStatusBadge"></div>
                                    </div>
                                </div>
                            </div>

                            {{-- Config Details --}}
                            <div class="mb-3 p-3 bg-light rounded border">
                                <h6 class="text-uppercase fw-bold mb-3"
                                    style="font-size: 0.813rem; letter-spacing: 0.8px;">
                                    <i class="bi bi-gear me-2 text-primary"></i>Configuration Details
                                </h6>

                                <div class="row g-2" id="bonusConfigDetails"></div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    if (typeof window.planScriptsInitialized === 'undefined') {
        window.planScriptsInitialized = true;

        // These functions are global and will be called by inline onclick/onchange attributes.
        // This approach is robust against AJAX content replacement issues.

        function viewBonusPlanDetails(id, button) {
            const container = $("#bonus-plan-container");
            if (!container.length) return;

            // Visual feedback for the clicked button
            container.find('.view-bonus-plan-details').removeClass('active');
            $(button).addClass('active');

            const emptyState = container.find("#bonusPlanEmptyState");
            const detailsContent = container.find("#bonusPlanDetailsContent");
            const skeletonLoader = container.find("#bonusPlanSkeletonLoader");

            emptyState.addClass("d-none");
            detailsContent.addClass("d-none");
            skeletonLoader.removeClass("d-none");

            $.ajax({
                url: `/get-bonus-plan-details/${id}`,
                type: "GET",
                success: function(res) {
                    detailsContent.find("#bonusPlanName").text(res.name);
                    detailsContent.find("#bonusPlanType").text(res.bonus_type);
                    detailsContent.find("#bonusPlanStatusBadge").html(`<span class="badge bg-${res.status === 'active' ? 'success' : 'secondary'}">${res.status}</span>`);
                    if (res.description) {
                        detailsContent.find("#bonusDescriptionSection").removeClass("d-none");
                        detailsContent.find("#bonusPlanDescription").html(res.description);
                    } else {
                        detailsContent.find("#bonusDescriptionSection").addClass("d-none");
                    }
                    detailsContent.find("#bonusBonusType").text(res.bonus_type);
                    detailsContent.find("#bonusConfigType").text(res.bonus_config_type);
                    detailsContent.find("#bonusStatusBadge").html(`<span class="badge bg-${res.status === 'active' ? 'success' : 'danger'}">${res.status}</span>`);
                    let configHtml = "";
                    if (res.bonus_config_type === "Custom") {
                        configHtml = `<div class="col-md-4"><label class="text-secondary text-uppercase fw-semibold mb-1">Custom Rate</label><div class="fw-semibold">${res.custom_rate ?? '-'}</div></div>`;
                    } else if (res.bonus_config_type === "Salary Based") {
                        configHtml += `<div class="col-md-4"><label class="text-secondary text-uppercase fw-semibold mb-1">Salary Rate Type</label><div class="fw-semibold">${res.salary_rate_type ?? '-'}</div></div>`;
                        if (res.salary_rate_type === "Multiplier") {
                            configHtml += `<div class="col-md-4"><label class="text-secondary text-uppercase fw-semibold mb-1">Multiplier</label><div class="fw-semibold">${res.multiplier ?? '-'}</div></div>`;
                        }
                    }
                    detailsContent.find("#bonusConfigDetails").html(configHtml);

                    skeletonLoader.addClass("d-none");
                    detailsContent.removeClass("d-none");
                },
                error: function() {
                    container.find("#bonusPlanSkeletonLoader").addClass("d-none");
                    container.find("#bonusPlanDetailsContent").addClass("d-none");
                    container.find("#bonusPlanEmptyState").removeClass("d-none").find("p").text("Error loading details.");
                }
            });
        }

        function viewLeavePlanDetails(id, button) {
            const container = $("#leave-plan-container");
            if (!container.length) return;

            // Visual feedback for the clicked button
            container.find('.view-leave-plan-details').removeClass('active');
            $(button).addClass('active');

            const emptyState = container.find("#leavePlanEmptyState");
            const detailsContent = container.find("#leavePlanDetailsContent");
            const skeletonLoader = container.find("#leavePlanSkeletonLoader");

            emptyState.addClass("d-none");
            detailsContent.addClass("d-none");
            skeletonLoader.removeClass("d-none");

            $.ajax({
                url: `/get-leave-plan-details/${id}`,
                type: "GET",
                success: function(plan) {
                    skeletonLoader.addClass("d-none");
                    detailsContent.removeClass("d-none");
                    detailsContent.find("#leavePlanName").text(plan.name);
                    detailsContent.find("#leavePlanShortName").text(plan.short_name);
                    detailsContent.find("#leavePlanLeaveType").text(plan.leave_type);
                    detailsContent.find("#leavePlanApplicableGender").text(plan.applicable_gender);
                    detailsContent.find("#leavePlanDisplaySerial").text(plan.display_serial);
                    detailsContent.find("#leavePlanLeaveLimit").text(plan.leave_limit);
                    detailsContent.find("#leavePlanMaxDays").text(plan.max_no_of_days);
                    detailsContent.find("#leavePlanApplyLimit").text(plan.apply_limit);
                    detailsContent.find("#leavePlanFractional").text(plan.allow_fractional_leave ? "Yes" : "No");
                    detailsContent.find("#leavePlanIncludeOffDays").text(plan.off_day_include ? "Yes" : "No");
                    detailsContent.find("#leavePlanStatusBadge").html(plan.active_ind ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>');
                },
                error: function() {
                    container.find("#leavePlanSkeletonLoader").addClass("d-none");
                    container.find("#leavePlanDetailsContent").addClass("d-none");
                    container.find("#leavePlanEmptyState").removeClass("d-none").find("p").text("Error loading details.");
                }
            });
        }

        function handleSelectAll(planType, isChecked) {
            const container = $(`#${planType}-plan-container`);
            container.find(`.${planType}-plan-checkbox`).prop("checked", isChecked);
        }

        function updateSelectAllState(planType) {
            const container = $(`#${planType}-plan-container`);
            const allCheckboxes = container.find(`.${planType}-plan-checkbox`);
            const checkedCheckboxes = container.find(`.${planType}-plan-checkbox:checked`);
            const selectAllCheckbox = container.find(`#${planType}SelectAll`);

            selectAllCheckbox.prop("checked", allCheckboxes.length > 0 && allCheckboxes.length === checkedCheckboxes.length);
        }
    }

    // A self-executing function to run state updates once the DOM is ready.
    // This needs to run every time the script is loaded, even if functions are already defined.
    (function() {
        if (typeof updateSelectAllState !== 'undefined') {
            updateSelectAllState('bonus');
            updateSelectAllState('leave.individual');
        }
    })();
</script>

