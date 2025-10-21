@extends('structure.master')
@section('content')
    <form class="needs-validation" novalidate method="POST" enctype="multipart/form-data">
        @csrf

        <!-- System Identifiers Section -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">System Identifiers</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4 mb-3">
                                <label for="applicant_id" class="form-label">Applicant ID</label>
                                <input type="text" class="form-control" id="applicant_id" name="applicant_id">
                            </div>

                            <div class="col-lg-4 mb-3">
                                <label for="system_id" class="form-label">System ID</label>
                                <input type="text" class="form-control" id="system_id" name="system_id">
                            </div>

                            <div class="col-lg-4 mb-3">
                                <label for="punch_card_no" class="form-label">Punch Card No</label>
                                <input type="text" class="form-control" id="punch_card_no" name="punch_card_no">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
                                <input type="text" class="form-control" id="first_name" name="first_name" required>
                                <div class="invalid-feedback">Please provide a first name.</div>
                            </div>

                            <div class="col-lg-4 mb-3">
                                <label for="middle_name" class="form-label">Middle Name</label>
                                <input type="text" class="form-control" id="middle_name" name="middle_name">
                            </div>

                            <div class="col-lg-4 mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 mb-3">
                                <label for="father_name" class="form-label">Father's Name</label>
                                <input type="text" class="form-control" id="father_name" name="father_name">
                            </div>

                            <div class="col-lg-4 mb-3">
                                <label for="mother_name" class="form-label">Mother's Name</label>
                                <input type="text" class="form-control" id="mother_name" name="mother_name">
                            </div>

                            <div class="col-lg-4 mb-3">
                                <label for="spouse_name" class="form-label">Spouse's Name</label>
                                <input type="text" class="form-control" id="spouse_name" name="spouse_name">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-3 mb-3">
                                <label for="marital_status" class="form-label">Marital Status</label>
                                <select class="form-select" id="marital_status" name="marital_status">
                                    <option value="">Select Status</option>
                                    <option value="Single">Single</option>
                                    <option value="Married">Married</option>
                                    <option value="Divorced">Divorced</option>
                                    <option value="Widowed">Widowed</option>
                                </select>
                            </div>

                            <div class="col-lg-3 mb-3">
                                <label class="form-label d-block">Gender <span class="text-danger">*</span></label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" id="gender_male"
                                        value="Male" required>
                                    <label class="form-check-label" for="gender_male">Male</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" id="gender_female"
                                        value="Female" required>
                                    <label class="form-check-label" for="gender_female">Female</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" id="gender_other"
                                        value="Other" required>
                                    <label class="form-check-label" for="gender_other">Other</label>
                                </div>
                                <div class="invalid-feedback d-block"></div>
                            </div>

                            <div class="col-lg-3 mb-3">
                                <label for="religion" class="form-label">Religion</label>
                                <input type="text" class="form-control" id="religion" name="religion">
                            </div>

                            <div class="col-lg-3 mb-3">
                                <label for="nationality" class="form-label">Nationality</label>
                                <input type="text" class="form-control" id="nationality" name="nationality">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 mb-3">
                                <label class="form-label">Height</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="height_feet" name="height_feet"
                                        placeholder="Feet" min="0" max="8">
                                    <span class="input-group-text">ft</span>
                                    <input type="number" class="form-control" id="height_inches" name="height_inches"
                                        placeholder="Inches" min="0" max="11">
                                    <span class="input-group-text">in</span>
                                </div>
                            </div>

                            <div class="col-lg-4 mb-3">
                                <label for="children_count" class="form-label">Number of Children</label>
                                <input type="number" class="form-control" id="children_count" name="children_count"
                                    value="0" min="0">
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
                                <input type="date" class="form-control" id="date_of_birth" name="date_of_birth"
                                    required>
                                <div class="invalid-feedback">Please provide date of birth.</div>
                            </div>

                            <div class="col-lg-4 mb-3">
                                <label for="birth_country" class="form-label">Birth Country</label>
                                <input type="text" class="form-control" id="birth_country" name="birth_country">
                            </div>

                            <div class="col-lg-4 mb-3">
                                <label for="birth_reg_no" class="form-label">Birth Registration Number</label>
                                <input type="text" class="form-control" id="birth_reg_no" name="birth_reg_no">
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
                                <input type="text" class="form-control" id="tin" name="tin">
                            </div>

                            <div class="col-lg-4 mb-3">
                                <label for="passport_no" class="form-label">Passport Number</label>
                                <input type="text" class="form-control" id="passport_no" name="passport_no">
                            </div>

                            <div class="col-lg-4 mb-3">
                                <label for="passport_expiry" class="form-label">Passport Expiry Date</label>
                                <input type="date" class="form-control" id="passport_expiry" name="passport_expiry">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 mb-3">
                                <label for="license_no" class="form-label">License Number</label>
                                <input type="text" class="form-control" id="license_no" name="license_no">
                            </div>

                            <div class="col-lg-4 mb-3">
                                <label for="license_expiry" class="form-label">License Expiry Date</label>
                                <input type="date" class="form-control" id="license_expiry" name="license_expiry">
                            </div>

                            <div class="col-lg-4 mb-3">
                                <label for="bgmea_id" class="form-label">BGMEA ID</label>
                                <input type="text" class="form-control" id="bgmea_id" name="bgmea_id">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 mb-3">
                                <label for="visa_expiry" class="form-label">Visa Expiry Date</label>
                                <input type="date" class="form-control" id="visa_expiry" name="visa_expiry">
                            </div>

                            <div class="col-lg-4 mb-3">
                                <label for="work_expiry" class="form-label">Work Permit Expiry Date</label>
                                <input type="date" class="form-control" id="work_expiry" name="work_expiry">
                            </div>

                            <div class="col-lg-4 mb-3">
                                <label for="residency_id_number" class="form-label">Residency ID Number</label>
                                <input type="text" class="form-control" id="residency_id_number"
                                    name="residency_id_number">
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
                                <input type="tel" class="form-control" id="personal_mobile" name="personal_mobile"
                                    required>
                                <div class="invalid-feedback">Please provide a personal mobile number.</div>
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label for="home_phone" class="form-label">Home Phone</label>
                                <input type="tel" class="form-control" id="home_phone" name="home_phone">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <label for="work_mobile" class="form-label">Work Mobile</label>
                                <input type="tel" class="form-control" id="work_mobile" name="work_mobile">
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label for="work_phone" class="form-label">Work Phone</label>
                                <input type="tel" class="form-control" id="work_phone" name="work_phone">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <label for="personal_email" class="form-label">Personal Email</label>
                                <input type="email" class="form-control" id="personal_email" name="personal_email">
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label for="work_email" class="form-label">Work Email</label>
                                <input type="email" class="form-control" id="work_email" name="work_email">
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
                                <label for="present_address_line_1" class="form-label">Address Line 1</label>
                                <input type="text" class="form-control" id="present_address_line_1"
                                    name="present_address[line_1]">
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label for="present_village" class="form-label">Village</label>
                                <input type="text" class="form-control" id="present_village" name="present_address[village]">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 mb-3">
                                <label for="present_post_office" class="form-label">Post Office</label>
                                <input type="text" class="form-control" id="present_post_office"
                                    name="present_address[post_office]">
                            </div>

                            <div class="col-lg-4 mb-3">
                                <label for="present_district" class="form-label">District</label>
                                <input type="text" class="form-control" id="present_district" name="present_address[district]">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 mb-3">
                                <label for="present_division" class="form-label">Division</label>
                                <input type="text" class="form-control" id="present_division" name="present_address[division]">
                            </div>

                            <div class="col-lg-4 mb-3">
                                <label for="present_zip_code" class="form-label">Zip Code</label>
                                <input type="text" class="form-control" id="present_zip_code" name="present_address[zip_code]">
                            </div>

                            <div class="col-lg-4 mb-3">
                                <label for="present_state" class="form-label">State</label>
                                <input type="text" class="form-control" id="present_state" name="present_address[state]">
                            </div>
                            <div class="col-lg-4 mb-3">
                                <label for="present_country" class="form-label">Country</label>
                                <input type="text" class="form-control" id="present_country" name="present_address[country]">
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
                                <input type="text" class="form-control" id="permanent_address_line_1"
                                    name="permanent_address[address_line_1]">
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label for="permanent_village" class="form-label">Village</label>
                                <input type="text" class="form-control" id="permanent_village" name="permanent_address[village]">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 mb-3">
                                <label for="permanent_post_office" class="form-label">Post Office</label>
                                <input type="text" class="form-control" id="permanent_post_office"
                                    name="permanent_address[post_office]">
                            </div>

                            <div class="col-lg-4 mb-3">
                                <label for="permanent_district" class="form-label">District</label>
                                <input type="text" class="form-control" id="permanent_district" name="permanent_address[district]">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 mb-3">
                                <label for="permanent_division" class="form-label">Division</label>
                                <input type="text" class="form-control" id="permanent_division" name="permanent_address[division]">
                            </div>

                            <div class="col-lg-4 mb-3">
                                <label for="permanent_zip_code" class="form-label">Zip Code</label>
                                <input type="text" class="form-control" id="permanent_zip_code" name="permanent_address[zip_code]">
                            </div>
                            <div class="col-lg-4 mb-3">
                                <label for="present_state" class="form-label">State</label>
                                <input type="text" class="form-control" id="present_state" name="permanent_address[state]">
                            </div>

                            <div class="col-lg-4 mb-3">
                                <label for="permanent_country" class="form-label">Country</label>
                                <input type="text" class="form-control" id="permanent_country" name="permanent_address[country]">
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
                                <input type="text" class="form-control" id="reference_emp_id" name="reference_address_emp_id">
                            </div>

                            <div class="col-lg-4 mb-3">
                                <label for="reference_name" class="form-label">Reference Name</label>
                                <input type="text" class="form-control" id="reference_name" name="reference_address_name">
                            </div>

                            <div class="col-lg-4 mb-3">
                                <label for="reference_designation" class="form-label">Reference Designation</label>
                                <input type="text" class="form-control" id="reference_address_designation"
                                    name="reference_address_designation">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <label for="reference_address_line_1" class="form-label">Address Line 1</label>
                                <input type="text" class="form-control" id="reference_address_line_1"
                                    name="reference_address[address_line_1]">
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label for="reference_village" class="form-label">Village</label>
                                <input type="text" class="form-control" id="reference_village" name="reference_address[village]">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 mb-3">
                                <label for="reference_post_office" class="form-label">Post Office</label>
                                <input type="text" class="form-control" id="reference_post_office"
                                    name="reference_address[post_office]">
                            </div>

                            <div class="col-lg-4 mb-3">
                                <label for="reference_district" class="form-label">District</label>
                                <input type="text" class="form-control" id="reference_district" name="reference_address[district]">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 mb-3">
                                <label for="reference_division" class="form-label">Division</label>
                                <input type="text" class="form-control" id="reference_division" name="reference_address[division]">
                            </div>

                            <div class="col-lg-4 mb-3">
                                <label for="reference_city" class="form-label">City</label>
                                <input type="text" class="form-control" id="reference_city" name="reference_address[city]">
                            </div>

                            <div class="col-lg-4 mb-3">
                                <label for="reference_zip_code" class="form-label">Zip Code</label>
                                <input type="text" class="form-control" id="reference_zip_code" name="reference_address[zip_code]">
                            </div>
                            <div class="col-lg-4 mb-3">
                                <label for="present_state" class="form-label">State</label>
                                <input type="text" class="form-control" id="present_state" name="reference_address[state]">
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-lg-4 mb-3">
                                <label for="reference_country" class="form-label">Country</label>
                                <input type="text" class="form-control" id="reference_country" name="reference_address[country]">
                            </div>

                            <div class="col-lg-4 mb-3">
                                <label for="reference_phone" class="form-label">Phone</label>
                                <input type="tel" class="form-control" id="reference_phone" name="reference_address[phone]">
                            </div>

                            <div class="col-lg-4 mb-3">
                                <label for="reference_mobile" class="form-label">Mobile</label>
                                <input type="tel" class="form-control" id="reference_mobile" name="reference_address[mobile]">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <label for="reference_email" class="form-label">Reference Email</label>
                                <input type="email" class="form-control" id="reference_email" name="reference_address_email">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
                                <input class="form-control" type="file" id="photo_path" name="photo_path"
                                    accept="image/*">
                                <small class="form-text text-muted">Accepted formats: JPG, PNG, GIF (Max: 2MB)</small>
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label for="fingerprint_path" class="form-label">Fingerprint</label>
                                <input class="form-control" type="file" id="fingerprint_path" name="fingerprint_path"
                                    accept="image/*">
                                <small class="form-text text-muted">Accepted formats: JPG, PNG (Max: 2MB)</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <label for="signature_path" class="form-label">Signature</label>
                                <input class="form-control" type="file" id="signature_path" name="signature_path"
                                    accept="image/*">
                                <small class="form-text text-muted">Accepted formats: JPG, PNG (Max: 1MB)</small>
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label for="experience_attachment_path" class="form-label">Experience Documents</label>
                                <input class="form-control" type="file" id="experience_attachment_path"
                                    name="experience_attachment_path" accept=".pdf,.doc,.docx">
                                <small class="form-text text-muted">Accepted formats: PDF, DOC, DOCX (Max: 5MB)</small>
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
                // Copy all present address fields to permanent address fields
                document.getElementById('permanent_address_line_1').value = document.getElementById('present_address_line_1').value;
                document.getElementById('permanent_village').value = document.getElementById('present_village').value;
                document.getElementById('permanent_post_office').value = document.getElementById('present_post_office').value;
                document.getElementById('permanent_thana').value = document.getElementById('present_thana').value;
                document.getElementById('permanent_district').value = document.getElementById('present_district').value;
                document.getElementById('permanent_division').value = document.getElementById('present_division').value;
                document.getElementById('permanent_zip_code').value = document.getElementById('present_zip_code').value;
                document.getElementById('permanent_country').value = document.getElementById('present_country').value;
                document.getElementById('permanent_phone').value = document.getElementById('present_phone').value;
                document.getElementById('permanent_mobile').value = document.getElementById('present_mobile').value;
            } else {
                // Clear all permanent address fields
                document.getElementById('permanent_address_line_1').value = '';
                document.getElementById('permanent_village').value = '';
                document.getElementById('permanent_post_office').value = '';
                document.getElementById('permanent_thana').value = '';
                document.getElementById('permanent_district').value = '';
                document.getElementById('permanent_division').value = '';
                document.getElementById('permanent_zip_code').value = '';
                document.getElementById('permanent_country').value = '';
                document.getElementById('permanent_phone').value = '';
                document.getElementById('permanent_mobile').value = '';
            }
        }

        // Bootstrap form validation
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>
@endsection
