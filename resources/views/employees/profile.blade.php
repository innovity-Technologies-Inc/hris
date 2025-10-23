@extends('structure.master')
@section('content')
{{--    @include('employees.partials.image_modal')--}}
    <!-- Profile Header -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <img src="{{ asset('assets/images/small/user-image.jpg') }}" class="rounded-top-2 img-fluid" alt="cover image">
                <div class="card-body">
                    <div class="align-items-center">
                        <div class="hando-main-sections">
                            <div class="hando-profile-main">
                                <img src="{{ asset('storage/' . $employee->photo_path) }}"
                                     class="rounded-circle img-fluid avatar-xxl img-thumbnail float-start"
                                     alt="profile photo">

                            </div>
                            <div class="overflow-hidden ms-md-4 ms-0">
                                <h4 class="m-0 text-dark fs-20 mt-2 mt-md-0">{{ $employee->first_name }}
                                    {{ $employee->middle_name }} {{ $employee->last_name }}</h4>
                                <p class="my-1 text-muted fs-16">
                                    {{--                                        Senior Software Engineer - --}}
                                    Employee ID: {{ $employee->applicant_id }}</p>
                                <span class="fs-15">
                                    <i class="mdi mdi-phone me-2 align-middle"></i>
                                    <span>{{ $employee->phone }}</span>
                                    <i class="mdi mdi-email ms-3 me-2 align-middle"></i>
                                    <span>{{ $employee->email }}</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- System Identifiers Card -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">System Identifiers</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <p class="mb-2"><strong>Applicant ID:</strong></p>
                            <p class="text-muted">{{ $employee->applicant_id }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-2"><strong>System ID:</strong></p>
                            <p class="text-muted">{{ $employee->system_id }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-2"><strong>Punch Card No:</strong></p>
                            <p class="text-muted">{{ $employee->punch_card_no }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabbed Content -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body pt-0">
                    <ul class="nav nav-underline border-bottom pt-2" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active p-2" id="personal_info_tab" data-bs-toggle="tab" href="#personal_info"
                               role="tab">
                                <span class="d-none d-sm-block">Personal Information</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link p-2" id="birth_doc_tab" data-bs-toggle="tab" href="#birth_doc"
                               role="tab">
                                <span class="d-none d-sm-block">Birth & Documents</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link p-2" id="contact_tab" data-bs-toggle="tab" href="#contact" role="tab">
                                <span class="d-none d-sm-block">Contact Information</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link p-2" id="address_tab" data-bs-toggle="tab" href="#address" role="tab">
                                <span class="d-none d-sm-block">Address Details</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link p-2" id="files_tab" data-bs-toggle="tab" href="#files" role="tab">
                                <span class="d-none d-sm-block">Files & Attachments</span>
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content text-muted">
                        <!-- Personal Information Tab -->
                        <div class="tab-pane active show pt-4" id="personal_info" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="fs-16 text-dark fw-semibold mb-3">Basic Information</h5>
                                    <div class="table-responsive">
                                        <table class="table table-borderless mb-0">
                                            <tbody>
                                            <tr>
                                                <td class="fw-semibold" style="width: 40%;">First Name</td>
                                                <td>{{ $employee->first_name }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold">Middle Name</td>
                                                <td>{{ $employee->middle_name }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold">Last Name</td>
                                                <td>{{ $employee->last_name }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold">Gender</td>
                                                <td>{{ $employee->gender }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold">Marital Status</td>
                                                <td>{{ $employee->marital_status }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold">Religion</td>
                                                <td>{{ $employee->religion }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold">Nationality</td>
                                                <td>{{ $employee->nationality }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold">Height</td>
                                                <td>{{ $employee->height }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold">Number of Children</td>
                                                <td>{{ $employee->number_of_children }}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <h5 class="fs-16 text-dark fw-semibold mb-3">Family Information</h5>
                                    <div class="table-responsive">
                                        <table class="table table-borderless mb-0">
                                            <tbody>
                                            <tr>
                                                <td class="fw-semibold" style="width: 40%;">Father's Name</td>
                                                <td>{{ $employee->father_name }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold">Mother's Name</td>
                                                <td>{{ $employee->mother_name }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold">Spouse's Name</td>
                                                <td>{{ $employee->spouse_name }}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Birth & Documents Tab -->
                        <div class="tab-pane pt-4" id="birth_doc" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="fs-16 text-dark fw-semibold mb-3">Birth Information</h5>
                                    <div class="table-responsive">
                                        <table class="table table-borderless mb-0">
                                            <tbody>
                                            <tr>
                                                <td class="fw-semibold" style="width: 40%;">Date of Birth</td>
                                                <td>{{ $employee->date_of_birth }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold">Birth Country</td>
                                                <td>{{ $employee->birth_country }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold">Birth Registration No</td>
                                                <td>{{ $employee->birth_registration_no }}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <h5 class="fs-16 text-dark fw-semibold mb-3 mt-4">Tax & Identity</h5>
                                    <div class="table-responsive">
                                        <table class="table table-borderless mb-0">
                                            <tbody>
                                            <tr>
                                                <td class="fw-semibold" style="width: 40%;">TIN Number</td>
                                                <td>{{ $employee->tin_number }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold">BGMEA ID</td>
                                                <td>{{ $employee->bgmea_id }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold">Residency ID</td>
                                                <td>{{ $employee->residency_id }}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <h5 class="fs-16 text-dark fw-semibold mb-3">Travel Documents</h5>
                                    <div class="table-responsive">
                                        <table class="table table-borderless mb-0">
                                            <tbody>
                                            <tr>
                                                <td class="fw-semibold" style="width: 40%;">Passport Number</td>
                                                <td>{{ $employee->passport_number }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold">Passport Expiry</td>
                                                <td>{{ $employee->passport_expiry }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold">Visa Expiry</td>
                                                <td>{{ $employee->visa_expiry }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold">Work Permit Expiry</td>
                                                <td>{{ $employee->work_permit_expiry }}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <h5 class="fs-16 text-dark fw-semibold mb-3 mt-4">License Information</h5>
                                    <div class="table-responsive">
                                        <table class="table table-borderless mb-0">
                                            <tbody>
                                            <tr>
                                                <td class="fw-semibold" style="width: 40%;">License Number</td>
                                                <td>{{ $employee->license_number }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold">License Expiry</td>
                                                <td>{{ $employee->license_expiry }}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information Tab -->
                        <div class="tab-pane pt-4" id="contact" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="fs-16 text-dark fw-semibold mb-3">Personal Contact</h5>
                                    <div class="table-responsive">
                                        <table class="table table-borderless mb-0">
                                            <tbody>
                                            <tr>
                                                <td class="fw-semibold" style="width: 40%;">Personal Mobile</td>
                                                <td>{{ $employee->personal_mobile }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold">Home Phone</td>
                                                <td>{{ $employee->home_phone }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold">Personal Email</td>
                                                <td>{{ $employee->personal_email }}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <h5 class="fs-16 text-dark fw-semibold mb-3">Work Contact</h5>
                                    <div class="table-responsive">
                                        <table class="table table-borderless mb-0">
                                            <tbody>
                                            <tr>
                                                <td class="fw-semibold" style="width: 40%;">Work Mobile</td>
                                                <td>{{ $employee->work_mobile }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold">Work Phone</td>
                                                <td>{{ $employee->work_phone }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold">Work Email</td>
                                                <td>{{ $employee->work_email }}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Address Details Tab -->
                        <div class="tab-pane pt-4" id="address" role="tabpanel">
                            <div class="row">
                                <div class="col-12">
                                    <!-- Present Address -->
                                    <div class="card border mb-3">
                                        <div class="card-header bg-light">
                                            <h5 class="card-title mb-0">Present Address</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p><strong>Address Line 1:</strong> {{$employee->present_address['line_1'] }}</p>
                                                    <p><strong>Village:</strong> {{ $employee->present_address['village'] }}</p>
                                                    <p><strong>Post Office:</strong> {{ $employee->present_address['post_office'] }}</p>
                                                    <p><strong>State:</strong> {{ $employee->present_address['state'] }}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p><strong>District:</strong> {{ $employee->present_address['district'] }}</p>
                                                    <p><strong>Division:</strong> {{ $employee->present_address['division'] }}</p>
                                                    <p><strong>Zip Code:</strong> {{ $employee->present_address['zip_code'] }}</p>
                                                    <p><strong>Country:</strong> {{ $employee->present_address['country'] }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Permanent Address -->
                                    <div class="card border mb-3">
                                        <div class="card-header bg-light">
                                            <h5 class="card-title mb-0">Permanent Address</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p><strong>Address Line 1:</strong> {{ $employee->permanent_address['line_1'] }}</p>
                                                    <p><strong>Village:</strong> {{ $employee->permanent_address['village'] }}</p>
                                                    <p><strong>Post Office:</strong> {{ $employee->permanent_address['post_office'] }}</p>
                                                    <p><strong>State:</strong> {{ $employee->permanent_address['state'] }}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p><strong>District:</strong> {{ $employee->permanent_address['district'] }}</p>
                                                    <p><strong>Division:</strong> {{ $employee->permanent_address['division'] }}</p>
                                                    <p><strong>Zip Code:</strong> {{ $employee->permanent_address['zip_code'] }}</p>
                                                    <p><strong>Country:</strong> {{ $employee->permanent_address['country'] }}</p>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <!-- Reference Address -->
                                    <div class="card border">
                                        <div class="card-header bg-light">
                                            <h5 class="card-title mb-0">Reference Information</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <p><strong>Reference Employee ID:</strong> {{ $employee->reference_address['emp_id'] }}</p>
                                                    <p><strong>Reference Name:</strong> {{ $employee->reference_address['reference_name'] }}</p>
                                                    <p><strong>Designation:</strong> {{ $employee->reference_designation }}</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p><strong>Address:</strong> {{ $employee->reference_address['line_1'] }}</p>
                                                    <p><strong>Village:</strong> {{ $employee->reference_address['village'] }}</p>
                                                    <p><strong>State:</strong> {{ $employee->reference_address['state'] }}</p>
                                                    <p><strong>District:</strong> {{ $employee->reference_address['district'] }}</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p><strong>Zip Code:</strong> {{ $employee->reference_address['zip_code'] }}</p>
                                                    <p><strong>Phone:</strong> {{ $employee->reference_address['phone'] }}</p>
                                                    <p><strong>Email:</strong> {{ $employee->reference_address['email'] }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Files & Attachments Tab -->
                        <div class="tab-pane pt-4" id="files" role="tabpanel">
                            <div class="row">
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <div class="card border">
                                        <div class="card-body text-center">
                                            <i class="mdi mdi-account-box fs-48 text-primary mb-2"></i>
                                            <h6 class="fw-semibold">Employee Photo</h6>
                                            <p class="text-muted small mb-2">photo_employee_001.jpg</p>
                                            <a href="#" class="btn btn-sm btn-primary" data-img="{{ asset('storage/' . $employee->photo_path) }}">View</a>
                                            <a href="#" class="btn btn-sm btn-outline-primary">Download</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 col-sm-6 mb-3">
                                    <div class="card border">
                                        <div class="card-body text-center">
                                            <i class="mdi mdi-fingerprint fs-48 text-success mb-2"></i>
                                            <h6 class="fw-semibold">Fingerprint</h6>
                                            <p class="text-muted small mb-2">fingerprint_001.png</p>
                                            <a href="#" class="btn btn-sm btn-success">View</a>
                                            <a href="#" class="btn btn-sm btn-outline-success">Download</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 col-sm-6 mb-3">
                                    <div class="card border">
                                        <div class="card-body text-center">
                                            <i class="mdi mdi-draw fs-48 text-warning mb-2"></i>
                                            <h6 class="fw-semibold">Signature</h6>
                                            <p class="text-muted small mb-2">signature_001.jpg</p>
                                            <a href="#" class="btn btn-sm btn-warning">View</a>
                                            <a href="#" class="btn btn-sm btn-outline-warning">Download</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 col-sm-6 mb-3">
                                    <div class="card border">
                                        <div class="card-body text-center">
                                            <i class="mdi mdi-file-document fs-48 text-info mb-2"></i>
                                            <h6 class="fw-semibold">Experience Documents</h6>
                                            <p class="text-muted small mb-2">experience_certificate.pdf</p>
                                            <a href="#" class="btn btn-sm btn-info">View</a>
                                            <a href="#" class="btn btn-sm btn-outline-info">Download</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="card border">
                                        <div class="card-header bg-light">
                                            <h5 class="card-title mb-0">Additional Documents</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-hover">
                                                    <thead>
                                                    <tr>
                                                        <th>Document Type</th>
                                                        <th>File Name</th>
                                                        <th>Upload Date</th>
                                                        <th>Size</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td>NID Copy</td>
                                                        <td>nid_front_back.pdf</td>
                                                        <td>Jan 10, 2024</td>
                                                        <td>1.2 MB</td>
                                                        <td>
                                                            <a href="#" class="btn btn-sm btn-primary">
                                                                <i class="mdi mdi-download"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Educational Certificates</td>
                                                        <td>certificates_all.pdf</td>
                                                        <td>Jan 10, 2024</td>
                                                        <td>3.5 MB</td>
                                                        <td>
                                                            <a href="#" class="btn btn-sm btn-primary">
                                                                <i class="mdi mdi-download"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Bank Statement</td>
                                                        <td>bank_details.pdf</td>
                                                        <td>Jan 10, 2024</td>
                                                        <td>850 KB</td>
                                                        <td>
                                                            <a href="#" class="btn btn-sm btn-primary">
                                                                <i class="mdi mdi-download"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="#" class="btn btn-secondary">
                            <i class="mdi mdi-printer me-1"></i> Print Profile
                        </a>
                        <a href="#" class="btn btn-primary">
                            <i class="mdi mdi-pencil me-1"></i> Edit Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Feather Icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    </script>
@endpush
