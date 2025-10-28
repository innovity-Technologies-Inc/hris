@extends('structure.master')
@section('content')
    @include('employees.partials.creation_button')
    <div class="mt-4">
        <!-- Trigger Button -->
        <div class="mb-3">
            <button type="button" class="btn btn-secondary shadow-sm" data-bs-toggle="modal" data-bs-target="#bulkUploadModal">
                <i class="mdi mdi-upload me-1"></i> Bulk Upload Nominee Information
            </button>
        </div>
        @include('employees.partials.modal.import')

        <form class="" method="POST" action="#" enctype="multipart/form-data">
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
                                    <label for="employee_id" class="form-label">Employee <span class="text-danger">*</span></label>
                                    <select id="employee_id" name="employee_id"
                                        class="form-select form-select-sm select2_list @error('employee_id') is-invalid @enderror"
                                        data-placeholder="Select employee name" aria-label="Employee Name" required>
                                        <option value="">Select Employee</option>
                                        {{-- @foreach ($employees as $employee)
                                            <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                                {{ $employee->name }}
                                            </option>
                                        @endforeach --}}
                                    </select>
                                    @error('employee_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="nominee_name" class="form-label">Nominee Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nominee_name') is-invalid @enderror"
                                        id="nominee_name" name="nominee_name" value="{{ old('nominee_name') }}" required>
                                    @error('nominee_name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="gender" class="form-label">Gender</label>
                                    <select class="form-select @error('gender') is-invalid @enderror"
                                        id="gender" name="gender" data-placeholder="Select Gender">
                                        <option value="">Select Gender</option>
                                        <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                        <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('gender')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <label for="father_name" class="form-label">Father's Name</label>
                                    <input type="text" class="form-control @error('father_name') is-invalid @enderror"
                                        id="father_name" name="father_name" value="{{ old('father_name') }}">
                                    @error('father_name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="mother_name" class="form-label">Mother's Name</label>
                                    <input type="text" class="form-control @error('mother_name') is-invalid @enderror"
                                        id="mother_name" name="mother_name" value="{{ old('mother_name') }}">
                                    @error('mother_name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="spouse_name" class="form-label">Spouse Name</label>
                                    <input type="text" class="form-control @error('spouse_name') is-invalid @enderror"
                                        id="spouse_name" name="spouse_name" value="{{ old('spouse_name') }}">
                                    @error('spouse_name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3 mb-3">
                                    <label for="date_of_birth" class="form-label">Date of Birth</label>
                                    <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror"
                                        id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}">
                                    @error('date_of_birth')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-3 mb-3">
                                    <label for="religion" class="form-label">Religion</label>
                                    <input type="text" class="form-control @error('religion') is-invalid @enderror"
                                        id="religion" name="religion" value="{{ old('religion') }}">
                                    @error('religion')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-3 mb-3">
                                    <label for="marital_status" class="form-label">Marital Status</label>
                                    <select class="form-select @error('marital_status') is-invalid @enderror"
                                        id="marital_status" name="marital_status" data-placeholder="Select Status">
                                        <option value="">Select Status</option>
                                        <option value="Single" {{ old('marital_status') == 'Single' ? 'selected' : '' }}>Single</option>
                                        <option value="Married" {{ old('marital_status') == 'Married' ? 'selected' : '' }}>Married</option>
                                        <option value="Divorced" {{ old('marital_status') == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                        <option value="Widowed" {{ old('marital_status') == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                    </select>
                                    @error('marital_status')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-3 mb-3">
                                    <label for="nationality" class="form-label">Nationality</label>
                                    <input type="text" class="form-control @error('nationality') is-invalid @enderror"
                                        id="nationality" name="nationality" value="{{ old('nationality') }}">
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
                                        <option value="A+" {{ old('blood_group') == 'A+' ? 'selected' : '' }}>A+</option>
                                        <option value="A-" {{ old('blood_group') == 'A-' ? 'selected' : '' }}>A-</option>
                                        <option value="B+" {{ old('blood_group') == 'B+' ? 'selected' : '' }}>B+</option>
                                        <option value="B-" {{ old('blood_group') == 'B-' ? 'selected' : '' }}>B-</option>
                                        <option value="AB+" {{ old('blood_group') == 'AB+' ? 'selected' : '' }}>AB+</option>
                                        <option value="AB-" {{ old('blood_group') == 'AB-' ? 'selected' : '' }}>AB-</option>
                                        <option value="O+" {{ old('blood_group') == 'O+' ? 'selected' : '' }}>O+</option>
                                        <option value="O-" {{ old('blood_group') == 'O-' ? 'selected' : '' }}>O-</option>
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
                                        id="nid" name="nid" value="{{ old('nid') }}" placeholder="Enter NID number">
                                    @error('nid')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-6 mb-3">
                                    <label for="birth_reg_no" class="form-label">Birth Registration No.</label>
                                    <input type="text" class="form-control @error('birth_reg_no') is-invalid @enderror"
                                        id="birth_reg_no" name="birth_reg_no" value="{{ old('birth_reg_no') }}"
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

            <!-- Financial Information Section -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Financial Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6 mb-3">
                                    <label for="bank_account_no" class="form-label">Bank Account Number</label>
                                    <input type="text" class="form-control @error('bank_account_no') is-invalid @enderror"
                                        id="bank_account_no" name="bank_account_no" value="{{ old('bank_account_no') }}"
                                        placeholder="Enter bank account number">
                                    @error('bank_account_no')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-6 mb-3">
                                    <label for="ratio" class="form-label">Ratio (%) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('ratio') is-invalid @enderror"
                                        id="ratio" name="ratio" value="{{ old('ratio') }}"
                                        min="0" max="100" step="0.01" placeholder="e.g., 100.00" required>
                                    @error('ratio')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                    <small class="text-muted">Enter percentage (e.g., 50 for 50%)</small>
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
                                        id="phone" name="phone" value="{{ old('phone') }}" placeholder="Enter phone number">
                                    @error('phone')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-6 mb-3">
                                    <label for="mobile" class="form-label">Mobile</label>
                                    <input type="text" class="form-control @error('mobile') is-invalid @enderror"
                                        id="mobile" name="mobile" value="{{ old('mobile') }}" placeholder="Enter mobile number">
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
                                        placeholder="House/Flat No., Road, Area">{{ old('present_address_line') }}</textarea>
                                    @error('present_address_line')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <label for="village" class="form-label">Village</label>
                                    <input type="text" class="form-control @error('village') is-invalid @enderror"
                                        id="village" name="village" value="{{ old('village') }}">
                                    @error('village')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="post_office" class="form-label">Post Office</label>
                                    <input type="text" class="form-control @error('post_office') is-invalid @enderror"
                                        id="post_office" name="post_office" value="{{ old('post_office') }}">
                                    @error('post_office')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="thana" class="form-label">Thana/Upazila</label>
                                    <input type="text" class="form-control @error('thana') is-invalid @enderror"
                                        id="thana" name="thana" value="{{ old('thana') }}">
                                    @error('thana')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3 mb-3">
                                    <label for="district" class="form-label">District</label>
                                    <input type="text" class="form-control @error('district') is-invalid @enderror"
                                        id="district" name="district" value="{{ old('district') }}">
                                    @error('district')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-3 mb-3">
                                    <label for="state" class="form-label">State/Division</label>
                                    <input type="text" class="form-control @error('state') is-invalid @enderror"
                                        id="state" name="state" value="{{ old('state') }}">
                                    @error('state')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-3 mb-3">
                                    <label for="zip_code" class="form-label">Zip/Postal Code</label>
                                    <input type="text" class="form-control @error('zip_code') is-invalid @enderror"
                                        id="zip_code" name="zip_code" value="{{ old('zip_code') }}">
                                    @error('zip_code')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-lg-3 mb-3">
                                    <label for="country" class="form-label">Country</label>
                                    <input type="text" class="form-control @error('country') is-invalid @enderror"
                                        id="country" name="country" value="{{ old('country') }}">
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
