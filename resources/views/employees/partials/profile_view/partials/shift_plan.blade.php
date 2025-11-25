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

{{-- Active Shift Plans Section --}}
<div class="row mb-4 mt-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-success-subtle">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <i class="mdi mdi-check-circle text-success fs-5 me-2"></i>
                        <h6 class="mb-0 fw-semibold text-success">Active Shift Plan</h6>
                    </div>
                    <span class="badge bg-success">{{$totalActiveShiftPlan}} Active</span>
                </div>
            </div>
            <div class="card-body p-0">
                @if ($totalActiveShiftPlan > 0)
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
                                <tr>

                                    <td><strong>{{ $activeShiftPLan->getPlan->name }}</strong></td>
                                    <td>
                                            <span class="badge bg-info-subtle text-secondary">
                                                <i class="mdi mdi-clock-in me-1"></i>{{ $activeShiftPLan->getPlan->clock_in }}
                                            </span>
                                    </td>
                                    <td>
                                            <span class="badge bg-warning-subtle text-warning">
                                                <i class="mdi mdi-clock-out me-1"></i>{{ $activeShiftPLan['clock_out'] }}
                                            </span>
                                    </td>
                                    <td>{{ date('d M Y', strtotime($activeShiftPLan->from)) }}</td>
                                    <td>{{ date('d M Y', strtotime($activeShiftPLan['to'])) }}</td>
                                    <td>
                                            <span class="badge bg-success">
                                                <i class="mdi mdi-check-circle me-1"></i>{{ $activeShiftPLan['status'] }}
                                            </span>
                                    </td>
                                    <td class="text-center">
                                        <form
                                            action="{{route('employees.profile.plans.remove', ['id' => $activeShiftPLan->id, 'type' => 'shift-plans'])}}"
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
                        No active shift plan assignments found.
                    </div>
                @endif
            </div>
        </div>
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
                                                <i class="mdi mdi-clock-in me-1"></i>{{ \Carbon\Carbon::parse($plan->getPlan->clock_in)->format('h:i A') }}
                                            </span>
                                    </td>
                                    <td>
                                            <span class="badge bg-light text-secondary">
                                                <i class="mdi mdi-clock-out me-1"></i>{{ \Carbon\Carbon::parse($plan->getPlan->clock_out)->format('h:i A') }}
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
                                            action="{{route('employees.profile.plans.delete', ['type' => 'shift-plans', 'id' => $plan->id])}}"
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
