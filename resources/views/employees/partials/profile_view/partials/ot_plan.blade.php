{{-- OT Plan Assignment Interface --}}

<div class="row">
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="d-flex align-items-center">
                <i class="mdi mdi-clock-plus-outline text-primary fs-4 me-2"></i>
                <h5 class="fs-16 text-dark fw-semibold mb-0">Overtime Plan Management</h5>
            </div>
        </div>
        <div>
            {{-- Create Button to Open Modal --}}
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createOTPlanModal">
                <i class="mdi mdi-plus-circle me-1"></i> Create New
            </button>
        </div>
    </div>
</div>

{{-- Active OT Plans Section --}}
<div class="row mb-4 mt-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-success-subtle">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <i class="mdi mdi-check-circle text-success fs-5 me-2"></i>
                        <h6 class="mb-0 fw-semibold text-success">Active OT Plan Assignments</h6>
                    </div>
                    <span class="badge bg-success">{{ $totalActiveOtPlan }} Active</span>
                </div>
            </div>
            <div class="card-body p-0">
                @if ($totalActiveOtPlan > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>OT Plan Name</th>
                                <th>OT Type</th>
                                <th>Config Type</th>
                                <th>Rate</th>
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
                                <td><strong>{{ $activeOtPLan->getPlan->name }}</strong></td>
                                <td>
                                            <span class="badge bg-info-subtle text-info">
                                                {{ ucwords(str_replace('_', ' ', $activeOtPLan->getPlan->ot_type)) }}
                                            </span>
                                </td>
                                <td>
                                    @if ($activeOtPLan->getPlan->ot_config_type == 'salary_based')
                                        <span class="badge bg-primary-subtle text-primary">Salary Based</span>
                                    @else
                                        <span class="badge bg-success-subtle text-success">Custom</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($activeOtPLan->getPlan->ot_config_type == 'salary_based')
                                        @if (isset($activeOtPLan->getPlan->salary_rate_type) &&
                                                $activeOtPLan->getPlan->salary_rate_type == 'multiplier' &&
                                                isset($activeOtPLan->getPlan->overtime_multiplier))
                                            <span class="badge bg-success-subtle text-success">
                                                        {{ number_format($activeOtPLan->getPlan->overtime_multiplier, 2) }}x
                                                    </span>
                                        @elseif (isset($activeOtPLan->getPlan->salary_rate_type) && $activeOtPLan->getPlan->salary_rate_type == 'basic_rate')
                                            <span class="badge bg-info-subtle text-info">Basic Rate</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    @else
                                        @if (isset($activeOtPLan->getPlan->custom_overtime_rate))
                                            <span class="badge bg-warning-subtle text-warning">
                                                        ৳{{ number_format($activeOtPLan->getPlan->custom_overtime_rate, 2) }}/hr
                                                    </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    @endif
                                </td>
                                <td>{{ date('d M Y', strtotime($activeOtPLan->from)) }}</td>
                                <td>{{ date('d M Y', strtotime($activeOtPLan->to)) }}</td>
                                <td>
                                            <span class="badge bg-success">
                                                <i class="mdi mdi-check-circle me-1"></i>{{ $activeOtPLan->status }}
                                            </span>
                                </td>
                                <td class="text-center">
                                    <form
                                        action="{{route('employees.profile.plans.remove', ['id' => $activeOtPLan->id, 'type' => 'ot-plans'])}}"
                                        method="post">
                                        @csrf
                                        @method('put')
                                        <button type="submit" class="btn btn-sm btn-warning removeBtn"
                                                title="Edit Assignment">
                                            <i class="mdi mdi-close"></i> Remove
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info m-3">
                        <i class="mdi mdi-information-outline me-2"></i>
                        No active OT plan assignments found.
                    </div>
                @endif
            </div>
        </div>
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
                                <th>OT Type</th>
                                <th>Effective From</th>
                                <th>Effective To</th>
                                <th>Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($previousOtPlans as $plan)
                                @php($sl = 1)
                                <tr class="text-muted">
                                    <td><span
                                            class="badge bg-secondary-subtle text-secondary">#{{ $sl++ }}</span>
                                    </td>
                                    <td>{{ $plan->getPlan->name }}</td>
                                    <td>
                                            <span class="badge bg-light text-secondary">
                                                {{ ucwords(str_replace('_', ' ', $plan->getPlan->ot_type)) }}
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
                                            action="{{route('employees.profile.plans.delete', ['type' => 'ot-plans', 'id' => $plan->id])}}"
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
                        No previous OT plan assignments found.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Include the Create Modal --}}
@include('employees.partials.modal.create_ot_modal')
