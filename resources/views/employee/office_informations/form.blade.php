@extends('structure.master')
@section('content')
    @php
        $isEdit = isset($employee_office_info);
        $generalSettings = App\HelperClass::getGeneralSetting();
    @endphp

    <div class="mt-4">
        <form method="POST" enctype="multipart/form-data" 
              action="{{ $isEdit ? route('employee.office_informations.update', $employee_office_info->id) : route('employee.office_informations.store') }}">
            @if($isEdit)
                @method('PUT')
            @endif
            @csrf

            <input type="hidden" name="employee_id" value="{{ $employee->id }}">

            <!-- Payroll Information Section -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header py-3">
                            <h5 class="card-title mb-0">Payroll Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-lg-6">
                                    <label for="emp_type" class="form-label fw-semibold">Employee Type <span class="text-danger">*</span></label>
                                    <select class="form-select select2_list @error('emp_type') is-invalid @enderror" id="emp_type"
                                        name="emp_type" data-placeholder="Select Type">
                                        <option value="">Select Type</option>
                                        <option value="permanent" {{ old('emp_type', $employee_office_info->emp_type ?? '') == 'permanent' ? 'selected' : '' }}>Permanent</option>
                                        <option value="contractual" {{ old('emp_type', $employee_office_info->emp_type ?? '') == 'contractual' ? 'selected' : '' }}>Contractual</option>
                                    </select>
                                    @error('emp_type')
                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>



                                <div class="col-lg-6">
                                    <label for="hr_file_no" class="form-label fw-semibold">HR File Number</label>
                                    <input type="text" class="form-control @error('hr_file_no') is-invalid @enderror"
                                        id="hr_file_no" name="hr_file_no" value="{{ old('hr_file_no', $employee_office_info->hr_file_no ?? '') }}">
                                    @error('hr_file_no')
                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-6">
                                    <label for="file_note" class="form-label fw-semibold">HR File Note</label>
                                    <input type="text" class="form-control @error('file_note') is-invalid @enderror"
                                           id="file_note" name="file_note" value="{{ old('file_note', $employee_office_info->file_note ?? '') }}">
                                    @error('file_note')
                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Joining Information Section -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header py-3">
                            <h5 class="card-title mb-0">Joining Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-lg-4">
                                    <label for="joining_company_id" class="form-label fw-semibold">Company <span class="text-danger">*</span></label>
                                    <select class="form-select select2_list @error('joining_company_id') is-invalid @enderror" id="joining_company_id"
                                            name="joining_company_id" data-placeholder="Select Company">
                                        <option value="">Choose Company</option>
                                        @foreach ($companies as $company)
                                            <option value="{{ $company->id }}"
                                                {{ old('joining_company_id', $employee_office_info->joining_company_id ?? '') == $company->id ? 'selected' : '' }}>
                                                {{ $company->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('joining_company_id')
                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>

                                @if($generalSettings->branch_status == 1)
                                <div class="col-lg-4">
                                    <label for="joining_business_unit_id" class="form-label fw-semibold">Branch <span class="text-danger">*</span></label>
                                    <select id="joining_business_unit_id"
                                        class="form-select select2_list @error('joining_business_unit_id') is-invalid @enderror"
                                        name="joining_business_unit_id" data-placeholder="Select Branch">
                                        <option value="">Select Branch</option>
                                    </select>
                                    @error('joining_business_unit_id')
                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>
                                @endif

                                @if($generalSettings->division_status == 1)
                                <div class="col-lg-4">
                                    <label for="joining_division_id" class="form-label fw-semibold">Joining Division</label>
                                    <select class="form-select select2_list @error('joining_division_id') is-invalid @enderror"
                                        id="joining_division_id" name="joining_division_id" data-placeholder="Select Division">
                                        <option value="">Select Division</option>
                                    </select>
                                    @error('joining_division_id')
                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>
                                @endif

                                @if($generalSettings->department_status == 1)
                                <div class="col-lg-4">
                                    <label for="joining_department_id" class="form-label fw-semibold">Joining Department</label>
                                    <select class="form-select select2_list @error('joining_department_id') is-invalid @enderror"
                                        id="joining_department_id" name="joining_department_id" data-placeholder="Select Department">
                                        <option value="">Select Department</option>
                                    </select>
                                    @error('joining_department_id')
                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>
                                @endif

                                @if($generalSettings->section_status == 1)
                                <div class="col-lg-4">
                                    <label for="joining_section_id" class="form-label fw-semibold">Joining Section</label>
                                    <select class="form-select select2_list @error('joining_section_id') is-invalid @enderror"
                                        id="joining_section_id" name="joining_section_id" data-placeholder="Select Section">
                                        <option value="">Select Section</option>
                                    </select>
                                    @error('joining_section_id')
                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>
                                @endif

                                <div class="col-lg-4">
                                    <label for="joining_designation_id" class="form-label fw-semibold">Joining Designation</label>
                                    <select class="form-select select2_list @error('joining_designation_id') is-invalid @enderror"
                                        id="joining_designation_id" name="joining_designation_id" data-placeholder="Select Designation">
                                        <option value="">Select Designation</option>
                                        @foreach ($designations as $item)
                                            <option value="{{ $item->id }}"
                                                {{ old('joining_designation_id', $employee_office_info->joining_designation_id ?? '') == $item->id ? 'selected' : '' }}>
                                                {{ $item->company_designation }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('joining_designation_id')
                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4">
                                    <label for="date_of_join" class="form-label fw-semibold">Date of Join <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('date_of_join') is-invalid @enderror"
                                        id="date_of_join" name="date_of_join" value="{{ old('date_of_join', $employee_office_info->date_of_join ?? '') }}">
                                    @error('date_of_join')
                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Current Information Section -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header py-3">
                            <h5 class="card-title mb-0">Current Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-lg-4">
                                    <label for="current_company_id" class="form-label fw-semibold">Current Company</label>
                                    <select class="form-select select2_list @error('current_company_id') is-invalid @enderror"
                                        id="current_company_id" name="current_company_id" data-placeholder="Select Company">
                                        <option value="">Select Company</option>
                                        @foreach ($companies as $company)
                                            <option value="{{ $company->id }}"
                                                {{ old('current_company_id', $employee_office_info->current_company_id ?? '') == $company->id ? 'selected' : '' }}>
                                                {{ $company->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('current_company_id')
                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>

                                @if($generalSettings->branch_status == 1)
                                <div class="col-lg-4">
                                    <label for="current_business_unit_id" class="form-label fw-semibold">Current Branch</label>
                                    <select class="form-select select2_list @error('current_business_unit_id') is-invalid @enderror"
                                        id="current_business_unit_id" name="current_business_unit_id" data-placeholder="Select Branch">
                                        <option value="">Select Branch</option>
                                    </select>
                                    @error('current_business_unit_id')
                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>
                                @endif

                                @if($generalSettings->division_status == 1)
                                <div class="col-lg-4">
                                    <label for="current_division_id" class="form-label fw-semibold">Current Division</label>
                                    <select class="form-select select2_list @error('current_division_id') is-invalid @enderror"
                                        id="current_division_id" name="current_division_id" data-placeholder="Select Division">
                                        <option value="">Select Division</option>
                                    </select>
                                    @error('current_division_id')
                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>
                                @endif

                                @if($generalSettings->department_status == 1)
                                <div class="col-lg-4">
                                    <label for="current_department_id" class="form-label fw-semibold">Current Department</label>
                                    <select class="form-select select2_list @error('current_department_id') is-invalid @enderror"
                                        id="current_department_id" name="current_department_id" data-placeholder="Select Department">
                                        <option value="">Select Department</option>
                                    </select>
                                    @error('current_department_id')
                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>
                                @endif

                                @if($generalSettings->section_status == 1)
                                <div class="col-lg-4">
                                    <label for="current_section_id" class="form-label fw-semibold">Current Section</label>
                                    <select class="form-select select2_list @error('current_section_id') is-invalid @enderror"
                                        id="current_section_id" name="current_section_id" data-placeholder="Select Section">
                                        <option value="">Select Section</option>
                                    </select>
                                    @error('current_section_id')
                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>
                                @endif

                                <div class="col-lg-4">
                                    <label for="current_designation_id" class="form-label fw-semibold">Current Designation</label>
                                    <select class="form-select select2_list @error('current_designation_id') is-invalid @enderror"
                                        id="current_designation_id" name="current_designation_id" data-placeholder="Select Designation">
                                        <option value="">Select Designation</option>
                                        @foreach ($designations as $item)
                                            <option value="{{ $item->id }}"
                                                {{ old('current_designation_id', $employee_office_info->current_designation_id ?? '') == $item->id ? 'selected' : '' }}>
                                                {{ $item->company_designation }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('current_designation_id')
                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Orientation Section -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header py-3">
                            <h5 class="card-title mb-0">Orientation</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-lg-4">
                                    <label for="orientation_required" class="form-label fw-semibold">Orientation Required</label>
                                    <select class="form-select @error('orientation_required') is-invalid @enderror"
                                        id="orientation_required" name="orientation_required" data-placeholder="Select Option">
                                        <option value="">Select Option</option>
                                        <option value="yes" {{ old('orientation_required', $employee_office_info->orientation_required ?? '') == 'yes' ? 'selected' : '' }}>Yes</option>
                                        <option value="no" {{ old('orientation_required', $employee_office_info->orientation_required ?? '') == 'no' ? 'selected' : '' }}>No</option>
                                    </select>
                                    @error('orientation_required')
                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4">
                                    <label for="orientation_from" class="form-label fw-semibold">Orientation From</label>
                                    <input type="date" class="form-control @error('orientation_from') is-invalid @enderror"
                                        id="orientation_from" name="orientation_from"
                                        value="{{ old('orientation_from', $employee_office_info->orientation_from ?? '') }}">
                                    @error('orientation_from')
                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4">
                                    <label for="orientation_to" class="form-label fw-semibold">Orientation To</label>
                                    <input type="date" class="form-control @error('orientation_to') is-invalid @enderror"
                                        id="orientation_to" name="orientation_to" 
                                        value="{{ old('orientation_to', $employee_office_info->orientation_to ?? '') }}">
                                    @error('orientation_to')
                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-6">
                                    <label for="orientation_type" class="form-label fw-semibold">Orientation Type</label>
                                    <input type="text" class="form-control @error('orientation_type') is-invalid @enderror"
                                        id="orientation_type" name="orientation_type"
                                        value="{{ old('orientation_type', $employee_office_info->orientation_type ?? '') }}">
                                    @error('orientation_type')
                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-6">
                                    <label for="orientation_days" class="form-label fw-semibold">Orientation Days</label>
                                    <input type="number" class="form-control @error('orientation_days') is-invalid @enderror"
                                        id="orientation_days" name="orientation_days"
                                        value="{{ old('orientation_days', $employee_office_info->orientation_days ?? '') }}" min="0">
                                    @error('orientation_days')
                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Duration & Cycles Section -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header py-3">
                            <h5 class="card-title mb-0">Duration & Cycles</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-lg-4">
                                    <label for="confirmation_date" class="form-label fw-semibold">Confirmation Date</label>
                                    <input type="date" class="form-control @error('confirmation_date') is-invalid @enderror"
                                        id="confirmation_date" name="confirmation_date"
                                        value="{{ old('confirmation_date', $employee_office_info->confirmation_date ?? '') }}">
                                    @error('confirmation_date')
                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4">
                                    <label for="probation_duration" class="form-label fw-semibold">Probation Duration (Days)</label>
                                    <input type="number" min="0" class="form-control @error('probation_duration') is-invalid @enderror"
                                        id="probation_duration" name="probation_duration"
                                        value="{{ old('probation_duration', $employee_office_info->probation_duration ?? '') }}">
                                    @error('probation_duration')
                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4">
                                    <label for="next_promotion_date" class="form-label fw-semibold">Next Promotion Date</label>
                                    <input type="date" class="form-control @error('next_promotion_date') is-invalid @enderror"
                                        id="next_promotion_date" name="next_promotion_date"
                                        value="{{ old('next_promotion_date', $employee_office_info->next_promotion_date ?? '') }}">
                                    @error('next_promotion_date')
                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-6">
                                    <label for="promotion_cycle" class="form-label fw-semibold">Promotion Cycle</label>
                                    <input type="text" class="form-control @error('promotion_cycle') is-invalid @enderror"
                                        id="promotion_cycle" name="promotion_cycle" value="{{ old('promotion_cycle', $employee_office_info->promotion_cycle ?? '') }}"
                                        placeholder="e.g., Annual">
                                    @error('promotion_cycle')
                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-6">
                                    <label for="increment_cycle" class="form-label fw-semibold">Increment Cycle</label>
                                    <input type="text" class="form-control @error('increment_cycle') is-invalid @enderror"
                                        id="increment_cycle" name="increment_cycle" value="{{ old('increment_cycle', $employee_office_info->increment_cycle ?? '') }}"
                                        placeholder="e.g., Annual">
                                    @error('increment_cycle')
                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Work Schedule Section -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header py-3">
                            <h5 class="card-title mb-0">Work Schedule</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-lg-12">
                                    <label class="form-label d-block fw-semibold">Weekends</label>
                                    @php
                                        $weekdays = ['Friday', 'Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday'];
                                        $selectedWeekends = old('weekends', $employee_office_info->weekends ?? []);
                                    @endphp
                                    @foreach($weekdays as $day)
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="weekends[]"
                                                id="weekend_{{ strtolower($day) }}" value="{{ $day }}"
                                                {{ in_array($day, $selectedWeekends) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="weekend_{{ strtolower($day) }}">{{ $day }}</label>
                                        </div>
                                    @endforeach
                                    @error('weekends')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-lg-12">
                                    <label class="form-label d-block fw-semibold">Alternate Off Day</label>
                                    @php
                                        $altOffDays = array_merge($weekdays, ['None']);
                                        $selectedAltOffDays = old('alternate_off_day', $employee_office_info->alternate_off_day ?? []);
                                    @endphp
                                    @foreach($altOffDays as $day)
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="alternate_off_day[]"
                                                id="altoff_{{ strtolower($day) }}" value="{{ $day }}"
                                                {{ in_array($day, $selectedAltOffDays) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="altoff_{{ strtolower($day) }}">{{ $day }}</label>
                                        </div>
                                    @endforeach
                                    @error('alternate_off_day')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Eligibility & Benefits Section -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header py-3">
                            <h5 class="card-title mb-0">Eligibility & Benefits</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-4 align-items-center">
                                @php
                                    $checkboxes = [
                                        'ot_allowed' => 'OT Allowed',
                                        'pf_eligible' => 'PF Eligible',
                                        'transport_eligible' => 'Transport Eligible',
                                        'gratuity_eligible' => 'Gratuity Eligible',
                                        'can_apply_loan' => 'Can Apply Loan',
                                        'can_apply_advance' => 'Can Apply Advance',
                                    ];
                                @endphp

                                @foreach($checkboxes as $name => $label)
                                    <div class="col-lg-2 col-md-4 col-6">
                                        <div class="form-check">
                                            <input type="hidden" name="{{ $name }}" value="no">
                                            <input class="form-check-input" type="checkbox" name="{{ $name }}"
                                                   id="{{ $name }}" value="yes"
                                                {{ old($name, $employee_office_info->$name ?? '') == 'yes' ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold small" for="{{ $name }}">{{ $label }}</label>
                                        </div>
                                    </div>
                                @endforeach

                                <div class="col-12">
                                    <label for="pf_effective_date" class="form-label fw-semibold">PF Effective Date</label>
                                    <input type="date" class="form-control @error('pf_effective_date') is-invalid @enderror"
                                           id="pf_effective_date" name="pf_effective_date"
                                           value="{{ old('pf_effective_date', $employee_office_info->pf_effective_date ?? '') }}">
                                    @error('pf_effective_date')
                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="row mb-5">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('employee.profile.office_informations', $employee->id) }}" class="btn btn-light px-4">Cancel</a>
                                <button type="reset" class="btn btn-secondary px-4">Reset</button>
                                <button type="submit" class="btn btn-primary px-5">
                                    <i data-feather="save" class="me-1" style="width: 16px;"></i>
                                    {{ $isEdit ? 'Update Information' : 'Submit Information' }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function () {
            // Re-initialize Feather Icons
            if (typeof feather !== 'undefined') { feather.replace(); }

            let silenceChangeEvents = false;

            // Standard Ajax Loader with Pre-selection support
            function ajaxLoad(url, $select, placeholder, selectedValue = null) {
                if (!$select.length) return Promise.resolve();
                return $.get(url).then(function (data) {
                    $select.html(`<option value="">${placeholder}</option>`);
                    $.each(data, function (_, item) {
                        $select.append(
                            `<option value="${item.id}">${item.name ?? item.department_name ?? item.full_name}</option>`
                        );
                    });
                    if (selectedValue && selectedValue !== 'null' && selectedValue !== '') {
                        $select.val(selectedValue).trigger('change.select2');
                    } else {
                        $select.val('').trigger('change.select2');
                    }
                }).catch(function () {
                    $select.html('<option value="">Error loading data</option>');
                });
            }

            // --- Unified Hierarchy Loader ---
            function loadHierarchy(prefix, companyId, branchId = null, divisionId = null, departmentId = null, sectionId = null) {
                if (!companyId) return Promise.resolve();

                let branchPromise = Promise.resolve();
                if ($(`#${prefix}_business_unit_id`).length) {
                    branchPromise = ajaxLoad(`/get-units/${companyId}`, $(`#${prefix}_business_unit_id`), 'Select Branch', branchId);
                }

                return branchPromise.then(() => {
                    const currentBranchId = $(`#${prefix}_business_unit_id`).val() || 'null';
                    return ajaxLoad(`/get-divisions/${companyId}/${currentBranchId}`, $(`#${prefix}_division_id`), 'Select Division', divisionId);
                }).then(() => {
                    const currentBranchId = $(`#${prefix}_business_unit_id`).val() || 'null';
                    const currentDivisionId = $(`#${prefix}_division_id`).val() || 'null';
                    return ajaxLoad(`/get-departments/${companyId}/${currentBranchId}/${currentDivisionId}`, $(`#${prefix}_department_id`), 'Select Department', departmentId);
                }).then(() => {
                    const currentBranchId = $(`#${prefix}_business_unit_id`).val() || 'null';
                    const currentDivisionId = $(`#${prefix}_division_id`).val() || 'null';
                    const currentDeptId = $(`#${prefix}_department_id`).val() || 'null';
                    return ajaxLoad(`/get-sections/${companyId}/${currentBranchId}/${currentDivisionId}/${currentDeptId}`, $(`#${prefix}_section_id`), 'Select Section', sectionId);
                });
            }

            // --- Change Listeners ---
            $('#joining_company_id, #current_company_id').on('change', function () {
                if (silenceChangeEvents) return;
                const prefix = this.id.replace('_company_id', '');
                silenceChangeEvents = true;
                loadHierarchy(prefix, $(this).val()).then(() => {
                    silenceChangeEvents = false;
                });
            });

            $('#joining_business_unit_id, #current_business_unit_id').on('change', function () {
                if (silenceChangeEvents) return;
                const prefix = this.id.replace('_business_unit_id', '');
                silenceChangeEvents = true;
                loadHierarchy(prefix, $(`#${prefix}_company_id`).val(), $(this).val()).then(() => {
                    silenceChangeEvents = false;
                });
            });

            $('#joining_division_id, #current_division_id').on('change', function () {
                if (silenceChangeEvents) return;
                const prefix = this.id.replace('_division_id', '');
                const companyId = $(`#${prefix}_company_id`).val();
                const branchId = $(`#${prefix}_business_unit_id`).val() || 'null';
                const divisionId = $(this).val() || 'null';

                silenceChangeEvents = true;
                ajaxLoad(`/get-departments/${companyId}/${branchId}/${divisionId}`, $(`#${prefix}_department_id`), 'Select Department')
                    .then(() => {
                        const deptId = $(`#${prefix}_department_id`).val() || 'null';
                        return ajaxLoad(`/get-sections/${companyId}/${branchId}/${divisionId}/${deptId}`, $(`#${prefix}_section_id`), 'Select Section');
                    }).then(() => {
                        silenceChangeEvents = false;
                    });
            });

            $('#joining_department_id, #current_department_id').on('change', function () {
                if (silenceChangeEvents) return;
                const prefix = this.id.replace('_department_id', '');
                const companyId = $(`#${prefix}_company_id`).val();
                const branchId = $(`#${prefix}_business_unit_id`).val() || 'null';
                const divisionId = $(`#${prefix}_division_id`).val() || 'null';
                const deptId = $(this).val() || 'null';

                silenceChangeEvents = true;
                ajaxLoad(`/get-sections/${companyId}/${branchId}/${divisionId}/${deptId}`, $(`#${prefix}_section_id`), 'Select Section')
                    .then(() => {
                        silenceChangeEvents = false;
                    });
            });

            // --- Initial Load in Edit Mode ---
            @if($isEdit)
                silenceChangeEvents = true;

                loadHierarchy(
                    'joining',
                    "{{ old('joining_company_id', $employee_office_info->joining_company_id) }}",
                    "{{ old('joining_business_unit_id', $employee_office_info->joining_business_unit_id) }}",
                    "{{ old('joining_division_id', $employee_office_info->joining_division_id) }}",
                    "{{ old('joining_department_id', $employee_office_info->joining_department_id) }}",
                    "{{ old('joining_section_id', $employee_office_info->joining_section_id) }}"
                ).then(() => {
                    return loadHierarchy(
                        'current',
                        "{{ old('current_company_id', $employee_office_info->current_company_id) }}",
                        "{{ old('current_business_unit_id', $employee_office_info->current_business_unit_id) }}",
                        "{{ old('current_division_id', $employee_office_info->current_division_id) }}",
                        "{{ old('current_department_id', $employee_office_info->current_department_id) }}",
                        "{{ old('current_section_id', $employee_office_info->current_section_id) }}"
                    );
                }).then(() => {
                    silenceChangeEvents = false;
                }).catch(() => {
                    silenceChangeEvents = false;
                });
            @endif
        });
    </script>
    @endpush
@endsection
