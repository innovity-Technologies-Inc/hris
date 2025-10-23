@extends('structure.master')
@section('content')
    @include('employees.partials.creation_button')
    <div class="mt-4">
        <!-- Trigger Button -->
        <div class="mb-3">
            <button type="button" class="btn btn-secondary shadow-sm" data-bs-toggle="modal" data-bs-target="#bulkUploadModal">
                <i class="mdi mdi-upload me-1"></i> Bulk Upload Office Informations
            </button>
        </div>
        @include('employees.partials.modal')

        <form class="" method="POST" action="#">
            @csrf

            <!-- Payroll Information Section -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Payroll Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <label for="employee_id" class="form-label">Employee ID <span
                                            class="text-danger">*</span></label>
                                    <select id="employeeName" name="employee_name"
                                        class="form-select form-select-sm select2_list"
                                        data-placeholder="Select employee name" aria-label="Employee Name">
                                    </select>
                                    @error('employee_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="emp_type" class="form-label">Employee Type <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select @error('emp_type') is-invalid @enderror" id="emp_type"
                                        name="emp_type">
                                        <option value="">Select Type</option>
                                        <option value="Permanent" {{ old('emp_type') == 'Permanent' ? 'selected' : '' }}>
                                            Permanent</option>
                                        <option value="Contractual"
                                            {{ old('emp_type') == 'Contractual' ? 'selected' : '' }}>Contractual</option>
                                        <option value="Temporary" {{ old('emp_type') == 'Temporary' ? 'selected' : '' }}>
                                            Temporary</option>
                                    </select>
                                    @error('emp_type')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="paygrade" class="form-label">Pay Grade</label>
                                    <input type="text" class="form-control @error('paygrade') is-invalid @enderror"
                                        id="paygrade" name="paygrade" value="{{ old('paygrade') }}">
                                    @error('paygrade')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <label for="category" class="form-label">Category <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('category') is-invalid @enderror"
                                        id="category" name="category" value="{{ old('category') }}">
                                    @error('category')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="hr_file_no" class="form-label">HR File Number</label>
                                    <input type="text" class="form-control @error('hr_file_no') is-invalid @enderror"
                                        id="hr_file_no" name="hr_file_no" value="{{ old('hr_file_no') }}">
                                    @error('hr_file_no')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="totali" class="form-label">Totali</label>
                                    <input type="text" class="form-control @error('totali') is-invalid @enderror"
                                        id="totali" name="totali" value="{{ old('totali') }}">
                                    @error('totali')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12 mb-3">
                                    <label for="file_note" class="form-label">File Note</label>
                                    <textarea class="form-control @error('file_note') is-invalid @enderror" id="file_note" name="file_note" rows="3">{{ old('file_note') }}</textarea>
                                    @error('file_note')
                                        <small class="text-danger">{{ $message }}</small>
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
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Joining Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                        <label for="company_id" class="form-label">Company <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select select2_list" name="company_id" id="company_id" required>
                                            <option value="">Choose Company</option>
                                            {{-- @foreach ($companies as $item)
                                                <option value="{{ $item->id }}"
                                                    @if (isset($office_info) && $office_info->company_id == $item->id) selected @endif>
                                                    {{ $item->name }}
                                                </option>
                                            @endforeach --}}
                                        </select>
                                        @error('company_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                <div class="col-lg-4 mb-3">
                                        <label for="location_id" class="form-label">Company Location <span
                                                class="text-danger">*</span></label>
                                        <select id="location_id" class="form-select select2_list" name="location_id"
                                            required>
                                            <option value="">Select Location</option>
                                            {{-- @foreach ($locations as $location)
                                                <option value="{{ $location->id }}"
                                                    {{ isset($office_info) && $office_info->location_id == $location->id ? 'selected' : '' }}>
                                                    {{ $location->unit_name }}</option>
                                            @endforeach --}}
                                        </select>
                                        @error('location_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                <div class="col-lg-4 mb-3">
                                    <label for="joining_division_id" class="form-label">Joining Division</label>
                                    <select class="form-select @error('joining_division_id') is-invalid @enderror"
                                        id="joining_division_id" name="joining_division_id">
                                        <option value="">Select Division</option>
                                        <!-- Add division options dynamically -->
                                    </select>
                                    @error('joining_division_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <label for="joining_department_id" class="form-label">Joining Department</label>
                                    <select class="form-select @error('joining_department_id') is-invalid @enderror"
                                        id="joining_department_id" name="joining_department_id">
                                        <option value="">Select Department</option>
                                        <!-- Add department options dynamically -->
                                    </select>
                                    @error('joining_department_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="joining_designation_id" class="form-label">Joining Designation</label>
                                    <select class="form-select @error('joining_designation_id') is-invalid @enderror"
                                        id="joining_designation_id" name="joining_designation_id">
                                        <option value="">Select Designation</option>
                                        <!-- Add designation options dynamically -->
                                    </select>
                                    @error('joining_designation_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="joining_section_id" class="form-label">Joining Section</label>
                                    <select class="form-select @error('joining_section_id') is-invalid @enderror"
                                        id="joining_section_id" name="joining_section_id">
                                        <option value="">Select Section</option>
                                        <!-- Add section options dynamically -->
                                    </select>
                                    @error('joining_section_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <label for="joining_subsection" class="form-label">Joining Subsection</label>
                                    <input type="text"
                                        class="form-control @error('joining_subsection') is-invalid @enderror"
                                        id="joining_subsection" name="joining_subsection"
                                        value="{{ old('joining_subsection') }}">
                                    @error('joining_subsection')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="joining_floor" class="form-label">Joining Floor</label>
                                    <input type="text"
                                        class="form-control @error('joining_floor') is-invalid @enderror"
                                        id="joining_floor" name="joining_floor" value="{{ old('joining_floor') }}">
                                    @error('joining_floor')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="date_of_join" class="form-label">Date of Join <span
                                            class="text-danger">*</span></label>
                                    <input type="date"
                                        class="form-control @error('date_of_join') is-invalid @enderror"
                                        id="date_of_join" name="date_of_join" value="{{ old('date_of_join') }}">
                                    @error('date_of_join')
                                        <small class="text-danger">{{ $message }}</small>
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
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Current Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="same_as_joining"
                                            onchange="copyJoiningInfo()">
                                        <label class="form-check-label" for="same_as_joining">
                                            Same as Joining Information
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <label for="current_company_id" class="form-label">Current Company</label>
                                    <select class="form-select @error('current_company_id') is-invalid @enderror"
                                        id="current_company_id" name="current_company_id">
                                        <option value="">Select Company</option>
                                        <!-- Add company options dynamically -->
                                    </select>
                                    @error('current_company_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="current_business_unit_id" class="form-label">Current Business Unit</label>
                                    <select class="form-select @error('current_business_unit_id') is-invalid @enderror"
                                        id="current_business_unit_id" name="current_business_unit_id">
                                        <option value="">Select Business Unit</option>
                                        <!-- Add business unit options dynamically -->
                                    </select>
                                    @error('current_business_unit_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="current_division_id" class="form-label">Current Division</label>
                                    <select class="form-select @error('current_division_id') is-invalid @enderror"
                                        id="current_division_id" name="current_division_id">
                                        <option value="">Select Division</option>
                                        <!-- Add division options dynamically -->
                                    </select>
                                    @error('current_division_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <label for="current_department_id" class="form-label">Current Department</label>
                                    <select class="form-select @error('current_department_id') is-invalid @enderror"
                                        id="current_department_id" name="current_department_id">
                                        <option value="">Select Department</option>
                                        <!-- Add department options dynamically -->
                                    </select>
                                    @error('current_department_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="current_designation_id" class="form-label">Current Designation</label>
                                    <select class="form-select @error('current_designation_id') is-invalid @enderror"
                                        id="current_designation_id" name="current_designation_id">
                                        <option value="">Select Designation</option>
                                        <!-- Add designation options dynamically -->
                                    </select>
                                    @error('current_designation_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="current_section_id" class="form-label">Current Section</label>
                                    <select class="form-select @error('current_section_id') is-invalid @enderror"
                                        id="current_section_id" name="current_section_id">
                                        <option value="">Select Section</option>
                                        <!-- Add section options dynamically -->
                                    </select>
                                    @error('current_section_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <label for="current_subsection" class="form-label">Current Subsection</label>
                                    <input type="text"
                                        class="form-control @error('current_subsection') is-invalid @enderror"
                                        id="current_subsection" name="current_subsection"
                                        value="{{ old('current_subsection') }}">
                                    @error('current_subsection')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="current_floor" class="form-label">Current Floor</label>
                                    <input type="text"
                                        class="form-control @error('current_floor') is-invalid @enderror"
                                        id="current_floor" name="current_floor" value="{{ old('current_floor') }}">
                                    @error('current_floor')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="current_info_effective_date" class="form-label">Effective Date</label>
                                    <input type="date"
                                        class="form-control @error('current_info_effective_date') is-invalid @enderror"
                                        id="current_info_effective_date" name="current_info_effective_date"
                                        value="{{ old('current_info_effective_date') }}">
                                    @error('current_info_effective_date')
                                        <small class="text-danger">{{ $message }}</small>
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
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Orientation</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-3 mb-3">
                                    <label class="form-label d-block">Orientation Required</label>
                                    <div class="form-check form-check-inline">
                                        <input
                                            class="form-check-input @error('orientation_required') is-invalid @enderror"
                                            type="radio" name="orientation_required" id="orientation_yes"
                                            value="1" {{ old('orientation_required') == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="orientation_yes">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input
                                            class="form-check-input @error('orientation_required') is-invalid @enderror"
                                            type="radio" name="orientation_required" id="orientation_no"
                                            value="0" {{ old('orientation_required') == '0' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="orientation_no">No</label>
                                    </div>
                                    @error('orientation_required')
                                        <small class="text-danger d-block">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-3 mb-3">
                                    <label for="orientation_from" class="form-label">Orientation From</label>
                                    <input type="date"
                                        class="form-control @error('orientation_from') is-invalid @enderror"
                                        id="orientation_from" name="orientation_from"
                                        value="{{ old('orientation_from') }}">
                                    @error('orientation_from')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-3 mb-3">
                                    <label for="orientation_to" class="form-label">Orientation To</label>
                                    <input type="date"
                                        class="form-control @error('orientation_to') is-invalid @enderror"
                                        id="orientation_to" name="orientation_to" value="{{ old('orientation_to') }}">
                                    @error('orientation_to')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-3 mb-3">
                                    <label for="orientation_type" class="form-label">Orientation Type</label>
                                    <input type="text"
                                        class="form-control @error('orientation_type') is-invalid @enderror"
                                        id="orientation_type" name="orientation_type"
                                        value="{{ old('orientation_type') }}">
                                    @error('orientation_type')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <label for="orientation_days" class="form-label">Orientation Days</label>
                                    <input type="number"
                                        class="form-control @error('orientation_days') is-invalid @enderror"
                                        id="orientation_days" name="orientation_days"
                                        value="{{ old('orientation_days') }}" min="0">
                                    @error('orientation_days')
                                        <small class="text-danger">{{ $message }}</small>
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
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Duration & Cycles</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <label for="confirmation_date" class="form-label">Confirmation Date</label>
                                    <input type="date"
                                        class="form-control @error('confirmation_date') is-invalid @enderror"
                                        id="confirmation_date" name="confirmation_date"
                                        value="{{ old('confirmation_date') }}">
                                    @error('confirmation_date')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="probation_duration" class="form-label">Probation Duration</label>
                                    <input type="text"
                                        class="form-control @error('probation_duration') is-invalid @enderror"
                                        id="probation_duration" name="probation_duration"
                                        value="{{ old('probation_duration') }}" placeholder="e.g., 6 months">
                                    @error('probation_duration')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="next_promotion_date" class="form-label">Next Promotion Date</label>
                                    <input type="date"
                                        class="form-control @error('next_promotion_date') is-invalid @enderror"
                                        id="next_promotion_date" name="next_promotion_date"
                                        value="{{ old('next_promotion_date') }}">
                                    @error('next_promotion_date')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <label for="promotion_cycle" class="form-label">Promotion Cycle</label>
                                    <input type="text"
                                        class="form-control @error('promotion_cycle') is-invalid @enderror"
                                        id="promotion_cycle" name="promotion_cycle" value="{{ old('promotion_cycle') }}"
                                        placeholder="e.g., Annual">
                                    @error('promotion_cycle')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="increment_cycle" class="form-label">Increment Cycle</label>
                                    <input type="text"
                                        class="form-control @error('increment_cycle') is-invalid @enderror"
                                        id="increment_cycle" name="increment_cycle" value="{{ old('increment_cycle') }}"
                                        placeholder="e.g., Annual">
                                    @error('increment_cycle')
                                        <small class="text-danger">{{ $message }}</small>
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
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Work Schedule</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6 mb-3">
                                    <label class="form-label d-block">Weekends</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="weekends[]"
                                            id="weekend_friday" value="Friday"
                                            {{ in_array('Friday', old('weekends', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="weekend_friday">Friday</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="weekends[]"
                                            id="weekend_saturday" value="Saturday"
                                            {{ in_array('Saturday', old('weekends', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="weekend_saturday">Saturday</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="weekends[]"
                                            id="weekend_sunday" value="Sunday"
                                            {{ in_array('Sunday', old('weekends', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="weekend_sunday">Sunday</label>
                                    </div>
                                    @error('weekends')
                                        <small class="text-danger d-block">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-6 mb-3">
                                    <label for="alternate_off_day" class="form-label">Alternate Off Day</label>
                                    <select class="form-select @error('alternate_off_day') is-invalid @enderror"
                                        id="alternate_off_day" name="alternate_off_day">
                                        <option value="">Select Day</option>
                                        <option value="Monday"
                                            {{ old('alternate_off_day') == 'Monday' ? 'selected' : '' }}>Monday</option>
                                        <option value="Tuesday"
                                            {{ old('alternate_off_day') == 'Tuesday' ? 'selected' : '' }}>Tuesday</option>
                                        <option value="Wednesday"
                                            {{ old('alternate_off_day') == 'Wednesday' ? 'selected' : '' }}>Wednesday
                                        </option>
                                        <option value="Thursday"
                                            {{ old('alternate_off_day') == 'Thursday' ? 'selected' : '' }}>Thursday
                                        </option>
                                        <option value="Friday"
                                            {{ old('alternate_off_day') == 'Friday' ? 'selected' : '' }}>Friday</option>
                                        <option value="Saturday"
                                            {{ old('alternate_off_day') == 'Saturday' ? 'selected' : '' }}>Saturday
                                        </option>
                                        <option value="Sunday"
                                            {{ old('alternate_off_day') == 'Sunday' ? 'selected' : '' }}>Sunday</option>
                                    </select>
                                    @error('alternate_off_day')
                                        <small class="text-danger">{{ $message }}</small>
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
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Eligibility & Benefits</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <label for="salary_type" class="form-label">Salary Type <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select @error('salary_type') is-invalid @enderror"
                                        id="salary_type" name="salary_type">
                                        <option value="">Select Type</option>
                                        <option value="Monthly" {{ old('salary_type') == 'Monthly' ? 'selected' : '' }}>
                                            Monthly</option>
                                        <option value="Hourly" {{ old('salary_type') == 'Hourly' ? 'selected' : '' }}>
                                            Hourly</option>
                                        <option value="Daily" {{ old('salary_type') == 'Daily' ? 'selected' : '' }}>Daily
                                        </option>
                                    </select>
                                    @error('salary_type')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="imprest_fund" class="form-label">Imprest Fund</label>
                                    <input type="number" step="0.01"
                                        class="form-control @error('imprest_fund') is-invalid @enderror"
                                        id="imprest_fund" name="imprest_fund" value="{{ old('imprest_fund') }}">
                                    @error('imprest_fund')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status"
                                        name="status">
                                        <option value="Active"
                                            {{ old('status', 'Active') == 'Active' ? 'selected' : '' }}>Active</option>
                                        <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>
                                            Inactive</option>
                                        <option value="Suspended" {{ old('status') == 'Suspended' ? 'selected' : '' }}>
                                            Suspended</option>
                                    </select>
                                    @error('status')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="ot_allowed"
                                            id="ot_allowed" value="1" {{ old('ot_allowed') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="ot_allowed">
                                            OT Allowed
                                        </label>
                                    </div>
                                    @error('ot_allowed')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-3 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="pf_eligible"
                                            id="pf_eligible" value="1" {{ old('pf_eligible') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="pf_eligible">
                                            PF Eligible
                                        </label>
                                    </div>
                                    @error('pf_eligible')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-3 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="transport_eligible"
                                            id="transport_eligible" value="1"
                                            {{ old('transport_eligible') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="transport_eligible">
                                            Transport Eligible
                                        </label>
                                    </div>
                                    @error('transport_eligible')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-3 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="gratuity_eligible"
                                            id="gratuity_eligible" value="1"
                                            {{ old('gratuity_eligible') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="gratuity_eligible">
                                            Gratuity Eligible
                                        </label>
                                    </div>
                                    @error('gratuity_eligible')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="can_apply_loan"
                                            id="can_apply_loan" value="1"
                                            {{ old('can_apply_loan') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="can_apply_loan">
                                            Can Apply Loan
                                        </label>
                                    </div>
                                    @error('can_apply_loan')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-3 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="can_apply_advance"
                                            id="can_apply_advance" value="1"
                                            {{ old('can_apply_advance') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="can_apply_advance">
                                            Can Apply Advance
                                        </label>
                                    </div>
                                    @error('can_apply_advance')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-3 mb-3">
                                    <label for="pf_effective_date" class="form-label">PF Effective Date</label>
                                    <input type="date"
                                        class="form-control @error('pf_effective_date') is-invalid @enderror"
                                        id="pf_effective_date" name="pf_effective_date"
                                        value="{{ old('pf_effective_date') }}">
                                    @error('pf_effective_date')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-3 mb-3">
                                    <label for="cash_collector" class="form-label">Cash Collector</label>
                                    <input type="text"
                                        class="form-control @error('cash_collector') is-invalid @enderror"
                                        id="cash_collector" name="cash_collector" value="{{ old('cash_collector') }}">
                                    @error('cash_collector')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <label for="separation_type" class="form-label">Separation Type</label>
                                    <select class="form-select @error('separation_type') is-invalid @enderror"
                                        id="separation_type" name="separation_type">
                                        <option value="">Select Type</option>
                                        <option value="Resignation"
                                            {{ old('separation_type') == 'Resignation' ? 'selected' : '' }}>Resignation
                                        </option>
                                        <option value="Termination"
                                            {{ old('separation_type') == 'Termination' ? 'selected' : '' }}>Termination
                                        </option>
                                        <option value="Retirement"
                                            {{ old('separation_type') == 'Retirement' ? 'selected' : '' }}>Retirement
                                        </option>
                                    </select>
                                    @error('separation_type')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
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
                                <button type="submit" class="btn btn-primary">Submit Payroll Information</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </form>

        <script>
            function copyJoiningInfo() {
                const checkbox = document.getElementById('same_as_joining');

                if (checkbox.checked) {
                    document.getElementById('current_company_id').value = document.getElementById('joining_company_id').value;
                    document.getElementById('current_business_unit_id').value = document.getElementById(
                        'joining_business_unit_id').value;
                    document.getElementById('current_division_id').value = document.getElementById('joining_division_id').value;
                    document.getElementById('current_department_id').value = document.getElementById('joining_department_id')
                        .value;
                    document.getElementById('current_designation_id').value = document.getElementById('joining_designation_id')
                        .value;
                    document.getElementById('current_section_id').value = document.getElementById('joining_section_id').value;
                    document.getElementById('current_subsection').value = document.getElementById('joining_subsection').value;
                    document.getElementById('current_floor').value = document.getElementById('joining_floor').value;
                } else {
                    document.getElementById('current_company_id').value = '';
                    document.getElementById('current_business_unit_id').value = '';
                    document.getElementById('current_division_id').value = '';
                    document.getElementById('current_department_id').value = '';
                    document.getElementById('current_designation_id').value = '';
                    document.getElementById('current_section_id').value = '';
                    document.getElementById('current_subsection').value = '';
                    document.getElementById('current_floor').value = '';
                }
            }
        </script>
    </div>
@endsection
