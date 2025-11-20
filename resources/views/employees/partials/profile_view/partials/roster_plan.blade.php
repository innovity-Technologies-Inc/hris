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
            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#createRosterPlanModal">
                <i class="mdi mdi-plus-circle me-1"></i> Create New
            </button>
        </div>
    </div>
</div>

{{-- Active Roster Plans Section --}}
<div class="row mb-4 mt-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-success-subtle">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <i class="mdi mdi-check-circle text-success fs-5 me-2"></i>
                        <h6 class="mb-0 fw-semibold text-success">Active Roster Plan Assignments</h6>
                    </div>
                    <span class="badge bg-success">{{ count($activeRosterPLan) }} Active</span>
                </div>
            </div>
            <div class="card-body p-0">
                @if (count($activeRosterPLan) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Plan Name</th>
                                <th>Short Name</th>
                                <th>Repetition Days</th>
                                <th>Effective From</th>
                                <th>Effective To</th>
                                <th>Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($activeRosterPLan as $plan)
                                <tr>
                                    <td><span
                                            class="badge bg-secondary-subtle text-secondary">#{{ $plan['id'] }}</span>
                                    </td>
                                    <td><strong>{{ $plan->getPlan->name }}</strong></td>
                                    <td>
                                        @if (!empty($plan->getPlan->short_name))
                                            <span
                                                class="badge bg-secondary-subtle text-secondary">{{ $plan->getPlan->short_name }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                            <span class="badge bg-primary-subtle text-primary">
                                                <i
                                                    class="mdi mdi-calendar-range me-1"></i>{{ $plan['repetition_days'] }}
                                                days
                                            </span>
                                    </td>
                                    <td>{{ date('d M Y', strtotime($plan['effective_from'])) }}</td>
                                    <td>{{ date('d M Y', strtotime($plan['effective_to'])) }}</td>
                                    <td>
                                            <span class="badge bg-success">
                                                <i class="mdi mdi-check-circle me-1"></i>{{ $plan['status'] }}
                                            </span>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-warning"
                                                title="Edit Assignment">
                                            <i class="mdi mdi-pencil"></i> Edit
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info m-3">
                        <i class="mdi mdi-information-outline me-2"></i>
                        No active roster plan assignments found.
                    </div>
                @endif
            </div>
        </div>
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
                    <span class="badge bg-secondary">{{ count($previousRosterPlans) }} Records</span>
                </div>
            </div>
            <div class="card-body p-0">
                @if (count($previousRosterPlans) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Plan Name</th>
                                <th>Short Name</th>
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
                                            class="badge bg-secondary-subtle text-secondary">#{{ $plan['id'] }}</span>
                                    </td>
                                    <td>{{ $plan['plan_name'] }}</td>
                                    <td>
                                        @if (!empty($plan->getPlan->short_name))
                                            <span
                                                class="badge bg-light text-secondary">{{ $plan->getPlan->short_name }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                            <span class="badge bg-light text-secondary">
                                                <i
                                                    class="mdi mdi-calendar-range me-1"></i>{{ $plan['repetition_days'] }}
                                                days
                                            </span>
                                    </td>
                                    <td>{{ date('d M Y', strtotime($plan['effective_from'])) }}</td>
                                    <td>{{ date('d M Y', strtotime($plan['effective_to'])) }}</td>
                                    <td>
                                            <span class="badge bg-warning-subtle text-warning">
                                                <i class="mdi mdi-clock-alert-outline me-1"></i>{{ $plan['status'] }}
                                            </span>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-danger" title="Delete Record"
                                                onclick="confirmRosterDelete({{ $plan['id'] }})">
                                            <i class="mdi mdi-delete"></i> Delete
                                        </button>
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

