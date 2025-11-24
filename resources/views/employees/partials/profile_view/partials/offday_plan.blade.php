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
            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#createOffDayPlanModal">
                <i class="mdi mdi-plus-circle me-1"></i> Create New
            </button>
        </div>
    </div>
</div>

{{-- Active Off Day Plans Section --}}
<div class="row mb-4 mt-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-success-subtle">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <i class="mdi mdi-check-circle text-success fs-5 me-2"></i>
                        <h6 class="mb-0 fw-semibold text-success">Active Off Day Plan Assignments</h6>
                    </div>
                    <span class="badge bg-success">{{ $totalActiveOffDayPlan }} Active</span>
                </div>
            </div>
            <div class="card-body p-0">
                @if ($totalActiveOffDayPlan > 0)
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
                            @php($sl=1)
                            <tbody>
                                <tr>
                                    <td><span
                                            class="badge bg-secondary-subtle text-secondary">#{{ $sl++ }}</span>
                                    </td>
                                    <td><strong>{{ $activeOffDayPLan->getPlan->name }}</strong></td>
                                    <td>
                                        @if (!empty($activeOffDayPLan->getPlan->short_name))
                                            <span
                                                class="badge bg-secondary-subtle text-secondary">{{ $activeOffDayPLan->getPlan->short_name }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong class="text-success">
                                            ৳{{ number_format($activeOffDayPLan->remuneration, 2) }}
                                        </strong>
                                    </td>
                                    <td>{{ date('d M Y', strtotime($activeOffDayPLan->from)) }}</td>
                                    <td>{{ date('d M Y', strtotime($activeOffDayPLan->to)) }}</td>
                                    <td>
                                            <span class="badge bg-success">
                                                <i class="mdi mdi-check-circle me-1"></i>{{ $activeOffDayPLan->status }}
                                            </span>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-warning"
                                                title="Edit Assignment">
                                            <i class="mdi mdi-pencil"></i> Edit
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info m-3">
                        <i class="mdi mdi-information-outline me-2"></i>
                        No active off day plan assignments found.
                    </div>
                @endif
            </div>
        </div>
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
                        <h6 class="mb-0 fw-semibold text-secondary">Previous Off Day Plan Assignments</h6>
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
                            @foreach ($previousOffDayPlans as $plan)
                                <tr class="text-muted">
                                    <td><span
                                            class="badge bg-secondary-subtle text-secondary">#{{ $plan['id'] }}</span>
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
                                                ৳{{ number_format($plan['remuneration_amount'], 2) }}
                                            </span>
                                    </td>
                                    <td>{{ date('d M Y', strtotime($plan['from'])) }}</td>
                                    <td>{{ date('d M Y', strtotime($plan['to'])) }}</td>
                                    <td>
                                            <span class="badge bg-warning-subtle text-warning">
                                                <i class="mdi mdi-clock-alert-outline me-1"></i>{{ $plan['status'] }}
                                            </span>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-danger" title="Delete Record"
                                                onclick="confirmOffDayDelete({{ $plan['id'] }})">
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
                        No previous off day plan assignments found.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@include('employees.partials.modal.create_offday_modal')
