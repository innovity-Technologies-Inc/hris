@extends('structure.master')
@section('content')
    <!-- Start Content-->
    <div class="container-fluid">
        <!-- Page Title -->
        <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
            <div class="flex-grow-1">
                <h4 class="fs-18 fw-semibold m-0">Employee Profile</h4>
            </div>
            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                    <li class="breadcrumb-item active">Profile</li>
                </ol>
            </div>
        </div>

        <!-- Profile Header -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <img src="assets/images/small/user-image.jpg" class="rounded-top-2 img-fluid" alt="cover image">
                    <div class="card-body">
                        <div class="align-items-center">
                            <div class="hando-main-sections">
                                <div class="hando-profile-main">
                                    <img src="assets/images/users/user-11.jpg" class="rounded-circle img-fluid avatar-xxl img-thumbnail float-start" alt="profile photo">
                                    <span class="sil-profile_main-pic-change img-thumbnail">
                                        <i class="mdi mdi-camera text-white"></i>
                                    </span>
                                </div>
                                <div class="overflow-hidden ms-md-4 ms-0">
                                    <h4 class="m-0 text-dark fs-20 mt-2 mt-md-0">Mohammad Rahman Khan</h4>
                                    <p class="my-1 text-muted fs-16">Senior Software Engineer - Employee ID: EMP-2024-001</p>
                                    <span class="fs-15">
                                        <i class="mdi mdi-phone me-2 align-middle"></i>
                                        <span>+880 1712-345678</span>
                                        <i class="mdi mdi-email ms-3 me-2 align-middle"></i>
                                        <span>mohammad.khan@company.com</span>
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
                                <p class="text-muted">APP-2024-12345</p>
                            </div>
                            <div class="col-md-4">
                                <p class="mb-2"><strong>System ID:</strong></p>
                                <p class="text-muted">SYS-EMP-001-2024</p>
                            </div>
                            <div class="col-md-4">
                                <p class="mb-2"><strong>Punch Card No:</strong></p>
                                <p class="text-muted">PC-54321</p>
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
                                <a class="nav-link active p-2" id="personal_info_tab" data-bs-toggle="tab" href="#personal_info" role="tab">
                                    <span class="d-none d-sm-block">Personal Information</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link p-2" id="birth_doc_tab" data-bs-toggle="tab" href="#birth_doc" role="tab">
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
                                                        <td>Mohammad Rahman</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold">Middle Name</td>
                                                        <td>Abdul</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold">Last Name</td>
                                                        <td>Khan</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold">Gender</td>
                                                        <td>Male</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold">Marital Status</td>
                                                        <td>Married</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold">Religion</td>
                                                        <td>Islam</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold">Nationality</td>
                                                        <td>Bangladeshi</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold">Height</td>
                                                        <td>5 ft 8 in</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold">Number of Children</td>
                                                        <td>2</td>
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
                                                        <td>Abdul Karim Khan</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold">Mother's Name</td>
                                                        <td>Fatima Begum</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold">Spouse's Name</td>
                                                        <td>Ayesha Rahman</td>
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
                                                        <td>January 15, 1990</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold">Birth Country</td>
                                                        <td>Bangladesh</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold">Birth Registration No</td>
                                                        <td>19900115123456789</td>
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
                                                        <td>123-456-789-012</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold">BGMEA ID</td>
                                                        <td>BGMEA-2024-5678</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold">Residency ID</td>
                                                        <td>RID-BD-2024-9876</td>
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
                                                        <td>BD1234567</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold">Passport Expiry</td>
                                                        <td>December 31, 2028</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold">Visa Expiry</td>
                                                        <td>June 30, 2026</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold">Work Permit Expiry</td>
                                                        <td>December 31, 2025</td>
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
                                                        <td>DL-DHK-2019-123456</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold">License Expiry</td>
                                                        <td>March 15, 2029</td>
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
                                                        <td>+880 1712-345678</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold">Home Phone</td>
                                                        <td>+880 2-9876543</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold">Personal Email</td>
                                                        <td>mohammad.khan.personal@gmail.com</td>
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
                                                        <td>+880 1798-765432</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold">Work Phone</td>
                                                        <td>+880 2-5551234 (Ext: 205)</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold">Work Email</td>
                                                        <td>mohammad.khan@company.com</td>
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
                                                        <p><strong>Address Line 1:</strong> House 45, Road 12, Block C</p>
                                                        <p><strong>Village:</strong> Banani</p>
                                                        <p><strong>Post Office:</strong> Banani Post Office</p>
                                                        <p><strong>Thana:</strong> Gulshan</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p><strong>District:</strong> Dhaka</p>
                                                        <p><strong>Division:</strong> Dhaka</p>
                                                        <p><strong>Zip Code:</strong> 1213</p>
                                                        <p><strong>Country:</strong> Bangladesh</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p><strong>Phone:</strong> +880 2-9876543</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p><strong>Mobile:</strong> +880 1712-345678</p>
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
                                                        <p><strong>Address Line 1:</strong> Village Rampur, Ward No. 3</p>
                                                        <p><strong>Village:</strong> Rampur</p>
                                                        <p><strong>Post Office:</strong> Rampur Bazar</p>
                                                        <p><strong>Thana:</strong> Savar</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p><strong>District:</strong> Dhaka</p>
                                                        <p><strong>Division:</strong> Dhaka</p>
                                                        <p><strong>Zip Code:</strong> 1340</p>
                                                        <p><strong>Country:</strong> Bangladesh</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p><strong>Phone:</strong> +880 2-7789456</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p><strong>Mobile:</strong> +880 1823-456789</p>
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
                                                        <p><strong>Reference Employee ID:</strong> EMP-2020-045</p>
                                                        <p><strong>Reference Name:</strong> Dr. Kamal Ahmed</p>
                                                        <p><strong>Designation:</strong> Senior Manager, HR</p>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <p><strong>Address:</strong> House 78, Road 5, Dhanmondi</p>
                                                        <p><strong>Village:</strong> Dhanmondi</p>
                                                        <p><strong>Thana:</strong> Dhanmondi</p>
                                                        <p><strong>District:</strong> Dhaka</p>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <p><strong>City:</strong> Dhaka</p>
                                                        <p><strong>Zip Code:</strong> 1209</p>
                                                        <p><strong>Phone:</strong> +880 1755-123456</p>
                                                        <p><strong>Email:</strong> kamal.ahmed@company.com</p>
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
                                                <a href="#" class="btn btn-sm btn-primary">View</a>
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

    </div> <!-- container-fluid -->
@endsection

@push('scripts')
<script>
    // Feather Icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
</script>
@endpush
