@extends('structure.master')
@section('content')
    @include('employees.partials.creation_button')
    <div class="mt-4">
        <form class="" method="POST" enctype="multipart/form-data"
            action="{{ isset($employeePlan) ? route('employees.eligible_plans.update', $employeePlan->id) : route('employees.eligible_plans.store') }}"
            autocomplete="off">
            @if (isset($employeePlan))
                @method('PUT')
            @endif

            @csrf

            <!-- Employee Selection Section -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Employee Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6 mb-3">
                                    <label for="employee_id" class="form-label">Employee Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" readonly
                                           value="{{ $employee->full_name }}">

                                    <input type="hidden" name="employee_id" value="{{ $employee->id }}">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- All Plans Section -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Employee Eligible Plans</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Shift Plan -->
                                <div class="col-md-6 mb-3">
                                    <div class="card border h-100">
                                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0 fw-semibold">
                                                <i class="mdi mdi-clock-outline text-primary me-2"></i>Shift Plan
                                            </h6>
                                            <div class="form-check form-switch mb-0">
                                                <input type="hidden" name="shift_plan_status" value="inactive">
                                                <input class="form-check-input" type="checkbox" name="shift_plan_status"
                                                    id="shift_plan_status" value="active"
                                                    {{ (isset($employeePlan) && $employeePlan->shift_plan_status == 'active') || old('shift_plan_status') == 'active' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="shift_plan_status">Active</label>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="shift_plan_from" class="form-label">From Date</label>
                                                <input type="date"
                                                    class="form-control @error('shift_plan_from') is-invalid @enderror"
                                                    id="shift_plan_from" name="shift_plan_from"
                                                    value="{{ isset($employeePlan) && $employeePlan->shift_plan_from ? $employeePlan->shift_plan_from->format('Y-m-d') : old('shift_plan_from') }}">
                                                @error('shift_plan_from')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="mb-0">
                                                <label for="shift_plan_to" class="form-label">To Date</label>
                                                <input type="date"
                                                    class="form-control @error('shift_plan_to') is-invalid @enderror"
                                                    id="shift_plan_to" name="shift_plan_to"
                                                    value="{{ isset($employeePlan) && $employeePlan->shift_plan_to ? $employeePlan->shift_plan_to->format('Y-m-d') : old('shift_plan_to') }}">
                                                @error('shift_plan_to')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Leave Plan -->
                                <div class="col-md-6 mb-3">
                                    <div class="card border h-100">
                                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0 fw-semibold">
                                                <i class="mdi mdi-calendar-text text-success me-2"></i>Leave Plan
                                            </h6>
                                            <div class="form-check form-switch mb-0">
                                                <input type="hidden" name="leave_plan_status" value="inactive">
                                                <input class="form-check-input" type="checkbox" name="leave_plan_status"
                                                    id="leave_plan_status" value="active"
                                                    {{ (isset($employeePlan) && $employeePlan->leave_plan_status == 'active') || old('leave_plan_status') == 'active' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="leave_plan_status">Active</label>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="leave_plan_from" class="form-label">From Date</label>
                                                <input type="date"
                                                    class="form-control @error('leave_plan_from') is-invalid @enderror"
                                                    id="leave_plan_from" name="leave_plan_from"
                                                    value="{{ isset($employeePlan) && $employeePlan->leave_plan_from ? $employeePlan->leave_plan_from->format('Y-m-d') : old('leave_plan_from') }}">
                                                @error('leave_plan_from')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="mb-0">
                                                <label for="leave_plan_to" class="form-label">To Date</label>
                                                <input type="date"
                                                    class="form-control @error('leave_plan_to') is-invalid @enderror"
                                                    id="leave_plan_to" name="leave_plan_to"
                                                    value="{{ isset($employeePlan) && $employeePlan->leave_plan_to ? $employeePlan->leave_plan_to->format('Y-m-d') : old('leave_plan_to') }}">
                                                @error('leave_plan_to')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Roster Plans -->
                                <div class="col-md-6 mb-3">
                                    <div class="card border h-100">
                                        <div
                                            class="card-header bg-light d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0 fw-semibold">
                                                <i class="mdi mdi-calendar-month text-info me-2"></i>Roster Plans
                                            </h6>
                                            <div class="form-check form-switch mb-0">
                                                <input type="hidden" name="roster_plans_status" value="inactive">
                                                <input class="form-check-input" type="checkbox"
                                                    name="roster_plans_status" id="roster_plans_status" value="active"
                                                    {{ (isset($employeePlan) && $employeePlan->roster_plans_status == 'active') || old('roster_plans_status') == 'active' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="roster_plans_status">Active</label>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="roster_plans_from" class="form-label">From Date</label>
                                                <input type="date"
                                                    class="form-control @error('roster_plans_from') is-invalid @enderror"
                                                    id="roster_plans_from" name="roster_plans_from"
                                                    value="{{ isset($employeePlan) && $employeePlan->roster_plans_from ? $employeePlan->roster_plans_from->format('Y-m-d') : old('roster_plans_from') }}">
                                                @error('roster_plans_from')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="mb-0">
                                                <label for="roster_plans_to" class="form-label">To Date</label>
                                                <input type="date"
                                                    class="form-control @error('roster_plans_to') is-invalid @enderror"
                                                    id="roster_plans_to" name="roster_plans_to"
                                                    value="{{ isset($employeePlan) && $employeePlan->roster_plans_to ? $employeePlan->roster_plans_to->format('Y-m-d') : old('roster_plans_to') }}">
                                                @error('roster_plans_to')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Day Off Work Plan -->
                                <div class="col-md-6 mb-3">
                                    <div class="card border h-100">
                                        <div
                                            class="card-header bg-light d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0 fw-semibold">
                                                <i class="mdi mdi-beach text-warning me-2"></i>Day Off Work Plan
                                            </h6>
                                            <div class="form-check form-switch mb-0">
                                                <input type="hidden" name="day_off_work_plan_status" value="inactive">
                                                <input class="form-check-input" type="checkbox"
                                                    name="day_off_work_plan_status" id="day_off_work_plan_status"
                                                    value="active"
                                                    {{ (isset($employeePlan) && $employeePlan->day_off_work_plan_status == 'active') || old('day_off_work_plan_status') == 'active' ? 'checked' : '' }}>
                                                <label class="form-check-label"
                                                    for="day_off_work_plan_status">Active</label>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="day_off_work_plan_from" class="form-label">From Date</label>
                                                <input type="date"
                                                    class="form-control @error('day_off_work_plan_from') is-invalid @enderror"
                                                    id="day_off_work_plan_from" name="day_off_work_plan_from"
                                                    value="{{ isset($employeePlan) && $employeePlan->day_off_work_plan_from ? $employeePlan->day_off_work_plan_from->format('Y-m-d') : old('day_off_work_plan_from') }}">
                                                @error('day_off_work_plan_from')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="mb-0">
                                                <label for="day_off_work_plan_to" class="form-label">To Date</label>
                                                <input type="date"
                                                    class="form-control @error('day_off_work_plan_to') is-invalid @enderror"
                                                    id="day_off_work_plan_to" name="day_off_work_plan_to"
                                                    value="{{ isset($employeePlan) && $employeePlan->day_off_work_plan_to ? $employeePlan->day_off_work_plan_to->format('Y-m-d') : old('day_off_work_plan_to') }}">
                                                @error('day_off_work_plan_to')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- OT Plan -->
                                <div class="col-md-6 mb-3">
                                    <div class="card border h-100">
                                        <div
                                            class="card-header bg-light d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0 fw-semibold">
                                                <i class="mdi mdi-clock-check text-primary me-2"></i>OT Plan
                                            </h6>
                                            <div class="form-check form-switch mb-0">
                                                <input type="hidden" name="ot_plan_status" value="inactive">
                                                <input class="form-check-input" type="checkbox" name="ot_plan_status"
                                                    id="ot_plan_status" value="active"
                                                    {{ (isset($employeePlan) && $employeePlan->ot_plan_status == 'active') || old('ot_plan_status') == 'active' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="ot_plan_status">Active</label>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="ot_plan_from" class="form-label">From Date</label>
                                                <input type="date"
                                                    class="form-control @error('ot_plan_from') is-invalid @enderror"
                                                    id="ot_plan_from" name="ot_plan_from"
                                                    value="{{ isset($employeePlan) && $employeePlan->ot_plan_from ? $employeePlan->ot_plan_from->format('Y-m-d') : old('ot_plan_from') }}">
                                                @error('ot_plan_from')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="mb-0">
                                                <label for="ot_plan_to" class="form-label">To Date</label>
                                                <input type="date"
                                                    class="form-control @error('ot_plan_to') is-invalid @enderror"
                                                    id="ot_plan_to" name="ot_plan_to"
                                                    value="{{ isset($employeePlan) && $employeePlan->ot_plan_to ? $employeePlan->ot_plan_to->format('Y-m-d') : old('ot_plan_to') }}">
                                                @error('ot_plan_to')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Bonus Plan -->
                                <div class="col-md-6 mb-3">
                                    <div class="card border h-100">
                                        <div
                                            class="card-header bg-light d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0 fw-semibold">
                                                <i class="mdi mdi-gift text-success me-2"></i>Bonus Plan
                                            </h6>
                                            <div class="form-check form-switch mb-0">
                                                <input type="hidden" name="bonus_plan_status" value="inactive">
                                                <input class="form-check-input" type="checkbox" name="bonus_plan_status"
                                                    id="bonus_plan_status" value="active"
                                                    {{ (isset($employeePlan) && $employeePlan->bonus_plan_status == 'active') || old('bonus_plan_status') == 'active' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="bonus_plan_status">Active</label>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="bonus_plan_from" class="form-label">From Date</label>
                                                <input type="date"
                                                    class="form-control @error('bonus_plan_from') is-invalid @enderror"
                                                    id="bonus_plan_from" name="bonus_plan_from"
                                                    value="{{ isset($employeePlan) && $employeePlan->bonus_plan_from ? $employeePlan->bonus_plan_from->format('Y-m-d') : old('bonus_plan_from') }}">
                                                @error('bonus_plan_from')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="mb-0">
                                                <label for="bonus_plan_to" class="form-label">To Date</label>
                                                <input type="date"
                                                    class="form-control @error('bonus_plan_to') is-invalid @enderror"
                                                    id="bonus_plan_to" name="bonus_plan_to"
                                                    value="{{ isset($employeePlan) && $employeePlan->bonus_plan_to ? $employeePlan->bonus_plan_to->format('Y-m-d') : old('bonus_plan_to') }}">
                                                @error('bonus_plan_to')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Allowance Plan -->
                                <div class="col-md-6 mb-3">
                                    <div class="card border h-100">
                                        <div
                                            class="card-header bg-light d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0 fw-semibold">
                                                <i class="mdi mdi-cash-multiple text-info me-2"></i>Allowance Plan
                                            </h6>
                                            <div class="form-check form-switch mb-0">
                                                <input type="hidden" name="allowance_plan_status" value="inactive">
                                                <input class="form-check-input" type="checkbox"
                                                    name="allowance_plan_status" id="allowance_plan_status"
                                                    value="active"
                                                    {{ (isset($employeePlan) && $employeePlan->allowance_plan_status == 'active') || old('allowance_plan_status') == 'active' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="allowance_plan_status">Active</label>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="allowance_plan_from" class="form-label">From Date</label>
                                                <input type="date"
                                                    class="form-control @error('allowance_plan_from') is-invalid @enderror"
                                                    id="allowance_plan_from" name="allowance_plan_from"
                                                    value="{{ isset($employeePlan) && $employeePlan->allowance_plan_from ? $employeePlan->allowance_plan_from->format('Y-m-d') : old('allowance_plan_from') }}">
                                                @error('allowance_plan_from')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="mb-0">
                                                <label for="allowance_plan_to" class="form-label">To Date</label>
                                                <input type="date"
                                                    class="form-control @error('allowance_plan_to') is-invalid @enderror"
                                                    id="allowance_plan_to" name="allowance_plan_to"
                                                    value="{{ isset($employeePlan) && $employeePlan->allowance_plan_to ? $employeePlan->allowance_plan_to->format('Y-m-d') : old('allowance_plan_to') }}">
                                                @error('allowance_plan_to')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Attendance Bonus Plan -->
                                <div class="col-md-6 mb-3">
                                    <div class="card border h-100">
                                        <div
                                            class="card-header bg-light d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0 fw-semibold">
                                                <i class="mdi mdi-star text-warning me-2"></i>Attendance Bonus Plan
                                            </h6>
                                            <div class="form-check form-switch mb-0">
                                                <input type="hidden" name="attendance_bonus_plan_status"
                                                    value="inactive">
                                                <input class="form-check-input" type="checkbox"
                                                    name="attendance_bonus_plan_status" id="attendance_bonus_plan_status"
                                                    value="active"
                                                    {{ (isset($employeePlan) && $employeePlan->attendance_bonus_plan_status == 'active') || old('attendance_bonus_plan_status') == 'active' ? 'checked' : '' }}>
                                                <label class="form-check-label"
                                                    for="attendance_bonus_plan_status">Active</label>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="attendance_bonus_plan_from" class="form-label">From
                                                    Date</label>
                                                <input type="date"
                                                    class="form-control @error('attendance_bonus_plan_from') is-invalid @enderror"
                                                    id="attendance_bonus_plan_from" name="attendance_bonus_plan_from"
                                                    value="{{ isset($employeePlan) && $employeePlan->attendance_bonus_plan_from ? $employeePlan->attendance_bonus_plan_from->format('Y-m-d') : old('attendance_bonus_plan_from') }}">
                                                @error('attendance_bonus_plan_from')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="mb-0">
                                                <label for="attendance_bonus_plan_to" class="form-label">To Date</label>
                                                <input type="date"
                                                    class="form-control @error('attendance_bonus_plan_to') is-invalid @enderror"
                                                    id="attendance_bonus_plan_to" name="attendance_bonus_plan_to"
                                                    value="{{ isset($employeePlan) && $employeePlan->attendance_bonus_plan_to ? $employeePlan->attendance_bonus_plan_to->format('Y-m-d') : old('attendance_bonus_plan_to') }}">
                                                @error('attendance_bonus_plan_to')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Production Plan -->
                                <div class="col-md-6 mb-3">
                                    <div class="card border h-100">
                                        <div
                                            class="card-header bg-light d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0 fw-semibold">
                                                <i class="mdi mdi-factory text-danger me-2"></i>Production Plan
                                            </h6>
                                            <div class="form-check form-switch mb-0">
                                                <input type="hidden" name="production_plan_status" value="inactive">
                                                <input class="form-check-input" type="checkbox"
                                                    name="production_plan_status" id="production_plan_status"
                                                    value="active"
                                                    {{ (isset($employeePlan) && $employeePlan->production_plan_status == 'active') || old('production_plan_status') == 'active' ? 'checked' : '' }}>
                                                <label class="form-check-label"
                                                    for="production_plan_status">Active</label>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="production_plan_from" class="form-label">From Date</label>
                                                <input type="date"
                                                    class="form-control @error('production_plan_from') is-invalid @enderror"
                                                    id="production_plan_from" name="production_plan_from"
                                                    value="{{ isset($employeePlan) && $employeePlan->production_plan_from ? $employeePlan->production_plan_from->format('Y-m-d') : old('production_plan_from') }}">
                                                @error('production_plan_from')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="mb-0">
                                                <label for="production_plan_to" class="form-label">To Date</label>
                                                <input type="date"
                                                    class="form-control @error('production_plan_to') is-invalid @enderror"
                                                    id="production_plan_to" name="production_plan_to"
                                                    value="{{ isset($employeePlan) && $employeePlan->production_plan_to ? $employeePlan->production_plan_to->format('Y-m-d') : old('production_plan_to') }}">
                                                @error('production_plan_to')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Salary Breakdown Plan -->
                                <div class="col-md-6 mb-3">
                                    <div class="card border h-100">
                                        <div
                                            class="card-header bg-light d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0 fw-semibold">
                                                <i class="mdi mdi-currency-usd text-success me-2"></i>Salary Breakdown Plan
                                            </h6>
                                            <div class="form-check form-switch mb-0">
                                                <input type="hidden" name="salary_breakdown_plan_status"
                                                    value="inactive">
                                                <input class="form-check-input" type="checkbox"
                                                    name="salary_breakdown_plan_status" id="salary_breakdown_plan_status"
                                                    value="active"
                                                    {{ (isset($employeePlan) && $employeePlan->salary_breakdown_plan_status == 'active') || old('salary_breakdown_plan_status') == 'active' ? 'checked' : '' }}>
                                                <label class="form-check-label"
                                                    for="salary_breakdown_plan_status">Active</label>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="salary_breakdown_plan_from" class="form-label">From
                                                    Date</label>
                                                <input type="date"
                                                    class="form-control @error('salary_breakdown_plan_from') is-invalid @enderror"
                                                    id="salary_breakdown_plan_from" name="salary_breakdown_plan_from"
                                                    value="{{ isset($employeePlan) && $employeePlan->salary_breakdown_plan_from ? $employeePlan->salary_breakdown_plan_from->format('Y-m-d') : old('salary_breakdown_plan_from') }}">
                                                @error('salary_breakdown_plan_from')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="mb-0">
                                                <label for="salary_breakdown_plan_to" class="form-label">To Date</label>
                                                <input type="date"
                                                    class="form-control @error('salary_breakdown_plan_to') is-invalid @enderror"
                                                    id="salary_breakdown_plan_to" name="salary_breakdown_plan_to"
                                                    value="{{ isset($employeePlan) && $employeePlan->salary_breakdown_plan_to ? $employeePlan->salary_breakdown_plan_to->format('Y-m-d') : old('salary_breakdown_plan_to') }}">
                                                @error('salary_breakdown_plan_to')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Late Deduction Plan -->
                                <div class="col-md-6 mb-3">
                                    <div class="card border h-100">
                                        <div
                                            class="card-header bg-light d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0 fw-semibold">
                                                <i class="mdi mdi-clock-alert text-warning me-2"></i>Late Deduction Plan
                                            </h6>
                                            <div class="form-check form-switch mb-0">
                                                <input type="hidden" name="late_deduction_plan_status" value="inactive">
                                                <input class="form-check-input" type="checkbox"
                                                    name="late_deduction_plan_status" id="late_deduction_plan_status"
                                                    value="active"
                                                    {{ (isset($employeePlan) && $employeePlan->late_deduction_plan_status == 'active') || old('late_deduction_plan_status') == 'active' ? 'checked' : '' }}>
                                                <label class="form-check-label"
                                                    for="late_deduction_plan_status">Active</label>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="late_deduction_plan_from" class="form-label">From Date</label>
                                                <input type="date"
                                                    class="form-control @error('late_deduction_plan_from') is-invalid @enderror"
                                                    id="late_deduction_plan_from" name="late_deduction_plan_from"
                                                    value="{{ isset($employeePlan) && $employeePlan->late_deduction_plan_from ? $employeePlan->late_deduction_plan_from->format('Y-m-d') : old('late_deduction_plan_from') }}">
                                                @error('late_deduction_plan_from')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="mb-0">
                                                <label for="late_deduction_plan_to" class="form-label">To Date</label>
                                                <input type="date"
                                                    class="form-control @error('late_deduction_plan_to') is-invalid @enderror"
                                                    id="late_deduction_plan_to" name="late_deduction_plan_to"
                                                    value="{{ isset($employeePlan) && $employeePlan->late_deduction_plan_to ? $employeePlan->late_deduction_plan_to->format('Y-m-d') : old('late_deduction_plan_to') }}">
                                                @error('late_deduction_plan_to')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Early Out Deduction Plan -->
                                <div class="col-md-6 mb-3">
                                    <div class="card border h-100">
                                        <div
                                            class="card-header bg-light d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0 fw-semibold">
                                                <i class="mdi mdi-exit-run text-danger me-2"></i>Early Out Deduction Plan
                                            </h6>
                                            <div class="form-check form-switch mb-0">
                                                <input type="hidden" name="early_out_deduction_plan_status"
                                                    value="inactive">
                                                <input class="form-check-input" type="checkbox"
                                                    name="early_out_deduction_plan_status"
                                                    id="early_out_deduction_plan_status" value="active"
                                                    {{ (isset($employeePlan) && $employeePlan->early_out_deduction_plan_status == 'active') || old('early_out_deduction_plan_status') == 'active' ? 'checked' : '' }}>
                                                <label class="form-check-label"
                                                    for="early_out_deduction_plan_status">Active</label>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="early_out_deduction_plan_from" class="form-label">From
                                                    Date</label>
                                                <input type="date"
                                                    class="form-control @error('early_out_deduction_plan_from') is-invalid @enderror"
                                                    id="early_out_deduction_plan_from"
                                                    name="early_out_deduction_plan_from"
                                                    value="{{ isset($employeePlan) && $employeePlan->early_out_deduction_plan_from ? $employeePlan->early_out_deduction_plan_from->format('Y-m-d') : old('early_out_deduction_plan_from') }}">
                                                @error('early_out_deduction_plan_from')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="mb-0">
                                                <label for="early_out_deduction_plan_to" class="form-label">To
                                                    Date</label>
                                                <input type="date"
                                                    class="form-control @error('early_out_deduction_plan_to') is-invalid @enderror"
                                                    id="early_out_deduction_plan_to" name="early_out_deduction_plan_to"
                                                    value="{{ isset($employeePlan) && $employeePlan->early_out_deduction_plan_to ? $employeePlan->early_out_deduction_plan_to->format('Y-m-d') : old('early_out_deduction_plan_to') }}">
                                                @error('early_out_deduction_plan_to')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Excessive Late Plan -->
                                <div class="col-md-6 mb-3">
                                    <div class="card border h-100">
                                        <div
                                            class="card-header bg-light d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0 fw-semibold">
                                                <i class="mdi mdi-alarm-multiple text-warning me-2"></i>Excessive Late Plan
                                            </h6>
                                            <div class="form-check form-switch mb-0">
                                                <input type="hidden" name="excessive_late_plan_status" value="inactive">
                                                <input class="form-check-input" type="checkbox"
                                                    name="excessive_late_plan_status" id="excessive_late_plan_status"
                                                    value="active"
                                                    {{ (isset($employeePlan) && $employeePlan->excessive_late_plan_status == 'active') || old('excessive_late_plan_status') == 'active' ? 'checked' : '' }}>
                                                <label class="form-check-label"
                                                    for="excessive_late_plan_status">Active</label>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="excessive_late_plan_from" class="form-label">From Date</label>
                                                <input type="date"
                                                    class="form-control @error('excessive_late_plan_from') is-invalid @enderror"
                                                    id="excessive_late_plan_from" name="excessive_late_plan_from"
                                                    value="{{ isset($employeePlan) && $employeePlan->excessive_late_plan_from ? $employeePlan->excessive_late_plan_from->format('Y-m-d') : old('excessive_late_plan_from') }}">
                                                @error('excessive_late_plan_from')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="mb-0">
                                                <label for="excessive_late_plan_to" class="form-label">To Date</label>
                                                <input type="date"
                                                    class="form-control @error('excessive_late_plan_to') is-invalid @enderror"
                                                    id="excessive_late_plan_to" name="excessive_late_plan_to"
                                                    value="{{ isset($employeePlan) && $employeePlan->excessive_late_plan_to ? $employeePlan->excessive_late_plan_to->format('Y-m-d') : old('excessive_late_plan_to') }}">
                                                @error('excessive_late_plan_to')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Medical Plan -->
                                <div class="col-md-6 mb-3">
                                    <div class="card border h-100">
                                        <div
                                            class="card-header bg-light d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0 fw-semibold">
                                                <i class="mdi mdi-hospital-box text-danger me-2"></i>Medical Plan
                                            </h6>
                                            <div class="form-check form-switch mb-0">
                                                <input type="hidden" name="medical_plan_status" value="inactive">
                                                <input class="form-check-input" type="checkbox"
                                                    name="medical_plan_status" id="medical_plan_status" value="active"
                                                    {{ (isset($employeePlan) && $employeePlan->medical_plan_status == 'active') || old('medical_plan_status') == 'active' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="medical_plan_status">Active</label>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="medical_plan_from" class="form-label">From Date</label>
                                                <input type="date"
                                                    class="form-control @error('medical_plan_from') is-invalid @enderror"
                                                    id="medical_plan_from" name="medical_plan_from"
                                                    value="{{ isset($employeePlan) && $employeePlan->medical_plan_from ? $employeePlan->medical_plan_from->format('Y-m-d') : old('medical_plan_from') }}">
                                                @error('medical_plan_from')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="mb-0">
                                                <label for="medical_plan_to" class="form-label">To Date</label>
                                                <input type="date"
                                                    class="form-control @error('medical_plan_to') is-invalid @enderror"
                                                    id="medical_plan_to" name="medical_plan_to"
                                                    value="{{ isset($employeePlan) && $employeePlan->medical_plan_to ? $employeePlan->medical_plan_to->format('Y-m-d') : old('medical_plan_to') }}">
                                                @error('medical_plan_to')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Night Bill Plan -->
                                <div class="col-md-6 mb-3">
                                    <div class="card border h-100">
                                        <div
                                            class="card-header bg-light d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0 fw-semibold">
                                                <i class="mdi mdi-weather-night text-primary me-2"></i>Night Bill Plan
                                            </h6>
                                            <div class="form-check form-switch mb-0">
                                                <input type="hidden" name="night_bill_plan_status" value="inactive">
                                                <input class="form-check-input" type="checkbox"
                                                    name="night_bill_plan_status" id="night_bill_plan_status"
                                                    value="active"
                                                    {{ (isset($employeePlan) && $employeePlan->night_bill_plan_status == 'active') || old('night_bill_plan_status') == 'active' ? 'checked' : '' }}>
                                                <label class="form-check-label"
                                                    for="night_bill_plan_status">Active</label>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="night_bill_plan_from" class="form-label">From Date</label>
                                                <input type="date"
                                                    class="form-control @error('night_bill_plan_from') is-invalid @enderror"
                                                    id="night_bill_plan_from" name="night_bill_plan_from"
                                                    value="{{ isset($employeePlan) && $employeePlan->night_bill_plan_from ? $employeePlan->night_bill_plan_from->format('Y-m-d') : old('night_bill_plan_from') }}">
                                                @error('night_bill_plan_from')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="mb-0">
                                                <label for="night_bill_plan_to" class="form-label">To Date</label>
                                                <input type="date"
                                                    class="form-control @error('night_bill_plan_to') is-invalid @enderror"
                                                    id="night_bill_plan_to" name="night_bill_plan_to"
                                                    value="{{ isset($employeePlan) && $employeePlan->night_bill_plan_to ? $employeePlan->night_bill_plan_to->format('Y-m-d') : old('night_bill_plan_to') }}">
                                                @error('night_bill_plan_to')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Breakfast Plan -->
                                <div class="col-md-6 mb-3">
                                    <div class="card border h-100">
                                        <div
                                            class="card-header bg-light d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0 fw-semibold">
                                                <i class="mdi mdi-coffee text-warning me-2"></i>Breakfast Plan
                                            </h6>
                                            <div class="form-check form-switch mb-0">
                                                <input type="hidden" name="breakfast_plan_status" value="inactive">
                                                <input class="form-check-input" type="checkbox"
                                                    name="breakfast_plan_status" id="breakfast_plan_status"
                                                    value="active"
                                                    {{ (isset($employeePlan) && $employeePlan->breakfast_plan_status == 'active') || old('breakfast_plan_status') == 'active' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="breakfast_plan_status">Active</label>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="breakfast_plan_from" class="form-label">From Date</label>
                                                <input type="date"
                                                    class="form-control @error('breakfast_plan_from') is-invalid @enderror"
                                                    id="breakfast_plan_from" name="breakfast_plan_from"
                                                    value="{{ isset($employeePlan) && $employeePlan->breakfast_plan_from ? $employeePlan->breakfast_plan_from->format('Y-m-d') : old('breakfast_plan_from') }}">
                                                @error('breakfast_plan_from')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="mb-0">
                                                <label for="breakfast_plan_to" class="form-label">To Date</label>
                                                <input type="date"
                                                    class="form-control @error('breakfast_plan_to') is-invalid @enderror"
                                                    id="breakfast_plan_to" name="breakfast_plan_to"
                                                    value="{{ isset($employeePlan) && $employeePlan->breakfast_plan_to ? $employeePlan->breakfast_plan_to->format('Y-m-d') : old('breakfast_plan_to') }}">
                                                @error('breakfast_plan_to')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Lunch Plan -->
                                <div class="col-md-6 mb-3">
                                    <div class="card border h-100">
                                        <div
                                            class="card-header bg-light d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0 fw-semibold">
                                                <i class="mdi mdi-food text-success me-2"></i>Lunch Plan
                                            </h6>
                                            <div class="form-check form-switch mb-0">
                                                <input type="hidden" name="lunch_plan_status" value="inactive">
                                                <input class="form-check-input" type="checkbox" name="lunch_plan_status"
                                                    id="lunch_plan_status" value="active"
                                                    {{ (isset($employeePlan) && $employeePlan->lunch_plan_status == 'active') || old('lunch_plan_status') == 'active' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="lunch_plan_status">Active</label>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="lunch_plan_from" class="form-label">From Date</label>
                                                <input type="date"
                                                    class="form-control @error('lunch_plan_from') is-invalid @enderror"
                                                    id="lunch_plan_from" name="lunch_plan_from"
                                                    value="{{ isset($employeePlan) && $employeePlan->lunch_plan_from ? $employeePlan->lunch_plan_from->format('Y-m-d') : old('lunch_plan_from') }}">
                                                @error('lunch_plan_from')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="mb-0">
                                                <label for="lunch_plan_to" class="form-label">To Date</label>
                                                <input type="date"
                                                    class="form-control @error('lunch_plan_to') is-invalid @enderror"
                                                    id="lunch_plan_to" name="lunch_plan_to"
                                                    value="{{ isset($employeePlan) && $employeePlan->lunch_plan_to ? $employeePlan->lunch_plan_to->format('Y-m-d') : old('lunch_plan_to') }}">
                                                @error('lunch_plan_to')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tiffin Plan -->
                                <div class="col-md-6 mb-3">
                                    <div class="card border h-100">
                                        <div
                                            class="card-header bg-light d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0 fw-semibold">
                                                <i class="mdi mdi-food-variant text-info me-2"></i>Tiffin Plan
                                            </h6>
                                            <div class="form-check form-switch mb-0">
                                                <input type="hidden" name="tiffin_plan_status" value="inactive">
                                                <input class="form-check-input" type="checkbox" name="tiffin_plan_status"
                                                    id="tiffin_plan_status" value="active"
                                                    {{ (isset($employeePlan) && $employeePlan->tiffin_plan_status == 'active') || old('tiffin_plan_status') == 'active' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="tiffin_plan_status">Active</label>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="tiffin_plan_from" class="form-label">From Date</label>
                                                <input type="date"
                                                    class="form-control @error('tiffin_plan_from') is-invalid @enderror"
                                                    id="tiffin_plan_from" name="tiffin_plan_from"
                                                    value="{{ isset($employeePlan) && $employeePlan->tiffin_plan_from ? $employeePlan->tiffin_plan_from->format('Y-m-d') : old('tiffin_plan_from') }}">
                                                @error('tiffin_plan_from')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="mb-0">
                                                <label for="tiffin_plan_to" class="form-label">To Date</label>
                                                <input type="date"
                                                    class="form-control @error('tiffin_plan_to') is-invalid @enderror"
                                                    id="tiffin_plan_to" name="tiffin_plan_to"
                                                    value="{{ isset($employeePlan) && $employeePlan->tiffin_plan_to ? $employeePlan->tiffin_plan_to->format('Y-m-d') : old('tiffin_plan_to') }}">
                                                @error('tiffin_plan_to')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Dinner Plan -->
                                <div class="col-md-6 mb-3">
                                    <div class="card border h-100">
                                        <div
                                            class="card-header bg-light d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0 fw-semibold">
                                                <i class="mdi mdi-silverware-fork-knife text-primary me-2"></i>Dinner Plan
                                            </h6>
                                            <div class="form-check form-switch mb-0">
                                                <input type="hidden" name="dinner_plan_status" value="inactive">
                                                <input class="form-check-input" type="checkbox" name="dinner_plan_status"
                                                    id="dinner_plan_status" value="active"
                                                    {{ (isset($employeePlan) && $employeePlan->dinner_plan_status == 'active') || old('dinner_plan_status') == 'active' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="dinner_plan_status">Active</label>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="dinner_plan_from" class="form-label">From Date</label>
                                                <input type="date"
                                                    class="form-control @error('dinner_plan_from') is-invalid @enderror"
                                                    id="dinner_plan_from" name="dinner_plan_from"
                                                    value="{{ isset($employeePlan) && $employeePlan->dinner_plan_from ? $employeePlan->dinner_plan_from->format('Y-m-d') : old('dinner_plan_from') }}">
                                                @error('dinner_plan_from')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="mb-0">
                                                <label for="dinner_plan_to" class="form-label">To Date</label>
                                                <input type="date"
                                                    class="form-control @error('dinner_plan_to') is-invalid @enderror"
                                                    id="dinner_plan_to" name="dinner_plan_to"
                                                    value="{{ isset($employeePlan) && $employeePlan->dinner_plan_to ? $employeePlan->dinner_plan_to->format('Y-m-d') : old('dinner_plan_to') }}">
                                                @error('dinner_plan_to')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Snacks Plan -->
                                <div class="col-md-6 mb-3">
                                    <div class="card border h-100">
                                        <div
                                            class="card-header bg-light d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0 fw-semibold">
                                                <i class="mdi mdi-cookie text-warning me-2"></i>Snacks Plan
                                            </h6>
                                            <div class="form-check form-switch mb-0">
                                                <input type="hidden" name="snacks_plan_status" value="inactive">
                                                <input class="form-check-input" type="checkbox" name="snacks_plan_status"
                                                    id="snacks_plan_status" value="active"
                                                    {{ (isset($employeePlan) && $employeePlan->snacks_plan_status == 'active') || old('snacks_plan_status') == 'active' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="snacks_plan_status">Active</label>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="snacks_plan_from" class="form-label">From Date</label>
                                                <input type="date"
                                                    class="form-control @error('snacks_plan_from') is-invalid @enderror"
                                                    id="snacks_plan_from" name="snacks_plan_from"
                                                    value="{{ isset($employeePlan) && $employeePlan->snacks_plan_from ? $employeePlan->snacks_plan_from->format('Y-m-d') : old('snacks_plan_from') }}">
                                                @error('snacks_plan_from')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="mb-0">
                                                <label for="snacks_plan_to" class="form-label">To Date</label>
                                                <input type="date"
                                                    class="form-control @error('snacks_plan_to') is-invalid @enderror"
                                                    id="snacks_plan_to" name="snacks_plan_to"
                                                    value="{{ isset($employeePlan) && $employeePlan->snacks_plan_to ? $employeePlan->snacks_plan_to->format('Y-m-d') : old('snacks_plan_to') }}">
                                                @error('snacks_plan_to')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Food Com Plan -->
                                <div class="col-md-6 mb-3">
                                    <div class="card border h-100">
                                        <div
                                            class="card-header bg-light d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0 fw-semibold">
                                                <i class="mdi mdi-food-apple text-danger me-2"></i>Food Com Plan
                                            </h6>
                                            <div class="form-check form-switch mb-0">
                                                <input type="hidden" name="food_com_plan_status" value="inactive">
                                                <input class="form-check-input" type="checkbox"
                                                    name="food_com_plan_status" id="food_com_plan_status" value="active"
                                                    {{ (isset($employeePlan) && $employeePlan->food_com_plan_status == 'active') || old('food_com_plan_status') == 'active' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="food_com_plan_status">Active</label>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="food_com_plan_from" class="form-label">From Date</label>
                                                <input type="date"
                                                    class="form-control @error('food_com_plan_from') is-invalid @enderror"
                                                    id="food_com_plan_from" name="food_com_plan_from"
                                                    value="{{ isset($employeePlan) && $employeePlan->food_com_plan_from ? $employeePlan->food_com_plan_from->format('Y-m-d') : old('food_com_plan_from') }}">
                                                @error('food_com_plan_from')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="mb-0">
                                                <label for="food_com_plan_to" class="form-label">To Date</label>
                                                <input type="date"
                                                    class="form-control @error('food_com_plan_to') is-invalid @enderror"
                                                    id="food_com_plan_to" name="food_com_plan_to"
                                                    value="{{ isset($employeePlan) && $employeePlan->food_com_plan_to ? $employeePlan->food_com_plan_to->format('Y-m-d') : old('food_com_plan_to') }}">
                                                @error('food_com_plan_to')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-end gap-2">
                                <button type="reset" class="btn btn-secondary">Reset</button>
                                <button type="submit" class="btn btn-primary">Submit Employee Plans</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>
@endsection
