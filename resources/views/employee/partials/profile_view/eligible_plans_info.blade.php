<div class="row mt-3">
    @if (!empty($employeePlan))
        <div class="col-12">
            <div class="card">
                <div class="card-body pt-0">
                    <ul class="nav nav-underline border-bottom pt-2" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active p-2" id="work_plans_tab" data-bs-toggle="tab" href="#work_plans"
                                role="tab">
                                <span class="d-none d-sm-block">Work Plans</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link p-2" id="compensation_tab" data-bs-toggle="tab" href="#compensation"
                                role="tab">
                                <span class="d-none d-sm-block">Compensation</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link p-2" id="deductions_tab" data-bs-toggle="tab" href="#deductions"
                                role="tab">
                                <span class="d-none d-sm-block">Deductions</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link p-2" id="benefits_tab" data-bs-toggle="tab" href="#benefits"
                                role="tab">
                                <span class="d-none d-sm-block">Benefits</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link p-2" id="meal_plans_tab" data-bs-toggle="tab" href="#meal_plans"
                                role="tab">
                                <span class="d-none d-sm-block">Meal Plans</span>
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content text-muted">
                        <!-- Work Plans Tab -->
                        <div class="tab-pane active show pt-4" id="work_plans" role="tabpanel">
                            <div class="row">
                                <!-- Shift Plan -->
                                <div class="col-md-6 mb-3">
                                    <div class="card border h-100">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0 fw-semibold">
                                                <i class="mdi mdi-clock-outline text-primary me-2"></i>Shift Plan
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-borderless mb-0">
                                                    <tbody>
                                                        <tr>
                                                            <td class="fw-semibold" style="width: 40%;">Status</td>
                                                            <td>
                                                                <span
                                                                    class="badge px-3 py-1 bg-{{ ($employeePlan->shift_plan_status ?? '') == 'active' ? 'success' : 'danger' }}">
                                                                    {{ ucfirst($employeePlan->shift_plan_status ?? 'Inactive') }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-semibold">From Date</td>
                                                            <td>{{ ($employeePlan->shift_plan_from ?? null) ? $employeePlan->shift_plan_from->format('Y-m-d') : 'Not Set' }}
                                                            </td>
                                                        </tr>
                                                        {{--<tr>
                                                            <td class="fw-semibold">To Date</td>
                                                            <td>{{ $employeePlan->shift_plan_to ? $employeePlan->shift_plan_to->format('Y-m-d') : 'Not Set' }}
                                                            </td>
                                                        </tr>--}}
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Leave Plan -->
                                <div class="col-md-6 mb-3">
                                    <div class="card border h-100">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0 fw-semibold">
                                                <i class="mdi mdi-calendar-text text-success me-2"></i>Leave Plan
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-borderless mb-0">
                                                    <tbody>
                                                        <tr>
                                                            <td class="fw-semibold" style="width: 40%;">Status</td>
                                                            <td>
                                                                <span
                                                                    class="badge px-3 py-1 bg-{{ ($employeePlan->leave_plan_status ?? '') == 'active' ? 'success' : 'danger' }}">
                                                                    {{ ucfirst($employeePlan->leave_plan_status ?? 'Inactive') }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-semibold">From Date</td>
                                                            <td>{{ ($employeePlan->leave_plan_from ?? null) ? $employeePlan->leave_plan_from->format('Y-m-d') : 'Not Set' }}
                                                            </td>
                                                        </tr>
                                                        {{--<tr>
                                                            <td class="fw-semibold">To Date</td>
                                                            <td>{{ $employeePlan->leave_plan_to ? $employeePlan->leave_plan_to->format('Y-m-d') : 'Not Set' }}
                                                            </td>
                                                        </tr>--}}
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Roster Plans -->
                                <div class="col-md-6 mb-3">
                                    <div class="card border h-100">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0 fw-semibold">
                                                <i class="mdi mdi-calendar-month text-info me-2"></i>Roster Plans
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-borderless mb-0">
                                                    <tbody>
                                                        <tr>
                                                            <td class="fw-semibold" style="width: 40%;">Status</td>
                                                            <td>
                                                                <span
                                                                    class="badge px-3 py-1 bg-{{ ($employeePlan->roster_plans_status ?? '') == 'active' ? 'success' : 'danger' }}">
                                                                    {{ ucfirst($employeePlan->roster_plans_status ?? 'Inactive') }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-semibold">From Date</td>
                                                            <td>{{ ($employeePlan->roster_plans_from ?? null) ? $employeePlan->roster_plans_from->format('Y-m-d') : 'Not Set' }}
                                                            </td>
                                                        </tr>
                                                        {{--<tr>
                                                            <td class="fw-semibold">To Date</td>
                                                            <td>{{ $employeePlan->roster_plans_to ? $employeePlan->roster_plans_to->format('Y-m-d') : 'Not Set' }}
                                                            </td>
                                                        </tr>--}}
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Off Day Work Plan -->
                                <div class="col-md-6 mb-3">
                                    <div class="card border h-100">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0 fw-semibold">
                                                <i class="mdi mdi-beach text-warning me-2"></i>Off Day Work Plan
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-borderless mb-0">
                                                    <tbody>
                                                        <tr>
                                                            <td class="fw-semibold" style="width: 40%;">Status</td>
                                                            <td>
                                                                <span
                                                                    class="badge px-3 py-1 bg-{{ ($employeePlan->day_off_work_plan_status ?? '') == 'active' ? 'success' : 'danger' }}">
                                                                    {{ ucfirst($employeePlan->day_off_work_plan_status ?? 'Inactive') }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-semibold">From Date</td>
                                                            <td>{{ ($employeePlan->day_off_work_plan_from ?? null) ? $employeePlan->day_off_work_plan_from->format('Y-m-d') : 'Not Set' }}
                                                            </td>
                                                        </tr>
                                                        {{--<tr>
                                                            <td class="fw-semibold">To Date</td>
                                                            <td>{{ $employeePlan->day_off_work_plan_to ? $employeePlan->day_off_work_plan_to->format('Y-m-d') : 'Not Set' }}
                                                            </td>
                                                        </tr>--}}
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Compensation Tab -->
                        <div class="tab-pane pt-4" id="compensation" role="tabpanel">
                            <div class="row">
                                <!-- Overtime Plan -->
                                <div class="col-md-6 mb-3">
                                    <div class="card border h-100">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0 fw-semibold">
                                                <i class="mdi mdi-clock-check text-primary me-2"></i>Overtime Plan
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-borderless mb-0">
                                                    <tbody>
                                                        <tr>
                                                            <td class="fw-semibold" style="width: 40%;">Status</td>
                                                            <td>
                                                                <span
                                                                    class="badge px-3 py-1 bg-{{ ($employeePlan->ot_plan_status ?? '') == 'active' ? 'success' : 'danger' }}">
                                                                    {{ ucfirst($employeePlan->ot_plan_status ?? 'Inactive') }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-semibold">From Date</td>
                                                            <td>{{ ($employeePlan->ot_plan_from ?? null) ? $employeePlan->ot_plan_from->format('Y-m-d') : 'Not Set' }}
                                                            </td>
                                                        </tr>
                                                        {{--<tr>
                                                            <td class="fw-semibold">To Date</td>
                                                            <td>{{ $employeePlan->ot_plan_to ? $employeePlan->ot_plan_to->format('Y-m-d') : 'Not Set' }}
                                                            </td>
                                                        </tr>--}}
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <!-- Allowance Plan -->
                                <div class="col-md-6 mb-3">
                                    <div class="card border h-100">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0 fw-semibold">
                                                <i class="mdi mdi-cash-multiple text-info me-2"></i>Allowance Plan
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-borderless mb-0">
                                                    <tbody>
                                                        <tr>
                                                            <td class="fw-semibold" style="width: 40%;">Status</td>
                                                            <td>
                                                                <span
                                                                    class="badge px-3 py-1 bg-{{ ($employeePlan->allowance_plan_status ?? '') == 'active' ? 'success' : 'danger' }}">
                                                                    {{ ucfirst($employeePlan->allowance_plan_status ?? 'Inactive') }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-semibold">From Date</td>
                                                            <td>{{ ($employeePlan->allowance_plan_from ?? null) ? $employeePlan->allowance_plan_from->format('Y-m-d') : 'Not Set' }}
                                                            </td>
                                                        </tr>
                                                        {{--<tr>
                                                            <td class="fw-semibold">To Date</td>
                                                            <td>{{ $employeePlan->allowance_plan_to ? $employeePlan->allowance_plan_to->format('Y-m-d') : 'Not Set' }}
                                                            </td>
                                                        </tr>--}}
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Benefits Tab -->
                        <div class="tab-pane pt-4" id="benefits" role="tabpanel">
                            <div class="row">

                                <!-- Bonus Plan -->
                                <div class="col-md-6 mb-3">
                                    <div class="card border h-100">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0 fw-semibold">
                                                <i class="mdi mdi-gift text-success me-2"></i>Bonus & Reward Plan
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-borderless mb-0">
                                                    <tbody>
                                                    <tr>
                                                        <td class="fw-semibold" style="width: 40%;">Status</td>
                                                        <td>
                                                                <span
                                                                    class="badge px-3 py-1 bg-{{ ($employeePlan->bonus_plan_status ?? '') == 'active' ? 'success' : 'danger' }}">
                                                                    {{ ucfirst($employeePlan->bonus_plan_status ?? 'Inactive') }}
                                                                </span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold">From Date</td>
                                                        <td>{{ ($employeePlan->bonus_plan_from ?? null) ? $employeePlan->bonus_plan_from->format('Y-m-d') : 'Not Set' }}
                                                        </td>
                                                    </tr>
                                                    {{--<tr>
                                                        <td class="fw-semibold">To Date</td>
                                                        <td>{{ $employeePlan->bonus_plan_to ? $employeePlan->bonus_plan_to->format('Y-m-d') : 'Not Set' }}
                                                        </td>
                                                    </tr>--}}
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Medical Plan -->
                                <div class="col-md-6 mb-3">
                                    <div class="card border h-100">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0 fw-semibold">
                                                <i class="mdi mdi-hospital-box text-danger me-2"></i>Medical Plan
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-borderless mb-0">
                                                    <tbody>
                                                    <tr>
                                                        <td class="fw-semibold" style="width: 40%;">Status</td>
                                                        <td>
                                                                <span
                                                                    class="badge px-3 py-1 bg-{{ ($employeePlan->medical_plan_status ?? '') == 'active' ? 'success' : 'danger' }}">
                                                                    {{ ucfirst($employeePlan->medical_plan_status ?? 'Inactive') }}
                                                                </span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold">From Date</td>
                                                        <td>{{ ($employeePlan->medical_plan_from ?? null) ? $employeePlan->medical_plan_from->format('Y-m-d') : 'Not Set' }}
                                                        </td>
                                                    </tr>
                                                    {{--                                                        <tr>--}}
                                                    {{--                                                            <td class="fw-semibold">To Date</td>--}}
                                                    {{--                                                            <td>{{ $employeePlan->medical_plan_to ? $employeePlan->medical_plan_to->format('Y-m-d') : 'Not Set' }}--}}
                                                    {{--                                                            </td>--}}
                                                    {{--                                                        </tr>--}}
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Deductions Tab -->
                        <div class="tab-pane pt-4" id="deductions" role="tabpanel">
                            <div class="row">
                                <!-- Late Deduction Plan -->
                                <div class="col-md-6 mb-3">
                                    <div class="card border h-100">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0 fw-semibold">
                                                <i class="mdi mdi-clock-alert text-warning me-2"></i>Late Deduction
                                                Plan
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-borderless mb-0">
                                                    <tbody>
                                                        <tr>
                                                            <td class="fw-semibold" style="width: 40%;">Status</td>
                                                            <td>
                                                                <span
                                                                    class="badge px-3 py-1 bg-{{ ($employeePlan->late_deduction_plan_status ?? '') == 'active' ? 'success' : 'danger' }}">
                                                                    {{ ucfirst($employeePlan->late_deduction_plan_status ?? 'Inactive') }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-semibold">From Date</td>
                                                            <td>{{ ($employeePlan->late_deduction_plan_from ?? null) ? $employeePlan->late_deduction_plan_from->format('Y-m-d') : 'Not Set' }}
                                                            </td>
                                                        </tr>
                                                        {{--<tr>
                                                            <td class="fw-semibold">To Date</td>
                                                            <td>{{ $employeePlan->late_deduction_plan_to ? $employeePlan->late_deduction_plan_to->format('Y-m-d') : 'Not Set' }}
                                                            </td>
                                                        </tr>--}}
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Early Out Deduction Plan -->
                                <div class="col-md-6 mb-3">
                                    <div class="card border h-100">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0 fw-semibold">
                                                <i class="mdi mdi-exit-run text-danger me-2"></i>Early Out Deduction
                                                Plan
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-borderless mb-0">
                                                    <tbody>
                                                        <tr>
                                                            <td class="fw-semibold" style="width: 40%;">Status</td>
                                                            <td>
                                                                <span
                                                                    class="badge px-3 py-1 bg-{{ ($employeePlan->early_out_deduction_plan_status ?? '') == 'active' ? 'success' : 'danger' }}">
                                                                    {{ ucfirst($employeePlan->early_out_deduction_plan_status ?? 'Inactive') }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-semibold">From Date</td>
                                                            <td>{{ ($employeePlan->early_out_deduction_plan_from ?? null) ? $employeePlan->early_out_deduction_plan_from->format('Y-m-d') : 'Not Set' }}
                                                            </td>
                                                        </tr>
                                                        {{--<tr>
                                                            <td class="fw-semibold">To Date</td>
                                                            <td>{{ $employeePlan->early_out_deduction_plan_to ? $employeePlan->early_out_deduction_plan_to->format('Y-m-d') : 'Not Set' }}
                                                            </td>
                                                        </tr>--}}
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Excessive Late Plan -->
                                <div class="col-md-6 mb-3">
                                    <div class="card border h-100">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0 fw-semibold">
                                                <i class="mdi mdi-alarm-multiple text-warning me-2"></i>Excessive Late
                                                Plan
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-borderless mb-0">
                                                    <tbody>
                                                        <tr>
                                                            <td class="fw-semibold" style="width: 40%;">Status</td>
                                                            <td>
                                                                <span
                                                                    class="badge px-3 py-1 bg-{{ ($employeePlan->excessive_late_plan_status ?? '') == 'active' ? 'success' : 'danger' }}">
                                                                    {{ ucfirst($employeePlan->excessive_late_plan_status ?? 'Inactive') }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-semibold">From Date</td>
                                                            <td>{{ ($employeePlan->excessive_late_plan_from ?? null) ? $employeePlan->excessive_late_plan_from->format('Y-m-d') : 'Not Set' }}
                                                            </td>
                                                        </tr>
                                                        {{--<tr>
                                                            <td class="fw-semibold">To Date</td>
                                                            <td>{{ $employeePlan->excessive_late_plan_to ? $employeePlan->excessive_late_plan_to->format('Y-m-d') : 'Not Set' }}
                                                            </td>
                                                        </tr>--}}
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Meal Plans Tab -->
                        <div class="tab-pane pt-4" id="meal_plans" role="tabpanel">
                            <div class="row">
                                <!-- Meal Plan -->
                                <div class="col-md-6 mb-3">
                                    <div class="card border h-100">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0 fw-semibold">
                                                <i class="mdi mdi-food text-success me-2"></i>Meal Plan
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-borderless mb-0">
                                                    <tbody>
                                                        <tr>
                                                            <td class="fw-semibold" style="width: 40%;">Status</td>
                                                            <td>
                                                                <span
                                                                    class="badge px-3 py-1 bg-{{ $employeePlan && strtolower($employeePlan->meal_plan_status) == 'active' ? 'success' : 'danger' }}">
                                                                    {{ ucfirst($employeePlan->meal_plan_status ?? 'Inactive') }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-semibold">From Date</td>
                                                            <td>{{ $employeePlan->meal_plan_from ? $employeePlan->meal_plan_from->format('Y-m-d') : 'Not Set' }}
                                                            </td>
                                                        </tr>
                                                        {{--<tr>
                                                            <td class="fw-semibold">To Date</td>
                                                            <td>{{ $employeePlan->meal_plan_to ? $employeePlan->meal_plan_to->format('Y-m-d') : 'Not Set' }}
                                                            </td>
                                                        </tr>--}}
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-6 col-md-8 col-sm-10">
                            <div class="card shadow-sm border-0 mt-5 mb-5">
                                <div class="card-body text-center p-5">

                                    <!-- Empty State Circle -->
                                    <div class="d-flex justify-content-center mb-4">
                                        <div class="rounded-circle bg-light border border-2 border-secondary d-flex align-items-center justify-content-center"
                                            style="width: 120px; height: 120px;">
                                            <span class="display-1 text-secondary fw-light">?</span>
                                        </div>
                                    </div>

                                    <!-- Heading -->
                                    <h3 class="fw-bold text-dark mb-3">Employee Information Not Found</h3>

                                    <!-- Divider -->
                                    <hr class="w-50 mx-auto opacity-25 mb-4">

                                    <!-- Message -->
                                    <p class="text-muted mb-4 fs-6 lh-base px-lg-5">
                                        No employee records are currently available in the system.
                                        Please add employee information to get started.
                                    </p>

                                    <!-- Action Button -->
                                    @if(auth()->user()->user_type !== \App\Enums\UserType::Employee)
                                        @can('employee-management.create')
                                        <a href="{{ route('employee.eligible_plans.create', $employee->id) }}"
                                            class="btn btn-primary btn-lg px-5 rounded-pill">
                                            Add Information
                                        </a>
                                        @endcan
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    @endif
</div>

@if (!empty($employeePlan))
    <!-- Action Buttons -->
    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('employee.index') }}" class="btn btn-secondary">Back to List</a>
                        @if ($employeePlan)
                            @if(auth()->user()->user_type !== \App\Enums\UserType::Employee)
                                @can('employee-management.edit')
                                    <a href="{{ route('employee.eligible_plans.edit', $employee->id) }}"
                                        class="btn btn-primary">Edit Plans</a>
                                @endcan
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

