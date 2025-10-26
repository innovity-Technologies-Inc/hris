@extends('structure.master')
@section('content')
    @if(!isset($employee_office_info))
    @include('employees.partials.creation_button')
    @endif
    <div class="mt-4">

        <form class="" method="POST" enctype="multipart/form-data" action="{{isset($employee_office_info) ? route('employees.office_informations.update', $employee_office_info->id) : route('employees.office_informations.store') }}">
            @if(isset($employee_office_info))
                @method('PUT')
            @endif

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
                                <div class="col-lg-6 mb-3">
                                    <label for="employee_id" class="form-label">Employee Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" readonly
                                           value="{{ $employee->full_name }}">

                                    <input type="hidden" name="employee_id" value="{{ $employee->id }}">

                                </div>

                                <div class="col-lg-6 mb-3">
                                    <label for="emp_type" class="form-label">Employee Type <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select select2_list @error('emp_type') is-invalid @enderror" id="emp_type"
                                        name="emp_type" data-placeholder="Select Type">
                                        <option value="">Select Type</option>
                                        <option value="permanent" {{ old('emp_type') == 'permanent' || isset($employee_office_info) && ($employee_office_info->emp_type == 'permanent') ? 'selected' : '' }}>
                                            Permanent</option>
                                        <option value="contractual"
                                            {{ old('emp_type') == 'contractual' || isset($employee_office_info) && ($employee_office_info->emp_type == 'contractual') ? 'selected' : '' }}>Contractual</option>
                                    </select>
                                    @error('emp_type')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-6 mb-3">
                                    <label for="tofsil_id" class="form-label">Act</label>
                                    <select class="form-select select2_list" id="tofsil_id"
                                            name="tofsil_id" data-placeholder="Select Salary Act">
                                        <option value="">Select Salary Act</option>
                                        @foreach($acts as $act)
                                            <option
                                                value="{{ $act->id }}"
                                                {{ old('tofsil_id', $employee_office_info->tofsil_id ?? '') == $act->id ? 'selected' : '' }}>
                                                {{ $act->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('tofsil_id')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>



                                <div class="col-lg-6 mb-3">
                                    <label for="grade_id" class="form-label">Pay Grade</label>
                                    <select class="form-select" id="grade_id"
                                        name="grade_id" data-placeholder="Select Grade">
                                        <!-- Add grade options dynamically -->
                                    </select>
                                    @error('grade_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-6 mb-3">
                                    <label for="hr_file_no" class="form-label">HR File Number</label>
                                    <input type="text" class="form-control @error('hr_file_no') is-invalid @enderror"
                                        id="hr_file_no" name="hr_file_no" value="{{ isset($employee_office_info) ? $employee_office_info->hr_file_no : old('hr_file_no') }}">
                                    @error('hr_file_no')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-6 mb-3">
                                    <label for="file_note" class="form-label">HR File Note</label>
                                    <input type="text" class="form-control @error('file_note') is-invalid @enderror"
                                           id="file_note" name="file_note" value="{{isset($employee_office_info) ? $employee_office_info->file_note : old('file_note') }}">
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
                                    <label for="joining_company_id" class="form-label">Company <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select select2_list" id="joining_company_id"
                                            name="joining_company_id" data-placeholder="Select Company">
                                        <option value="">Choose Company</option>
                                        @foreach ($companies as $company)
                                            <option
                                                value="{{ $company->id }}"
                                                {{ old('joining_company_id', $employee_office_info->joining_company_id ?? '') == $company->id ? 'selected' : '' }}>
                                                {{ $company->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('joining_company_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="joining_business_unit_id" class="form-label">Business Unit <span
                                            class="text-danger">*</span></label>
                                    <select id="joining_business_unit_id"
                                        class="form-select select2_list @error('joining_business_unit_id') is-invalid @enderror"
                                        name="joining_business_unit_id"
                                        data-placeholder="Select Business Unit" required>
                                        <option value="">Select Business Unit</option>
                                    </select>
                                    @error('joining_business_unit_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="joining_division_id" class="form-label">Joining Division</label>
                                    <select class="form-select select2_list @error('joining_division_id') is-invalid @enderror"
                                        id="joining_division_id" name="joining_division_id"
                                        data-placeholder="Select Division">
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
                                    <select class="form-select select2_list @error('joining_department_id') is-invalid @enderror"
                                        id="joining_department_id" name="joining_department_id"
                                        data-placeholder="Select Department">
                                        <option value="">Select Department</option>
                                        <!-- Add department options dynamically -->
                                    </select>
                                    @error('joining_department_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="joining_section_id" class="form-label">Joining Section</label>
                                    <select class="form-select select2_list @error('joining_section_id') is-invalid @enderror"
                                        id="joining_section_id" name="joining_section_id"
                                        data-placeholder="Select Section">
                                        <option value="">Select Section</option>
                                        <!-- Add section options dynamically -->
                                    </select>
                                    @error('joining_section_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="joining_designation_id" class="form-label">Joining Designation</label>
                                    <select class="form-select select2_list @error('joining_designation_id') is-invalid @enderror"
                                        id="joining_designation_id" name="joining_designation_id"
                                        data-placeholder="Select Designation">
                                        <option value="">Select Designation</option>

                                    </select>
                                    @error('joining_designation_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <label for="date_of_join" class="form-label">Date of Join <span
                                            class="text-danger">*</span></label>
                                    <input type="date"
                                        class="form-control @error('date_of_join') is-invalid @enderror"
                                        id="date_of_join" name="date_of_join" value="{{ isset($employee_office_info) ? $employee_office_info->date_of_join : old('date_of_join') }}">
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
                            {{--<div class="row">
                                <div class="col-12 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="same_as_joining"
                                            onchange="copyJoiningInfo()">
                                        <label class="form-check-label" for="same_as_joining">
                                            Same as Joining Information
                                        </label>
                                    </div>
                                </div>
                            </div>--}}

                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <label for="current_company_id" class="form-label">Current Company</label>
                                    <select class="form-select select2_list"
                                        id="current_company_id" name="current_company_id"
                                        data-placeholder="Select Company">
                                        <option value="">Select Company</option>
                                        @foreach ($companies as $company)
                                            <option
                                                value="{{ $company->id }}"
                                                {{ old('joining_company_id', $employee_office_info->joining_company_id ?? '') == $company->id ? 'selected' : '' }}>
                                                {{ $company->name }}
                                            </option>                                        @endforeach
                                    </select>
                                    @error('current_company_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="current_business_unit_id" class="form-label">Current Business Unit</label>
                                    <select class="form-select select2_list @error('current_business_unit_id') is-invalid @enderror"
                                        id="current_business_unit_id" name="current_business_unit_id"
                                        data-placeholder="Select Business Unit">
                                        <option value="">Select Business Unit</option>
                                        <!-- Add business unit options dynamically -->
                                    </select>
                                    @error('current_business_unit_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="current_division_id" class="form-label">Current Division</label>
                                    <select class="form-select select2_list @error('current_division_id') is-invalid @enderror"
                                        id="current_division_id" name="current_division_id"
                                        data-placeholder="Select Division">
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
                                    <select class="form-select select2_list @error('current_department_id') is-invalid @enderror"
                                        id="current_department_id" name="current_department_id"
                                        data-placeholder="Select Department">
                                        <option value="">Select Department</option>
                                        <!-- Add department options dynamically -->
                                    </select>
                                    @error('current_department_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="current_section_id" class="form-label">Current Section</label>
                                    <select class="form-select select2_list @error('current_section_id') is-invalid @enderror"
                                        id="current_section_id" name="current_section_id"
                                        data-placeholder="Select Section">
                                        <option value="">Select Section</option>
                                        <!-- Add section options dynamically -->
                                    </select>
                                    @error('current_section_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="current_designation_id" class="form-label">Current Designation</label>
                                    <select class="form-select select2_list @error('current_designation_id') is-invalid @enderror"
                                        id="current_designation_id" name="current_designation_id"
                                        data-placeholder="Select Designation">
                                        <option value="">Select Designation</option>
                                        <!-- Add designation options dynamically -->
                                    </select>
                                    @error('current_designation_id')
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
                                <div class="col-lg-4 mb-3">
                                    <label for="orientation_required" class="form-label">Orientation Required</label>
                                    <select class="form-select  @error('orientation_required') is-invalid @enderror"
                                        id="orientation_required" name="orientation_required"
                                        data-placeholder="Select Option">
                                        <option value="">Select Option</option>
                                        <option value="yes" {{  old('orientation_required') == 'yes' || isset($employee_office_info) && ($employee_office_info->orientation_required == 'yes') ? 'selected' : '' }}>Yes</option>
                                        <option value="no" {{ old('orientation_required') == 'no' || isset($employee_office_info) && ($employee_office_info->orientation_required == 'no') ? 'selected' : '' }}>No</option>
                                    </select>
                                    @error('orientation_required')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="orientation_from" class="form-label">Orientation From</label>
                                    <input type="date"
                                        class="form-control @error('orientation_from') is-invalid @enderror"
                                        id="orientation_from" name="orientation_from"
                                        value="{{ isset($employee_office_info) ? $employee_office_info->orientation_from : old('orientation_from') }}">
                                    @error('orientation_from')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="orientation_to" class="form-label">Orientation To</label>
                                    <input type="date"
                                        class="form-control @error('orientation_to') is-invalid @enderror"
                                        id="orientation_to" name="orientation_to" value="{{isset($employee_office_info) ? $employee_office_info->orientation_to : old('orientation_to') }}">
                                    @error('orientation_to')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-6 mb-3">
                                    <label for="orientation_type" class="form-label">Orientation Type</label>
                                    <input type="text"
                                        class="form-control @error('orientation_type') is-invalid @enderror"
                                        id="orientation_type" name="orientation_type"
                                        value="{{ isset($employee_office_info) ? $employee_office_info->orientation_type : old('orientation_type') }}">
                                    @error('orientation_type')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>


                                <div class="col-lg-6 mb-3">
                                    <label for="orientation_days" class="form-label">Orientation Days</label>
                                    <input type="number"
                                        class="form-control @error('orientation_days') is-invalid @enderror"
                                        id="orientation_days" name="orientation_days"
                                        value="{{isset($employee_office_info) ? $employee_office_info->orientation_days : old('orientation_days') }}" min="0">
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
                                        value="{{ isset($employee_office_info) ? $employee_office_info->confirmation_date : old('confirmation_date') }}">
                                    @error('confirmation_date')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="probation_duration" class="form-label">Probation Duration (In Days)</label>
                                    <input type="number" min="0"
                                        class="form-control @error('probation_duration') is-invalid @enderror"
                                        id="probation_duration" name="probation_duration"
                                        value="{{ isset($employee_office_info) ? $employee_office_info->probation_duration : old('probation_duration') }}" placeholder="e.g., 6 months">
                                    @error('probation_duration')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="next_promotion_date" class="form-label">Next Promotion Date</label>
                                    <input type="date"
                                        class="form-control @error('next_promotion_date') is-invalid @enderror"
                                        id="next_promotion_date" name="next_promotion_date"
                                        value="{{ isset($employee_office_info) ? $employee_office_info->next_promotion_date : old('next_promotion_date') }}">
                                    @error('next_promotion_date')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6 mb-3">
                                    <label for="promotion_cycle" class="form-label">Promotion Cycle</label>
                                    <input type="text"
                                        class="form-control @error('promotion_cycle') is-invalid @enderror"
                                        id="promotion_cycle" name="promotion_cycle" value=" {{isset($employee_office_info) ? $employee_office_info->promotion_cycle :  old('promotion_cycle') }}"
                                        placeholder="e.g., Annual">
                                    @error('promotion_cycle')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-6 mb-3">
                                    <label for="increment_cycle" class="form-label">Increment Cycle</label>
                                    <input type="text"
                                        class="form-control @error('increment_cycle') is-invalid @enderror"
                                        id="increment_cycle" name="increment_cycle" value="{{ isset($employee_office_info) ? $employee_office_info->increment_cycle :  old('increment_cycle') }}"
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
                                <div class="col-lg-12 mb-3">
                                    <label class="form-label d-block">Weekends</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="weekends[]"
                                            id="weekend_friday" value="Friday"
                                            {{ in_array('Friday', old('weekends', [])) || isset($employee_office_info) && in_array('Friday',  $employee_office_info->weekends) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="weekend_friday">Friday</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="weekends[]"
                                            id="weekend_saturday" value="Saturday"
                                            {{ in_array('Saturday', old('weekends', [])) || isset($employee_office_info) && in_array('Saturday', $employee_office_info->weekends)  ? 'checked' : '' }}>
                                        <label class="form-check-label" for="weekend_saturday">Saturday</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="weekends[]"
                                            id="weekend_sunday" value="Sunday"
                                            {{ in_array('Sunday', old('weekends', [])) || isset($employee_office_info) && in_array('Sunday', $employee_office_info->weekends) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="weekend_sunday">Sunday</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="weekends[]"
                                               id="" value="Monday"
                                            {{ in_array('Monday', old('weekends', [])) || isset($employee_office_info) && in_array('Monday', $employee_office_info->weekends) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="">Monday</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="weekends[]"
                                               id="" value="Tuesday"
                                            {{ in_array('Tuesday', old('weekends', [])) || isset($employee_office_info) && in_array('Tuesday', $employee_office_info->weekends) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="">Tuesday</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="weekends[]"
                                               id="" value="Wednesday"
                                            {{ in_array('Wednesday', old('weekends', [])) || isset($employee_office_info) && in_array('Wednesday', $employee_office_info->weekends) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="">Wednesday</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="weekends[]"
                                               id="" value="Thursday"
                                            {{ in_array('Thursday', old('weekends', [])) || isset($employee_office_info) && in_array('Thursday', $employee_office_info->weekends) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="">Thursday</label>
                                    </div>
                                    @error('weekends')
                                        <small class="text-danger d-block">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-12 mb-3">
                                    <label class="form-label d-block">Alternate Off Day</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="alternate_off_day[]"
                                               id="weekend_friday" value="Friday"
                                            {{ in_array('Friday', old('alternate_off_day', [])) || isset($employee_office_info) && in_array('Friday', $employee_office_info->alternate_off_day) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="weekend_friday">Friday</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="alternate_off_day[]"
                                               id="weekend_saturday" value="Saturday"
                                            {{ in_array('Saturday', old('alternate_off_day', [])) || isset($employee_office_info) && in_array('Saturday', $employee_office_info->alternate_off_day)  ? 'checked' : '' }}>
                                        <label class="form-check-label" for="weekend_saturday">Saturday</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="alternate_off_day[]"
                                               id="weekend_sunday" value="Sunday"
                                            {{ in_array('Sunday', old('alternate_off_day', [])) || isset($employee_office_info) && in_array('Sunday', $employee_office_info->alternate_off_day) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="weekend_sunday">Sunday</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="alternate_off_day[]"
                                               id="" value="Monday"
                                            {{ in_array('Monday', old('alternate_off_day', [])) || isset($employee_office_info) && in_array('Monday', $employee_office_info->alternate_off_day) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="">Monday</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="alternate_off_day[]"
                                               id="" value="Tuesday"
                                            {{ in_array('Tuesday', old('alternate_off_day', [])) || isset($employee_office_info) && in_array('Tuesday', $employee_office_info->alternate_off_day) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="">Tuesday</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="alternate_off_day[]"
                                               id="" value="Wednesday"
                                            {{ in_array('Wednesday', old('alternate_off_day', [])) || isset($employee_office_info) && in_array('Wednesday', $employee_office_info->alternate_off_day) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="">Wednesday</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="alternate_off_day[]"
                                               id="" value="Thursday"
                                            {{ in_array('Thursday', old('alternate_off_day', [])) || isset($employee_office_info) && in_array('Thursday', $employee_office_info->alternate_off_day) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="">Thursday</label>
                                    </div>
                                    @error('alternate_off_day')
                                    <small class="text-danger d-block">{{ $message }}</small>
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
                                <div class="col-lg-2 mb-3">
                                    <div class="form-check">
                                        <input type="hidden" name="ot_allowed" value="no">
                                        <input class="form-check-input" type="checkbox" name="ot_allowed"
                                               id="ot_allowed" value="yes"
                                            {{ old('ot_allowed') == 'yes' || isset($employee_office_info) && ($employee_office_info->ot_allowed == 'yes') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="ot_allowed">OT Allowed</label>
                                    </div>
                                    @error('ot_allowed')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-2 mb-3">
                                    <div class="form-check">
                                        <input type="hidden" name="pf_eligible" value="no">
                                        <input class="form-check-input" type="checkbox" name="pf_eligible"
                                               id="pf_eligible" value="yes"
                                            {{ old('pf_eligible') == 'yes' || isset($employee_office_info) && ($employee_office_info->pf_eligible == 'yes') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="pf_eligible">PF Eligible</label>
                                    </div>
                                    @error('pf_eligible')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-2 mb-3">
                                    <div class="form-check">
                                        <input type="hidden" name="transport_eligible" value="no">
                                        <input class="form-check-input" type="checkbox" name="transport_eligible"
                                               id="transport_eligible" value="yes"
                                            {{ old('transport_eligible') == 'yes' || isset($employee_office_info) && ($employee_office_info->transport_eligible == 'yes')? 'checked' : '' }}>
                                        <label class="form-check-label" for="transport_eligible">Transport Eligible</label>
                                    </div>
                                </div>

                                <div class="col-lg-2 mb-3">
                                    <div class="form-check">
                                        <input type="hidden" name="gratuity_eligible" value="no">
                                        <input class="form-check-input" type="checkbox" name="gratuity_eligible"
                                               id="gratuity_eligible" value="yes"
                                            {{ old('gratuity_eligible') == 'yes' || isset($employee_office_info) && ($employee_office_info->gratuity_eligible == 'yes') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="gratuity_eligible">Gratuity Eligible</label>
                                    </div>
                                </div>

                                <div class="col-lg-2 mb-3">
                                    <div class="form-check">
                                        <input type="hidden" name="can_apply_loan" value="no">
                                        <input class="form-check-input" type="checkbox" name="can_apply_loan"
                                               id="can_apply_loan" value="yes"
                                            {{ old('can_apply_loan') == 'yes' || isset($employee_office_info) && ($employee_office_info->can_apply_loan == 'yes') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="can_apply_loan">Can Apply Loan</label>
                                    </div>
                                </div>

                                <div class="col-lg-2 mb-3">
                                    <div class="form-check">
                                        <input type="hidden" name="can_apply_advance" value="no">
                                        <input class="form-check-input" type="checkbox" name="can_apply_advance"
                                               id="can_apply_advance" value="yes"
                                            {{ old('can_apply_advance') == 'yes' || isset($employee_office_info) && ($employee_office_info->can_apply_advance == 'yes') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="can_apply_advance">Can Apply Advance</label>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-lg-6 mb-3">
                                    <label for="pf_effective_date" class="form-label">PF Effective Date</label>
                                    <input type="date"
                                           class="form-control @error('pf_effective_date') is-invalid @enderror"
                                           id="pf_effective_date" name="pf_effective_date"
                                           value="{{ isset($employee_office_info) ? $employee_office_info->pf_effective_date : old('pf_effective_date') }}">
                                    @error('pf_effective_date')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label for="salary_type" class="form-label">Salary Type <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select  @error('salary_type') is-invalid @enderror"
                                            id="salary_type" name="salary_type"
                                            data-placeholder="Select Type">
                                        <option value="">Select Type</option>
                                        <option value="hourly" {{ old('salary_type') == 'hourly' || isset($employee_office_info) && ($employee_office_info->salary_type == 'hourly') ? 'selected' : '' }}>
                                            Hourly</option>
                                        <option value="daily" {{ old('salary_type') == 'daily' || isset($employee_office_info) && ($employee_office_info->salary_type == 'daily') ? 'selected' : '' }}>
                                            Daily</option>
                                        <option value="monthly" {{ old('salary_type') == 'monthly' || isset($employee_office_info) && ($employee_office_info->salary_type == 'monthly') ? 'selected' : '' }}>
                                            Monthly</option>
                                        <option value="weekly" {{ old('salary_type') == 'weekly' || isset($employee_office_info) && ($employee_office_info->salary_type == 'weekly') ?  'selected' : '' }}>
                                            Weekly</option>
                                        <option value="yearly" {{ old('salary_type') == 'yearly' || isset($employee_office_info) && ($employee_office_info->salary_type == 'yearly') ? 'selected' : '' }}>Yearly
                                        </option>
                                    </select>
                                    @error('salary_type')
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
    </div>

    <script src="{{asset('assets/libs/jquery/jquery.min.js')}}"></script>



    <script>
        $(function() {

            function loadGrades(tofsilId, selectedGrade = null) {
                if (tofsilId) {
                    $.get('/get-grades/' + tofsilId, function(data) {
                        let $gradeSelect = $('#grade_id');
                        $gradeSelect.html('<option value="">-- Select --</option>');
                        $.each(data, function(key, value) {
                            let selected = (selectedGrade == value.id) ? 'selected' : '';
                            $gradeSelect.append('<option value="'+ value.id +'" '+selected+'>'+ value.name +'</option>');
                        });
                    });
                }
            }

            // --- Change Event ---
            $('#tofsil_id').on('change', function() {
                loadGrades($(this).val());
            });

            // --- Auto-load existing values from DB when editing ---
            @if(isset($employee_office_info))
            let tofsilId = "{{ old('tofsil_id', $employee_office_info->tofsil_id ?? '') }}";
            let gradeId  = "{{ old('grade_id', $employee_office_info->grade_id ?? '') }}";

            if (tofsilId) {
                loadGrades(tofsilId, gradeId);
            }
            @endif

        });
    </script>


    <script>
        $(function() {

            // ----------- JOINING INFORMATION -----------
            function loadUnits(companyId, selectedUnit = null) {
                if (companyId) {
                    $.get('/get-units/' + companyId, function(data) {
                        let $unitSelect = $('#joining_business_unit_id');
                        $unitSelect.html('<option value="">-- Select --</option>');
                        $.each(data, function(key, value) {
                            let selected = (selectedUnit == value.id) ? 'selected' : '';
                            $unitSelect.append('<option value="'+ value.id +'" '+selected+'>'+ value.unit_name +'</option>');
                        });
                    });
                }
            }

            function loadDivisions(unitId, selectedDivision = null) {
                if (unitId) {
                    $.get('/get-divisions/' + unitId, function(data) {
                        let $divSelect = $('#joining_division_id');
                        $divSelect.html('<option value="">-- Select --</option>');
                        $.each(data, function(key, value) {
                            let selected = (selectedDivision == value.id) ? 'selected' : '';
                            $divSelect.append('<option value="'+ value.id +'" '+selected+'>'+ value.division_name +'</option>');
                        });
                    });
                }
            }

            function loadDepartments(divisionId, selectedDepartment = null) {
                if (divisionId) {
                    $.get('/get-departments/' + divisionId, function(data) {
                        let $deptSelect = $('#joining_department_id');
                        $deptSelect.html('<option value="">-- Select --</option>');
                        $.each(data, function(key, value) {
                            let selected = (selectedDepartment == value.id) ? 'selected' : '';
                            $deptSelect.append('<option value="'+ value.id +'" '+selected+'>'+ value.department_name +'</option>');
                        });
                    });
                }
            }

            function loadSections(deptId, selectedSection = null) {
                if (deptId) {
                    $.get('/get-sections/' + deptId, function(data) {
                        let $sectionSelect = $('#joining_section_id');
                        $sectionSelect.html('<option value="">-- Select --</option>');
                        $.each(data, function(key, value) {
                            let selected = (selectedSection == value.id) ? 'selected' : '';
                            $sectionSelect.append('<option value="'+ value.id +'" '+selected+'>'+ value.section_name +'</option>');
                        });
                    });
                }
            }

            function loadDesignations(divisionId, selectedDesignation = null) {
                if (divisionId) {
                    $.get('/get-designations/' + divisionId, function(data) {
                        let $designationSelect = $('#joining_designation_id');
                        $designationSelect.html('<option value="">-- Select --</option>');
                        $.each(data, function(key, value) {
                            let selected = (selectedDesignation == value.id) ? 'selected' : '';
                            $designationSelect.append('<option value="'+ value.id +'" '+selected+'>'+ value.company_designation +'</option>');
                        });
                    });
                }
            }

            // --- Change Events ---
            $('#joining_company_id').on('change', function() {
                loadUnits($(this).val());
            });

            $('#joining_business_unit_id').on('change', function() {
                loadDivisions($(this).val());
            });

            $('#joining_division_id').on('change', function() {
                loadDepartments($(this).val());
            });

            $('#joining_department_id').on('change', function() {
                loadSections($(this).val());
            });

            $('#joining_division_id').on('change', function() {
                loadDesignations($(this).val());
            });

            // --- Auto-load existing values from DB when editing ---
            @if(isset($employee_office_info))
            let companyId   = "{{ old('joining_company_id', $employee_office_info->joining_company_id ?? '') }}";
            let unitId      = "{{ old('joining_business_unit_id', $employee_office_info->joining_business_unit_id ?? '') }}";
            let divisionId  = "{{ old('joining_division_id', $employee_office_info->joining_division_id ?? '') }}";
            let deptId      = "{{ old('joining_department_id', $employee_office_info->joining_department_id ?? '') }}";
            let sectionId   = "{{ old('joining_section_id', $employee_office_info->joining_section_id ?? '') }}";
            let designationId   = "{{ old('joining_designation_id', $employee_office_info->joining_designation_id ?? '') }}";

            if (companyId) {
                loadUnits(companyId, unitId);
                if (unitId) {
                    loadDivisions(unitId, divisionId);
                    if (divisionId) {
                        loadDepartments(divisionId, deptId);
                        loadDesignations(divisionId, designationId);
                        if (deptId) {
                            loadSections(deptId, sectionId);
                        }
                    }
                }
            }
            @endif

        });
    </script>

    <script>
        $(function() {

            // ----------- JOINING INFORMATION -----------
            function loadUnits(companyId, selectedUnit = null) {
                if (companyId) {
                    $.get('/get-units/' + companyId, function(data) {
                        let $unitSelect = $('#current_business_unit_id');
                        $unitSelect.html('<option value="">-- Select --</option>');
                        $.each(data, function(key, value) {
                            let selected = (selectedUnit == value.id) ? 'selected' : '';
                            $unitSelect.append('<option value="'+ value.id +'" '+selected+'>'+ value.unit_name +'</option>');
                        });
                    });
                }
            }

            function loadDivisions(unitId, selectedDivision = null) {
                if (unitId) {
                    $.get('/get-divisions/' + unitId, function(data) {
                        let $divSelect = $('#current_division_id');
                        $divSelect.html('<option value="">-- Select --</option>');
                        $.each(data, function(key, value) {
                            let selected = (selectedDivision == value.id) ? 'selected' : '';
                            $divSelect.append('<option value="'+ value.id +'" '+selected+'>'+ value.division_name +'</option>');
                        });
                    });
                }
            }

            function loadDepartments(divisionId, selectedDepartment = null) {
                if (divisionId) {
                    $.get('/get-departments/' + divisionId, function(data) {
                        let $deptSelect = $('#current_department_id');
                        $deptSelect.html('<option value="">-- Select --</option>');
                        $.each(data, function(key, value) {
                            let selected = (selectedDepartment == value.id) ? 'selected' : '';
                            $deptSelect.append('<option value="'+ value.id +'" '+selected+'>'+ value.department_name +'</option>');
                        });
                    });
                }
            }

            function loadSections(deptId, selectedSection = null) {
                if (deptId) {
                    $.get('/get-sections/' + deptId, function(data) {
                        let $sectionSelect = $('#current_section_id');
                        $sectionSelect.html('<option value="">-- Select --</option>');
                        $.each(data, function(key, value) {
                            let selected = (selectedSection == value.id) ? 'selected' : '';
                            $sectionSelect.append('<option value="'+ value.id +'" '+selected+'>'+ value.section_name +'</option>');
                        });
                    });
                }
            }

            function loadDesignations(divisionId, selectedDesignation = null) {
                if (divisionId) {
                    $.get('/get-designations/' + divisionId, function(data) {
                        let $designationSelect = $('#current_designation_id');
                        $designationSelect.html('<option value="">-- Select --</option>');
                        $.each(data, function(key, value) {
                            let selected = (selectedDesignation == value.id) ? 'selected' : '';
                            $designationSelect.append('<option value="'+ value.id +'" '+selected+'>'+ value.company_designation +'</option>');
                        });
                    });
                }
            }

            // --- Change Events ---
            $('#current_company_id').on('change', function() {
                loadUnits($(this).val());
            });

            $('#current_business_unit_id').on('change', function() {
                loadDivisions($(this).val());
            });

            $('#current_division_id').on('change', function() {
                loadDepartments($(this).val());
            });

            $('#current_department_id').on('change', function() {
                loadSections($(this).val());
            });

            $('#current_division_id').on('change', function() {
                loadDesignations($(this).val());
            });

            // --- Auto-load existing values from DB when editing ---
            @if(isset($employee_office_info))
            let companyId   = "{{ old('current_company_id', $employee_office_info->current_company_id ?? '') }}";
            let unitId      = "{{ old('current_business_unit_id', $employee_office_info->current_business_unit_id ?? '') }}";
            let divisionId  = "{{ old('current_division_id', $employee_office_info->current_division_id ?? '') }}";
            let deptId      = "{{ old('current_department_id', $employee_office_info->current_department_id ?? '') }}";
            let sectionId   = "{{ old('current_section_id', $employee_office_info->current_section_id ?? '') }}";
            let designationId   = "{{ old('current_designation_id', $employee_office_info->current_designation_id ?? '') }}";

            if (companyId) {
                loadUnits(companyId, unitId);
                if (unitId) {
                    loadDivisions(unitId, divisionId);
                    if (divisionId) {
                        loadDepartments(divisionId, deptId);
                        loadDesignations(divisionId, designationId);
                        if (deptId) {
                            loadSections(deptId, sectionId);
                        }
                    }
                }
            }
            @endif

        });
    </script>






@endsection
