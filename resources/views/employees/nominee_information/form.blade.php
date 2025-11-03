@extends('structure.master')
@section('content')
    @if(!isset($employee_nominee_info))
        @include('employees.partials.creation_button')
    @endif
    <div class="mt-4">

        <form class="" method="POST" enctype="multipart/form-data"
              action="{{isset($employee_nominee_info) ? route('employees.nominee_information.update', $employee_nominee_info->id) : route('employees.nominee_information.store') }}">
            @if(isset($employee_nominee_info))
                @method('PUT')
            @endif
            @csrf

            <!-- Basic Information Section -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Basic Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <label for="employee_id" class="form-label">Employee <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" readonly
                                           value="{{ $employee->full_name }}">

                                    <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="nominee_name" class="form-label">Nominee Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nominee_name') is-invalid @enderror"
                                           id="nominee_name" name="nominee_name"
                                           value="{{ isset($employee_nominee_info) ? $employee_nominee_info->nominee_name : old('nominee_name') }}"
                                           required>
                                    @error('nominee_name')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label class="form-label d-block">Gender <span class="text-danger">*</span></label>
                                    <select class="form-select @error('gender') is-invalid @enderror" name="gender"
                                            id="gender">
                                        <option value="" disabled selected>Select Gender</option>
                                        <option
                                            value="Male" {{isset($employee_nominee_info) && $employee_nominee_info->gender == 'Male' ? 'selected' : '' }} {{ old('gender') == 'Male' ? 'selected' : '' }}>
                                            Male
                                        </option>
                                        <option
                                            value="Female" {{isset($employee_nominee_info) && $employee_nominee_info->gender == 'Female' ? 'selected' : '' }} {{ old('gender') == 'Female' ? 'selected' : '' }}>
                                            Female
                                        </option>
                                        <option
                                            value="Other" {{isset($employee_nominee_info) && $employee_nominee_info->gender == 'Other' ? 'selected' : '' }} {{ old('gender') == 'Other' ? 'selected' : '' }}>
                                            Other
                                        </option>
                                    </select>
                                    @error('gender')
                                    <small class="text-danger d-block">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <label for="father_name" class="form-label">Father's Name</label>
                                    <input type="text" class="form-control @error('father_name') is-invalid @enderror"
                                           id="father_name" name="father_name"
                                           value="{{ isset($employee_nominee_info) ? $employee_nominee_info->father_name :  old('father_name') }}">
                                    @error('father_name')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="mother_name" class="form-label">Mother's Name</label>
                                    <input type="text" class="form-control @error('mother_name') is-invalid @enderror"
                                           id="mother_name" name="mother_name"
                                           value="{{ isset($employee_nominee_info) ? $employee_nominee_info->mother_name : old('mother_name') }}">
                                    @error('mother_name')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="spouse_name" class="form-label">Spouse Name</label>
                                    <input type="text" class="form-control @error('spouse_name') is-invalid @enderror"
                                           id="spouse_name" name="spouse_name"
                                           value="{{ isset($employee_nominee_info) ? $employee_nominee_info->spouse_name : old('spouse_name') }}">
                                    @error('spouse_name')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3 mb-3">
                                    <label for="date_of_birth" class="form-label">Date of Birth</label>
                                    <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror"
                                           id="date_of_birth" name="date_of_birth"
                                           value="{{ isset($employee_nominee_info) ? $employee_nominee_info->date_of_birth : old('date_of_birth') }}">
                                    @error('date_of_birth')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-3 mb-3">
                                    <label for="religion" class="form-label">Religion</label>
                                    <input type="text" class="form-control @error('religion') is-invalid @enderror"
                                           id="religion" name="religion"
                                           value="{{ isset($employee_nominee_info) ? $employee_nominee_info->religion : old('religion') }}">
                                    @error('religion')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-3 mb-3">
                                    <label for="marital_status" class="form-label">Marital Status</label>
                                    <select class="form-select @error('marital_status') is-invalid @enderror"
                                            id="marital_status" name="marital_status" data-placeholder="Select Status">
                                        <option value="">Select Status</option>
                                        <option
                                            value="single" {{ isset($employee_nominee_info) ? ($employee_nominee_info->marital_status == 'single' ? 'selected' : '') : (old('marital_status') == 'single' ? 'selected' : '') }}>
                                            Single
                                        </option>
                                        <option
                                            value="married" {{ isset($employee_nominee_info) ? ($employee_nominee_info->marital_status == 'married' ? 'selected' : '') : (old('marital_status') == 'married' ? 'selected' : '') }}>
                                            Married
                                        </option>
                                        <option
                                            value="divorced" {{ isset($employee_nominee_info) ? ($employee_nominee_info->marital_status == 'divorced' ? 'selected' : '') : (old('marital_status') == 'divorced' ? 'selected' : '') }}>
                                            Divorced
                                        </option>
                                        <option
                                            value="widowed" {{ isset($employee_nominee_info) ? ($employee_nominee_info->marital_status == 'widowed' ? 'selected' : '') : (old('marital_status') == 'widowed' ? 'selected' : '') }}>
                                            Widowed
                                        </option>
                                    </select>
                                    @error('marital_status')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-3 mb-3">
                                    <label for="nationality" class="form-label">Nationality</label>
                                    <input type="text" class="form-control @error('nationality') is-invalid @enderror"
                                           id="nationality" name="nationality"
                                           value="{{ isset($employee_nominee_info) ? $employee_nominee_info->nationality : old('nationality') }}">
                                    @error('nationality')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <label for="blood_group" class="form-label">Blood Group</label>
                                    <select class="form-select @error('blood_group') is-invalid @enderror"
                                            id="blood_group" name="blood_group" data-placeholder="Select Blood Group">
                                        <option value="">Select Blood Group</option>
                                        <option
                                            value="A+" {{ isset($employee_nominee_info) ? ($employee_nominee_info->blood_group == 'A+' ? 'selected' : '') : (old('blood_group') == 'A+' ? 'selected' : '') }}>
                                            A+
                                        </option>
                                        <option
                                            value="A-" {{ isset($employee_nominee_info) ? ($employee_nominee_info->blood_group == 'A-' ? 'selected' : '') : (old('blood_group') == 'A-' ? 'selected' : '') }}>
                                            A-
                                        </option>
                                        <option
                                            value="B+" {{ isset($employee_nominee_info) ? ($employee_nominee_info->blood_group == 'B+' ? 'selected' : '') : (old('blood_group') == 'B+' ? 'selected' : '') }}>
                                            B+
                                        </option>
                                        <option
                                            value="B-" {{ isset($employee_nominee_info) ? ($employee_nominee_info->blood_group == 'B-' ? 'selected' : '') : (old('blood_group') == 'B-' ? 'selected' : '') }}>
                                            B-
                                        </option>
                                        <option
                                            value="AB+" {{ isset($employee_nominee_info) ? ($employee_nominee_info->blood_group == 'AB+' ? 'selected' : '') : (old('blood_group') == 'AB+' ? 'selected' : '') }}>
                                            AB+
                                        </option>
                                        <option
                                            value="AB-" {{ isset($employee_nominee_info) ? ($employee_nominee_info->blood_group == 'AB-' ? 'selected' : '') : (old('blood_group') == 'AB-' ? 'selected' : '') }}>
                                            AB-
                                        </option>
                                        <option
                                            value="O+" {{ isset($employee_nominee_info) ? ($employee_nominee_info->blood_group == 'O+' ? 'selected' : '') : (old('blood_group') == 'O+' ? 'selected' : '') }}>
                                            O+
                                        </option>
                                        <option
                                            value="O-" {{ isset($employee_nominee_info) ? ($employee_nominee_info->blood_group == 'O-' ? 'selected' : '') : (old('blood_group') == 'O-' ? 'selected' : '') }}>
                                            O-
                                        </option>
                                    </select>
                                    @error('blood_group')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="photo_path" class="form-label">Nominee Photo</label>
                                    <input type="file" class="form-control @error('photo_path') is-invalid @enderror"
                                           id="photo_path" name="photo_path" accept="image/*">
                                    @error('photo_path')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Identification Section -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Identification</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6 mb-3">
                                    <label for="nid" class="form-label">National ID (NID)</label>
                                    <input type="text" class="form-control @error('nid') is-invalid @enderror"
                                           id="nid" name="nid"
                                           value="{{ isset($employee_nominee_info) ? $employee_nominee_info->nid : old('nid') }}"
                                           placeholder="Enter NID number">
                                    @error('nid')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-6 mb-3">
                                    <label for="birth_reg_no" class="form-label">Birth Registration No.</label>
                                    <input type="text" class="form-control @error('birth_reg_no') is-invalid @enderror"
                                           id="birth_reg_no" name="birth_reg_no"
                                           value="{{ isset($employee_nominee_info) ? $employee_nominee_info->birth_reg_no : old('birth_reg_no') }}"
                                           placeholder="Enter birth registration number">
                                    @error('birth_reg_no')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Information Section -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Contact Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6 mb-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                           id="phone" name="phone"
                                           value="{{ isset($employee_nominee_info) ? $employee_nominee_info->phone : old('phone') }}"
                                           placeholder="Enter phone number">
                                    @error('phone')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-6 mb-3">
                                    <label for="mobile" class="form-label">Mobile</label>
                                    <input type="text" class="form-control @error('mobile') is-invalid @enderror"
                                           id="mobile" name="mobile"
                                           value="{{ isset($employee_nominee_info) ? $employee_nominee_info->mobile : old('mobile') }}"
                                           placeholder="Enter mobile number">
                                    @error('mobile')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Address Information Section -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Address Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12 mb-3">
                                    <label for="present_address_line" class="form-label">Present Address Line</label>
                                    <textarea class="form-control @error('present_address_line') is-invalid @enderror"
                                              id="present_address_line" name="present_address_line" rows="2"
                                              placeholder="House/Flat No., Road, Area">{{ isset($employee_nominee_info) ? $employee_nominee_info->present_address_line : old('present_address_line') }}</textarea>
                                    @error('present_address_line')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <label for="village" class="form-label">Village</label>
                                    <input type="text" class="form-control @error('village') is-invalid @enderror"
                                           id="village" name="village"
                                           value="{{ isset($employee_nominee_info) ? $employee_nominee_info->village : old('village') }}">
                                    @error('village')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="post_office" class="form-label">Post Office</label>
                                    <input type="text" class="form-control @error('post_office') is-invalid @enderror"
                                           id="post_office" name="post_office"
                                           value="{{ isset($employee_nominee_info) ? $employee_nominee_info->post_office : old('post_office') }}">
                                    @error('post_office')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="thana" class="form-label">Thana/Upazila</label>
                                    <input type="text" class="form-control @error('thana') is-invalid @enderror"
                                           id="thana" name="thana"
                                           value="{{ isset($employee_nominee_info) ? $employee_nominee_info->thana : old('thana') }}">
                                    @error('thana')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3 mb-3">
                                    <label for="district" class="form-label">District</label>
                                    <input type="text" class="form-control @error('district') is-invalid @enderror"
                                           id="district" name="district"
                                           value="{{ isset($employee_nominee_info) ? $employee_nominee_info->district : old('district') }}">
                                    @error('district')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-3 mb-3">
                                    <label for="state" class="form-label">State/Division</label>
                                    <input type="text" class="form-control @error('state') is-invalid @enderror"
                                           id="state" name="state"
                                           value="{{ isset($employee_nominee_info) ? $employee_nominee_info->state : old('state') }}">
                                    @error('state')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-3 mb-3">
                                    <label for="zip_code" class="form-label">Zip/Postal Code</label>
                                    <input type="text" class="form-control @error('zip_code') is-invalid @enderror"
                                           id="zip_code" name="zip_code"
                                           value="{{ isset($employee_nominee_info) ? $employee_nominee_info->zip_code : old('zip_code') }}">
                                    @error('zip_code')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-3 mb-3">
                                    <label for="country" class="form-label">Country</label>
                                    <input type="text" class="form-control @error('country') is-invalid @enderror"
                                           id="country" name="country"
                                           value="{{ isset($employee_nominee_info) ? $employee_nominee_info->country : old('country') }}">
                                    @error('country')
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
                                <button type="submit" class="btn btn-primary">Submit Nominee Information</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>
@endsection
