@extends('structure.master')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 rounded-4 my-4">
        <div class="card-body p-4 p-md-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fs-4 fw-bold text-dark mb-0">Leave Application</h2>
                <a href="{{ route('leave.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                    <i class="bi bi-list me-1"></i> View Logs
                </a>
            </div>

            @php
                $isEmployee = auth()->user()->user_type === \App\Enums\UserType::Employee;
                $loggedInEmployeeId = auth()->user()->employee_id;
                $loggedInEmployeeName = auth()->user()->employee?->full_name ?? auth()->user()->name;
            @endphp

            @if(session('message'))
                <div class="alert alert-{{ session('alert-type') === 'success' ? 'success' : 'danger' }} alert-dismissible fade show mb-4" role="alert">
                    {{ session('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form id="leaveApplicationForm" method="POST" action="{{ route('leave.store') }}">
                @csrf

                {{-- Leave Type / Category Selection --}}
                <div class="mb-4">
                    <label class="form-label fw-bold text-dark fs-6 mb-2">Leave Category Type <span class="text-danger">*</span></label>
                    <div class="d-flex flex-wrap gap-3 p-3 bg-light rounded-3 border">
                        <div class="form-check form-check-inline me-4">
                            <input class="form-check-input" type="radio" name="leave_category_type" id="category_standard" value="standard" checked>
                            <label class="form-check-label fw-semibold" for="category_standard">
                                <i class="bi bi-journal-bookmark text-primary me-1"></i> Standard Leave Plan
                            </label>
                        </div>
                        <div class="form-check form-check-inline me-0">
                            <input class="form-check-input" type="radio" name="leave_category_type" id="category_compensatory" value="compensatory" disabled>
                            <label class="form-check-label fw-semibold" for="category_compensatory" id="label_category_compensatory">
                                <i class="bi bi-clock-history text-warning me-1"></i> Compensatory Leave <span id="comp_off_badge_status" class="badge bg-secondary ms-1">Select Employee First</span>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Employee Selection Section --}}
                <div class="mb-5">
                    <div class="d-flex align-items-center mb-4">
                        <div class="rounded-circle p-3 me-3 d-inline-flex align-items-center justify-content-center">
                            <i class="bi bi-person-badge fs-4"></i>
                        </div>
                        <h3 class="fs-5 fw-bold text-dark mb-0">Employee Information</h3>
                    </div>

                    <div class="row g-4">
                        {{-- Employee Selection --}}
                        <div class="col-md-6">
                            <div class="card border shadow-sm h-100">
                                <div class="card-body p-4">
                                    <label for="employee_id" class="form-label fw-semibold text-dark mb-3 d-flex align-items-center">
                                        <i class="bi bi-person text-primary me-2 fs-5"></i>
                                        <span>Select Employee</span>
                                        <span class="badge bg-danger ms-2">Required</span>
                                    </label>
                                    <select id="employee_id" name="employee_id" class="form-select form-select-lg select2_list" required @if($isEmployee) disabled @endif>
                                        <option value="">-- Select Employee --</option>
                                        @foreach ($employees as $employee)
                                            <option value="{{ $employee->id }}"
                                                {{ (old('employee_id') == $employee->id || ($isEmployee && $loggedInEmployeeId == $employee->id)) ? 'selected' : '' }}>
                                                {{ $employee->full_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if($isEmployee)
                                        <input type="hidden" name="employee_id" value="{{ $loggedInEmployeeId }}">
                                    @endif
                                    @error('employee_id')
                                        <div class="text-danger small mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Leave Plan --}}
                        <div class="col-md-6" id="plan_id_col">
                            <div class="card border shadow-sm h-100">
                                <div class="card-body p-4">
                                    <label for="plan_id" class="form-label fw-semibold text-dark mb-3 d-flex align-items-center">
                                        <i class="bi bi-calendar-check text-success me-2 fs-5"></i>
                                        <span>Leave Plan</span>
                                        <span class="badge bg-danger ms-2">Required</span>
                                    </label>
                                    <select id="plan_id" name="plan_id" class="form-select form-select-lg select2_list" required>
                                        <option value="">-- Select Employee First --</option>
                                    </select>
                                    @error('plan_id')
                                        <div class="text-danger small mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Leave Plan Balance Card --}}
                    <div class="mt-4" id="leave-plan-card-container" style="display:none;">
                        <div class="border rounded-3 p-4">
                            <div id="leave-plan-skeleton">
                                <div class="placeholder-glow">
                                    <div class="placeholder col-6 mb-2" style="height:20px;"></div>
                                    <div class="row">
                                        <div class="col-4"><div class="placeholder col-12" style="height:40px;"></div></div>
                                        <div class="col-4"><div class="placeholder col-12" style="height:40px;"></div></div>
                                        <div class="col-4"><div class="placeholder col-12" style="height:40px;"></div></div>
                                    </div>
                                </div>
                            </div>
                            <div class="leave-card-content" style="display:none;">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0 fw-bold text-dark" id="card-plan-name">-</h5>
                                    <span class="badge bg-primary rounded-pill px-3" id="card-plan-type-badge">Leave Plan</span>
                                </div>
                                <div class="row g-3 text-center">
                                    <div class="col-4">
                                        <div>
                                            <span class="d-block text-muted small mb-1">Limit (This Year)</span>
                                            <span class="h4 mb-0 fw-bold text-primary" id="card-plan-limit">0</span>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div>
                                            <span class="d-block text-muted small mb-1">Taken (This Year)</span>
                                            <span class="h4 mb-0 fw-bold text-danger" id="card-plan-taken">0</span>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div>
                                            <span class="d-block text-muted small mb-1">Remaining</span>
                                            <span class="h4 mb-0 fw-bold text-success" id="card-plan-remaining">0</span>
                                        </div>
                                    </div>
                                </div>
                                {{-- Half Day notice --}}
                                <div id="fractional-leave-notice" class="alert alert-info d-flex align-items-center mt-3 mb-0 py-2" style="display:none !important;">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <span>This plan supports <strong>half-day</strong> leave applications.</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Comp-Off Balance Card --}}
                    <div class="mt-4" id="comp-off-card-container" style="display:none;">
                        <div class="border rounded-3 p-4 bg-light-subtle">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-clock-history text-warning me-2"></i>Compensatory Leave Balance</h5>
                                <span class="badge bg-warning text-dark rounded-pill px-3">Comp-Off</span>
                            </div>
                            <div class="row g-3 text-center">
                                <div class="col-4">
                                    <div>
                                        <span class="d-block text-muted small mb-1">Earned Days</span>
                                        <span class="h4 mb-0 fw-bold text-primary" id="card-comp-earned">0</span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div>
                                        <span class="d-block text-muted small mb-1">Used Days</span>
                                        <span class="h4 mb-0 fw-bold text-danger" id="card-comp-used">0</span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div>
                                        <span class="d-block text-muted small mb-1">Remaining Balance</span>
                                        <span class="h4 mb-0 fw-bold text-success" id="card-comp-balance">0</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Leave Duration Section --}}
                <div class="mb-5">
                    <div class="d-flex align-items-center mb-4">
                        <div class="rounded-circle p-3 me-3 d-inline-flex align-items-center justify-content-center">
                            <i class="bi bi-clock-history fs-4"></i>
                        </div>
                        <h3 class="fs-5 fw-bold text-dark mb-0">Leave Duration &amp; Details</h3>
                    </div>

                    <div class="border rounded-3 p-4">

                        {{-- Off-Day Policy Notice (shown dynamically when a plan is selected) --}}
                        <div id="off-day-notice" class="alert mb-4 d-flex align-items-start gap-2" style="display:none !important;">
                            <i class="bi fs-5 mt-1" id="off-day-notice-icon"></i>
                            <div>
                                <strong id="off-day-notice-title"></strong>
                                <div class="small mt-1" id="off-day-notice-text"></div>
                            </div>
                        </div>

                        <div class="row g-4">

                            {{-- Day Type (half/full) - only shows when plan allows fractional --}}
                            <div class="col-md-4" id="day-type-col" style="display:none;">
                                <label for="day_type" class="form-label fw-semibold">Day Type</label>
                                <select id="day_type" name="day_type" class="form-select form-select-lg">
                                    <option value="full_day" selected>Full Day</option>
                                    <option value="half_day">Half Day (0.5)</option>
                                </select>
                                <div class="form-text text-muted">Half day deducts 0.5 from leave balance.</div>
                            </div>

                            {{-- Leave Count --}}
                            <div class="col-md-4">
                                <label for="leave_count" class="form-label fw-semibold">Leave Count (Days) <span class="text-danger">*</span></label>
                                <input type="number" id="leave_count" name="leave_count" class="form-control form-control-lg"
                                    placeholder="Days" min="0.5" step="0.5" value="{{ old('leave_count') }}" required>
                                <div class="form-text text-muted" id="leave-count-hint">Enter number of days.</div>
                            </div>

                            {{-- From Date --}}
                            <div class="col-md-4">
                                <label for="from" class="form-label fw-semibold">From Date <span class="text-danger">*</span></label>
                                <input type="date" id="from" name="from" class="form-control form-control-lg"
                                    value="{{ old('from') ?? date('Y-m-d') }}" required>
                            </div>

                            {{-- To Date (auto-calculated, read-only) --}}
                            <div class="col-md-4">
                                <label for="to" class="form-label fw-semibold">To Date <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="date" id="to" name="to" class="form-control form-control-lg"
                                        value="{{ old('to') }}" required>
                                    <span class="input-group-text" id="to-calculating" style="display:none;">
                                        <span class="spinner-border spinner-border-sm text-secondary"></span>
                                    </span>
                                </div>
                                <div class="form-text text-muted">Auto-calculated based on leave count and plan rules.</div>
                            </div>

                            {{-- Reason --}}
                            <div class="col-12">
                                <label for="reason" class="form-label fw-semibold">Reason <span class="text-danger">*</span></label>
                                <textarea id="reason" name="reason" class="form-control form-control-lg" rows="3"
                                    placeholder="Enter reason for leave application..." required>{{ old('reason') }}</textarea>
                            </div>

                            <input type="hidden" name="status" value="pending">
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="d-flex justify-content-end gap-3 mt-4">
                    <a href="{{ route('leave.index') }}" class="btn btn-lg btn-outline-secondary px-4 px-md-5 rounded-3">
                        <i class="bi bi-x-circle me-2"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-lg btn-dark px-4 px-md-5 rounded-3 shadow">
                        <i class="bi bi-send-fill me-2"></i>Submit Application
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
$(function () {
    // State: store selected plan's off_day_include & allow_fractional_leave
    let currentPlanMeta = { off_day_include: 'no', allow_fractional_leave: 'inactive' };
    let endDateCalculationTimer = null;
    let compOffData = { has_comp_off: false, balance_days: 0, comp_off_days: 0, used_days: 0 };

    // ── 0. Fetch Comp-Off Details for selected employee ────────────────────────
    function fetchCompOffDetails(employeeId) {
        if (!employeeId) {
            $('#category_compensatory').prop('disabled', true);
            $('#comp_off_badge_status').text('Select Employee First').removeClass('bg-success bg-danger').addClass('bg-secondary');
            return;
        }

        $.get('/get-comp-off-details/' + employeeId, function (response) {
            const data = response.data || response;
            compOffData = data;
            if (data.has_comp_off && data.balance_days > 0) {
                $('#category_compensatory').prop('disabled', false);
                $('#comp_off_badge_status')
                    .text(`Available: ${data.balance_days} Day(s)`)
                    .removeClass('bg-secondary bg-danger')
                    .addClass('bg-success');
                $('#card-comp-earned').text(data.comp_off_days);
                $('#card-comp-used').text(data.used_days);
                $('#card-comp-balance').text(data.balance_days);
            } else {
                $('#category_compensatory').prop('disabled', true);
                $('#comp_off_badge_status')
                    .text(data.has_comp_off ? '0 Balance' : 'No Comp-Off Balance')
                    .removeClass('bg-secondary bg-success')
                    .addClass('bg-danger');
                $('#card-comp-earned').text(0);
                $('#card-comp-used').text(0);
                $('#card-comp-balance').text(0);

                if ($('input[name="leave_category_type"]:checked').val() === 'compensatory') {
                    $('#category_standard').prop('checked', true).trigger('change');
                }
            }
        }).fail(function() {
            $('#category_compensatory').prop('disabled', true);
            $('#comp_off_badge_status').text('Error checking balance').removeClass('bg-success bg-secondary').addClass('bg-danger');
        });
    }

    // ── Category Switch Listener ──────────────────────────────────────────────
    $('input[name="leave_category_type"]').on('change', function () {
        const category = $(this).val();
        if (category === 'compensatory') {
            $('#plan_id_col').hide();
            $('#plan_id').prop('required', false);
            $('#leave-plan-card-container').hide();
            $('#comp-off-card-container').show();

            currentPlanMeta.off_day_include = 'no';
            updateOffDayNotice();
            scheduleEndDateCalc();
        } else {
            $('#plan_id_col').show();
            $('#plan_id').prop('required', true);
            $('#comp-off-card-container').hide();
            if ($('#plan_id').val()) {
                $('#leave-plan-card-container').show();
            }
            updateOffDayNotice();
            scheduleEndDateCalc();
        }
    });

    // ── 1. Load Plans for selected employee ──────────────────────────────────
    function loadPlans(employeeId, selectedPlan = null) {
        const $planSelect = $('#plan_id');
        $planSelect.html('<option value="">Loading…</option>').prop('disabled', true);

        if (!employeeId) {
            $planSelect.html('<option value="">-- Select Employee First --</option>').prop('disabled', false);
            return;
        }

        $.get('/get-leave-plans/' + employeeId, function (data) {
            $planSelect.html('<option value="">-- Select Leave Plan --</option>');
            if (Array.isArray(data) && data.length) {
                $.each(data, function (key, value) {
                    let isSelected = (selectedPlan == value.plan_id) ? 'selected' : '';
                    $planSelect.append(
                        `<option value="${value.plan_id}" ${isSelected}
                            data-off-day="${value.get_plan.off_day_include}"
                            data-fractional="${value.get_plan.allow_fractional_leave}">
                            ${value.get_plan.name}
                        </option>`
                    );
                });
            } else {
                $planSelect.append('<option value="" disabled>No leave plans assigned</option>');
            }
        }).fail(function () {
            $planSelect.html('<option value="">-- No Plans Found --</option>');
        }).always(function () {
            $planSelect.prop('disabled', false);
            if (typeof $planSelect.select2 === 'function') {
                $planSelect.trigger('change.select2');
            }
        });
    }

    $('#employee_id').on('change', function () {
        const empId = $(this).val();
        loadPlans(empId);
        fetchCompOffDetails(empId);
        $('#leave-plan-card-container').hide();
        resetDayType();
    });

    // Init on page load (for employees / old input)
    let initialEmployeeId = $('#employee_id').val();
    let initialPlanId = "{{ old('plan_id') }}";
    if (initialEmployeeId) {
        loadPlans(initialEmployeeId, initialPlanId);
        fetchCompOffDetails(initialEmployeeId);
    }

    // ── 2. Load Plan Balance Card + update UI when plan is selected ──────────
    $(document).on('change', '#plan_id', function () {
        const planId = $(this).val();
        const employeeId = $('#employee_id').val();
        const $selectedOpt = $(this).find('option:selected');

        if (!planId || !employeeId) {
            $('#leave-plan-card-container').hide();
            resetDayType();
            return;
        }

        // Capture plan meta from data attributes
        currentPlanMeta.off_day_include    = $selectedOpt.data('off-day')    || 'no';
        currentPlanMeta.allow_fractional_leave = $selectedOpt.data('fractional') || 'inactive';

        // Show / hide half-day toggle
        applyFractionalLeaveUI();
        updateOffDayNotice();

        // Show balance card if standard category selected
        if ($('input[name="leave_category_type"]:checked').val() === 'standard') {
            $('#leave-plan-card-container').show();
            $('#leave-plan-skeleton').show();
            $('.leave-card-content').hide();

            $.ajax({
                url: '/get-leave-details/' + employeeId + '/' + planId,
                type: 'GET',
                success: function (data) {
                    $('#leave-plan-skeleton').hide();
                    $('.leave-card-content').show();
                    $('#card-plan-name').text(data.name ?? '-');
                    $('#card-plan-limit').text(data.limit ?? 0);
                    $('#card-plan-taken').text(data.taken ?? 0);
                    let remaining = (data.limit ?? 0) - (data.taken ?? 0);
                    $('#card-plan-remaining').text(remaining >= 0 ? remaining : 0);
                }
            });
        }

        scheduleEndDateCalc();
    });

    // ── 3. Half-Day / Full-Day type handling ─────────────────────────────────
    function applyFractionalLeaveUI() {
        const isFractional = (currentPlanMeta.allow_fractional_leave === 'active');
        if (isFractional) {
            $('#day-type-col').show();
            $('#fractional-leave-notice').show();
        } else {
            $('#day-type-col').hide();
            $('#fractional-leave-notice').hide();
            $('#day_type').val('full_day');
        }
    }

    function resetDayType() {
        currentPlanMeta = { off_day_include: 'no', allow_fractional_leave: 'inactive' };
        $('#day-type-col').hide();
        $('#fractional-leave-notice').hide();
        $('#day_type').val('full_day');
        $('#off-day-notice').hide();
    }

    // ── Off-Day Policy Notice ──────────────────────────────────────────────────
    function updateOffDayNotice() {
        const $notice = $('#off-day-notice');
        const category = $('input[name="leave_category_type"]:checked').val();
        const includesOffDays = (category === 'standard' && currentPlanMeta.off_day_include === 'yes');

        if (includesOffDays) {
            $notice
                .removeClass('alert-info alert-warning')
                .addClass('alert-warning')
                .css('display', 'flex');
            $('#off-day-notice-icon')
                .removeClass('bi-calendar-x bi-calendar-check')
                .addClass('bi-calendar-check');
            $('#off-day-notice-title').text('Off Days Are Included');
            $('#off-day-notice-text').text(
                'This leave plan counts weekends and public holidays as leave days. ' +
                'The end date is calculated on a pure calendar-day basis — no days are skipped.'
            );
        } else {
            $notice
                .removeClass('alert-warning alert-info')
                .addClass('alert-info')
                .css('display', 'flex');
            $('#off-day-notice-icon')
                .removeClass('bi-calendar-check bi-calendar-x')
                .addClass('bi-calendar-x');
            $('#off-day-notice-title').text('Off Days Are Excluded');
            $('#off-day-notice-text').text(
                'This leave application excludes your configured weekends and public holidays from the leave count. ' +
                'The end date is automatically extended to skip non-working days.'
            );
        }
    }

    // When day type changes → fix leave_count to 0.5 for half day
    $('#day_type').on('change', function () {
        if ($(this).val() === 'half_day') {
            $('#leave_count').val(0.5).prop('min', 0.5).prop('step', 0.5);
            $('#leave-count-hint').text('Half day = 0.5 days deducted from balance.');
        } else {
            $('#leave_count').val('').prop('min', 1).prop('step', 1);
            $('#leave-count-hint').text('Enter number of days.');
        }
        scheduleEndDateCalc();
    });

    // ── 4. Auto-calculate End Date ────────────────────────────────────────────
    function scheduleEndDateCalc() {
        clearTimeout(endDateCalculationTimer);
        endDateCalculationTimer = setTimeout(calculateEndDate, 400);
    }

    function calculateEndDate() {
        const categoryType = $('input[name="leave_category_type"]:checked').val() || 'standard';
        const employeeId   = $('#employee_id').val();
        const planId       = $('#plan_id').val();
        const fromDate     = $('#from').val();
        const leaveCount   = parseFloat($('#leave_count').val());

        if (categoryType === 'standard' && (!employeeId || !planId || !fromDate || !leaveCount || leaveCount < 0.5)) {
            return;
        }
        if (categoryType === 'compensatory' && (!employeeId || !fromDate || !leaveCount || leaveCount < 0.5)) {
            return;
        }

        $('#to-calculating').show();

        $.ajax({
            url: "{{ route('leave.calculate-end-date') }}",
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                employee_id: employeeId,
                leave_category_type: categoryType,
                plan_id: planId,
                start_date: fromDate,
                leave_count: leaveCount
            },
            success: function (response) {
                if (response.success) {
                    $('#to').val(response.end_date);
                }
            },
            complete: function () {
                $('#to-calculating').hide();
            }
        });
    }

    // Trigger calc on leave_count or from-date change
    $('#leave_count, #from').on('change input', function () {
        scheduleEndDateCalc();
    });

    // ── 5. Form submit via Axios ─────────────────────────────────────────────
    $('#leaveApplicationForm').on('submit', function (e) {
        e.preventDefault();
        const form = this;
        const submitBtn = form.querySelector('button[type="submit"]');

        if (!$('#to').val()) {
            Swal.fire({
                icon: 'warning',
                title: 'Please wait',
                text: 'The end date is being calculated. Try again in a moment.'
            });
            return;
        }

        $('.invalid-feedback').remove();
        $('.is-invalid').removeClass('is-invalid');
        if (submitBtn) submitBtn.disabled = true;

        const formData = new FormData(form);

        axios.post(form.action, formData)
            .then(response => {
                const res = response.data;
                if (res.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: res.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = res.redirect || "{{ route('leave.index') }}";
                    });
                }
            })
            .catch(error => {
                if (submitBtn) submitBtn.disabled = false;
                if (error.response && error.response.status === 422) {
                    const errors = error.response.data.errors;
                    if (errors) {
                        Object.keys(errors).forEach(key => {
                            const input = form.querySelector(`[name="${key}"]`);
                            if (input) {
                                input.classList.add('is-invalid');
                                const feedback = document.createElement('div');
                                feedback.className = 'invalid-feedback d-block';
                                feedback.innerText = errors[key][0];
                                input.after(feedback);
                            }
                        });
                    }
                    const msg = error.response.data.message || 'Validation error. Please check your inputs.';
                    Swal.fire({ icon: 'error', title: 'Validation Error', text: msg });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.response?.data?.message || 'Something went wrong. Please try again later.'
                    });
                }
            });
    });
});
</script>
@endsection
