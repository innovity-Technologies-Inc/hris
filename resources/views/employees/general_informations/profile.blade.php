@extends('structure.master')
@section('content')
    <style>
        .profile-header-wrapper {
            position: relative;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 200px;
            border-radius: 12px;
        }

        .profile-avatar-section {
            position: relative;
            margin-top: -80px;
            padding: 0 30px;
        }

        .profile-avatar {
            width: 140px;
            height: 140px;
            border: 5px solid #fff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .info-card {
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .info-card:hover {
            box-shadow: 0 4px 16px rgba(0,0,0,0.12);
            transform: translateY(-2px);
        }

        .info-label {
            color: #64748b;
            font-size: 13px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .info-value {
            color: #1e293b;
            font-size: 15px;
            font-weight: 400;
        }

        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e2e8f0;
        }

        .tab-link-custom {
            color: #64748b;
            font-weight: 500;
            border: none;
            padding: 12px 20px;
            transition: all 0.3s ease;
        }

        .tab-link-custom.active {
            color: #667eea;
            background: transparent;
            border-bottom: 2px solid #667eea;
        }

        .tab-link-custom:hover {
            color: #667eea;
        }

        .address-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 24px;
            margin-bottom: 20px;
        }

        .address-card h6 {
            font-size: 15px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 16px;
        }

        .file-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 24px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .file-card:hover {
            border-color: #667eea;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
        }

        .file-icon {
            font-size: 48px;
            color: #667eea;
            margin-bottom: 12px;
        }

        .badge-id {
            background: #f1f5f9;
            color: #475569;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 500;
            font-size: 14px;
        }

        .btn-action {
            padding: 10px 24px;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .info-row {
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .info-row:last-child {
            border-bottom: none;
        }
    </style>

    <!-- Start Content-->
    <div class="container-fluid">
        <!-- Page Title -->
        <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
            <div class="flex-grow-1">
                <h4 class="fs-18 fw-semibold m-0 text-dark">Employee Profile</h4>
            </div>
            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active">Profile</li>
                </ol>
            </div>
        </div>

        <!-- Profile Header Card -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card info-card">
                    <div class="card-body p-0">
                        <div class="profile-header-wrapper"></div>
                        <div class="profile-avatar-section">
                            <div class="d-flex align-items-end flex-wrap">
                                <div class="position-relative">
                                    <img src="assets/images/users/user-11.jpg"
                                         class="rounded-circle profile-avatar"
                                         alt="profile photo">
                                </div>
                                <div class="ms-4 mb-3 flex-grow-1">
                                    <h3 class="mb-1 text-dark fw-semibold">Mohammad Rahman Khan</h3>
                                    <p class="text-muted mb-2">Senior Software Engineer</p>
                                    <div class="d-flex flex-wrap gap-2 align-items-center">
                                        <span class="badge-id">
                                            <i class="mdi mdi-account-card-details me-1"></i>
                                            EMP-2024-001
                                        </span>
                                        <span class="text-muted">
                                            <i class="mdi mdi-phone me-1"></i>
                                            +880 1712-345678
                                        </span>
                                        <span class="text-muted">
                                            <i class="mdi mdi-email me-1"></i>
                                            mohammad.khan@company.com
                                        </span>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <button class="btn btn-outline-secondary btn-action me-2">
                                        <i class="mdi mdi-printer me-1"></i> Print
                                    </button>
                                    <button class="btn btn-primary btn-action">
                                        <i class="mdi mdi-pencil me-1"></i> Edit Profile
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Identifiers -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card info-card">
                    <div class="card-body">
                        <h5 class="section-title">System Identifiers</h5>
                        <div class="row g-4">
                            <div class="col-md-4">
                                <div class="info-label">Applicant ID</div>
                                <div class="info-value">APP-2024-12345</div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-label">System ID</div>
                                <div class="info-value">SYS-EMP-001-2024</div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-label">Punch Card No</div>
                                <div class="info-value">PC-54321</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabbed Content -->
        <div class="row">
            <div class="col-12">
                <div class="card info-card">
                    <div class="card-body">
                        <ul class="nav nav-tabs border-0 mb-4" id="profileTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link tab-link-custom active" id="personal-tab"
                                        data-bs-toggle="tab" data-bs-target="#personal" type="button">
                                    Personal Information
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link tab-link-custom" id="documents-tab"
                                        data-bs-toggle="tab" data-bs-target="#documents" type="button">
                                    Birth & Documents
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link tab-link-custom" id="contact-tab"
                                        data-bs-toggle="tab" data-bs-target="#contact" type="button">
                                    Contact Information
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link tab-link-custom" id="address-tab"
                                        data-bs-toggle="tab" data-bs-target="#address" type="button">
                                    Address Details
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link tab-link-custom" id="files-tab"
                                        data-bs-toggle="tab" data-bs-target="#files" type="button">
                                    Files & Attachments
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <!-- Personal Information Tab -->
                            <div class="tab-pane fade show active" id="personal" role="tabpanel">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <h5 class="section-title">Basic Information</h5>
                                        <div class="info-row">
                                            <div class="info-label">First Name</div>
                                            <div class="info-value">Mohammad Rahman</div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-label">Middle Name</div>
                                            <div class="info-value">Abdul</div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-label">Last Name</div>
                                            <div class="info-value">Khan</div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-label">Gender</div>
                                            <div class="info-value">Male</div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-label">Marital Status</div>
                                            <div class="info-value">Married</div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-label">Religion</div>
                                            <div class="info-value">Islam</div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-label">Nationality</div>
                                            <div class="info-value">Bangladeshi</div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-label">Height</div>
                                            <div class="info-value">5 ft 8 in</div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-label">Number of Children</div>
                                            <div class="info-value">2</div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <h5 class="section-title">Family Information</h5>
                                        <div class="info-row">
                                            <div class="info-label">Father's Name</div>
                                            <div class="info-value">Abdul Karim Khan</div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-label">Mother's Name</div>
                                            <div class="info-value">Fatima Begum</div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-label">Spouse's Name</div>
                                            <div class="info-value">Ayesha Rahman</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Birth & Documents Tab -->
                            <div class="tab-pane fade" id="documents" role="tabpanel">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <h5 class="section-title">Birth Information</h5>
                                        <div class="info-row">
                                            <div class="info-label">Date of Birth</div>
                                            <div class="info-value">January 15, 1990</div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-label">Birth Country</div>
                                            <div class="info-value">Bangladesh</div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-label">Birth Registration No</div>
                                            <div class="info-value">19900115123456789</div>
                                        </div>

                                        <h5 class="section-title mt-4">Tax & Identity</h5>
                                        <div class="info-row">
                                            <div class="info-label">TIN Number</div>
                                            <div class="info-value">123-456-789-012</div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-label">BGMEA ID</div>
                                            <div class="info-value">BGMEA-2024-5678</div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-label">Residency ID Number</div>
                                            <div class="info-value">RID-BD-2024-9876</div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <h5 class="section-title">Travel Documents</h5>
                                        <div class="info-row">
                                            <div class="info-label">Passport Number</div>
                                            <div class="info-value">BD1234567</div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-label">Passport Expiry Date</div>
                                            <div class="info-value">December 31, 2028</div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-label">Visa Expiry Date</div>
                                            <div class="info-value">June 30, 2026</div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-label">Work Permit Expiry Date</div>
                                            <div class="info-value">December 31, 2025</div>
                                        </div>

                                        <h5 class="section-title mt-4">License Information</h5>
                                        <div class="info-row">
                                            <div class="info-label">License Number</div>
                                            <div class="info-value">DL-DHK-2019-123456</div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-label">License Expiry Date</div>
                                            <div class="info-value">March 15, 2029</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Information Tab -->
                            <div class="tab-pane fade" id="contact" role="tabpanel">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <h5 class="section-title">Personal Contact</h5>
                                        <div class="info-row">
                                            <div class="info-label">Personal Mobile</div>
                                            <div class="info-value">+880 1712-345678</div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-label">Home Phone</div>
                                            <div class="info-value">+880 2-9876543</div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-label">Personal Email</div>
                                            <div class="info-value">mohammad.khan.personal@gmail.com</div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <h5 class="section-title">Work Contact</h5>
                                        <div class="info-row">
                                            <div class="info-label">Work Mobile</div>
                                            <div class="info-value">+880 1798-765432</div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-label">Work Phone</div>
                                            <div class="info-value">+880 2-5551234 (Ext: 205)</div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-label">Work Email</div>
                                            <div class="info-value">mohammad.khan@company.com</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Address Details Tab -->
                            <div class="tab-pane fade" id="address" role="tabpanel">
                                <!-- Present Address -->
                                <div class="address-card">
                                    <h6><i class="mdi mdi-home-map-marker me-2"></i>Present Address</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="info-row">
                                                <div class="info-label">Address Line 1</div>
                                                <div class="info-value">House 45, Road 12, Block C</div>
                                            </div>
                                            <div class="info-row">
                                                <div class="info-label">Village</div>
                                                <div class="info-value">Banani</div>
                                            </div>
                                            <div class="info-row">
                                                <div class="info-label">Post Office</div>
                                                <div class="info-value">Banani Post Office</div>
                                            </div>
                                            <div class="info-row">
                                                <div class="info-label">Thana</div>
                                                <div class="info-value">Gulshan</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-row">
                                                <div class="info-label">District</div>
                                                <div class="info-value">Dhaka</div>
                                            </div>
                                            <div class="info-row">
                                                <div class="info-label">Division</div>
                                                <div class="info-value">Dhaka</div>
                                            </div>
                                            <div class="info-row">
                                                <div class="info-label">Zip Code</div>
                                                <div class="info-value">1213</div>
                                            </div>
                                            <div class="info-row">
                                                <div class="info-label">Country</div>
                                                <div class="info-value">Bangladesh</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-row">
                                                <div class="info-label">Phone</div>
                                                <div class="info-value">+880 2-9876543</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-row">
                                                <div class="info-label">Mobile</div>
                                                <div class="info-value">+880 1712-345678</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Permanent Address -->
                                <div class="address-card">
                                    <h6><i class="mdi mdi-home me-2"></i>Permanent Address</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="info-row">
                                                <div class="info-label">Address Line 1</div>
                                                <div class="info-value">Village Rampur, Ward No. 3</div>
                                            </div>
                                            <div class="info-row">
                                                <div class="info-label">Village</div>
                                                <div class="info-value">Rampur</div>
                                            </div>
                                            <div class="info-row">
                                                <div class="info-label">Post Office</div>
                                                <div class="info-value">Rampur Bazar</div>
                                            </div>
                                            <div class="info-row">
                                                <div class="info-label">Thana</div>
                                                <div class="info-value">Savar</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-row">
                                                <div class="info-label">District</div>
                                                <div class="info-value">Dhaka</div>
                                            </div>
                                            <div class="info-row">
                                                <div class="info-label">Division</div>
                                                <div class="info-value">Dhaka</div>
                                            </div>
                                            <div class="info-row">
                                                <div class="info-label">Zip Code</div>
                                                <div class="info-value">1340</div>
                                            </div>
                                            <div class="info-row">
                                                <div class="info-label">Country</div>
                                                <div class="info-value">Bangladesh</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-row">
                                                <div class="info-label">Phone</div>
                                                <div class="info-value">+880 2-7789456</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-row">
                                                <div class="info-label">Mobile</div>
                                                <div class="info-value">+880 1823-456789</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Reference Information -->
                                <div class="address-card">
                                    <h6><i class="mdi mdi-account-supervisor me-2"></i>Reference Information</h6>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="info-row">
                                                <div class="info-label">Reference Employee ID</div>
                                                <div class="info-value">EMP-2020-045</div>
                                            </div>
                                            <div class="info-row">
                                                <div class="info-label">Reference Name</div>
                                                <div class="info-value">Dr. Kamal Ahmed</div>
                                            </div>
                                            <div class="info-row">
                                                <div class="info-label">Designation</div>
                                                <div class="info-value">Senior Manager, HR</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="info-row">
                                                <div class="info-label">Address</div>
                                                <div class="info-value">House 78, Road 5, Dhanmondi</div>
                                            </div>
                                            <div class="info-row">
                                                <div class="info-label">Thana</div>
                                                <div class="info-value">Dhanmondi</div>
                                            </div>
                                            <div class="info-row">
                                                <div class="info-label">District</div>
                                                <div class="info-value">Dhaka</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="info-row">
                                                <div class="info-label">City</div>
                                                <div class="info-value">Dhaka</div>
                                            </div>
                                            <div class="info-row">
                                                <div class="info-label">Zip Code</div>
                                                <div class="info-value">1209</div>
                                            </div>
                                            <div class="info-row">
                                                <div class="info-label">Phone</div>
                                                <div class="info-value">+880 1755-123456</div>
                                            </div>
                                            <div class="info-row">
                                                <div class="info-label">Email</div>
                                                <div class="info-value">kamal.ahmed@company.com</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Files & Attachments Tab -->
                            <div class="tab-pane fade" id="files" role="tabpanel">
                                <h5 class="section-title">Uploaded Documents</h5>
                                <div class="row g-3">
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <div class="file-card">
                                            <i class="mdi mdi-account-box file-icon"></i>
                                            <h6 class="mb-2 fw-semibold">Employee Photo</h6>
                                            <p class="text-muted small mb-3">photo_employee_001.jpg</p>
                                            <div class="d-flex gap-2 justify-content-center">
                                                <button class="btn btn-sm btn-outline-secondary">
                                                    <i class="mdi mdi-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-secondary">
                                                    <i class="mdi mdi-download"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <div class="file-card">
                                            <i class="mdi mdi-fingerprint file-icon"></i>
                                            <h6 class="mb-2 fw-semibold">Fingerprint</h6>
                                            <p class="text-muted small mb-3">fingerprint_001.png</p>
                                            <div class="d-flex gap-2 justify-content-center">
                                                <button class="btn btn-sm btn-outline-secondary">
                                                    <i class="mdi mdi-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-secondary">
                                                    <i class="mdi mdi-download"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <div class="file-card">
                                            <i class="mdi mdi-draw file-icon"></i>
                                            <h6 class="mb-2 fw-semibold">Signature</h6>
                                            <p class="text-muted small mb-3">signature_001.jpg</p>
                                            <div class="d-flex gap-2 justify-content-center">
                                                <button class="btn btn-sm btn-outline-secondary">
                                                    <i class="mdi mdi-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-secondary">
                                                    <i class="mdi mdi-download"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <div class="file-card">
                                            <i class="mdi mdi-file-document file-icon"></i>
                                            <h6 class="mb-2 fw-semibold">Experience Docs</h6>
                                            <p class="text-muted small mb-3">experience_certificate.pdf</p>
                                            <div class="d-flex gap-2 justify-content-center">
                                                <button class="btn btn-sm btn-outline-secondary">
                                                    <i class="mdi mdi-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-secondary">
                                                    <i class="mdi mdi-download"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <h5 class="section-title mt-5">Additional Documents</h5>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Document Type</th>
                                                <th>File Name</th>
                                                <th>Upload Date</th>
                                                <th>Size</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><i class="mdi mdi-file-document me-2 text-muted"></i>NID Copy</td>
                                                <td>nid_front_back.pdf</td>
                                                <td>Jan 10, 2024</td>
                                                <td>1.2 MB</td>
                                                <td class="text-center">
                                                    <button class="btn btn-sm btn-outline-secondary">
                                                        <i class="mdi mdi-download"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><i class="mdi mdi-certificate me-2 text-muted"></i>Educational Certificates</td>
                                                <td>certificates_all.pdf</td>
                                                <td>Jan 10, 2024</td>
                                                <td>3.5 MB</td>
                                                <td class="text-center">
                                                    <button class="btn btn-sm btn-outline-secondary">
                                                        <i class="mdi mdi-download"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><i class="mdi mdi-bank me-2 text-muted"></i>Bank Statement</td>
                                                <td>bank_details.pdf</td>
                                                <td>Jan 10, 2024</td>
                                                <td>850 KB</td>
                                                <td class="text-center">
                                                    <button class="btn btn-sm btn-outline-secondary">
                                                        <i class="mdi mdi-download"></i>
                                                    </button>
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

    </div> <!-- container-fluid -->
@endsection

@push('scripts')
<script>
    // Initialize Bootstrap tabs
    var triggerTabList = [].slice.call(document.querySelectorAll('#profileTabs button'))
    triggerTabList.forEach(function (triggerEl) {
        var tabTrigger = new bootstrap.Tab(triggerEl)
        triggerEl.addEventListener('click', function (event) {
            event.preventDefault()
            tabTrigger.show()
        })
    })
</script>
@endpush
