@extends('structure.master')
@section('content')
    @include('employees.partials.creation_button')

    <div class="mt-4">
        <!-- Tabbed Content -->
        <div class="row mt-3">
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
                                                                        class="badge bg-{{ $employeePlan && $employeePlan->shift_plan_status == 'Active' ? 'success' : 'secondary' }}">
                                                                        {{ $employeePlan->shift_plan_status ?? 'Inactive' }}
                                                                    </span>

                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fw-semibold">From Date</td>
                                                                <td>{{ $employeePlan->shift_plan_from ?? 'Not Set' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fw-semibold">To Date</td>
                                                                <td>{{ $employeePlan->shift_plan_to ?? 'Not Set' }}</td>
                                                            </tr>
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
                                                                        class="badge bg-{{ $employeePlan && $employeePlan->leave_plan_status == 'Active' ? 'success' : 'secondary' }}">
                                                                        {{ $employeePlan->leave_plan_status ?? 'Inactive' }}
                                                                    </span>

                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fw-semibold">From Date</td>
                                                                <td>{{ $employeePlan->leave_plan_from ?? 'Not Set' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fw-semibold">To Date</td>
                                                                <td>{{ $employeePlan->leave_plan_to ?? 'Not Set' }}</td>
                                                            </tr>
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
                                                                        class="badge bg-{{ $employeePlan && $employeePlan->roster_plan_status == 'Active' ? 'success' : 'secondary' }}">
                                                                        {{ $employeePlan->roster_plan_status ?? 'Inactive' }}
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fw-semibold">From Date</td>
                                                                <td>{{ $employeePlan->roster_plan_from ?? 'Not Set' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fw-semibold">To Date</td>
                                                                <td>{{ $employeePlan->roster_plan_to ?? 'Not Set' }}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Day Off Work Plan -->
                                    <div class="col-md-6 mb-3">
                                        <div class="card border h-100">
                                            <div class="card-header bg-light">
                                                <h6 class="mb-0 fw-semibold">
                                                    <i class="mdi mdi-beach text-warning me-2"></i>Day Off Work Plan
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
                                                                        class="badge bg-{{ $employeePlan && $employeePlan->day_off_plan_status == 'Active' ? 'success' : 'secondary' }}">
                                                                        {{ $employeePlan->day_off_plan_status ?? 'Inactive' }}
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fw-semibold">From Date</td>
                                                                <td>{{ $employeePlan->day_off_plan_from ?? 'Not Set' }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fw-semibold">To Date</td>
                                                                <td>{{ $employeePlan->day_off_plan_to ?? 'Not Set' }}</td>
                                                            </tr>
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
                                    <!-- OT Plan -->
                                    <div class="col-md-6 mb-3">
                                        <div class="card border h-100">
                                            <div class="card-header bg-light">
                                                <h6 class="mb-0 fw-semibold">
                                                    <i class="mdi mdi-clock-check text-primary me-2"></i>OT Plan
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
                                                                        class="badge bg-{{ $employeePlan && $employeePlan->ot_plan_status == 'Active' ? 'success' : 'secondary' }}">
                                                                        {{ $employeePlan->ot_plan_status ?? 'Inactive' }}
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fw-semibold">From Date</td>
                                                                <td>{{ $employeePlan->ot_plan_from ?? 'Not Set' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fw-semibold">To Date</td>
                                                                <td>{{ $employeePlan->ot_plan_to ?? 'Not Set' }}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Bonus Plan -->
                                    <div class="col-md-6 mb-3">
                                        <div class="card border h-100">
                                            <div class="card-header bg-light">
                                                <h6 class="mb-0 fw-semibold">
                                                    <i class="mdi mdi-gift text-success me-2"></i>Bonus Plan
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
                                                                        class="badge bg-{{ $employeePlan && $employeePlan->bonus_plan_status == 'Active' ? 'success' : 'secondary' }}">
                                                                        {{ $employeePlan->bonus_plan_status ?? 'Inactive' }}
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fw-semibold">From Date</td>
                                                                <td>{{ $employeePlan->bonus_plan_from ?? 'Not Set' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fw-semibold">To Date</td>
                                                                <td>{{ $employeePlan->bonus_plan_to ?? 'Not Set' }}</td>
                                                            </tr>
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
                                                                        class="badge bg-{{ $employeePlan && $employeePlan->allowance_plan_status == 'Active' ? 'success' : 'secondary' }}">
                                                                        {{ $employeePlan->allowance_plan_status ?? 'Inactive' }}
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fw-semibold">From Date</td>
                                                                <td>{{ $employeePlan->allowance_plan_from ?? 'Not Set' }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fw-semibold">To Date</td>
                                                                <td>{{ $employeePlan->allowance_plan_to ?? 'Not Set' }}
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Attendance Bonus Plan -->
                                    <div class="col-md-6 mb-3">
                                        <div class="card border h-100">
                                            <div class="card-header bg-light">
                                                <h6 class="mb-0 fw-semibold">
                                                    <i class="mdi mdi-star text-warning me-2"></i>Attendance Bonus Plan
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
                                                                        class="badge bg-{{ $employeePlan && $employeePlan->attendance_bonus_plan_status == 'Active' ? 'success' : 'secondary' }}">
                                                                        {{ $employeePlan->attendance_bonus_plan_status ?? 'Inactive' }}
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fw-semibold">From Date</td>
                                                                <td>{{ $employeePlan->attendance_bonus_plan_from ?? 'Not Set' }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fw-semibold">To Date</td>
                                                                <td>{{ $employeePlan->attendance_bonus_plan_to ?? 'Not Set' }}
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Production Plan -->
                                    <div class="col-md-6 mb-3">
                                        <div class="card border h-100">
                                            <div class="card-header bg-light">
                                                <h6 class="mb-0 fw-semibold">
                                                    <i class="mdi mdi-factory text-danger me-2"></i>Production Plan
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
                                                                        class="badge bg-{{ $employeePlan && $employeePlan->production_plan_status == 'Active' ? 'success' : 'secondary' }}">
                                                                        {{ $employeePlan->production_plan_status ?? 'Inactive' }}
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fw-semibold">From Date</td>
                                                                <td>{{ $employeePlan->production_plan_from ?? 'Not Set' }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fw-semibold">To Date</td>
                                                                <td>{{ $employeePlan->production_plan_to ?? 'Not Set' }}
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Salary Breakdown Plan -->
                                    <div class="col-md-6 mb-3">
                                        <div class="card border h-100">
                                            <div class="card-header bg-light">
                                                <h6 class="mb-0 fw-semibold">
                                                    <i class="mdi mdi-currency-usd text-success me-2"></i>Salary Breakdown
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
                                                                        class="badge bg-{{ $employeePlan && $employeePlan->salary_breakdown_plan_status == 'Active' ? 'success' : 'secondary' }}">
                                                                        {{ $employeePlan->salary_breakdown_plan_status ?? 'Inactive' }}
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fw-semibold">From Date</td>
                                                                <td>{{ $employeePlan->salary_breakdown_plan_from ?? 'Not Set' }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fw-semibold">To Date</td>
                                                                <td>{{ $employeePlan->salary_breakdown_plan_to ?? 'Not Set' }}
                                                                </td>
                                                            </tr>
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
                                                                        class="badge bg-{{ $employeePlan && $employeePlan->late_deduction_plan_status == 'Active' ? 'success' : 'secondary' }}">
                                                                        {{ $employeePlan->late_deduction_plan_status ?? 'Inactive' }}
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fw-semibold">From Date</td>
                                                                <td>{{ $employeePlan->late_deduction_plan_from ?? 'Not Set' }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fw-semibold">To Date</td>
                                                                <td>{{ $employeePlan->late_deduction_plan_to ?? 'Not Set' }}
                                                                </td>
                                                            </tr>
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
                                                                        class="badge bg-{{ $employeePlan && $employeePlan->early_out_deduction_plan_status == 'Active' ? 'success' : 'secondary' }}">
                                                                        {{ $employeePlan->early_out_deduction_plan_status ?? 'Inactive' }}
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fw-semibold">From Date</td>
                                                                <td>{{ $employeePlan->early_out_deduction_plan_from ?? 'Not Set' }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fw-semibold">To Date</td>
                                                                <td>{{ $employeePlan->early_out_deduction_plan_to ?? 'Not Set' }}
                                                                </td>
                                                            </tr>
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
                                                                        class="badge bg-{{ $employeePlan && $employeePlan->excessive_late_plan_status == 'Active' ? 'success' : 'secondary' }}">
                                                                        {{ $employeePlan->excessive_late_plan_status ?? 'Inactive' }}
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fw-semibold">From Date</td>
                                                                <td>{{ $employeePlan->excessive_late_plan_from ?? 'Not Set' }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fw-semibold">To Date</td>
                                                                <td>{{ $employeePlan->excessive_late_plan_to ?? 'Not Set' }}
                                                                </td>
                                                            </tr>
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
                                                                        class="badge bg-{{ $employeePlan && $employeePlan->medical_plan_status == 'Active' ? 'success' : 'secondary' }}">
                                                                        {{ $employeePlan->medical_plan_status ?? 'Inactive' }}
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fw-semibold">From Date</td>
                                                                <td>{{ $employeePlan->medical_plan_from ?? 'Not Set' }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fw-semibold">To Date</td>
                                                                <td>{{ $employeePlan->medical_plan_to ?? 'Not Set' }}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Night Bill Plan -->
                                    <div class="col-md-6 mb-3">
                                        <div class="card border h-100">
                                            <div class="card-header bg-light">
                                                <h6 class="mb-0 fw-semibold">
                                                    <i class="mdi mdi-weather-night text-primary me-2"></i>Night Bill Plan
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
                                                                        class="badge bg-{{ $employeePlan && $employeePlan->night_bill_plan_status == 'Active' ? 'success' : 'secondary' }}">
                                                                        {{ $employeePlan->night_bill_plan_status ?? 'Inactive' }}
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fw-semibold">From Date</td>
                                                                <td>{{ $employeePlan->night_bill_plan_from ?? 'Not Set' }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fw-semibold">To Date</td>
                                                                <td>{{ $employeePlan->night_bill_plan_to ?? 'Not Set' }}
                                                                </td>
                                                            </tr>
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
                                    <!-- Breakfast Plan -->
                                    <div class="col-md-6 mb-3">
                                        <div class="card border h-100">
                                            <div class="card-header bg-light">
                                                <h6 class="mb-0 fw-semibold">
                                                    <i class="mdi mdi-coffee text-warning me-2"></i>Breakfast Plan
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
                                                                        class="badge bg-{{ $employeePlan && $employeePlan->breakfast_plan_status == 'Active' ? 'success' : 'secondary' }}">
                                                                        {{ $employeePlan->breakfast_plan_status ?? 'Inactive' }}
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fw-semibold">From Date</td>
                                                                <td>{{ $employeePlan->breakfast_plan_from ?? 'Not Set' }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fw-semibold">To Date</td>
                                                                <td>{{ $employeePlan->breakfast_plan_to ?? 'Not Set' }}
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Lunch Plan -->
                                    <div class="col-md-6 mb-3">
                                        <div class="card border h-100">
                                            <div class="card-header bg-light">
                                                <h6 class="mb-0 fw-semibold">
                                                    <i class="mdi mdi-food text-success me-2"></i>Lunch Plan
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
                                                                        class="badge bg-{{ $employeePlan && $employeePlan->lunch_plan_status == 'Active' ? 'success' : 'secondary' }}">
                                                                        {{ $employeePlan->lunch_plan_status ?? 'Inactive' }}
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fw-semibold">From Date</td>
                                                                <td>{{ $employeePlan->lunch_plan_from ?? 'Not Set' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fw-semibold">To Date</td>
                                                                <td>{{ $employeePlan->lunch_plan_to ?? 'Not Set' }}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tiffin Plan -->
                                    <div class="col-md-6 mb-3">
                                        <div class="card border h-100">
                                            <div class="card-header bg-light">
                                                <h6 class="mb-0 fw-semibold">
                                                    <i class="mdi mdi-food-variant text-info me-2"></i>Tiffin Plan
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
                                                                        class="badge bg-{{ $employeePlan && $employeePlan->tiffin_plan_status == 'Active' ? 'success' : 'secondary' }}">
                                                                        {{ $employeePlan->tiffin_plan_status ?? 'Inactive' }}
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fw-semibold">From Date</td>
                                                                <td>{{ $employeePlan->tiffin_plan_from ?? 'Not Set' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fw-semibold">To Date</td>
                                                                <td>{{ $employeePlan->tiffin_plan_to ?? 'Not Set' }}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Dinner Plan -->
                                    <div class="col-md-6 mb-3">
                                        <div class="card border h-100">
                                            <div class="card-header bg-light">
                                                <h6 class="mb-0 fw-semibold">
                                                    <i class="mdi mdi-silverware-fork-knife text-primary me-2"></i>Dinner
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
                                                                        class="badge bg-{{ $employeePlan && $employeePlan->dinner_plan_status == 'Active' ? 'success' : 'secondary' }}">
                                                                        {{ $employeePlan->dinner_plan_status ?? 'Inactive' }}
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fw-semibold">From Date</td>
                                                                <td>{{ $employeePlan->dinner_plan_from ?? 'Not Set' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fw-semibold">To Date</td>
                                                                <td>{{ $employeePlan->dinner_plan_to ?? 'Not Set' }}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Snacks Plan -->
                                    <div class="col-md-6 mb-3">
                                        <div class="card border h-100">
                                            <div class="card-header bg-light">
                                                <h6 class="mb-0 fw-semibold">
                                                    <i class="mdi mdi-cookie text-warning me-2"></i>Snacks Plan
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
                                                                        class="badge bg-{{ $employeePlan && $employeePlan->snacks_plan_status == 'Active' ? 'success' : 'secondary' }}">
                                                                        {{ $employeePlan->snacks_plan_status ?? 'Inactive' }}
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fw-semibold">From Date</td>
                                                                <td>{{ $employeePlan->snacks_plan_from ?? 'Not Set' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fw-semibold">To Date</td>
                                                                <td>{{ $employeePlan->snacks_plan_to ?? 'Not Set' }}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Food Com Plan -->
                                    <div class="col-md-6 mb-3">
                                        <div class="card border h-100">
                                            <div class="card-header bg-light">
                                                <h6 class="mb-0 fw-semibold">
                                                    <i class="mdi mdi-food-apple text-danger me-2"></i>Food Com Plan
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
                                                                        class="badge bg-{{ $employeePlan && $employeePlan->food_com_plan_status == 'Active' ? 'success' : 'secondary' }}">
                                                                        {{ $employeePlan->food_com_plan_status ?? 'Inactive' }}
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fw-semibold">From Date</td>
                                                                <td>{{ $employeePlan->food_com_plan_from ?? 'Not Set' }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="fw-semibold">To Date</td>
                                                                <td>{{ $employeePlan->food_com_plan_to ?? 'Not Set' }}</td>
                                                            </tr>
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
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('employees.index') }}" class="btn btn-secondary">Back to List</a>
                            
                                {{-- <a href="{{ route('employees.eligible_plans.edit', $employeePlan->id) }}"
                                    class="btn btn-primary">Edit Plans</a> --}}
                            

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
