@extends('structure.master')
@section('content')
    @if (Route::currentRouteNamed('employees.eligible_plans.create'))
        @include('employees.partials.creation_button')
    @endif
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
                                    <input type="text" class="form-control" readonly value="{{ $employee->full_name }}">

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
                                            {{--<div class="mb-0">
                                                <label for="shift_plan_to" class="form-label">To Date</label>
                                                <input type="date"
                                                    class="form-control @error('shift_plan_to') is-invalid @enderror"
                                                    id="shift_plan_to" name="shift_plan_to"
                                                    value="{{ isset($employeePlan) && $employeePlan->shift_plan_to ? $employeePlan->shift_plan_to->format('Y-m-d') : old('shift_plan_to') }}">
                                                @error('shift_plan_to')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>--}}
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
                                            {{--<div class="mb-0">
                                                <label for="leave_plan_to" class="form-label">To Date</label>
                                                <input type="date"
                                                    class="form-control @error('leave_plan_to') is-invalid @enderror"
                                                    id="leave_plan_to" name="leave_plan_to"
                                                    value="{{ isset($employeePlan) && $employeePlan->leave_plan_to ? $employeePlan->leave_plan_to->format('Y-m-d') : old('leave_plan_to') }}">
                                                @error('leave_plan_to')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>--}}
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
                                            {{--<div class="mb-0">
                                                <label for="roster_plans_to" class="form-label">To Date</label>
                                                <input type="date"
                                                    class="form-control @error('roster_plans_to') is-invalid @enderror"
                                                    id="roster_plans_to" name="roster_plans_to"
                                                    value="{{ isset($employeePlan) && $employeePlan->roster_plans_to ? $employeePlan->roster_plans_to->format('Y-m-d') : old('roster_plans_to') }}">
                                                @error('roster_plans_to')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>--}}
                                        </div>
                                    </div>
                                </div>

                                <!-- Off Day Work Plan -->
                                <div class="col-md-6 mb-3">
                                    <div class="card border h-100">
                                        <div
                                            class="card-header bg-light d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0 fw-semibold">
                                                <i class="mdi mdi-beach text-warning me-2"></i>Off Day Work Plan
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
                                            {{--<div class="mb-0">
                                                <label for="day_off_work_plan_to" class="form-label">To Date</label>
                                                <input type="date"
                                                    class="form-control @error('day_off_work_plan_to') is-invalid @enderror"
                                                    id="day_off_work_plan_to" name="day_off_work_plan_to"
                                                    value="{{ isset($employeePlan) && $employeePlan->day_off_work_plan_to ? $employeePlan->day_off_work_plan_to->format('Y-m-d') : old('day_off_work_plan_to') }}">
                                                @error('day_off_work_plan_to')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>--}}
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
                                            {{--<div class="mb-0">
                                                <label for="ot_plan_to" class="form-label">To Date</label>
                                                <input type="date"
                                                    class="form-control @error('ot_plan_to') is-invalid @enderror"
                                                    id="ot_plan_to" name="ot_plan_to"
                                                    value="{{ isset($employeePlan) && $employeePlan->ot_plan_to ? $employeePlan->ot_plan_to->format('Y-m-d') : old('ot_plan_to') }}">
                                                @error('ot_plan_to')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>--}}
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
                                            {{--<div class="mb-0">
                                                <label for="bonus_plan_to" class="form-label">To Date</label>
                                                <input type="date"
                                                    class="form-control @error('bonus_plan_to') is-invalid @enderror"
                                                    id="bonus_plan_to" name="bonus_plan_to"
                                                    value="{{ isset($employeePlan) && $employeePlan->bonus_plan_to ? $employeePlan->bonus_plan_to->format('Y-m-d') : old('bonus_plan_to') }}">
                                                @error('bonus_plan_to')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>--}}
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
                                            {{--<div class="mb-0">
                                                <label for="allowance_plan_to" class="form-label">To Date</label>
                                                <input type="date"
                                                    class="form-control @error('allowance_plan_to') is-invalid @enderror"
                                                    id="allowance_plan_to" name="allowance_plan_to"
                                                    value="{{ isset($employeePlan) && $employeePlan->allowance_plan_to ? $employeePlan->allowance_plan_to->format('Y-m-d') : old('allowance_plan_to') }}">
                                                @error('allowance_plan_to')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>--}}
                                        </div>
                                    </div>
                                </div>

                                <!-- Meal Plan -->
                                <div class="col-md-6 mb-3">
                                    <div class="card border h-100">
                                        <div
                                            class="card-header bg-light d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0 fw-semibold">
                                                <i class="mdi mdi-food text-success me-2"></i>Meal Plan
                                            </h6>
                                            <div class="form-check form-switch mb-0">
                                                <input type="hidden" name="meal_plan_status" value="inactive">
                                                <input class="form-check-input" type="checkbox" name="meal_plan_status"
                                                    id="meal_plan_status" value="active"
                                                    {{ (isset($employeePlan) && $employeePlan->meal_plan_status == 'active') || old('meal_plan_status') == 'active' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="meal_plan_status">Active</label>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="meal_plan_from" class="form-label">From Date</label>
                                                <input type="date"
                                                    class="form-control @error('meal_plan_from') is-invalid @enderror"
                                                    id="meal_plan_from" name="meal_plan_from"
                                                    value="{{ isset($employeePlan) && $employeePlan->meal_plan_from ? $employeePlan->meal_plan_from->format('Y-m-d') : old('meal_plan_from') }}">
                                                @error('meal_plan_from')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            {{--<div class="mb-0">
                                                <label for="meal_plan_to" class="form-label">To Date</label>
                                                <input type="date"
                                                    class="form-control @error('meal_plan_to') is-invalid @enderror"
                                                    id="meal_plan_to" name="meal_plan_to"
                                                    value="{{ isset($employeePlan) && $employeePlan->meal_plan_to ? $employeePlan->meal_plan_to->format('Y-m-d') : old('meal_plan_to') }}">
                                                @error('meal_plan_to')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>--}}
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
                                            {{--<div class="mb-0">
                                                <label for="late_deduction_plan_to" class="form-label">To Date</label>
                                                <input type="date"
                                                    class="form-control @error('late_deduction_plan_to') is-invalid @enderror"
                                                    id="late_deduction_plan_to" name="late_deduction_plan_to"
                                                    value="{{ isset($employeePlan) && $employeePlan->late_deduction_plan_to ? $employeePlan->late_deduction_plan_to->format('Y-m-d') : old('late_deduction_plan_to') }}">
                                                @error('late_deduction_plan_to')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>--}}
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
{{--
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
--}}
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
{{--
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
--}}
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
                                            {{--<div class="mb-0">
                                                <label for="medical_plan_to" class="form-label">To Date</label>
                                                <input type="date"
                                                    class="form-control @error('medical_plan_to') is-invalid @enderror"
                                                    id="medical_plan_to" name="medical_plan_to"
                                                    value="{{ isset($employeePlan) && $employeePlan->medical_plan_to ? $employeePlan->medical_plan_to->format('Y-m-d') : old('medical_plan_to') }}">
                                                @error('medical_plan_to')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>--}}
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
                                <button type="button" id="previewBtn" class="btn btn-info text-white">
                                    <i class="mdi mdi-eye me-1"></i> Preview
                                </button>
                                <button type="submit" class="btn btn-primary">Submit Employee Plans</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>
    @include('employees.partials.preview_modal')
@endsection
