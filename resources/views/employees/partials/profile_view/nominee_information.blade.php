<div class="row">
    <div class="col-12">
        @if (!empty($employee_nominee_info))
            <div class="card">
                <div class="card-body pt-0">
                    <ul class="nav nav-underline border-bottom pt-2" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active p-2" id="basic_info_tab" data-bs-toggle="tab" href="#basic_info"
                               role="tab">
                                <span class="d-none d-sm-block">Basic Information</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link p-2" id="identification_tab" data-bs-toggle="tab" href="#identification"
                               role="tab">
                                <span class="d-none d-sm-block">Identification</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link p-2" id="contact_tab" data-bs-toggle="tab" href="#contact"
                               role="tab">
                                <span class="d-none d-sm-block">Contact Information</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link p-2" id="address_tab" data-bs-toggle="tab" href="#address"
                               role="tab">
                                <span class="d-none d-sm-block">Address Information</span>
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content text-muted">
                        <!-- Basic Information Tab -->
                        <div class="tab-pane active show pt-4" id="basic_info" role="tabpanel">
                            <div class="row">
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <i class="mdi mdi-account-heart me-2"></i>
                                        <strong>Employee:</strong> {{ $employee->full_name }}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="fs-16 text-dark fw-semibold mb-3">Personal Details</h5>
                                    <div class="table-responsive">
                                        <table class="table table-borderless mb-0">
                                            <tbody>
                                            <tr>
                                                <td class="fw-semibold" style="width: 40%;">Nominee Name</td>
                                                <td>{{ $employee_nominee_info->nominee_name }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold">Gender</td>
                                                <td><span
                                                        class="badge bg-info">{{ $employee_nominee_info->gender }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold">Date of Birth</td>
                                                <td>{{ $employee_nominee_info->date_of_birth }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold">Blood Group</td>
                                                <td><span
                                                        class="badge bg-danger">{{ $employee_nominee_info->blood_group }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold">Religion</td>
                                                <td>{{ $employee_nominee_info->religion }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold">Marital Status</td>
                                                <td>{{ $employee_nominee_info->marital_status }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold">Nationality</td>
                                                <td>{{ $employee_nominee_info->nationality }}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <h5 class="fs-16 text-dark fw-semibold mb-3">Family Details</h5>
                                    <div class="table-responsive">
                                        <table class="table table-borderless mb-0">
                                            <tbody>
                                            <tr>
                                                <td class="fw-semibold" style="width: 40%;">Father's Name</td>
                                                <td>{{ $employee_nominee_info->father_name }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold">Mother's Name</td>
                                                <td>{{ $employee_nominee_info->mother_name }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold">Spouse Name</td>
                                                <td>{{ $employee_nominee_info->spouse_name }}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <h5 class="fs-16 text-dark fw-semibold mb-3 mt-4">Photo</h5>
                                    <div class="card bg-light border-0">
                                        <div class="card-body text-center">
                                            <img src="{{ asset('storage/'.$employee_nominee_info->photo_path) }}" alt="Nominee Photo"
                                                 class="img-thumbnail" style="max-width: 200px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Identification Tab -->
                        <div class="tab-pane pt-4" id="identification" role="tabpanel">
                            <div class="row">
                                <div class="col-12">
                                    {{-- <div class="alert alert-success">
                                        <i class="mdi mdi-shield-check me-2"></i>
                                        <strong>Verification Status:</strong> Documents Verified
                                    </div> --}}
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="fs-16 text-dark fw-semibold mb-3">National Identity</h5>
                                    <div class="card border">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <div>
                                                    <i class="mdi mdi-card-account-details text-primary fs-20 me-2"></i>
                                                    <span class="fw-semibold">National ID (NID)</span>
                                                </div>
                                                {{-- <span class="badge bg-primary">Verified</span> --}}
                                            </div>
                                            <p class="mb-0 fs-15 text-dark">{{ $employee_nominee_info->nid }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <h5 class="fs-16 text-dark fw-semibold mb-3">Birth Registration</h5>
                                    <div class="card border">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <div>
                                                    <i class="mdi mdi-certificate text-success fs-20 me-2"></i>
                                                    <span class="fw-semibold">Birth Registration No.</span>
                                                </div>
                                                {{-- <span class="badge bg-success">Verified</span> --}}
                                            </div>
                                            <p class="mb-0 fs-15 text-dark">{{ $employee_nominee_info->birth_reg_no }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information Tab -->
                        <div class="tab-pane pt-4" id="contact" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="fs-16 text-dark fw-semibold mb-3">Contact Details</h5>
                                    <div class="card border">
                                        <div class="card-body">
                                            <div class="mb-3 pb-3 border-bottom">
                                                <div class="d-flex align-items-center">
                                                    <i class="mdi mdi-phone text-primary fs-20 me-3"></i>
                                                    <div>
                                                        <small class="text-muted d-block">Phone</small>
                                                        <span
                                                            class="fw-semibold">{{ $employee_nominee_info->phone }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="d-flex align-items-center">
                                                    <i class="mdi mdi-cellphone text-success fs-20 me-3"></i>
                                                    <div>
                                                        <small class="text-muted d-block">Mobile</small>
                                                        <span
                                                            class="fw-semibold">{{ $employee_nominee_info->mobile }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <h5 class="fs-16 text-dark fw-semibold mb-3">Emergency Contact</h5>
                                    {{-- <div class="alert alert-warning">
                                    <i class="mdi mdi-alert-circle-outline me-2"></i>
                                    Primary contact number: <strong>+880-1712-345678</strong>
                                </div> --}}
                                    <div class="card bg-light border-0">
                                        <div class="card-body">
                                            <p class="mb-2"><i class="mdi mdi-clock-outline me-2"></i>Available:
                                                24/7</p>
                                            <p class="mb-0"><i
                                                    class="mdi mdi-information-outline me-2"></i>Preferred
                                                contact method: Mobile</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Address Information Tab -->
                        <div class="tab-pane pt-4" id="address" role="tabpanel">
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="fs-16 text-dark fw-semibold mb-3">Present Address</h5>
                                    <div class="card bg-light border-0 mb-4">
                                        <div class="card-body">
                                            <p class="mb-0">
                                                <i class="mdi mdi-map-marker text-danger me-2"></i>
                                                {{ $employee_nominee_info->present_address_line }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="fs-16 text-dark fw-semibold mb-3">Location Details</h5>
                                    <div class="table-responsive">
                                        <table class="table table-borderless mb-0">
                                            <tbody>
                                            <tr>
                                                <td class="fw-semibold" style="width: 40%;">Village</td>
                                                <td>{{ $employee_nominee_info->village }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold">Post Office</td>
                                                <td>{{ $employee_nominee_info->post_office }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold">Thana/Upazila</td>
                                                <td>{{ $employee_nominee_info->thana }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold">District</td>
                                                <td>{{ $employee_nominee_info->district }}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <h5 class="fs-16 text-dark fw-semibold mb-3">Regional Details</h5>
                                    <div class="table-responsive">
                                        <table class="table table-borderless mb-0">
                                            <tbody>
                                            <tr>
                                                <td class="fw-semibold" style="width: 40%;">State/Division</td>
                                                <td>{{ $employee_nominee_info->state }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold">Zip/Postal Code</td>
                                                <td>{{ $employee_nominee_info->zip_code }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold">Country</td>
                                                <td><span
                                                        class="badge bg-success">{{ $employee_nominee_info->country }}</span>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <h5 class="fs-16 text-dark fw-semibold mb-3 mt-4">Address Type</h5>
                                    <div class="card border">
                                        <div class="card-body">
                                            {{-- <span class="badge bg-primary me-2">Permanent Address</span> --}}
                                            <span class="badge bg-info">Present Address</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{--<div class="row mt-4">
                                <div class="col-12">
                                    <h5 class="fs-16 text-dark fw-semibold mb-3">Map View</h5>
                                    <div class="card border-0">
                                        <div class="card-body bg-light text-center" style="height: 250px;">
                                            <i class="mdi mdi-map-marker-radius text-muted"
                                               style="font-size: 64px;"></i>
                                            <p class="text-muted mt-2">Map integration placeholder</p>
                                        </div>
                                    </div>
                                </div>
                            </div>--}}
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-6 col-md-8 col-sm-10">
                        <div class="card shadow-sm border-0 mt-5 mb-5">
                            <div class="card-body text-center p-5">

                                <!-- Empty State Circle -->
                                <div class="d-flex justify-content-center mb-4">
                                    <div class="rounded-circle bg-light border border-2 border-secondary d-flex align-items-center justify-content-center"
                                         style="width: 120px; height: 120px;">
                                        <span class="display-1 text-secondary fw-light">?</span>
                                    </div>
                                </div>

                                <!-- Heading -->
                                <h3 class="fw-bold text-dark mb-3">Employee Information Not Found</h3>

                                <!-- Divider -->
                                <hr class="w-50 mx-auto opacity-25 mb-4">

                                <!-- Message -->
                                <p class="text-muted mb-4 fs-6 lh-base px-lg-5">
                                    No employee records are currently available in the system.
                                    Please add employee information to get started.
                                </p>

                                <!-- Action Button -->
                                @can('employee-management.create')
                                <a href="{{ route('employees.nominee_information.create', $employee->id) }}"
                                    class="btn btn-primary btn-lg px-5 rounded-pill">
                                    Add Information
                                </a>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@if(!empty($employee_nominee_info))
    <!-- Action Buttons -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-end gap-2">
                        @can('employee-management.edit')
                            <a href="{{ route('employees.nominee_information.edit', $employee->id) }}"
                               class="btn btn-primary">
                                <i class="mdi mdi-pencil me-1"></i> Edit Nominee Information
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
