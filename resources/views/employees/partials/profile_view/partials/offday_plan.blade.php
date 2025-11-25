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
                <i class="mdi mdi-plus-circle me-1"></i> Add
            </button>
        </div>
    </div>
</div>

{{-- Active Off Day Plans Section --}}
<div class="row mb-4 mt-4">
    <div class="col-12">
        {{--
            SECTION HEADER
            Title with count badge
        --}}
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="d-flex align-items-center gap-2">
                <div class="d-flex align-items-center justify-content-center rounded-circle shadow-sm"
                     style="width: 36px; height: 36px; background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                    <i class="mdi mdi-check-circle text-white fs-6"></i>
                </div>
                <h5 class="mb-0 fw-bold text-dark">Active Off Day Plan Assignments</h5>
            </div>
            <span class="badge bg-success shadow-sm px-3 py-2 rounded-pill">
                {{ $totalActiveOffDayPlan }} Active
            </span>
        </div>

        @if ($totalActiveOffDayPlan > 0)
            {{--
                ACTIVE PLAN CARD - MEDIUM SIZE
                Single centered card with status and action in header
            --}}
            <div class="row justify-content-center">
                <div class="col-lg-8 col-xl-7">
                    <div class="card border-0 shadow rounded-3 overflow-hidden">

                        {{-- Card Header with Plan Name, Status & Remove Button --}}
                        <div class="card-header border-0 py-3 position-relative"
                             style="background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);">

                            {{-- Status and Remove Button (Top Right Corner) --}}
                            <div class="position-absolute top-0 end-0 mt-3 me-3 d-flex align-items-center gap-2">
                                <button type="button"
                                        class="btn btn-outline-danger btn-sm rounded-pill px-3 py-2 shadow-sm"
                                        title="Remove Assignment">
                                    <i class="mdi mdi-close-circle me-1"></i> Remove
                                </button>
                            </div>

                            {{-- Center Content: Icon and Plan Name --}}
                            <div class="text-center">
                                <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-white shadow-sm mb-2"
                                     style="width: 45px; height: 45px;">
                                    <i class="mdi mdi-calendar-check text-success fs-5"></i>
                                </div>
                                <h5 class="mb-2 fw-bold text-success">{{ $activeOffDayPLan->getPlan->name }}</h5>
                                <span class="badge bg-success shadow-sm px-3 py-2 rounded-pill">
                                    <i class="mdi mdi-check-circle me-1"></i>{{ $activeOffDayPLan->status }}
                                </span>
                            </div>
                        </div>

                        {{-- Card Body with Plan Details --}}
                        <div class="card-body p-4">

                            {{-- Remuneration Highlight --}}
                            <div class="text-center mb-3 pb-3 border-bottom">
                                <p class="text-muted text-uppercase small fw-semibold mb-2">Remuneration Amount</p>
                                <div class="d-inline-flex align-items-center gap-2 bg-success bg-opacity-10 px-4 py-2 rounded-pill border border-success border-opacity-25">
                                    <i class="mdi mdi-cash-multiple text-success fs-5"></i>
                                    <h5 class="mb-0 fw-bold text-success">
                                        {{ \App\HelperClass::getCurrency() ?? '৳' }} {{ number_format($activeOffDayPLan->getPlan->remuneration, 2) }}
                                    </h5>
                                </div>
                            </div>

                            {{-- Plan Details Grid --}}
                            <div class="row g-3">

                                {{-- Effective From --}}
                                <div class="col-md-6">
                                    <div class="text-center p-3 bg-light rounded-3 h-100">
                                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10 mb-2"
                                             style="width: 40px; height: 40px;">
                                            <i class="mdi mdi-calendar-start text-primary fs-6"></i>
                                        </div>
                                        <p class="text-muted text-uppercase small fw-semibold mb-1">Effective From</p>
                                        <h6 class="mb-0 fw-bold text-dark">{{ date('d M Y', strtotime($activeOffDayPLan->from)) }}</h6>
                                    </div>
                                </div>

                                {{-- Effective To --}}
                                <div class="col-md-6">
                                    <div class="text-center p-3 bg-light rounded-3 h-100">
                                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-danger bg-opacity-10 mb-2"
                                             style="width: 40px; height: 40px;">
                                            <i class="mdi mdi-calendar-end text-danger fs-6"></i>
                                        </div>
                                        <p class="text-muted text-uppercase small fw-semibold mb-1">Effective To</p>
                                        <h6 class="mb-0 fw-bold text-dark">{{ date('d M Y', strtotime($activeOffDayPLan->to)) }}</h6>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>

        @else

            {{--
                EMPTY STATE CARD - MEDIUM SIZE
                Centered card when no active plans exist
            --}}
            <div class="row justify-content-center">
                <div class="col-lg-6 col-xl-5">
                    <div class="card border-0 shadow rounded-3">
                        <div class="card-body text-center py-4 px-4">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-info bg-opacity-10 mb-3"
                                 style="width: 70px; height: 70px;">
                                <i class="mdi mdi-information-outline text-info" style="font-size: 2.5rem;"></i>
                            </div>
                            <h5 class="text-dark fw-semibold mb-2">No Active Off Day Plans</h5>
                            <p class="text-muted mb-0">There are currently no active off day plan assignments for this employee.</p>
                        </div>
                    </div>
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
                                                {{\App\HelperClass::getCurrency() ?? '৳'}} {{ number_format($plan->getPlan->remuneration, 2) }}
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
                                        <button type="button" class="btn btn-sm btn-danger" title="Delete Record"
                                                onclick="confirmOffDayDelete({{ $plan->id }})">
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
