{{-- Roster Plan Assignment Interface --}}
@php($sl = 1)
<div class="row">
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="d-flex align-items-center">
                <i class="mdi mdi-calendar-multiple text-primary fs-4 me-2"></i>
                <h5 class="fs-16 text-dark fw-semibold mb-0">Roster Plan Management</h5>
            </div>
        </div>
        <div>
            {{-- Create Button to Open Modal --}}
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRosterPlanModal">
                <i class="mdi mdi-plus-circle me-1"></i> Add
            </button>
        </div>
    </div>
</div>

{{--
    =====================================================
    ACTIVE ROSTER PLANS SECTION
    =====================================================
--}}
<div class="row mb-4 mt-4">
    <div class="col-12">

        {{-- Section Header --}}
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="d-flex align-items-center gap-2">
                <div class="d-flex align-items-center justify-content-center rounded-circle shadow-sm"
                    style="width: 40px; height: 40px; background: linear-gradient(135deg, #fd7e14 0%, #e36209 100%);">
                    <i class="mdi mdi-check-circle text-white"></i>
                </div>
                <h5 class="mb-0 fw-bold">Active Roster Plan</h5>
            </div>
            <span class="badge bg-warning shadow-sm px-3 py-2 rounded-pill text-dark">
                <i class="mdi mdi-check-decagram me-1"></i>{{ $totalActiveRosterPlan }} Active
            </span>
        </div>

        @if ($totalActiveRosterPlan > 0)
            {{-- Active Plan Card --}}
            <div class="card border-0 shadow rounded-3 overflow-hidden">

                {{-- Card Header --}}
                <div class="card-header border-0 py-3 position-relative" style="background: var(--bs-tertiary-bg);">

                    {{-- Remove Button --}}
                    <div class="position-absolute top-0 end-0 mt-2 me-3">
                        <form
                            action="{{ route('employees.profile.plans.remove', ['id' => $activeRosterPLan->id, 'type' => 'roster-plans']) }}"
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
                                class="mdi mdi-calendar-multiple text-warning me-2"></i>{{ $activeRosterPLan->getPlan->name }}
                        </h5>
                        @if (!empty($activeRosterPLan->getPlan->short_name))
                            <span class="badge bg-secondary shadow-sm px-3 py-1 rounded-pill me-2">
                                <i class="mdi mdi-tag me-1"></i>{{ $activeRosterPLan->getPlan->short_name }}
                            </span>
                        @endif
                        <span class="badge bg-success shadow-sm px-3 py-1 rounded-pill">
                            <i class="mdi mdi-check-circle me-1"></i>{{ ucfirst($activeRosterPLan->status) }}
                        </span>
                    </div>
                </div>

                {{-- Card Body --}}
                <div class="card-body p-3">

                    <div class="row g-2">

                        {{-- Swapping days --}}
                        <div class="col-lg-4 col-md-6">
                            <div class="p-2 rounded-3 border shadow-sm"
                                style="background-color: var(--bs-info-bg-subtle);">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="d-flex align-items-center justify-content-center rounded-circle shadow-sm"
                                        style="width: 32px; height: 32px; min-width: 32px; background-color: var(--bs-body-bg);">
                                        <i class="mdi mdi-swap-horizontal text-info"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-muted small mb-0 fw-semibold">Shift Swapping (after)</p>
                                        <h6 class="mb-0 fw-bold">
                                            {{ $activeRosterPLan->getPlan->swapping }} days
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Plan Status --}}
                        <div class="col-lg-4 col-md-6">
                            <div class="p-2 rounded-3 border shadow-sm"
                                style="background-color: var(--bs-success-bg-subtle);">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="d-flex align-items-center justify-content-center rounded-circle shadow-sm"
                                        style="width: 32px; height: 32px; min-width: 32px; background-color: var(--bs-body-bg);">
                                        <i class="mdi mdi-information text-success"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-muted small mb-0 fw-semibold">Plan Status</p>
                                        <h6 class="mb-0 fw-bold">
                                            {{ ucfirst($activeRosterPLan->getPlan->status ?? 'Active') }}
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Shift Details Section --}}
                        <div class="col-12">
                            <div class="border-top pt-2 mt-2">
                                <h6 class="fw-bold mb-2 text-muted">
                                    <i class="mdi mdi-calendar-clock me-1"></i>Shift Details
                                </h6>
                            </div>
                        </div>

                        {{-- First Shift --}}
                        <div class="col-lg-6 col-md-6">
                            <div class="p-3 rounded-3 border shadow-sm h-100"
                                style="background-color: var(--bs-success-bg-subtle);">
                                <div class="d-flex align-items-start gap-2 mb-2">
                                    <div class="d-flex align-items-center justify-content-center rounded-circle shadow-sm"
                                        style="width: 36px; height: 36px; min-width: 36px; background-color: var(--bs-body-bg);">
                                        <i class="mdi mdi-numeric-1-circle text-success fs-5"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-muted small mb-1 fw-semibold">First Shift</p>
                                        <h5 class="mb-2 fw-bold">
                                            {{ $activeRosterPLan->getPlan->getFirstShift->name ?? 'N/A' }}</h5>

                                        @if ($activeRosterPLan->getPlan->getFirstShift)
                                            <div class="d-flex gap-3 flex-wrap">
                                                <div>
                                                    <small class="text-muted d-block">Clock In</small>
                                                    <span class="badge bg-success text-white">
                                                        <i class="mdi mdi-clock-in"></i>
                                                        {{ date('h:i A', strtotime($activeRosterPLan->getPlan->getFirstShift->clock_in_time ?? '00:00')) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block">Clock Out</small>
                                                    <span class="badge bg-danger text-white">
                                                        <i class="mdi mdi-clock-out"></i>
                                                        {{ date('h:i A', strtotime($activeRosterPLan->getPlan->getFirstShift->clock_out_time ?? '00:00')) }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Second Shift --}}
                        <div class="col-lg-6 col-md-6">
                            <div class="p-3 rounded-3 border shadow-sm h-100"
                                style="background-color: var(--bs-warning-bg-subtle);">
                                <div class="d-flex align-items-start gap-2 mb-2">
                                    <div class="d-flex align-items-center justify-content-center rounded-circle shadow-sm"
                                        style="width: 36px; height: 36px; min-width: 36px; background-color: var(--bs-body-bg);">
                                        <i class="mdi mdi-numeric-2-circle text-warning fs-5"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-muted small mb-1 fw-semibold">Second Shift</p>
                                        <h5 class="mb-2 fw-bold">
                                            {{ $activeRosterPLan->getPlan->getSecondShift->name ?? 'N/A' }}</h5>

                                        @if ($activeRosterPLan->getPlan->getSecondShift)
                                            <div class="d-flex gap-3 flex-wrap">
                                                <div>
                                                    <small class="text-muted d-block">Clock In</small>
                                                    <span class="badge bg-success text-white">
                                                        <i class="mdi mdi-clock-in"></i>
                                                        {{ date('h:i A', strtotime($activeRosterPLan->getPlan->getSecondShift->clock_in_time ?? '00:00')) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block">Clock Out</small>
                                                    <span class="badge bg-danger text-white">
                                                        <i class="mdi mdi-clock-out"></i>
                                                        {{ date('h:i A', strtotime($activeRosterPLan->getPlan->getSecondShift->clock_out_time ?? '00:00')) }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

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
                                            {{ date('d M Y', strtotime($activeRosterPLan->from)) }}
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
                                            {{ date('d M Y', strtotime($activeRosterPLan->to)) }}
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Description (if available) --}}
                        @if (!empty($activeRosterPLan->getPlan->description))
                            <div class="col-12">
                                <div class="p-2 rounded-3 border shadow-sm"
                                    style="background-color: var(--bs-secondary-bg);">
                                    <div class="d-flex align-items-start gap-2">
                                        <div class="d-flex align-items-center justify-content-center rounded-circle"
                                            style="width: 32px; height: 32px; min-width: 32px; background-color: var(--bs-tertiary-bg);">
                                            <i class="mdi mdi-text text-secondary"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <p class="text-muted small mb-0 fw-semibold">Description</p>
                                            <p class="mb-0">{{ $activeRosterPLan->getPlan->description }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

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
                    <h5 class="fw-semibold mb-2">No Active Roster Plans</h5>
                    <p class="text-muted mb-0">There are currently no active roster plan assignments for this employee.
                    </p>
                </div>
            </div>

        @endif

    </div>
</div>

{{-- Previous/Expired Roster Plans Section --}}
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-secondary-subtle">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <i class="mdi mdi-history text-secondary fs-5 me-2"></i>
                        <h6 class="mb-0 fw-semibold text-secondary">Previous Roster Plan Assignments</h6>
                    </div>
                    <span class="badge bg-secondary">{{ $totalPreviousRosterPlan }} Records</span>
                </div>
            </div>
            <div class="card-body p-0">
                @if ($totalPreviousRosterPlan > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Plan Name</th>
                                    <th>First Shift</th>
                                    <th>Second Shift</th>
                                    <th>Repetition Days</th>
                                    <th>Effective From</th>
                                    <th>Effective To</th>
                                    <th>Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($previousRosterPlans as $plan)
                                    <tr class="text-muted">
                                        <td><span
                                                class="badge bg-secondary-subtle text-secondary">#{{ $plan->id }}</span>
                                        </td>
                                        <td>{{ $plan->getPlan->name }}</td>
                                        <td>{{ $plan->getPlan->getFirstShift->name }}</td>
                                        <td>{{ $plan->getPlan->getSecondShift->name }}</td>


                                        <td>
                                            <span class="badge bg-light text-secondary">
                                                <i
                                                    class="mdi mdi-calendar-range me-1"></i>{{ $plan->getPlan->repetition_days }}
                                                days
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
                        No previous roster plan assignments found.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Include the Create Modal --}}
@include('employees.partials.modal.create_roster_modal')
