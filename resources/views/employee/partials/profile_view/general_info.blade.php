@if(auth()->user()->user_type === 'Employee' && ($employee->general_info_status === 'incomplete' || empty($employee->first_name)))
    <!-- Only show Complete Profile button for incomplete status to Employees -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-none" style="background-color: rgba(151, 64, 99, 0.05); border: 1px solid rgba(151, 64, 99, 0.2) !important;">
                <div class="card-body text-center py-5">
                    <div class="avatar-lg rounded-circle mx-auto mb-4 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; background-color: rgba(151, 64, 99, 0.1);">
                        <i class="fas fa-user-clock" style="font-size: 40px; color: #974063;"></i>
                    </div>
                    <h4 class="fw-bold text-dark">Incomplete Profile</h4>
                    <p class="text-muted mb-4 mx-auto" style="max-width: 500px;">
                        Your employee profile is currently incomplete. Please provide your personal, contact, and address information to activate your account and move to the pending review status.
                    </p>
                    @can('employee-management.create')
                    <a href="{{ route('employee.general_informations.edit', $employee->id) }}"
                        class="btn btn-lg px-5 shadow-sm text-white" style="background-color: #974063;">
                        <i class="fas fa-user-edit me-1"></i> Complete profile Now
                    </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
@else
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
                                                    <td>{{ $employee->first_name ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-semibold">Middle Name</td>
                                                    <td>{{ $employee->middle_name ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-semibold">Last Name</td>
                                                    <td>{{ $employee->last_name ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-semibold">Gender</td>
                                                    <td>{{ $employee->gender ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-semibold">Marital Status</td>
                                                    <td>{{ $employee->marital_status ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-semibold">Religion</td>
                                                    <td>{{ $employee->religion ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-semibold">Nationality</td>
                                                    <td>{{ $employee->nationality ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-semibold">Blood Group</td>
                                                    <td>{{ $employee->blood_group ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-semibold">Height</td>
                                                    <td>{{ $employee->height_feet ?? 0 }} Feet {{ $employee->height_inches ?? 0 }}
                                                        Inches
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-semibold">Number of Children</td>
                                                    <td>{{ $employee->children_count ?? 0 }}</td>
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
                                                    <td>{{ $employee->father_name ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-semibold">Mother's Name</td>
                                                    <td>{{ $employee->mother_name ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-semibold">Spouse's Name</td>
                                                    <td>{{ $employee->spouse_name ?? 'N/A' }}</td>
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
                                                    <td>{{ $employee->date_of_birth ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-semibold">Birth Country</td>
                                                    <td>{{ $employee->birth_country ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-semibold">Birth Registration No</td>
                                                    <td>{{ $employee->birth_reg_no ?? 'N/A' }}</td>
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
                                                    <td>{{ $employee->tin ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-semibold">BGMEA ID</td>
                                                    <td>{{ $employee->bgmea_id ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-semibold">Residency ID</td>
                                                    <td>{{ $employee->residency_id_number ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-semibold">National ID (NID)</td>
                                                    <td>{{ $employee->nid ?? 'N/A' }}</td>
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
                                                    <td>{{ $employee->passport_no ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-semibold">Passport Expiry</td>
                                                    <td>{{ $employee->passport_expiry ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-semibold">Visa Expiry</td>
                                                    <td>{{ $employee->visa_expiry ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-semibold">Work Permit Expiry</td>
                                                    <td>{{ $employee->work_expiry ?? 'N/A' }}</td>
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
                                                    <td>{{ $employee->license_no ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-semibold">License Expiry</td>
                                                    <td>{{ $employee->license_expiry ?? 'N/A' }}</td>
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
                                                    <td>{{ $employee->personal_mobile ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-semibold">Home Phone</td>
                                                    <td>{{ $employee->home_phone ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-semibold">Personal Email</td>
                                                    <td>{{ $employee->personal_email ?? 'N/A' }}</td>
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
                                                    <td>{{ $employee->work_mobile ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-semibold">Work Phone</td>
                                                    <td>{{ $employee->work_phone ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-semibold">Work Email</td>
                                                    <td>{{ $employee->work_email ?? 'N/A' }}</td>
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
                                                    <p><strong>Address Line 1:</strong>
                                                        {{ $employee->present_address['line_1'] ?? 'N/A' }}</p>
                                                    <p><strong>Village:</strong>
                                                        {{ $employee->present_address['village'] ?? 'N/A' }}</p>
                                                    <p><strong>Post Office:</strong>
                                                        {{ $employee->present_address['post_office'] ?? 'N/A' }}</p>
                                                    <p><strong>State:</strong> {{ $employee->present_address['state'] ?? 'N/A' }}
                                                    </p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p><strong>District:</strong>
                                                        {{ $employee->present_address['district'] ?? 'N/A' }}</p>
                                                    <p><strong>Division:</strong>
                                                        {{ $employee->present_address['division'] ?? 'N/A' }}</p>
                                                    <p><strong>Zip Code:</strong>
                                                        {{ $employee->present_address['zip_code'] ?? 'N/A' }}</p>
                                                    <p><strong>Country:</strong>
                                                        {{ $employee->present_address['country'] ?? 'N/A' }}</p>
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
                                                    <p><strong>Address Line 1:</strong>
                                                        {{ $employee->permanent_address['line_1'] ?? 'N/A' }}</p>
                                                    <p><strong>Village:</strong>
                                                        {{ $employee->permanent_address['village'] ?? 'N/A' }}</p>
                                                    <p><strong>Post Office:</strong>
                                                        {{ $employee->permanent_address['post_office'] ?? 'N/A' }}</p>
                                                    <p>
                                                        <strong>State:</strong> {{ $employee->permanent_address['state'] ?? 'N/A' }}
                                                    </p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p><strong>District:</strong>
                                                        {{ $employee->permanent_address['district'] ?? 'N/A' }}</p>
                                                    <p><strong>Division:</strong>
                                                        {{ $employee->permanent_address['division'] ?? 'N/A' }}</p>
                                                    <p><strong>Zip Code:</strong>
                                                        {{ $employee->permanent_address['zip_code'] ?? 'N/A' }}</p>
                                                    <p><strong>Country:</strong>
                                                        {{ $employee->permanent_address['country'] ?? 'N/A' }}</p>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    @if (!empty($employee->reference_address))
                                        <!-- Reference Address -->
                                        <div class="card border">
                                            <div class="card-header bg-light">
                                                <h5 class="card-title mb-0">Reference Information</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <p><strong>Reference Employee ID:</strong>
                                                            {{ $employee->reference_address['emp_id'] ?? 'N/A' }}</p>
                                                        <p><strong>Reference Name:</strong>
                                                            {{ $employee->reference_address['reference_name'] ?? 'N/A' }}
                                                        </p>
                                                        <p><strong>Designation:</strong>
                                                            {{ $employee->reference_address['reference_designation'] ?? 'N/A' }}
                                                        </p>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <p><strong>Address:</strong>
                                                            {{ $employee->reference_address['line_1'] ?? 'N/A' }}</p>
                                                        <p><strong>Village:</strong>
                                                            {{ $employee->reference_address['village'] ?? 'N/A' }}</p>
                                                        <p>
                                                            <strong>State:</strong>
                                                            {{ $employee->reference_address['state'] ?? 'N/A' }}
                                                        </p>
                                                        <p><strong>District:</strong>
                                                            {{ $employee->reference_address['district'] ?? 'N/A' }}</p>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <p><strong>Zip Code:</strong>
                                                            {{ $employee->reference_address['zip_code'] ?? 'N/A' }}</p>
                                                        <p>
                                                            <strong>Phone:</strong>
                                                            {{ $employee->reference_address['phone'] ?? 'N/A' }}
                                                        </p>
                                                        <p>
                                                            <strong>Email:</strong>
                                                            {{ $employee->reference_address['email'] ?? 'N/A' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
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
                                            @if (isset($employee->photo_path))
                                                @if (file_exists(public_path('storage/' . $employee->photo_path)))
                                                    <a href="#" class="btn btn-sm btn-primary view-link"
                                                        data-img="{{ asset('storage/' . $employee->photo_path) }}">View</a>
                                                    <a href="{{ asset('storage/' . $employee->photo_path) }}"
                                                        class="btn btn-sm btn-outline-primary" download>Download</a>
                                                @else
                                                    <p class="text-muted">Photo deleted or moved</p>
                                                    <!-- Show this message if the file doesn't exist -->
                                                @endif
                                            @else
                                                <p class="text-muted">No photo available</p>
                                                <!-- Show this if the photo path is not set -->
                                            @endif
                                        </div>
                                    </div>

                                </div>

                                <div class="col-md-3 col-sm-6 mb-3">
                                    <div class="card border">
                                        <div class="card-body text-center">
                                            <i class="mdi mdi-fingerprint fs-48 text-success mb-2"></i>
                                            <h6 class="fw-semibold">Fingerprint</h6>
                                            @if (isset($employee->fingerprint_path))
                                                @if (file_exists(public_path('storage/' . $employee->fingerprint_path)))
                                                    <a href="#" class="btn btn-sm btn-success view-link"
                                                        data-img="{{ asset('storage/' . $employee->fingerprint_path) }}">View</a>
                                                    <a href="{{ asset('storage/' . $employee->fingerprint_path) }}"
                                                        class="btn btn-sm btn-outline-success" download>Download</a>
                                                @else
                                                    <p class="text-muted">Fingerprint may Deleted or moved</p>
                                                    <!-- Show this message if the file doesn't exist -->
                                                @endif
                                            @else
                                                <p class="text-muted">No Fingerprint available</p>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <div class="card border">
                                        <div class="card-body text-center">
                                            <i class="mdi mdi-draw fs-48 text-warning mb-2"></i>
                                            <h6 class="fw-semibold">Signature</h6>
                                            @if (isset($employee->signature_path))
                                                @if (file_exists(public_path('storage/' . $employee->signature_path)))
                                                    <a href="#" class="btn btn-sm btn-warning view-link"
                                                        data-img="{{ asset('storage/' . $employee->signature_path) }}">View</a>
                                                    <a href="{{ asset('storage/' . $employee->signature_path) }}"
                                                        class="btn btn-sm btn-outline-warning" download>Download</a>
                                                @else
                                                    <p class="text-muted">Signature file deleted or moved</p>
                                                    <!-- Show this message if file doesn't exist -->
                                                @endif
                                            @else
                                                <p class="text-muted">No signature available</p>
                                                <!-- Show this message if no signature path is set -->
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 col-sm-6 mb-3">
                                    <div class="card border">
                                        <div class="card-body text-center">
                                            <i class="mdi mdi-file-document fs-48 text-info mb-2"></i>
                                            <h6 class="fw-semibold">Experience Documents</h6>
                                            @if (isset($employee->experience_attachment_path))
                                                @if (file_exists(public_path('storage/' . $employee->experience_attachment_path)))
                                                    <a href="{{ asset('storage/' . $employee->experience_attachment_path) }}"
                                                        class="btn btn-sm btn-outline-info" download>Download</a>
                                                @else
                                                    <p class="text-muted">Experience document deleted or moved</p>
                                                @endif
                                            @else
                                                <p class="text-muted">No experience document available</p>
                                                <!-- Show this message if no experience document path is set -->
                                            @endif
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
                        @can('employee-management.edit')
                        <a href="{{ route('employee.general_informations.edit', $employee->id) }}"
                            class="btn btn-primary">
                            <i class="mdi mdi-pencil me-1"></i> Edit Profile
                        </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@include('employee.partials.modal.image')

