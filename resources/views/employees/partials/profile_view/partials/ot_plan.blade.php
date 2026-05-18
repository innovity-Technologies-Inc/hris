{{-- OT Plan Assignment Interface --}}

<div class="row">
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="d-flex align-items-center">
                <i class="mdi mdi-clock-plus-outline text-primary fs-4 me-2"></i>
                <h5 class="fs-16 text-dark fw-semibold mb-0">Overtime Plan Management</h5>
            </div>
        </div>
        @can('employee-management.edit')
            <div>
                {{-- Create Button to Open Modal --}}
                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#createOTPlanModal">
                    <i class="mdi mdi-plus-circle me-1"></i> Create New
                </button>
            </div>
        @endcan
    </div>
</div>

{{--
    =====================================================
    ACTIVE OT PLANS SECTION
    =====================================================
--}}
<div class="row mb-4 mt-4">
    <div class="col-12">

        {{-- Section Header --}}
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="d-flex align-items-center gap-2">
                <div class="d-flex align-items-center justify-content-center rounded-circle shadow-sm"
                    style="width: 40px; height: 40px; background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);">
                    <i class="mdi mdi-check-circle text-white"></i>
                </div>
                <h5 class="mb-0 fw-bold">Active OT Plan</h5>
            </div>
            <span class="badge bg-info shadow-sm px-3 py-2 rounded-pill">
                <i class="mdi mdi-check-decagram me-1"></i>{{ $totalActiveOtPlan }} Active
            </span>
        </div>

        @if ($totalActiveOtPlan > 0 && $activeOtPLan->getPlan)
            {{-- Active Plan Card --}}
            <div class="card border-0 shadow rounded-3 overflow-hidden">

                {{-- Card Header --}}
                <div class="card-header border-0 py-3 position-relative" style="background: var(--bs-tertiary-bg);">

                    {{-- Remove Button --}}
                    @can('employee-management.edit')
                        <div class="position-absolute top-0 end-0 mt-2 me-3">
                            <form
                                action="{{ route('employees.profile.plans.remove', ['id' => $activeOtPLan->id, 'type' => 'ot-plans']) }}"
                                method="post">
                                @csrf
                                @method('put')
                                <button type="submit" class="btn btn-sm btn-outline-danger shadow-sm removeBtn">
                                    <i class="mdi mdi-close-circle"></i> Remove
                                </button>
                            </form>
                        </div>
                    @endcan

                    {{-- Plan Name & Status --}}
                    <div class="text-center">
                        <h5 class="mb-2 fw-bold">
                            <i class="mdi mdi-clock-plus text-info me-2"></i>{{ $activeOtPLan->getPlan->name ?? 'N/A' }}
                        </h5>
                        <span class="badge bg-success shadow-sm px-3 py-1 rounded-pill">
                            <i class="mdi mdi-check-circle me-1"></i>{{ ucfirst($activeOtPLan->status) }}
                        </span>
                    </div>
                </div>

                {{-- Card Body --}}
                <div class="card-body p-3">

                    <div class="row g-2">

                        {{-- Config Type --}}
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="p-2 rounded-3 border shadow-sm"
                                style="background-color: var(--bs-primary-bg-subtle);">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="d-flex align-items-center justify-content-center rounded-circle shadow-sm"
                                        style="width: 32px; height: 32px; min-width: 32px; background-color: var(--bs-body-bg);">
                                        <i class="mdi mdi-cog text-primary"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-muted small mb-0 fw-semibold">Config Type</p>
                                        <h6 class="mb-0 fw-bold">
                                            {{ ucwords(str_replace('_', ' ', $activeOtPLan->getPlan->ot_config_type)) }}
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Rate Information --}}
                        @if ($activeOtPLan->getPlan->ot_config_type == 'salary_based')
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="p-2 rounded-3 border shadow-sm"
                                    style="background-color: var(--bs-success-bg-subtle);">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="d-flex align-items-center justify-content-center rounded-circle shadow-sm"
                                            style="width: 32px; height: 32px; min-width: 32px; background-color: var(--bs-body-bg);">
                                            <i class="mdi mdi-percent text-success"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <p class="text-muted small mb-0 fw-semibold">Salary Rate Type</p>
                                            <h6 class="mb-0 fw-bold">
                                                {{ ucwords(str_replace('_', ' ', $activeOtPLan->getPlan->salary_rate_type ?? 'N/A')) }}
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if ($activeOtPLan->getPlan->salary_rate_type == 'multiplier')
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="p-2 rounded-3 border shadow-sm"
                                        style="background-color: var(--bs-warning-bg-subtle);">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="d-flex align-items-center justify-content-center rounded-circle shadow-sm"
                                                style="width: 32px; height: 32px; min-width: 32px; background-color: var(--bs-body-bg);">
                                                <i class="mdi mdi-multiplication text-warning"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <p class="text-muted small mb-0 fw-semibold">Multiplier</p>
                                                <h6 class="mb-0 fw-bold text-warning">
                                                    {{ number_format($activeOtPLan->getPlan->overtime_multiplier ?? 0, 2) }}x
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="p-2 rounded-3 border shadow-sm"
                                    style="background-color: var(--bs-warning-bg-subtle);">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="d-flex align-items-center justify-content-center rounded-circle shadow-sm"
                                            style="width: 32px; height: 32px; min-width: 32px; background-color: var(--bs-body-bg);">
                                            <i class="mdi mdi-cash text-warning"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <p class="text-muted small mb-0 fw-semibold">Custom Rate</p>
                                            <h6 class="mb-0 fw-bold text-warning">
                                                {{ \App\HelperClass::getCurrency() ?? '৳' }}
                                                {{ number_format($activeOtPLan->getPlan->custom_overtime_rate ?? 0, 2) }}/hr
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Maximum Overtime --}}
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="p-2 rounded-3 border shadow-sm"
                                style="background-color: var(--bs-secondary-bg);">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="d-flex align-items-center justify-content-center rounded-circle shadow-sm"
                                        style="width: 32px; height: 32px; min-width: 32px; background-color: var(--bs-tertiary-bg);">
                                        <i class="mdi mdi-timer text-secondary"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-muted small mb-0 fw-semibold">Max Overtime</p>
                                        <h6 class="mb-0 fw-bold">
                                            {{ $activeOtPLan->getPlan->maximum_overtime ?? 'Unlimited' }} hrs
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Effective Dates --}}
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
                                            {{ date('d M Y', strtotime($activeOtPLan->from)) }}
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
                                            {{ date('d M Y', strtotime($activeOtPLan->to)) }}
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Description (if available) --}}
                        @if (!empty($activeOtPLan->getPlan->description))
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
                                            <p class="mb-0">{{ $activeOtPLan->getPlan->description }}</p>
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
                        <i class="mdi mdi-clock-remove-outline text-muted fs-1"></i>
                    </div>
                    <h5 class="fw-semibold mb-2">No Active OT Plans</h5>
                    <p class="text-muted mb-0">There are currently no active OT plan assignments for this employee.</p>
                </div>
            </div>

        @endif

    </div>
</div>

{{-- Previous/Expired OT Plans Section --}}
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-secondary-subtle">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <i class="mdi mdi-history text-secondary fs-5 me-2"></i>
                        <h6 class="mb-0 fw-semibold text-secondary">Previous OT Plan Assignments</h6>
                    </div>
                    <span class="badge bg-secondary">{{ $totalPreviousOtPlan }} Records</span>
                </div>
            </div>
            <div class="card-body p-0">
                @if ($totalPreviousOtPlan > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>OT Plan Name</th>
                                    <th>Effective From</th>
                                    <th>Effective To</th>
                                    <th>Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($previousOtPlans as $plan)
                                    @if ($plan->getPlan)
                                        @php($sl = 1)
                                        <tr class="text-muted">
                                            <td><span
                                                    class="badge bg-secondary-subtle text-secondary">#{{ $sl++ }}</span>
                                            </td>
                                            <td>{{ $plan->getPlan->name ?? 'N/A' }}</td>

                                            <td>{{ date('d M Y', strtotime($plan->from)) }}</td>
                                            <td>{{ date('d M Y', strtotime($plan->to)) }}</td>
                                            <td>
                                                <span class="badge bg-warning-subtle text-warning">
                                                    <i
                                                        class="mdi mdi-clock-alert-outline me-1"></i>{{ $plan->status }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @can('employee-management.edit')
                                                    <form
                                                        action="{{ route('employees.profile.plans.delete', ['type' => 'ot-plans', 'id' => $plan->id]) }}"
                                                        method="post">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" class="btn btn-sm btn-danger confirmDelete"
                                                            title="Delete Record">
                                                            <i class="mdi mdi-delete"></i> Delete
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="badge bg-light text-muted">No Actions</span>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-secondary m-3">
                        <i class="mdi mdi-information-outline me-2"></i>
                        No previous OT plan assignments found.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Include the Create Modal --}}
@include('employees.partials.modal.create_ot_modal')
