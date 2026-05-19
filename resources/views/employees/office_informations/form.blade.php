@extends('structure.master')
@section('content')
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
                                <input type="hidden" name="employee_id" value="{{ $employee->id }}">

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

                                @if(App\HelperClass::getGeneralSetting()->branch_status == 1)
                                <div class="col-lg-4 mb-3">
                                    <label for="joining_business_unit_id" class="form-label">Branch <span
                                            class="text-danger">*</span></label>
                                    <select id="joining_business_unit_id"
                                        class="form-select select2_list @error('joining_business_unit_id') is-invalid @enderror"
                                        name="joining_business_unit_id"
                                        data-placeholder="Select Branch">
                                        <option value="">Select Branch</option>
                                    </select>
                                    @error('joining_business_unit_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                @endif

                                @if(App\HelperClass::getGeneralSetting()->division_status == 1)
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
                                @endif
                            </div>

                            <div class="row">
                                @if(App\HelperClass::getGeneralSetting()->department_status == 1)

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
                                @endif
                                    @if(App\HelperClass::getGeneralSetting()->section_status == 1)

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
                                    @endif

                                <div class="col-lg-4 mb-3">
                                    <label for="joining_designation_id" class="form-label">Joining Designation</label>
                                    <select class="form-select select2_list @error('joining_designation_id') is-invalid @enderror"
                                        id="joining_designation_id" name="joining_designation_id"
                                        data-placeholder="Select Designation">
                                        <option value="">Select Designation</option>
                                        @foreach ($designations as $item)
                                            <option
                                                value="{{ $item->id }}"
                                                {{ old('joining_designation_id', $employee_office_info->joining_designation_id ?? '') == $item->id ? 'selected' : '' }}>
                                                {{ $item->company_designation }}
                                            </option>
                                        @endforeach
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
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('current_company_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                @if(App\HelperClass::getGeneralSetting()->branch_status == 1)

                                <div class="col-lg-4 mb-3">
                                    <label for="current_business_unit_id" class="form-label">Current Branch</label>
                                    <select class="form-select select2_list @error('current_business_unit_id') is-invalid @enderror"
                                        id="current_business_unit_id" name="current_business_unit_id"
                                        data-placeholder="Select Branch">
                                        <option value="">Select Branch</option>
                                        <!-- Add business unit options dynamically -->
                                    </select>
                                    @error('current_business_unit_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                @endif

                                @if(App\HelperClass::getGeneralSetting()->division_status == 1)

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
                                @endif
                            </div>

                            <div class="row">
                                @if(App\HelperClass::getGeneralSetting()->department_status == 1)
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
                                @endif

                                    @if(App\HelperClass::getGeneralSetting()->section_status == 1)
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
                                    @endif

                                <div class="col-lg-4 mb-3">
                                    <label for="current_designation_id" class="form-label">Current Designation</label>
                                    <select class="form-select select2_list @error('current_designation_id') is-invalid @enderror"
                                        id="current_designation_id" name="current_designation_id"
                                        data-placeholder="Select Designation">
                                        <option value="">Select Designation</option>
                                        @foreach ($designations as $item)
                                            <option
                                                value="{{ $item->id }}"
                                                {{ old('current_designation_id', $employee_office_info->current_designation_id ?? '') == $item->id ? 'selected' : '' }}>
                                                {{ $item->company_designation }}
                                            </option>
                                        @endforeach
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
                                            {{ in_array('Friday', old('weekends', [])) || isset($employee_office_info) && in_array('Friday',  $employee_office_info->weekends ?? []) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="weekend_friday">Friday</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="weekends[]"
                                            id="weekend_saturday" value="Saturday"
                                            {{ in_array('Saturday', old('weekends', [])) || isset($employee_office_info) && in_array('Saturday', $employee_office_info->weekends ?? [])  ? 'checked' : '' }}>
                                        <label class="form-check-label" for="weekend_saturday">Saturday</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="weekends[]"
                                            id="weekend_sunday" value="Sunday"
                                            {{ in_array('Sunday', old('weekends', [])) || isset($employee_office_info) && in_array('Sunday', $employee_office_info->weekends ?? []) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="weekend_sunday">Sunday</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="weekends[]"
                                               id="" value="Monday"
                                            {{ in_array('Monday', old('weekends', [])) || isset($employee_office_info) && in_array('Monday', $employee_office_info->weekends ?? []) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="">Monday</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="weekends[]"
                                               id="" value="Tuesday"
                                            {{ in_array('Tuesday', old('weekends', [])) || isset($employee_office_info) && in_array('Tuesday', $employee_office_info->weekends ?? []) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="">Tuesday</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="weekends[]"
                                               id="" value="Wednesday"
                                            {{ in_array('Wednesday', old('weekends', [])) || isset($employee_office_info) && in_array('Wednesday', $employee_office_info->weekends ?? []) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="">Wednesday</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="weekends[]"
                                               id="" value="Thursday"
                                            {{ in_array('Thursday', old('weekends', [])) || isset($employee_office_info) && in_array('Thursday', $employee_office_info->weekends ?? []) ? 'checked' : '' }}>
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
                                            {{ in_array('Friday', old('alternate_off_day', [])) || isset($employee_office_info) && in_array('Friday', $employee_office_info->alternate_off_day ?? []) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="weekend_friday">Friday</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="alternate_off_day[]"
                                               id="weekend_saturday" value="Saturday"
                                            {{ in_array('Saturday', old('alternate_off_day', [])) || isset($employee_office_info) && in_array('Saturday', $employee_office_info->alternate_off_day ?? [])  ? 'checked' : '' }}>
                                        <label class="form-check-label" for="weekend_saturday">Saturday</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="alternate_off_day[]"
                                               id="weekend_sunday" value="Sunday"
                                            {{ in_array('Sunday', old('alternate_off_day', [])) || isset($employee_office_info) && in_array('Sunday', $employee_office_info->alternate_off_day ?? []) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="weekend_sunday">Sunday</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="alternate_off_day[]"
                                               id="" value="Monday"
                                            {{ in_array('Monday', old('alternate_off_day', [])) || isset($employee_office_info) && in_array('Monday', $employee_office_info->alternate_off_day ?? []) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="">Monday</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="alternate_off_day[]"
                                               id="" value="Tuesday"
                                            {{ in_array('Tuesday', old('alternate_off_day', [])) || isset($employee_office_info) && in_array('Tuesday', $employee_office_info->alternate_off_day ?? []) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="">Tuesday</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="alternate_off_day[]"
                                               id="" value="Wednesday"
                                            {{ in_array('Wednesday', old('alternate_off_day', [])) || isset($employee_office_info) && in_array('Wednesday', $employee_office_info->alternate_off_day ?? []) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="">Wednesday</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="alternate_off_day[]"
                                               id="" value="Thursday"
                                            {{ in_array('Thursday', old('alternate_off_day', [])) || isset($employee_office_info) && in_array('Thursday', $employee_office_info->alternate_off_day ?? []) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="">Thursday</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="alternate_off_day[]"
                                               id="" value="None"
                                            {{ in_array('None', old('alternate_off_day', [])) || isset($employee_office_info) && in_array('None', $employee_office_info->alternate_off_day ?? []) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="">None</label>
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
                                <button type="button" id="previewBtn" class="btn btn-info text-white">
                                    <i class="mdi mdi-eye me-1"></i> Preview
                                </button>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    @include('employees.partials.preview_modal')

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



@endsection

@push('scripts')
    <script>
        $(document).ready(function () {

            function loading($el, text = 'Loading...') {
                $el.prop('disabled', true).html(`<option value="">${text}</option>`);
            }

            function reset($el, text) {
                $el.prop('disabled', false).html(`<option value="">${text}</option>`);
            }

            // -------------------------
            // Load Divisions + Chain (Department + Section)
            // -------------------------
            function loadDivisions(prefix) {
                const companyId = $(`#${prefix}_company_id`).val();
                if (!companyId) return;

                const locationId = $(`#${prefix}_business_unit_id`).val() || 'null';

                loading($(`#${prefix}_division_id`));
                reset($(`#${prefix}_department_id`), 'Select Department');
                reset($(`#${prefix}_section_id`), 'Select Section');

                $.get(`/get-divisions/${companyId}/${locationId}`, function (data) {
                    reset($(`#${prefix}_division_id`), 'Select Division');
                    if (!data.length) {
                        $(`#${prefix}_division_id`).html('<option value="">No division found</option>');
                    } else {
                        $.each(data, function (_, item) {
                            $(`#${prefix}_division_id`).append(`<option value="${item.id}">${item.name}</option>`);
                        });
                    }
                    // Chain: Load departments after divisions
                    loadDepartments(prefix);
                });
            }

            // -------------------------
            // Load Departments + Chain (Section)
            // -------------------------
            function loadDepartments(prefix) {
                const companyId = $(`#${prefix}_company_id`).val();
                if (!companyId) return;

                const locationId = $(`#${prefix}_business_unit_id`).val() || 'null';
                const divisionId = $(`#${prefix}_division_id`).val() || 'null';

                loading($(`#${prefix}_department_id`));
                reset($(`#${prefix}_section_id`), 'Select Section');

                $.get(`/get-departments/${companyId}/${locationId}/${divisionId}`, function (data) {
                    reset($(`#${prefix}_department_id`), 'Select Department');
                    if (!data.length) {
                        $(`#${prefix}_department_id`).html('<option value="">No department found</option>');
                    } else {
                        $.each(data, function (_, item) {
                            $(`#${prefix}_department_id`).append(`<option value="${item.id}">${item.department_name}</option>`);
                        });
                    }
                    // Chain: Load sections after departments
                    loadSections(prefix);
                });
            }

            // -------------------------
            // Load Sections
            // -------------------------
            function loadSections(prefix) {
                const companyId = $(`#${prefix}_company_id`).val();
                if (!companyId) return;

                const locationId = $(`#${prefix}_business_unit_id`).val() || 'null';
                const divisionId = $(`#${prefix}_division_id`).val() || 'null';
                const departmentId = $(`#${prefix}_department_id`).val() || 'null';

                loading($(`#${prefix}_section_id`));

                $.get(`/get-sections/${companyId}/${locationId}/${divisionId}/${departmentId}`, function (data) {
                    reset($(`#${prefix}_section_id`), 'Select Section');
                    if (!data.length) {
                        $(`#${prefix}_section_id`).html('<option value="">No section found</option>');
                    } else {
                        $.each(data, function (_, item) {
                            $(`#${prefix}_section_id`).append(`<option value="${item.id}">${item.name}</option>`);
                        });
                    }
                });
            }

            // -------------------------
            // Company Change → Load Branch + Full Chain
            // -------------------------
            $('#joining_company_id, #current_company_id').on('change', function () {
                const prefix = this.id.replace('_company_id', '');

                const companyId = $(this).val();
                if (!companyId) return;

                reset($(`#${prefix}_division_id`), 'Select Division');
                reset($(`#${prefix}_department_id`), 'Select Department');
                reset($(`#${prefix}_section_id`), 'Select Section');

                @if(\App\HelperClass::getGeneralSetting()->branch_status == '1')
                loading($(`#${prefix}_business_unit_id`));

                $.get(`/get-units/${companyId}`, function (data) {
                    reset($(`#${prefix}_business_unit_id`), 'Select Branch');
                    if (!data.length) {
                        $(`#${prefix}_business_unit_id`).html('<option value="">No branch found</option>');
                    } else {
                        $.each(data, function (_, item) {
                            $(`#${prefix}_business_unit_id`).append(`<option value="${item.id}">${item.name}</option>`);
                        });
                    }
                    // Immediately load the full chain after branches
                    loadDivisions(prefix);
                });
                @else
                // No branch → directly load divisions + chain
                loadDivisions(prefix);
                @endif
            });

            // -------------------------
            // Branch Change → Reload Full Chain
            // -------------------------
            $('#joining_business_unit_id, #current_business_unit_id').on('change', function () {
                const prefix = this.id.replace('_business_unit_id', '');
                loadDivisions(prefix); // This will chain to department → section
            });

            // -------------------------
            // Division Change → Reload Department + Section
            // -------------------------
            $('#joining_division_id, #current_division_id').on('change', function () {
                const prefix = this.id.replace('_division_id', '');
                loadDepartments(prefix); // This will chain to section
            });

            // -------------------------
            // Department Change → Reload Section
            // -------------------------
            $('#joining_department_id, #current_department_id').on('change', function () {
                const prefix = this.id.replace('_department_id', '');
                loadSections(prefix);
            });

            // -------------------------
            // Auto-trigger on edit mode
            // -------------------------
            @if(isset($employee_office_info))
            @if($employee_office_info->joining_company_id || old('joining_company_id'))
            $('#joining_company_id').trigger('change');

            @if(\App\HelperClass::getGeneralSetting()->branch_status == '1' && ($employee_office_info->joining_business_unit_id || old('joining_business_unit_id')))
            setTimeout(() => $('#joining_business_unit_id').val('{{ old('joining_business_unit_id', $employee_office_info->joining_business_unit_id) }}').trigger('change'), 600);
            @endif

            @if($employee_office_info->joining_division_id || old('joining_division_id'))
            setTimeout(() => $('#joining_division_id').val('{{ old('joining_division_id', $employee_office_info->joining_division_id) }}').trigger('change'), 1000);
            @endif

            @if($employee_office_info->joining_department_id || old('joining_department_id'))
            setTimeout(() => $('#joining_department_id').val('{{ old('joining_department_id', $employee_office_info->joining_department_id) }}').trigger('change'), 1400);
            @endif
            @endif

            @if($employee_office_info->current_company_id || old('current_company_id'))
            $('#current_company_id').trigger('change');

            @if(\App\HelperClass::getGeneralSetting()->branch_status == '1' && ($employee_office_info->current_business_unit_id || old('current_business_unit_id')))
            setTimeout(() => $('#current_business_unit_id').val('{{ old('current_business_unit_id', $employee_office_info->current_business_unit_id) }}').trigger('change'), 600);
            @endif

            @if($employee_office_info->current_division_id || old('current_division_id'))
            setTimeout(() => $('#current_division_id').val('{{ old('current_division_id', $employee_office_info->current_division_id) }}').trigger('change'), 1000);
            @endif

            @if($employee_office_info->current_department_id || old('current_department_id'))
            setTimeout(() => $('#current_department_id').val('{{ old('current_department_id', $employee_office_info->current_department_id) }}').trigger('change'), 1400);
            @endif
            @endif
            @endif

        });
    </script>
@endpush

