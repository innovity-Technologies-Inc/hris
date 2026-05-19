@extends('structure.master')
@section('content')
    @if (!isset($employee))
        @include('employees.partials.creation_button')
    @endif

    <div class="mt-4">

        <form class="" method="POST" enctype="multipart/form-data"
            action={{ isset($employee)
                ? route('employees.general_informations.update', $employee->id)
                : route('employees.general_informations.store') }}>
            @if (isset($employee))
                @method('PUT')
            @endif

            @csrf



            <!-- System Identifiers Section -->
            @if(auth()->user()->user_type !== 'Employee')
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">System Identifiers</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <label for="applicant_id" class="form-label">Applicant ID <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('applicant_id') is-invalid @enderror"
                                        id="applicant_id" name="applicant_id"
                                        value="{{ isset($employee) ? $employee->applicant_id : old('applicant_id') }}">
                                    @error('applicant_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="system_id" class="form-label">System ID <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('system_id') is-invalid @enderror"
                                        id="system_id" name="system_id"
                                        value="{{ isset($employee) ? $employee->system_id : old('system_id') }}">
                                    @error('system_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="punch_card_no" class="form-label">Punch Card No <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('punch_card_no') is-invalid @enderror"
                                        id="punch_card_no" name="punch_card_no"
                                        value="{{ isset($employee) ? $employee->punch_card_no : old('punch_card_no') }}">
                                    @error('punch_card_no')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Personal Information Section -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Personal Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <label for="first_name" class="form-label">First Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                        id="first_name" name="first_name"
                                        value="{{ isset($employee) ? $employee->first_name : old('first_name') }}">
                                    @error('first_name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="middle_name" class="form-label">Middle Name</label>
                                    <input type="text" class="form-control @error('middle_name') is-invalid @enderror"
                                        id="middle_name" name="middle_name"
                                        value="{{ isset($employee) ? $employee->middle_name : old('middle_name') }}">
                                    @error('middle_name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="last_name" class="form-label">Last Name</label>
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                        id="last_name" name="last_name"
                                        value="{{ isset($employee) ? $employee->last_name : old('last_name') }}">
                                    @error('last_name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <label for="father_name" class="form-label">Father's Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('father_name') is-invalid @enderror"
                                        id="father_name" name="father_name"
                                        value="{{ isset($employee) ? $employee->father_name : old('father_name') }}">
                                    @error('father_name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="mother_name" class="form-label">Mother's Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('mother_name') is-invalid @enderror"
                                        id="mother_name" name="mother_name"
                                        value="{{ isset($employee) ? $employee->mother_name : old('mother_name') }}">
                                    @error('mother_name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="spouse_name" class="form-label">Spouse's Name</label>
                                    <input type="text" class="form-control @error('spouse_name') is-invalid @enderror"
                                        id="spouse_name" name="spouse_name"
                                        value="{{ isset($employee) ? $employee->spouse_name : old('spouse_name') }}">
                                    @error('spouse_name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3 mb-3">
                                    <label for="marital_status" class="form-label">Marital Status</label>
                                    <select class="form-select @error('marital_status') is-invalid @enderror"
                                        id="marital_status" name="marital_status">
                                        <option value="">Select Status</option>
                                        <option value="Single"
                                            {{ isset($employee) && $employee->marital_status == 'Single' ? 'selected' : '' }}
                                            {{ old('marital_status') == 'Single' ? 'selected' : '' }}>
                                            Single</option>
                                        <option value="Married"
                                            {{ isset($employee) && $employee->marital_status == 'Married' ? 'selected' : '' }}
                                            {{ old('marital_status') == 'Married' ? 'selected' : '' }}>Married</option>
                                        <option value="Divorced"
                                            {{ isset($employee) && $employee->marital_status == 'Divorced' ? 'selected' : '' }}
                                            {{ old('marital_status') == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                        <option value="Widowed"
                                            {{ isset($employee) && $employee->marital_status == 'Widowed' ? 'selected' : '' }}
                                            {{ old('marital_status') == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                    </select>
                                    @error('marital_status')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-3 mb-3">
                                    <label class="form-label d-block">Gender <span class="text-danger">*</span></label>
                                    <select class="form-select @error('gender') is-invalid @enderror" name="gender"
                                        id="gender">
                                        <option value="" disabled selected>Select Gender</option>
                                        <option value="Male"
                                            {{ isset($employee) && $employee->gender == 'Male' ? 'selected' : '' }}
                                            {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female"
                                            {{ isset($employee) && $employee->gender == 'Female' ? 'selected' : '' }}
                                            {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                        <option value="Other"
                                            {{ isset($employee) && $employee->gender == 'Other' ? 'selected' : '' }}
                                            {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('gender')
                                        <small class="text-danger d-block">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-3 mb-3">
                                    <label for="religion" class="form-label">Religion <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('religion') is-invalid @enderror"
                                        id="religion" name="religion"
                                        value="{{ isset($employee) ? $employee->religion : old('religion') }}">
                                    @error('religion')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-3 mb-3">
                                    <label for="nationality" class="form-label">Nationality <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nationality') is-invalid @enderror"
                                        id="nationality" name="nationality"
                                        value="{{ isset($employee) ? $employee->nationality : old('nationality') }}">
                                    @error('nationality')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <label for="blood_group" class="form-label">Blood Group</label>
                                    <select class="form-select @error('blood_group') is-invalid @enderror"
                                        id="blood_group" name="blood_group">
                                        <option value="">Select Blood Group</option>
                                        <option value="A+"
                                            {{ (isset($employee) && $employee->blood_group == 'A+') || old('blood_group') == 'A+' ? 'selected' : '' }}>
                                            A+</option>
                                        <option value="A-"
                                            {{ (isset($employee) && $employee->blood_group == 'A-') || old('blood_group') == 'A-' ? 'selected' : '' }}>
                                            A-</option>
                                        <option value="B+"
                                            {{ (isset($employee) && $employee->blood_group == 'B+') || old('blood_group') == 'B+' ? 'selected' : '' }}>
                                            B+</option>
                                        <option value="B-"
                                            {{ (isset($employee) && $employee->blood_group == 'B-') || old('blood_group') == 'B-' ? 'selected' : '' }}>
                                            B-</option>
                                        <option value="AB+"
                                            {{ (isset($employee) && $employee->blood_group == 'AB+') || old('blood_group') == 'AB+' ? 'selected' : '' }}>
                                            AB+</option>
                                        <option value="AB-"
                                            {{ (isset($employee) && $employee->blood_group == 'AB-') || old('blood_group') == 'AB-' ? 'selected' : '' }}>
                                            AB-</option>
                                        <option value="O+"
                                            {{ (isset($employee) && $employee->blood_group == 'O+') || old('blood_group') == 'O+' ? 'selected' : '' }}>
                                            O+</option>
                                        <option value="O-"
                                            {{ (isset($employee) && $employee->blood_group == 'O-') || old('blood_group') == 'O-' ? 'selected' : '' }}>
                                            O-</option>
                                    </select>
                                    @error('blood_group')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label class="form-label">Height</label>
                                    <div class="input-group">
                                        <input type="number"
                                            class="form-control @error('height_feet') is-invalid @enderror"
                                            id="height_feet" name="height_feet" placeholder="Feet" min="0"
                                            max="8"
                                            value="{{ isset($employee) ? $employee->height_feet : old('height_feet') }}">
                                        <span class="input-group-text">ft</span>
                                        <input type="number"
                                            class="form-control @error('height_inches') is-invalid @enderror"
                                            id="height_inches" name="height_inches" placeholder="Inches" min="0"
                                            max="11"
                                            value="{{ isset($employee) ? $employee->height_inches : old('height_inches') }}">
                                        <span class="input-group-text">in</span>
                                    </div>
                                    @error('height_feet')
                                        <small class="text-danger d-block">{{ $message }}</small>
                                    @enderror
                                    @error('height_inches')
                                        <small class="text-danger d-block">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="children_count" class="form-label">Number of Children</label>
                                    <input type="number"
                                        class="form-control @error('children_count') is-invalid @enderror"
                                        id="children_count" name="children_count"
                                        value="{{ isset($employee) ? $employee->children_count : old('children_count', 0) }}"
                                        min="0">
                                    @error('children_count')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Birth Information Section -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Birth Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <label for="date_of_birth" class="form-label">Date of Birth <span
                                            class="text-danger">*</span></label>
                                    <input type="date"
                                        class="form-control @error('date_of_birth') is-invalid @enderror"
                                        id="date_of_birth" name="date_of_birth"
                                        value="{{ isset($employee) ? $employee->date_of_birth : old('date_of_birth') }}">
                                    @error('date_of_birth')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="birth_country" class="form-label">Birth Country</label>
                                    <input type="text"
                                        class="form-control @error('birth_country') is-invalid @enderror"
                                        id="birth_country" name="birth_country"
                                        value="{{ isset($employee) ? $employee->birth_country : old('birth_country') }}">
                                    @error('birth_country')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="birth_reg_no" class="form-label">Birth Registration Number</label>
                                    <input type="text"
                                        class="form-control @error('birth_reg_no') is-invalid @enderror"
                                        id="birth_reg_no" name="birth_reg_no"
                                        value="{{ isset($employee) ? $employee->birth_reg_no : old('birth_reg_no') }}">
                                    @error('birth_reg_no')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Document Information Section -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Document Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <label for="tin" class="form-label">TIN Number</label>
                                    <input type="text" class="form-control @error('tin') is-invalid @enderror"
                                        id="tin" name="tin"
                                        value="{{ isset($employee) ? $employee->tin : old('tin') }}">
                                    @error('tin')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="passport_no" class="form-label">Passport Number</label>
                                    <input type="text" class="form-control @error('passport_no') is-invalid @enderror"
                                        id="passport_no" name="passport_no"
                                        value="{{ isset($employee) ? $employee->passport_no : old('passport_no') }}">
                                    @error('passport_no')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="passport_expiry" class="form-label">Passport Expiry Date</label>
                                    <input type="date"
                                        class="form-control @error('passport_expiry') is-invalid @enderror"
                                        id="passport_expiry" name="passport_expiry"
                                        value="{{ isset($employee) ? $employee->passport_expiry : old('passport_expiry') }}">
                                    @error('passport_expiry')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <label for="license_no" class="form-label">License Number</label>
                                    <input type="text" class="form-control @error('license_no') is-invalid @enderror"
                                        id="license_no" name="license_no"
                                        value="{{ isset($employee) ? $employee->license_no : old('license_no') }}">
                                    @error('license_no')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="license_expiry" class="form-label">License Expiry Date</label>
                                    <input type="date"
                                        class="form-control @error('license_expiry') is-invalid @enderror"
                                        id="license_expiry" name="license_expiry"
                                        value="{{ isset($employee) ? $employee->license_expiry : old('license_expiry') }}">
                                    @error('license_expiry')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <label for="visa_expiry" class="form-label">Visa Expiry Date</label>
                                    <input type="date" class="form-control @error('visa_expiry') is-invalid @enderror"
                                        id="visa_expiry" name="visa_expiry"
                                        value="{{ isset($employee) ? $employee->visa_expiry : old('visa_expiry') }}">
                                    @error('visa_expiry')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="work_expiry" class="form-label">Work Permit Expiry Date</label>
                                    <input type="date" class="form-control @error('work_expiry') is-invalid @enderror"
                                        id="work_expiry" name="work_expiry"
                                        value="{{ isset($employee) ? $employee->work_expiry : old('work_expiry') }}">
                                    @error('work_expiry')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="residency_id_number" class="form-label">Residency ID Number</label>
                                    <input type="text"
                                        class="form-control @error('residency_id_number') is-invalid @enderror"
                                        id="residency_id_number" name="residency_id_number"
                                        value="{{ isset($employee) ? $employee->residency_id_number : old('residency_id_number') }}">
                                    @error('residency_id_number')
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
                                    <label for="personal_mobile" class="form-label">Personal Mobile <span
                                            class="text-danger">*</span></label>
                                    <input type="tel"
                                        class="form-control @error('personal_mobile') is-invalid @enderror"
                                        id="personal_mobile" name="personal_mobile"
                                        value="{{ isset($employee) ? $employee->personal_mobile : old('personal_mobile') }}">
                                    @error('personal_mobile')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-6 mb-3">
                                    <label for="home_phone" class="form-label">Home Phone</label>
                                    <input type="tel" class="form-control @error('home_phone') is-invalid @enderror"
                                        id="home_phone" name="home_phone"
                                        value="{{ isset($employee) ? $employee->home_phone : old('home_phone') }}">
                                    @error('home_phone')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6 mb-3">
                                    <label for="work_mobile" class="form-label">Work Mobile</label>
                                    <input type="tel" class="form-control @error('work_mobile') is-invalid @enderror"
                                        id="work_mobile" name="work_mobile"
                                        value="{{ isset($employee) ? $employee->work_mobile : old('work_mobile') }}">
                                    @error('work_mobile')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-6 mb-3">
                                    <label for="work_phone" class="form-label">Work Phone</label>
                                    <input type="tel" class="form-control @error('work_phone') is-invalid @enderror"
                                        id="work_phone" name="work_phone"
                                        value="{{ isset($employee) ? $employee->work_phone : old('work_phone') }}">
                                    @error('work_phone')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6 mb-3">
                                    <label for="personal_email" class="form-label">Personal Email</label>
                                    <input type="email"
                                        class="form-control @error('personal_email') is-invalid @enderror"
                                        id="personal_email" name="personal_email"
                                        value="{{ isset($employee) ? $employee->personal_email : old('personal_email') }}">
                                    @error('personal_email')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-6 mb-3">
                                    <label for="work_email" class="form-label">Work Email</label>
                                    <input type="email" class="form-control @error('work_email') is-invalid @enderror"
                                        id="work_email" name="work_email"
                                        value="{{ isset($employee) ? $employee->work_email : old('work_email') }}">
                                    @error('work_email')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Present Address Section -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Present Address</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6 mb-3">
                                    <label for="present_address_line_1" class="form-label">Address Line 1 <span
                                            class="text-danger">*</span></label>
                                    <input type="text"
                                        class="form-control @error('present_address.line_1') is-invalid @enderror"
                                        id="present_address_line_1" name="present_address[line_1]"
                                        value="{{ isset($employee->present_address['line_1']) ? $employee->present_address['line_1'] : old('present_address.line_1') }}">
                                    @error('present_address.line_1')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-6 mb-3">
                                    <label for="present_village" class="form-label">Village</label>
                                    <input type="text"
                                        class="form-control @error('present_address.village') is-invalid @enderror"
                                        id="present_village" name="present_address[village]"
                                        value="{{ isset($employee->present_address['village']) ? $employee->present_address['village'] : old('present_address.village') }}">
                                    @error('present_address.village')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <label for="present_post_office" class="form-label">Post Office <span
                                            class="text-danger">*</span></label>
                                    <input type="text"
                                        class="form-control @error('present_address.post_office') is-invalid @enderror"
                                        id="present_post_office" name="present_address[post_office]"
                                        value="{{ isset($employee->present_address['post_office']) ? $employee->present_address['post_office'] : old('present_address.post_office') }}">
                                    @error('present_address.post_office')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="present_district" class="form-label">District <span
                                            class="text-danger">*</span></label>
                                    <input type="text"
                                        class="form-control @error('present_address.district') is-invalid @enderror"
                                        id="present_district" name="present_address[district]"
                                        value="{{ isset($employee->present_address['district']) ? $employee->present_address['district'] : old('present_address.district') }}">
                                    @error('present_address.district')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="present_division" class="form-label">Division <span
                                            class="text-danger">*</span></label>
                                    <input type="text"
                                        class="form-control @error('present_address.division') is-invalid @enderror"
                                        id="present_division" name="present_address[division]"
                                        value="{{ isset($employee->present_address['division']) ? $employee->present_address['division'] : old('present_address.division') }}">
                                    @error('present_address.division')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <label for="present_zip_code" class="form-label">Zip Code <span
                                            class="text-danger">*</span></label>
                                    <input type="text"
                                        class="form-control @error('present_address.zip_code') is-invalid @enderror"
                                        id="present_zip_code" name="present_address[zip_code]"
                                        value="{{ isset($employee->present_address['zip_code']) ? $employee->present_address['zip_code'] : old('present_address.zip_code') }}">
                                    @error('present_address.zip_code')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="present_state" class="form-label">State <span
                                            class="text-danger">*</span></label>
                                    <input type="text"
                                        class="form-control @error('present_address.state') is-invalid @enderror"
                                        id="present_state" name="present_address[state]"
                                        value="{{ isset($employee->present_address['state']) ? $employee->present_address['state'] : old('present_address.state') }}">
                                    @error('present_address.state')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="present_country" class="form-label">Country <span
                                            class="text-danger">*</span></label>
                                    <input type="text"
                                        class="form-control @error('present_address.country') is-invalid @enderror"
                                        id="present_country" name="present_address[country]"
                                        value="{{ isset($employee->present_address['country']) ? $employee->present_address['country'] : old('present_address.country') }}">
                                    @error('present_address.country')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Permanent Address Section -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Permanent Address</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="same_as_present"
                                            onchange="copyPresentAddress()">
                                        <label class="form-check-label" for="same_as_present">
                                            Same as Present Address
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6 mb-3">
                                    <label for="permanent_address_line_1" class="form-label">Address Line 1</label>
                                    <input type="text"
                                        class="form-control @error('permanent_address.line_1') is-invalid @enderror"
                                        id="permanent_address_line_1" name="permanent_address[line_1]"
                                        value="{{ isset($employee->permanent_address['line_1']) ? $employee->permanent_address['line_1'] : old('permanent_address.line_1') }}">
                                    @error('permanent_address.line_1')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-6 mb-3">
                                    <label for="permanent_village" class="form-label">Village</label>
                                    <input type="text"
                                        class="form-control @error('permanent_address.village') is-invalid @enderror"
                                        id="permanent_village" name="permanent_address[village]"
                                        value="{{ isset($employee->permanent_address['village']) ? $employee->permanent_address['village'] : old('permanent_address.village') }}">
                                    @error('permanent_address.village')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <label for="permanent_post_office" class="form-label">Post Office</label>
                                    <input type="text"
                                        class="form-control @error('permanent_address.post_office') is-invalid @enderror"
                                        id="permanent_post_office" name="permanent_address[post_office]"
                                        value="{{ isset($employee->permanent_address['post_office']) ? $employee->permanent_address['post_office'] : old('permanent_address.post_office') }}">
                                    @error('permanent_address.post_office')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="permanent_district" class="form-label">District</label>
                                    <input type="text"
                                        class="form-control @error('permanent_address.district') is-invalid @enderror"
                                        id="permanent_district" name="permanent_address[district]"
                                        value="{{ isset($employee->permanent_address['district']) ? $employee->permanent_address['district'] : old('permanent_address.district') }}">
                                    @error('permanent_address.district')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="permanent_division" class="form-label">Division</label>
                                    <input type="text"
                                        class="form-control @error('permanent_address.division') is-invalid @enderror"
                                        id="permanent_division" name="permanent_address[division]"
                                        value="{{ isset($employee->permanent_address['division']) ? $employee->permanent_address['division'] : old('permanent_address.division') }}">
                                    @error('permanent_address.division')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <label for="permanent_zip_code" class="form-label">Zip Code</label>
                                    <input type="text"
                                        class="form-control @error('permanent_address.zip_code') is-invalid @enderror"
                                        id="permanent_zip_code" name="permanent_address[zip_code]"
                                        value="{{ isset($employee->permanent_address['zip_code']) ? $employee->permanent_address['zip_code'] : old('permanent_address.zip_code') }}">
                                    @error('permanent_address.zip_code')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="permanent_state" class="form-label">State</label>
                                    <input type="text"
                                        class="form-control @error('permanent_address.state') is-invalid @enderror"
                                        id="permanent_state" name="permanent_address[state]"
                                        value="{{ isset($employee->permanent_address['state']) ? $employee->permanent_address['state'] : old('permanent_address.state') }}">
                                    @error('permanent_address.state')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="permanent_country" class="form-label">Country</label>
                                    <input type="text"
                                        class="form-control @error('permanent_address.country') is-invalid @enderror"
                                        id="permanent_country" name="permanent_address[country]"
                                        value="{{ isset($employee->permanent_address['country']) ? $employee->permanent_address['country'] : old('permanent_address.country') }}">
                                    @error('permanent_address.country')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reference Address Section -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Reference Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <label for="reference_emp_id" class="form-label">Reference Employee ID</label>
                                    <input type="text"
                                        class="form-control @error('reference_address.emp_id') is-invalid @enderror"
                                        id="reference_emp_id" name="reference_address[emp_id]"
                                        value="{{ isset($employee->reference_address['emp_id']) ? $employee->reference_address['emp_id'] : old('reference_address.emp_id') }}">
                                    @error('reference_address.emp_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="reference_name" class="form-label">Reference Name</label>
                                    <input type="text"
                                        class="form-control @error('reference_address.reference_name') is-invalid @enderror"
                                        id="reference_name" name="reference_address[reference_name]"
                                        value="{{ isset($employee->reference_address['reference_name']) ? $employee->reference_address['reference_name'] : old('reference_address.reference_name') }}">
                                    @error('reference_address.reference_name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="reference_designation" class="form-label">Reference Designation</label>
                                    <input type="text"
                                        class="form-control @error('reference_address.reference_designation') is-invalid @enderror"
                                        id="reference_designation" name="reference_address[reference_designation]"
                                        value="{{ isset($employee->reference_address['reference_designation']) ? $employee->reference_address['reference_designation'] : old('reference_address.reference_designation') }}">
                                    @error('reference_address.reference_designation')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6 mb-3">
                                    <label for="reference_address_line_1" class="form-label">Address Line 1</label>
                                    <input type="text"
                                        class="form-control @error('reference_address.line_1') is-invalid @enderror"
                                        id="reference_address_line_1" name="reference_address[line_1]"
                                        value="{{ isset($employee->reference_address['line_1']) ? $employee->reference_address['line_1'] : old('reference_address.line_1') }}">
                                    @error('reference_address.line_1')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-6 mb-3">
                                    <label for="reference_village" class="form-label">Village</label>
                                    <input type="text"
                                        class="form-control @error('reference_address.village') is-invalid @enderror"
                                        id="reference_village" name="reference_address[village]"
                                        value="{{ isset($employee->reference_address['village']) ? $employee->reference_address['village'] : old('reference_address.village') }}">
                                    @error('reference_address.village')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <label for="reference_post_office" class="form-label">Post Office</label>
                                    <input type="text"
                                        class="form-control @error('reference_address.post_office') is-invalid @enderror"
                                        id="reference_post_office" name="reference_address[post_office]"
                                        value="{{ isset($employee->reference_address['post_office']) ? $employee->reference_address['post_office'] : old('reference_address.post_office') }}">
                                    @error('reference_address.post_office')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="reference_district" class="form-label">District</label>
                                    <input type="text"
                                        class="form-control @error('reference_address.district') is-invalid @enderror"
                                        id="reference_district" name="reference_address[district]"
                                        value="{{ isset($employee->reference_address['district']) ? $employee->reference_address['district'] : old('reference_address.district') }}">
                                    @error('reference_address.district')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="reference_division" class="form-label">Division</label>
                                    <input type="text"
                                        class="form-control @error('reference_address.division') is-invalid @enderror"
                                        id="reference_division" name="reference_address[division]"
                                        value="{{ isset($employee->reference_address['division']) ? $employee->reference_address['division'] : old('reference_address.division') }}">
                                    @error('reference_address.division')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <label for="reference_zip_code" class="form-label">Zip Code</label>
                                    <input type="text"
                                        class="form-control @error('reference_address.zip_code') is-invalid @enderror"
                                        id="reference_zip_code" name="reference_address[zip_code]"
                                        value="{{ isset($employee->reference_address['zip_code']) ? $employee->reference_address['zip_code'] : old('reference_address.zip_code') }}">
                                    @error('reference_address.zip_code')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="reference_state" class="form-label">State</label>
                                    <input type="text"
                                        class="form-control @error('reference_address.state') is-invalid @enderror"
                                        id="reference_state" name="reference_address[state]"
                                        value="{{ isset($employee->reference_address['state']) ? $employee->reference_address['state'] : old('reference_address.state') }}">
                                    @error('reference_address.state')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="reference_country" class="form-label">Country</label>
                                    <input type="text"
                                        class="form-control @error('reference_address.country') is-invalid @enderror"
                                        id="reference_country" name="reference_address[country]"
                                        value="{{ isset($employee->reference_address['country']) ? $employee->reference_address['country'] : old('reference_address.country') }}">
                                    @error('reference_address.country')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <label for="reference_phone" class="form-label">Phone</label>
                                    <input type="tel"
                                        class="form-control @error('reference_address.phone') is-invalid @enderror"
                                        id="reference_phone" name="reference_address[phone]"
                                        value="{{ isset($employee->reference_address['phone']) ? $employee->reference_address['phone'] : old('reference_address.phone') }}">
                                    @error('reference_address.phone')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="reference_mobile" class="form-label">Mobile</label>
                                    <input type="tel"
                                        class="form-control @error('reference_address.mobile') is-invalid @enderror"
                                        id="reference_mobile" name="reference_address[mobile]"
                                        value="{{ isset($employee->reference_address['mobile']) ? $employee->reference_address['mobile'] : old('reference_address.mobile') }}">
                                    @error('reference_address.mobile')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="reference_email" class="form-label">Reference Email</label>
                                    <input type="email"
                                        class="form-control @error('reference_address.email') is-invalid @enderror"
                                        id="reference_email" name="reference_address[email]"
                                        value="{{ isset($employee->reference_address['email']) ? $employee->reference_address['email'] : old('reference_address.email') }}">
                                    @error('reference_address.email')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if (!isset($employee))
                <!-- Login Information Section -->
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-primary text-white">
                                <h5 class="card-title mb-0">Login Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-4 mb-3">
                                        <label for="user_type" class="form-label text-primary fw-bold">User Type <span class="text-danger">*</span></label>
                                        <select class="form-select @error('user_type') is-invalid @enderror" id="user_type" name="user_type">
                                            <option value="">Select User Type</option>
                                            <option value="Group" {{ (isset($employee->user) && $employee->user->user_type == 'Group') || old('user_type') == 'Group' ? 'selected' : '' }}>Group</option>
                                            <option value="Company" {{ (isset($employee->user) && $employee->user->user_type == 'Company') || old('user_type') == 'Company' ? 'selected' : '' }}>Company</option>
                                            <option value="Business Unit" {{ (isset($employee->user) && $employee->user->user_type == 'Business Unit') || old('user_type') == 'Business Unit' ? 'selected' : '' }}>Business Unit</option>
                                            <option value="Division" {{ (isset($employee->user) && $employee->user->user_type == 'Division') || old('user_type') == 'Division' ? 'selected' : '' }}>Division</option>
                                            <option value="Department" {{ (isset($employee->user) && $employee->user->user_type == 'Department') || old('user_type') == 'Department' ? 'selected' : '' }}>Department</option>
                                            <option value="Section" {{ (isset($employee->user) && $employee->user->user_type == 'Section') || old('user_type') == 'Section' ? 'selected' : '' }}>Section</option>
                                            <option value="Employee" {{ (isset($employee->user) && $employee->user->user_type == 'Employee') || old('user_type') == 'Employee' ? 'selected' : '' }}>Employee</option>
                                        </select>
                                        @error('user_type')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-lg-8 mb-3">
                                        <label for="roles" class="form-label text-primary fw-bold">Assign Roles</label>
                                        <select class="form-select select2_list @error('roles') is-invalid @enderror" id="roles" name="roles[]" multiple>
                                            @foreach($roles as $role)
                                                <option value="{{ $role->name }}" {{ (isset($employee->user) && $employee->user->hasRole($role->name)) || (is_array(old('roles')) && in_array($role->name, old('roles'))) ? 'selected' : '' }}>
                                                    {{ $role->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('roles')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6 mb-3">
                                        <label for="password" class="form-label text-primary fw-bold">Password {{ isset($employee) ? '(Leave blank to keep current)' : '*' }}</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-lock"></i></span>
                                            <input type="password" class="form-control border-start-0 @error('password') is-invalid @enderror" id="password" name="password">
                                            <span class="input-group-text bg-white border-start-0"><i class="fas fa-eye password-toggle"></i></span>
                                        </div>
                                        @error('password')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-lg-6 mb-3">
                                        <label for="password_confirmation" class="form-label text-primary fw-bold">Confirm Password</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-check-circle"></i></span>
                                            <input type="password" class="form-control border-start-0" id="password_confirmation" name="password_confirmation">
                                            <span class="input-group-text bg-white border-start-0"><i class="fas fa-eye password-toggle"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- File Upload Section -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">File Uploads</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6 mb-3">
                                    <label for="photo_path" class="form-label">Photo</label>
                                    <input class=" filepond form-control @error('photo_path') is-invalid @enderror" type="file"
                                        id="photo_path" name="photo_path" accept="image/*">
                                    <small class="form-text text-muted">Accepted formats: JPG, PNG, GIF (Max: 2MB)</small>
                                    @error('photo_path')
                                        <small class="text-danger d-block">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-6 mb-3">
                                    <label for="fingerprint_path" class="form-label">Fingerprint</label>
                                    <input class="filepond form-control @error('fingerprint_path') is-invalid @enderror"
                                        type="file" id="fingerprint_path" name="fingerprint_path" accept="image/*">
                                    <small class="form-text text-muted">Accepted formats: JPG, PNG (Max: 2MB)</small>
                                    @error('fingerprint_path')
                                        <small class="text-danger d-block">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6 mb-3">
                                    <label for="signature_path" class="form-label">Signature</label>
                                    <input class="form-control @error('signature_path') is-invalid @enderror"
                                        type="file" id="signature_path" name="signature_path" accept="image/*">
                                    <small class="form-text text-muted">Accepted formats: JPG, PNG (Max: 1MB)</small>
                                    @error('signature_path')
                                        <small class="text-danger d-block">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-6 mb-3">
                                    <label for="experience_attachment_path" class="form-label">Experience
                                        Documents</label>
                                    <input class="form-control @error('experience_attachment_path') is-invalid @enderror"
                                        type="file" id="experience_attachment_path" name="experience_attachment_path"
                                        accept=".pdf,.doc,.docx">
                                    <small class="form-text text-muted">Accepted formats: PDF, DOC, DOCX (Max: 5MB)</small>
                                    @error('experience_attachment_path')
                                        <small class="text-danger d-block">{{ $message }}</small>
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
                                <button type="submit" class="btn btn-primary">Submit Employee Information</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </form>

        <script>
            function copyPresentAddress() {
                const checkbox = document.getElementById('same_as_present');

                if (checkbox.checked) {
                    document.getElementById('permanent_address_line_1').value = document.getElementById(
                        'present_address_line_1').value;
                    document.getElementById('permanent_village').value = document.getElementById('present_village').value;
                    document.getElementById('permanent_post_office').value = document.getElementById('present_post_office')
                        .value;
                    document.getElementById('permanent_district').value = document.getElementById('present_district').value;
                    document.getElementById('permanent_division').value = document.getElementById('present_division').value;
                    document.getElementById('permanent_zip_code').value = document.getElementById('present_zip_code').value;
                    document.getElementById('permanent_state').value = document.getElementById('present_state').value;
                    document.getElementById('permanent_country').value = document.getElementById('present_country').value;
                } else {
                    document.getElementById('permanent_address_line_1').value = '';
                    document.getElementById('permanent_village').value = '';
                    document.getElementById('permanent_post_office').value = '';
                    document.getElementById('permanent_district').value = '';
                    document.getElementById('permanent_division').value = '';
                    document.getElementById('permanent_zip_code').value = '';
                    document.getElementById('permanent_state').value = '';
                    document.getElementById('permanent_country').value = '';
                }
            }
        </script>
    @endsection
