@extends('structure.master')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Main Card -->
        <div class="card shadow-lg border-0 rounded-4 my-4">
        <!-- Form Body -->
        <div class="card-body p-4 p-md-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fs-4 fw-bold text-dark mb-0">Leave Application</h2>
                <a href="{{ route('leave.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                    <i class="bi bi-list me-1"></i> View Logs
                </a>
            </div>

            @php
                $isEmployee = auth()->user()->user_type === 'Employee';
                $loggedInEmployeeId = auth()->user()->employee_id;
                $loggedInEmployeeName = auth()->user()->employee?->full_name ?? auth()->user()->name;
            @endphp

            <form id="leaveApplicationForm" method="POST" action="{{route('leave.store')}}">
                @csrf

                <!-- Employee Selection Section -->
                <div class="mb-5">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3 d-inline-flex align-items-center justify-content-center">
                            <i class="bi bi-person-badge text-primary fs-4"></i>
                        </div>
                        <h3 class="fs-5 fw-bold text-dark mb-0">Employee Information</h3>
                    </div>

                    <div class="row g-4">
                        <!-- Employee Selection -->
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

                        <!-- Leave Plan -->
                        <div class="col-md-6">
                            <div class="card border shadow-sm h-100">
                                <div class="card-body p-4">
                                    <label for="plan_id" class="form-label fw-semibold text-dark mb-3 d-flex align-items-center">
                                        <i class="bi bi-calendar-check text-success me-2 fs-5"></i>
                                        <span>Leave Plan</span>
                                        <span class="badge bg-danger ms-2">Required</span>
                                    </label>
                                    <select id="plan_id" name="plan_id" class="form-select form-select-lg select2_list" required>
                                        <option value="">-- Select Leave Plan --</option>
                                    </select>
                                    @error('plan_id')
                                        <div class="text-danger small mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Leave Plan Details Card -->
                    <div class="mt-4" id="leave-plan-card-container" style="display:none;">
                        <div class="card border-primary border-opacity-25 bg-primary bg-opacity-5 rounded-3">
                            <div class="card-body p-4">
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
                                            <div class="p-2 rounded bg-white shadow-sm">
                                                <span class="d-block text-muted small mb-1">Limit</span>
                                                <span class="h4 mb-0 fw-bold text-primary" id="card-plan-limit">0</span>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="p-2 rounded bg-white shadow-sm">
                                                <span class="d-block text-muted small mb-1">Taken</span>
                                                <span class="h4 mb-0 fw-bold text-danger" id="card-plan-taken">0</span>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="p-2 rounded bg-white shadow-sm">
                                                <span class="d-block text-muted small mb-1">Remaining</span>
                                                <span class="h4 mb-0 fw-bold text-success" id="card-plan-remaining">0</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Leave Details Section -->
                <div class="mb-5">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3 d-inline-flex align-items-center justify-content-center">
                            <i class="bi bi-clock-history text-success fs-4"></i>
                        </div>
                        <h3 class="fs-5 fw-bold text-dark mb-0">Leave Duration & Details</h3>
                    </div>

                    <div class="card border shadow-sm p-4">
                        <div class="row g-4">
                            <div class="col-md-4">
                                <label for="leave_count" class="form-label fw-semibold">Leave Count (Days) <span class="text-danger">*</span></label>
                                <input type="number" id="leave_count" name="leave_count" class="form-control form-control-lg" placeholder="Days" min="1" value="{{ old('leave_count') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label for="from" class="form-label fw-semibold">From Date <span class="text-danger">*</span></label>
                                <input type="date" id="from" name="from" class="form-control form-control-lg" value="{{ old('from') ?? date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label for="to" class="form-label fw-semibold">To Date <span class="text-danger">*</span></label>
                                <input type="date" id="to" name="to" class="form-control form-control-lg" value="{{ old('to') }}" required>
                            </div>
                            <div class="col-12">
                                <label for="reason" class="form-label fw-semibold">Reason <span class="text-danger">*</span></label>
                                <textarea id="reason" name="reason" class="form-control form-control-lg" rows="3" placeholder="Enter reason for leave application..." required>{{ old('reason') }}</textarea>
                            </div>

                            @if(!$isEmployee)
                            <div class="col-md-12">
                                <label for="status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                                <select id="status" name="status" class="form-select form-select-lg" required>
                                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ old('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ old('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>
                            @else
                                <input type="hidden" name="status" value="pending">
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
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

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(function () {
        // Load Leave Plans
        function loadPlans(employeeId, selectedPlan = null) {
            if (employeeId) {
                $.get('/get-leave-plans/' + employeeId, function (data) {
                    let $planSelect = $('#plan_id');
                    $planSelect.html('<option value="">-- Select Leave Plan --</option>');

                    $.each(data, function (key, value) {
                        let isSelected = (selectedPlan == value.plan_id) ? 'selected' : '';
                        $planSelect.append('<option value="' + value.plan_id + '" ' + isSelected + '>' +
                            value.get_plan.name +
                            '</option>');
                    });
                });
            }
        }

        $('#employee_id').on('change', function () {
            loadPlans($(this).val());
            $('#leave-plan-card-container').hide();
        });

        // Initialize for Employee or Old Input
        let initialEmployeeId = $('#employee_id').val();
        let initialPlanId = "{{ old('plan_id') }}";

        if (initialEmployeeId) {
            loadPlans(initialEmployeeId, initialPlanId);
        }

        // Load Leave Plan Details
        $(document).on('change', '#plan_id', function () {
            let planId = $(this).val();
            let employeeId = $('#employee_id').val();

            if (!planId || !employeeId) {
                $('#leave-plan-card-container').hide();
                return;
            }

            $('#leave-plan-card-container').show();
            $('#leave-plan-skeleton').show();
            $('.leave-card-content').hide();

            $.ajax({
                url: "/get-leave-details/" + employeeId + "/" + planId,
                type: "GET",
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
        });
    });
</script>
@endsection

