<div class="row">
    <div class="col-12">
        @if(!empty($employee_nominee_info))
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
                        <a class="nav-link p-2" id="financial_tab" data-bs-toggle="tab" href="#financial" role="tab">
                            <span class="d-none d-sm-block">Financial Information</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link p-2" id="contact_tab" data-bs-toggle="tab" href="#contact" role="tab">
                            <span class="d-none d-sm-block">Contact Information</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link p-2" id="address_tab" data-bs-toggle="tab" href="#address" role="tab">
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
                                    <strong>Employee:</strong> John Doe (EMP-2024-001) | <strong>Nominee Ratio:</strong>
                                    100%
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
                                            <td>Sarah Doe</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Gender</td>
                                            <td><span class="badge bg-info">Female</span></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Date of Birth</td>
                                            <td>March 15, 1985</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Blood Group</td>
                                            <td><span class="badge bg-danger">B+</span></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Religion</td>
                                            <td>Islam</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Marital Status</td>
                                            <td>Married</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Nationality</td>
                                            <td>Bangladeshi</td>
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
                                            <td>Abdul Rahman</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Mother's Name</td>
                                            <td>Fatima Rahman</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Spouse Name</td>
                                            <td>Michael Johnson</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <h5 class="fs-16 text-dark fw-semibold mb-3 mt-4">Photo</h5>
                                <div class="card bg-light border-0">
                                    <div class="card-body text-center">
                                        <img src="/path/to/nominee-photo.jpg" alt="Nominee Photo" class="img-thumbnail"
                                             style="max-width: 200px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Identification Tab -->
                    <div class="tab-pane pt-4" id="identification" role="tabpanel">
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-success">
                                    <i class="mdi mdi-shield-check me-2"></i>
                                    <strong>Verification Status:</strong> Documents Verified
                                </div>
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
                                            <span class="badge bg-primary">Verified</span>
                                        </div>
                                        <p class="mb-0 fs-15 text-dark">1987654321098</p>
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
                                            <span class="badge bg-success">Verified</span>
                                        </div>
                                        <p class="mb-0 fs-15 text-dark">19850315123456789</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Financial Information Tab -->
                    <div class="tab-pane pt-4" id="financial" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="fs-16 text-dark fw-semibold mb-3">Banking Details</h5>
                                <div class="table-responsive">
                                    <table class="table table-borderless mb-0">
                                        <tbody>
                                        <tr>
                                            <td class="fw-semibold" style="width: 40%;">Bank Account Number</td>
                                            <td>1234567890123</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <h5 class="fs-16 text-dark fw-semibold mb-3 mt-4">Nominee Allocation</h5>
                                <div class="card bg-success bg-opacity-10 border-success">
                                    <div class="card-body">
                                        <div class="text-center">
                                            <h2 class="text-success mb-0">100.00%</h2>
                                            <p class="mb-0 text-muted">Allocated Ratio</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h5 class="fs-16 text-dark fw-semibold mb-3">Financial Summary</h5>
                                <div class="card border">
                                    <div class="card-body">
                                        <div class="mb-3 pb-3 border-bottom">
                                            <i class="mdi mdi-bank text-primary fs-20 me-2"></i>
                                            <span class="fw-semibold">Bank:</span> Dutch Bangla Bank Limited
                                        </div>
                                        <div class="mb-3 pb-3 border-bottom">
                                            <i class="mdi mdi-office-building text-info fs-20 me-2"></i>
                                            <span class="fw-semibold">Branch:</span> Gulshan Branch, Dhaka
                                        </div>
                                        <div>
                                            <i class="mdi mdi-calendar-check text-success fs-20 me-2"></i>
                                            <span class="fw-semibold">Account Type:</span> Savings Account
                                        </div>
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
                                                    <span class="fw-semibold">+880-2-9876543</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="d-flex align-items-center">
                                                <i class="mdi mdi-cellphone text-success fs-20 me-3"></i>
                                                <div>
                                                    <small class="text-muted d-block">Mobile</small>
                                                    <span class="fw-semibold">+880-1712-345678</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h5 class="fs-16 text-dark fw-semibold mb-3">Emergency Contact</h5>
                                <div class="alert alert-warning">
                                    <i class="mdi mdi-alert-circle-outline me-2"></i>
                                    Primary contact number: <strong>+880-1712-345678</strong>
                                </div>
                                <div class="card bg-light border-0">
                                    <div class="card-body">
                                        <p class="mb-2"><i class="mdi mdi-clock-outline me-2"></i>Available: 24/7</p>
                                        <p class="mb-0"><i class="mdi mdi-information-outline me-2"></i>Preferred
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
                                            House #45, Road #12, Block C, Banani, Dhaka-1213, Bangladesh
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
                                            <td>Banani</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Post Office</td>
                                            <td>Banani Post Office</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Thana/Upazila</td>
                                            <td>Gulshan</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">District</td>
                                            <td>Dhaka</td>
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
                                            <td>Dhaka Division</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Zip/Postal Code</td>
                                            <td>1213</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Country</td>
                                            <td><span class="badge bg-success">Bangladesh</span></td>
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

                        <div class="row mt-4">
                            <div class="col-12">
                                <h5 class="fs-16 text-dark fw-semibold mb-3">Map View</h5>
                                <div class="card border-0">
                                    <div class="card-body bg-light text-center" style="height: 250px;">
                                        <i class="mdi mdi-map-marker-radius text-muted" style="font-size: 64px;"></i>
                                        <p class="text-muted mt-2">Map integration placeholder</p>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                                    <div class="rounded-circle bg-light border border-2 border-secondary d-flex align-items-center justify-content-center" style="width: 120px; height: 120px;">
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
                                <a href="{{route('employees.nominee_information.create', $employee->id)}}" class="btn btn-primary btn-lg px-5 rounded-pill">
                                    Add Information
                                </a>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
