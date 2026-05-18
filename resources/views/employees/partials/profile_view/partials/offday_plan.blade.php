{{-- Off Day Plan Assignment Interface --}}

<div class="row">
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="d-flex align-items-center">
                <i class="mdi mdi-calendar-blank text-primary fs-4 me-2"></i>
                <h5 class="fs-16 text-dark fw-semibold mb-0">Off Day Plan Management</h5>
            </div>
        </div>
        @can('employee-management.edit')
            <div>
                {{-- Create Button to Open Modal --}}
                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#createOffDayPlanModal">
                    <i class="mdi mdi-plus-circle me-1"></i> Add
                </button>
            </div>
        @endcan
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
                <h5 class="mb-0 fw-bold">Active Off Day Plans</h5>
            </div>
            <span class="badge bg-success shadow-sm px-3 py-2 rounded-pill">
                <i class="mdi mdi-check-decagram me-1"></i>{{ $totalActiveOffDayPlan }} Active
            </span>
        </div>

        {{-- Active Plans Card with Table --}}
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
                                    <th>SL</th>
                                    <th>Plan Name</th>
                                    <th>Associated Shift</th>
                                    <th>Remuneration</th>
                                    <th>Effective From</th>
                                    <th>Effective To</th>
                                    <th>Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $sl = 1 @endphp
                                @foreach ($activeOffDayPlans as $plan)
                                    <tr>
                                        <td><span
                                                class="badge bg-secondary-subtle text-secondary">#{{ $sl++ }}</span>
                                        </td>
                                        <td><strong>{{ $plan->getPlan->name }}</strong></td>
                                        <td>
                                            @if ($plan->getPlan->getShift)
                                                <span class="badge bg-light text-secondary">
                                                    <i
                                                        class="mdi mdi-clock-outline me-1"></i>{{ $plan->getPlan->getShift->name }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="text-success fw-semibold">
                                                @php
                                                    $offPlan = $plan->getPlan;
                                                    if ($offPlan->offday_config_type === 'Salary Based') {
                                                        if ($offPlan->salary_rate_type === 'Basic Rate') {
                                                            echo 'Basic Rate';
                                                        } else {
                                                            echo number_format($offPlan->offday_multiplier, 2) . 'x';
                                                        }
                                                    } else {
                                                        echo (\App\HelperClass::getCurrency() ?? '৳') .
                                                            ' ' .
                                                            number_format($offPlan->custom_offday_rate ?? 0, 2) .
                                                            '/hr';
                                                    }
                                                @endphp
                                            </span>
                                        </td>
                                        <td>{{ date('d M Y', strtotime($plan->from)) }}</td>
                                        <td>{{ date('d M Y', strtotime($plan->to)) }}</td>
                                        <td>
                                            <span class="badge bg-success">
                                                <i class="mdi mdi-check-circle me-1"></i>{{ ucfirst($plan->status) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-1">
                                                {{-- View Button --}}
                                                <button type="button" class="btn btn-sm btn-outline-info shadow-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#viewOffDayPlanModal{{ $plan->id }}"
                                                    title="View Details">
                                                    <i class="mdi mdi-eye"></i> View
                                                </button>
                                                {{-- Remove Button --}}
                                                @can('employee-management.edit')
                                                    <form
                                                        action="{{ route('employees.profile.plans.remove', ['id' => $plan->id, 'type' => 'offday-plans']) }}"
                                                        method="post" class="d-inline">
                                                        @csrf
                                                        @method('put')
                                                        <button type="submit"
                                                            class="btn btn-sm btn-outline-danger shadow-sm removeBtn"
                                                            title="Remove Plan">
                                                            <i class="mdi mdi-close-circle"></i> Remove
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
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
                                                @php
                                                    $offPlan = $plan->getPlan;
                                                    if ($offPlan->offday_config_type === 'Salary Based') {
                                                        if ($offPlan->salary_rate_type === 'Basic Rate') {
                                                            echo 'Basic Rate';
                                                        } else {
                                                            echo number_format($offPlan->offday_multiplier, 2) . 'x';
                                                        }
                                                    } else {
                                                        echo (\App\HelperClass::getCurrency() ?? '৳') .
                                                            ' ' .
                                                            number_format($offPlan->custom_offday_rate ?? 0, 2) .
                                                            '/hr';
                                                    }
                                                @endphp
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
                                            <div class="d-flex justify-content-center gap-1">
                                                {{-- View Button --}}
                                                <button type="button" class="btn btn-sm btn-outline-info shadow-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#viewOffDayPlanModal{{ $plan->id }}"
                                                    title="View Details">
                                                    <i class="mdi mdi-eye"></i> View
                                                </button>
                                                {{-- Delete Button --}}
                                                @can('employee-management.edit')
                                                <form
                                                    action="{{ route('employees.profile.plans.delete', ['type' => 'offday-plans', 'id' => $plan->id]) }}"
                                                    method="post" class="d-inline">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="btn btn-sm btn-danger confirmDelete"
                                                        title="Delete Record">
                                                        <i class="mdi mdi-delete"></i> Delete
                                                    </button>
                                                </form>
                                                @endcan
                                            </div>
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

{{-- View Plan Details Modals for Active Plans --}}
@if ($totalActiveOffDayPlan > 0)
    @foreach ($activeOffDayPlans as $plan)
        @include('employees.partials.modal.view_offday_modal', ['plan' => $plan])
    @endforeach
@endif

{{-- View Plan Details Modals for Previous Plans --}}
@if ($totalPreviousOffDayPlan > 0)
    @foreach ($previousOffDayPlans as $plan)
        @include('employees.partials.modal.view_offday_modal', ['plan' => $plan])
    @endforeach
@endif
