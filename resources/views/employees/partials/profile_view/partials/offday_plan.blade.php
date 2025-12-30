{{-- Off Day Plan Assignment Interface --}}

<div class="row">
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="d-flex align-items-center">
                <i class="mdi mdi-calendar-blank text-primary fs-4 me-2"></i>
                <h5 class="fs-16 text-dark fw-semibold mb-0">Off Day Plan Management</h5>
            </div>
        </div>
        <div>
            {{-- Create Button to Open Modal --}}
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createOffDayPlanModal">
                <i class="mdi mdi-plus-circle me-1"></i> Add
            </button>
        </div>
    </div>
</div>

{{--
    =====================================================
    ACTIVE OFF DAY PLANS SECTION
    =====================================================
--}}
<div class="row mb-4 mt-4">
    <div class="col-12">

        {{-- Section Header --}}
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="d-flex align-items-center gap-2">
                <div class="d-flex align-items-center justify-content-center rounded-circle shadow-sm"
                    style="width: 40px; height: 40px; background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                    <i class="mdi mdi-check-circle text-white"></i>
                </div>
                <h5 class="mb-0 fw-bold">Active Off Day Plan</h5>
            </div>
            <span class="badge bg-success shadow-sm px-3 py-2 rounded-pill">
                <i class="mdi mdi-check-decagram me-1"></i>{{ $totalActiveOffDayPlan }} Active
            </span>
        </div>


        @if ($totalActiveOffDayPlan > 0)
            {{-- Active Plan Card --}}
            <div class="card border-0 shadow rounded-3 overflow-hidden">

                {{-- Card Header --}}
                <div class="card-header border-0 py-3 position-relative" style="background: var(--bs-tertiary-bg);">

                    {{-- Remove Button --}}
                    <div class="position-absolute top-0 end-0 mt-2 me-3">
                        <form
                            action="{{ route('employees.profile.plans.remove', ['id' => $activeOffDayPLan->id, 'type' => 'offday-plans']) }}"
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
                            <i
                                class="mdi mdi-calendar-check text-success me-2"></i>{{ $activeOffDayPLan->getPlan->name }}
                        </h5>
                        <span class="badge bg-success shadow-sm px-3 py-1 rounded-pill">
                            <i class="mdi mdi-check-circle me-1"></i>{{ ucfirst($activeOffDayPLan->status) }}
                        </span>
                    </div>
                </div>

                {{-- Card Body --}}
                <div class="card-body p-3">

                    <div class="row g-2">

                        {{-- Shift Information --}}
                        <div class="col-12 mb-2">
                            <div class="p-2 rounded-3 border shadow-sm"
                                style="background-color: var(--bs-primary-bg-subtle);">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="d-flex align-items-center justify-content-center rounded-circle shadow-sm"
                                        style="width: 32px; height: 32px; min-width: 32px; background-color: var(--bs-body-bg);">
                                        <i class="mdi mdi-clock-outline text-primary"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-muted small mb-0 fw-semibold">Associated Shift</p>
                                        <h6 class="mb-0 fw-bold">
                                            @if ($activeOffDayPLan->getPlan->getShift)
                                                {{ $activeOffDayPLan->getPlan->getShift->name }}
                                            @else
                                                <span class="text-muted">No shift assigned</span>
                                            @endif
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Timing Information - 4 Columns with Color --}}
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="p-2 rounded-3 border shadow-sm"
                                style="background-color: var(--bs-success-bg-subtle);">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="d-flex align-items-center justify-content-center rounded-circle shadow-sm"
                                        style="width: 32px; height: 32px; min-width: 32px; background-color: var(--bs-body-bg);">
                                        <i class="mdi mdi-clock-start text-success"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-muted small mb-0 fw-semibold">Clock In</p>
                                        <h6 class="mb-0 fw-bold">
                                            @if ($activeOffDayPLan->getPlan->getShift)
                                                {{ date('h:i A', strtotime($activeOffDayPLan->getPlan->getShift->clock_in_time ?? '00:00:00')) }}
                                            @else
                                                N/A
                                            @endif
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
                                        <i class="mdi mdi-clock-end text-danger"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-muted small mb-0 fw-semibold">Clock Out</p>
                                        <h6 class="mb-0 fw-bold">
                                            @if ($activeOffDayPLan->getPlan->getShift)
                                                {{ date('h:i A', strtotime($activeOffDayPLan->getPlan->getShift->clock_out_time ?? '00:00:00')) }}
                                            @else
                                                N/A
                                            @endif
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
                                        <i class="mdi mdi-clock-alert text-warning"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-muted small mb-0 fw-semibold">Grace Time</p>
                                        <h6 class="mb-0 fw-bold">
                                            @if ($activeOffDayPLan->getPlan->getShift)
                                                {{ $activeOffDayPLan->getPlan->getShift->grace_time ?? '0' }} min
                                            @else
                                                N/A
                                            @endif
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
                                        <i class="mdi mdi-clock-check text-info"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-muted small mb-0 fw-semibold">Early Out Grace</p>
                                        <h6 class="mb-0 fw-bold">
                                            @if ($activeOffDayPLan->getPlan->getShift)
                                                {{ $activeOffDayPLan->getPlan->getShift->early_out_grace_minutes ?? '0' }}
                                                min
                                            @else
                                                N/A
                                            @endif
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Date & Remuneration - Minimal Subtle Design --}}
                        <div class="col-lg-4 col-md-4">
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
                                            {{ date('d M Y', strtotime($activeOffDayPLan->from)) }}
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4">
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
                                            {{ date('d M Y', strtotime($activeOffDayPLan->to)) }}
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4">
                            <div class="p-2 rounded-3 border-2 shadow-sm h-100"
                                style="border: 2px solid var(--bs-success) !important; background-color: var(--bs-success-bg-subtle);">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="d-flex align-items-center justify-content-center rounded-circle"
                                        style="width: 32px; height: 32px; min-width: 32px; background-color: var(--bs-body-bg);">
                                        <i class="mdi mdi-cash-multiple text-success"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-muted small mb-0 fw-semibold">Remuneration</p>
                                        <h6 class="mb-0 fw-bold text-success">
                                            @php
                                                $plan = $activeOffDayPLan->getPlan;
                                                if ($plan->offday_config_type === 'Salary Based') {
                                                    if ($plan->salary_rate_type === 'Basic Rate') {
                                                        echo 'Salary Based - Basic Rate';
                                                    } else {
                                                        echo 'Salary Based - ' .
                                                            number_format($plan->offday_multiplier, 2) .
                                                            'x';
                                                    }
                                                } else {
                                                    echo (\App\HelperClass::getCurrency() ?? '৳') .
                                                        ' ' .
                                                        number_format($plan->custom_offday_rate ?? 0, 2) .
                                                        '/hr';
                                                }
                                            @endphp
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
                        <i class="mdi mdi-calendar-remove text-muted fs-1"></i>
                    </div>
                    <h5 class="fw-semibold mb-2">No Active Off Day Plans</h5>
                    <p class="text-muted mb-0">There are currently no active off day plan assignments for this
                        employee.</p>
                </div>
            </div>
        @endif

    </div>
</div>

{{-- Previous/Expired Off Day Plans Section --}}
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-secondary-subtle">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <i class="mdi mdi-history text-secondary fs-5 me-2"></i>
                        <h6 class="mb-0 fw-semibold text-secondary">Previous Off Day Work Plans</h6>
                    </div>
                    <span class="badge bg-secondary">{{ $totalPreviousOffDayPlan }} Records</span>
                </div>
            </div>
            <div class="card-body p-0">
                @if ($totalPreviousOffDayPlan > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Plan Name</th>
                                    <th>Short Name</th>
                                    <th>Remuneration</th>
                                    <th>Effective From</th>
                                    <th>Effective To</th>
                                    <th>Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $sl = 1 @endphp
                                @foreach ($previousOffDayPlans as $plan)
                                    <tr class="text-muted">
                                        <td><span
                                                class="badge bg-secondary-subtle text-secondary">#{{ $sl++ }}</span>
                                        </td>
                                        <td>{{ $plan->getPlan->name }}</td>
                                        <td>
                                            @if (!empty($plan->getPlan->short_name))
                                                <span
                                                    class="badge bg-light text-secondary">{{ $plan->getPlan->short_name }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="text-success">
                                                {{ \App\HelperClass::getCurrency() ?? '৳' }}
                                                {{ number_format($plan->getPlan->remuneration, 2) }}
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
                                                action="{{ route('employees.profile.plans.delete', ['type' => 'offday-plans', 'id' => $plan->id]) }}"
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
                        No previous off day plan assignments found.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@include('employees.partials.modal.create_offday_modal')
