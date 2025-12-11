{{-- Shift Plan Assignment Interface --}}

<div class="row">
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="d-flex align-items-center">
                <i class="mdi mdi-clock-outline text-primary fs-4 me-2"></i>
                <h5 class="fs-16 text-dark fw-semibold mb-0">Shift Plan Management</h5>
            </div>
        </div>
        <div>
            {{-- Create Button to Open Modal --}}
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createShiftPlanModal">
                <i class="mdi mdi-plus-circle me-1"></i> Add
            </button>
        </div>
    </div>
</div>

{{--
    =====================================================
    ACTIVE SHIFT PLANS SECTION
    =====================================================
--}}
<div class="row mb-4 mt-4">
    <div class="col-12">

        {{-- Section Header --}}
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="d-flex align-items-center gap-2">
                <div class="d-flex align-items-center justify-content-center rounded-circle shadow-sm"
                    style="width: 40px; height: 40px; background: linear-gradient(135deg, #6f42c1 0%, #5a32a3 100%);">
                    <i class="mdi mdi-check-circle text-white"></i>
                </div>
                <h5 class="mb-0 fw-bold">Active Shift Plan</h5>
            </div>
            <span class="badge bg-primary shadow-sm px-3 py-2 rounded-pill">
                <i class="mdi mdi-check-decagram me-1"></i>{{ $totalActiveShiftPlan }} Active
            </span>
        </div>

        @if ($totalActiveShiftPlan > 0)
            {{-- Active Plan Card --}}
            <div class="card border-0 shadow rounded-3 overflow-hidden">

                {{-- Card Header --}}
                <div class="card-header border-0 py-3 position-relative" style="background: var(--bs-tertiary-bg);">

                    {{-- Remove Button --}}
                    <div class="position-absolute top-0 end-0 mt-2 me-3">
                        <form
                            action="{{ route('employees.profile.plans.remove', ['id' => $activeShiftPLan->id, 'type' => 'shift-plans']) }}"
                            method="post">
                            @csrf
                            @method('put')
                            <button type="submit" class="btn btn-sm btn-outline-danger shadow-sm removeBtn">
                                <i class="mdi mdi-close-circle"></i> Remove
                            </button>
                        </form>
                    </div>

                    {{-- Plan Name & Status --}}
                    <div class="text-center">
                        <h5 class="mb-2 fw-bold">
                            <i class="mdi mdi-clock-check text-primary me-2"></i>{{ $activeShiftPLan->getPlan->name }}
                        </h5>
                        <span class="badge bg-success shadow-sm px-3 py-1 rounded-pill">
                            <i class="mdi mdi-check-circle me-1"></i>{{ ucfirst($activeShiftPLan->status) }}
                        </span>
                    </div>
                </div>

                {{-- Card Body --}}
                <div class="card-body p-3">

                    <div class="row g-2">

                        {{-- Clock In/Out Times --}}
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="p-2 rounded-3 border shadow-sm"
                                style="background-color: var(--bs-success-bg-subtle);">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="d-flex align-items-center justify-content-center rounded-circle shadow-sm"
                                        style="width: 32px; height: 32px; min-width: 32px; background-color: var(--bs-body-bg);">
                                        <i class="mdi mdi-clock-in text-success"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-muted small mb-0 fw-semibold">Clock In Time</p>
                                        <h6 class="mb-0 fw-bold">
                                            {{ date('h:i A', strtotime($activeShiftPLan->getPlan->clock_in_time ?? '00:00:00')) }}
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="p-2 rounded-3 border shadow-sm"
                                style="background-color: var(--bs-danger-bg-subtle);">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="d-flex align-items-center justify-content-center rounded-circle shadow-sm"
                                        style="width: 32px; height: 32px; min-width: 32px; background-color: var(--bs-body-bg);">
                                        <i class="mdi mdi-clock-out text-danger"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-muted small mb-0 fw-semibold">Clock Out Time</p>
                                        <h6 class="mb-0 fw-bold">
                                            {{ date('h:i A', strtotime($activeShiftPLan->getPlan->clock_out_time ?? '00:00:00')) }}
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Grace Time & Late Minutes --}}
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="p-2 rounded-3 border shadow-sm"
                                style="background-color: var(--bs-warning-bg-subtle);">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="d-flex align-items-center justify-content-center rounded-circle shadow-sm"
                                        style="width: 32px; height: 32px; min-width: 32px; background-color: var(--bs-body-bg);">
                                        <i class="mdi mdi-clock-alert text-warning"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-muted small mb-0 fw-semibold">Grace Time</p>
                                        <h6 class="mb-0 fw-bold">
                                            {{ $activeShiftPLan->getPlan->grace_time ?? '0' }} min
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="p-2 rounded-3 border shadow-sm"
                                style="background-color: var(--bs-warning-bg-subtle);">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="d-flex align-items-center justify-content-center rounded-circle shadow-sm"
                                        style="width: 32px; height: 32px; min-width: 32px; background-color: var(--bs-body-bg);">
                                        <i class="mdi mdi-alert-circle text-warning"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-muted small mb-0 fw-semibold">Late After</p>
                                        <h6 class="mb-0 fw-bold">
                                            {{ $activeShiftPLan->getPlan->late_after_minutes ?? '0' }} min
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Attendance Treatment Minutes --}}
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="p-2 rounded-3 border shadow-sm"
                                style="background-color: var(--bs-success-bg-subtle);">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="d-flex align-items-center justify-content-center rounded-circle shadow-sm"
                                        style="width: 32px; height: 32px; min-width: 32px; background-color: var(--bs-body-bg);">
                                        <i class="mdi mdi-calendar-check text-success"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-muted small mb-0 fw-semibold">Full Day</p>
                                        <h6 class="mb-0 fw-bold">
                                            {{ $activeShiftPLan->getPlan->treat_as_full_day_minutes ?? '0' }} min
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="p-2 rounded-3 border shadow-sm"
                                style="background-color: var(--bs-info-bg-subtle);">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="d-flex align-items-center justify-content-center rounded-circle shadow-sm"
                                        style="width: 32px; height: 32px; min-width: 32px; background-color: var(--bs-body-bg);">
                                        <i class="mdi mdi-calendar-half text-info"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-muted small mb-0 fw-semibold">Half Day</p>
                                        <h6 class="mb-0 fw-bold">
                                            {{ $activeShiftPLan->getPlan->treat_as_half_day_minutes ?? '0' }} min
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Excessive Late & Early Out --}}
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="p-2 rounded-3 border shadow-sm"
                                style="background-color: var(--bs-danger-bg-subtle);">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="d-flex align-items-center justify-content-center rounded-circle shadow-sm"
                                        style="width: 32px; height: 32px; min-width: 32px; background-color: var(--bs-body-bg);">
                                        <i class="mdi mdi-alarm-panel text-danger"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-muted small mb-0 fw-semibold">Excessive Late</p>
                                        <h6 class="mb-0 fw-bold">
                                            {{ $activeShiftPLan->getPlan->excessive_late_after_minutes ?? '0' }} min
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="p-2 rounded-3 border shadow-sm"
                                style="background-color: var(--bs-warning-bg-subtle);">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="d-flex align-items-center justify-content-center rounded-circle shadow-sm"
                                        style="width: 32px; height: 32px; min-width: 32px; background-color: var(--bs-body-bg);">
                                        <i class="mdi mdi-exit-run text-warning"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-muted small mb-0 fw-semibold">Early Out Grace</p>
                                        <h6 class="mb-0 fw-bold">
                                            {{ $activeShiftPLan->getPlan->early_out_grace_minutes ?? '0' }} min
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Break Times Section --}}
                        @if (
                            $activeShiftPLan->getPlan->breakfast_status ||
                                $activeShiftPLan->getPlan->lunch_status ||
                                $activeShiftPLan->getPlan->snacks_status ||
                                $activeShiftPLan->getPlan->dinner_status)
                            <div class="col-12">
                                <div class="border-top pt-2 mt-2">
                                    <h6 class="fw-bold mb-2 text-muted">
                                        <i class="mdi mdi-food me-1"></i>Break Times
                                    </h6>
                                </div>
                            </div>

                            {{-- Breakfast --}}
                            @if ($activeShiftPLan->getPlan->breakfast_status)
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="p-2 rounded-3 border shadow-sm"
                                        style="background-color: var(--bs-secondary-bg);">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="d-flex align-items-center justify-content-center rounded-circle shadow-sm"
                                                style="width: 32px; height: 32px; min-width: 32px; background-color: var(--bs-tertiary-bg);">
                                                <i class="mdi mdi-coffee text-secondary"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <p class="text-muted small mb-0 fw-semibold">Breakfast</p>
                                                <h6 class="mb-0 fw-bold small">
                                                    {{ date('h:i A', strtotime($activeShiftPLan->getPlan->breakfast_start_time ?? '00:00')) }}
                                                    -
                                                    {{ date('h:i A', strtotime($activeShiftPLan->getPlan->breakfast_end_time ?? '00:00')) }}
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Lunch --}}
                            @if ($activeShiftPLan->getPlan->lunch_status)
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="p-2 rounded-3 border shadow-sm"
                                        style="background-color: var(--bs-secondary-bg);">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="d-flex align-items-center justify-content-center rounded-circle shadow-sm"
                                                style="width: 32px; height: 32px; min-width: 32px; background-color: var(--bs-tertiary-bg);">
                                                <i class="mdi mdi-food text-secondary"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <p class="text-muted small mb-0 fw-semibold">Lunch</p>
                                                <h6 class="mb-0 fw-bold small">
                                                    {{ date('h:i A', strtotime($activeShiftPLan->getPlan->lunch_start_time ?? '00:00')) }}
                                                    -
                                                    {{ date('h:i A', strtotime($activeShiftPLan->getPlan->lunch_end_time ?? '00:00')) }}
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Snacks --}}
                            @if ($activeShiftPLan->getPlan->snacks_status)
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="p-2 rounded-3 border shadow-sm"
                                        style="background-color: var(--bs-secondary-bg);">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="d-flex align-items-center justify-content-center rounded-circle shadow-sm"
                                                style="width: 32px; height: 32px; min-width: 32px; background-color: var(--bs-tertiary-bg);">
                                                <i class="mdi mdi-cookie text-secondary"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <p class="text-muted small mb-0 fw-semibold">Snacks</p>
                                                <h6 class="mb-0 fw-bold small">
                                                    {{ date('h:i A', strtotime($activeShiftPLan->getPlan->snacks_start_time ?? '00:00')) }}
                                                    -
                                                    {{ date('h:i A', strtotime($activeShiftPLan->getPlan->snacks_end_time ?? '00:00')) }}
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Dinner --}}
                            @if ($activeShiftPLan->getPlan->dinner_status)
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="p-2 rounded-3 border shadow-sm"
                                        style="background-color: var(--bs-secondary-bg);">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="d-flex align-items-center justify-content-center rounded-circle shadow-sm"
                                                style="width: 32px; height: 32px; min-width: 32px; background-color: var(--bs-tertiary-bg);">
                                                <i class="mdi mdi-silverware-fork-knife text-secondary"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <p class="text-muted small mb-0 fw-semibold">Dinner</p>
                                                <h6 class="mb-0 fw-bold small">
                                                    {{ date('h:i A', strtotime($activeShiftPLan->getPlan->dinner_start_time ?? '00:00')) }}
                                                    -
                                                    {{ date('h:i A', strtotime($activeShiftPLan->getPlan->dinner_end_time ?? '00:00')) }}
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif

                        {{-- Effective Dates --}}
                        <div class="col-lg-6 col-md-6">
                            <div class="p-2 rounded-3 border shadow-sm h-100"
                                style="background-color: var(--bs-secondary-bg);">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="d-flex align-items-center justify-content-center rounded-circle"
                                        style="width: 32px; height: 32px; min-width: 32px; background-color: var(--bs-tertiary-bg);">
                                        <i class="mdi mdi-calendar-start text-secondary"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-muted small mb-0 fw-semibold">Effective From</p>
                                        <h6 class="mb-0 fw-bold">
                                            {{ date('d M Y', strtotime($activeShiftPLan->from)) }}
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6">
                            <div class="p-2 rounded-3 border shadow-sm h-100"
                                style="background-color: var(--bs-secondary-bg);">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="d-flex align-items-center justify-content-center rounded-circle"
                                        style="width: 32px; height: 32px; min-width: 32px; background-color: var(--bs-tertiary-bg);">
                                        <i class="mdi mdi-calendar-end text-secondary"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-muted small mb-0 fw-semibold">Effective To</p>
                                        <h6 class="mb-0 fw-bold">
                                            {{ date('d M Y', strtotime($activeShiftPLan->to)) }}
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </div>
        @else
            {{-- Empty State --}}
            <div class="card border-0 shadow rounded-3">
                <div class="card-body text-center py-4">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
                        style="width: 80px; height: 80px; background-color: var(--bs-tertiary-bg);">
                        <i class="mdi mdi-clock-remove-outline text-muted fs-1"></i>
                    </div>
                    <h5 class="fw-semibold mb-2">No Active Shift Plans</h5>
                    <p class="text-muted mb-0">There are currently no active shift plan assignments for this employee.
                    </p>
                </div>
            </div>

        @endif

    </div>
</div>

{{-- Previous/Expired Shift Plans Section --}}
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-secondary-subtle">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <i class="mdi mdi-history text-secondary fs-5 me-2"></i>
                        <h6 class="mb-0 fw-semibold text-secondary">Previous Shift Plan Assignments</h6>
                    </div>
                    <span class="badge bg-secondary">{{ $totalPreviousShiftPlan }} Records</span>
                </div>
            </div>
            <div class="card-body p-0">
                @if ($totalPreviousShiftPlan > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Shift Name</th>
                                    <th>Clock In</th>
                                    <th>Clock Out</th>
                                    <th>Effective From</th>
                                    <th>Effective To</th>
                                    <th>Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($previousShiftPlans as $plan)
                                    <tr class="text-muted">
                                        <td><span
                                                class="badge bg-secondary-subtle text-secondary">#{{ $plan->id }}</span>
                                        </td>
                                        <td>{{ $plan->getPlan->name }}</td>
                                        <td>
                                            <span class="badge bg-light text-secondary">
                                                <i
                                                    class="mdi mdi-clock-in me-1"></i>{{ \Carbon\Carbon::parse($plan->getPlan->clock_in)->format('h:i A') }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-secondary">
                                                <i
                                                    class="mdi mdi-clock-out me-1"></i>{{ \Carbon\Carbon::parse($plan->getPlan->clock_out)->format('h:i A') }}
                                            </span>
                                        </td>
                                        <td>{{ date('d M Y', strtotime($plan->from)) }}</td>
                                        <td>{{ date('d M Y', strtotime($plan->to)) }}</td>
                                        <td>
                                            <span class="badge bg-warning-subtle text-warning">
                                                <i class="mdi mdi-clock-alert-outline me-1"></i>{{ $plan->status }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <form
                                                action="{{ route('employees.profile.plans.delete', ['type' => 'shift-plans', 'id' => $plan->id]) }}"
                                                method="post">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="btn btn-sm btn-danger confirmDelete"
                                                    title="Delete Record">
                                                    <i class="mdi mdi-delete"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-secondary m-3">
                        <i class="mdi mdi-information-outline me-2"></i>
                        No previous shift plan assignments found.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@include('employees.partials.modal.create_shift_modal')
