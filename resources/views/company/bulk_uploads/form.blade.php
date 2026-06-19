@extends('structure.master')
@section('content')
    <div class="bulk-upload">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-12">
            <!-- Main Card -->
            <div class="card shadow-sm border">
                <!-- Header -->
                <div class="card-header border-bottom py-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-dark bg-opacity-10 rounded p-2 me-3">
                            <i class="bi bi-file-earmark-arrow-up fs-3 text-dark"></i>
                        </div>
                        <div>
                            <h4 class="mb-1 fw-bold text-dark">Company Information Import</h4>
                            <p class="mb-0 text-muted small">Complete all sections to create comprehensive company
                                setup data</p>
                        </div>
                    </div>
                    <!-- Information Alert -->
                    <div class="alert alert-light border border-secondary mt-4 mb-0">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-info-circle text-secondary fs-5 me-3 mt-1"></i>
                            <div>
                                <h6 class="fw-semibold mb-2 text-dark">Upload Instructions</h6>
                                <p class="mb-0 small text-muted">
                                    Download the template for each section, populate the required fields with accurate
                                    data,
                                    and upload the completed file. Ensure proper formatting and data validation before
                                    submission.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Body -->
                <div class="card-body p-4 p-md-5">


                    <!-- Section Header -->
                    <div class="mb-4 pb-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="text-uppercase text-secondary fw-semibold mb-0 small letter-spacing">
                                Import Company Information
                            </h6>
                            <span class="badge bg-secondary">14 Sections</span>
                        </div>
                    </div>

                    <!-- First Row -->
                    <div class="row g-3 mb-3">
                        <!-- Section 1 - Company Types -->
                        <div class="col-md-4">
                            <div class="card h-100 border shadow-sm professional-card">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-start mb-3">
                                        <span
                                            class="badge bg-dark text-white rounded-circle me-3 d-flex align-items-center justify-content-center fw-bold"
                                            style="width: 36px; height: 36px; font-size: 14px;">1</span>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fw-semibold text-dark">Company Types</h6>
                                            <p class="text-muted small mb-0" style="font-size: 0.813rem;">Company type
                                                classifications</p>
                                        </div>
                                    </div>
                                    <button type="button"
                                        class="btn btn-outline-dark btn-sm w-100 text-uppercase fw-semibold"
                                        style="font-size: 0.75rem; letter-spacing: 0.5px;" data-bs-toggle="modal"
                                        data-bs-target="#bulkUploadModal" data-section="company-types"
                                        data-section-name="Company Types" data-section-number="1" data-form-action="#"
                                        data-excel-link="#" data-csv-link="#">
                                        <i class="bi bi-upload me-2"></i>Upload
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Section 2 - Companies -->
                        <div class="col-md-4">
                            <div class="card h-100 border shadow-sm professional-card">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-start mb-3">
                                        <span
                                            class="badge bg-dark text-white rounded-circle me-3 d-flex align-items-center justify-content-center fw-bold"
                                            style="width: 36px; height: 36px; font-size: 14px;">2</span>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fw-semibold text-dark">Companies</h6>
                                            <p class="text-muted small mb-0" style="font-size: 0.813rem;">Company basic
                                                information</p>
                                        </div>
                                    </div>
                                    <button type="button"
                                        class="btn btn-outline-dark btn-sm w-100 text-uppercase fw-semibold"
                                        style="font-size: 0.75rem; letter-spacing: 0.5px;" data-bs-toggle="modal"
                                        data-bs-target="#bulkUploadModal" data-section="companies"
                                        data-section-name="Companies" data-section-number="2" data-form-action="#"
                                        data-excel-link="#" data-csv-link="#">
                                        <i class="bi bi-upload me-2"></i>Upload
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Section 3 - Company Branches -->
                        <div class="col-md-4">
                            <div class="card h-100 border shadow-sm professional-card">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-start mb-3">
                                        <span
                                            class="badge bg-dark text-white rounded-circle me-3 d-flex align-items-center justify-content-center fw-bold"
                                            style="width: 36px; height: 36px; font-size: 14px;">3</span>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fw-semibold text-dark">Company Branches</h6>
                                            <p class="text-muted small mb-0" style="font-size: 0.813rem;">Company location
                                                details</p>
                                        </div>
                                    </div>
                                    <button type="button"
                                        class="btn btn-outline-dark btn-sm w-100 text-uppercase fw-semibold"
                                        style="font-size: 0.75rem; letter-spacing: 0.5px;" data-bs-toggle="modal"
                                        data-bs-target="#bulkUploadModal" data-section="company-branches"
                                        data-section-name="Company Branches" data-section-number="3" data-form-action="#"
                                        data-excel-link="#" data-csv-link="#">
                                        <i class="bi bi-upload me-2"></i>Upload
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Section 4 - Divisions -->
                        <div class="col-md-4">
                            <div class="card h-100 border shadow-sm professional-card">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-start mb-3">
                                        <span
                                            class="badge bg-dark text-white rounded-circle me-3 d-flex align-items-center justify-content-center fw-bold"
                                            style="width: 36px; height: 36px; font-size: 14px;">4</span>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fw-semibold text-dark">Divisions</h6>
                                            <p class="text-muted small mb-0" style="font-size: 0.813rem;">Organizational
                                                divisions</p>
                                        </div>
                                    </div>
                                    <button type="button"
                                        class="btn btn-outline-dark btn-sm w-100 text-uppercase fw-semibold"
                                        style="font-size: 0.75rem; letter-spacing: 0.5px;" data-bs-toggle="modal"
                                        data-bs-target="#bulkUploadModal" data-section="divisions"
                                        data-section-name="Divisions" data-section-number="4" data-form-action="#"
                                        data-excel-link="#" data-csv-link="#">
                                        <i class="bi bi-upload me-2"></i>Upload
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Section 5 - Departments -->
                        <div class="col-md-4">
                            <div class="card h-100 border shadow-sm professional-card">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-start mb-3">
                                        <span
                                            class="badge bg-dark text-white rounded-circle me-3 d-flex align-items-center justify-content-center fw-bold"
                                            style="width: 36px; height: 36px; font-size: 14px;">5</span>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fw-semibold text-dark">Departments</h6>
                                            <p class="text-muted small mb-0" style="font-size: 0.813rem;">Department
                                                information</p>
                                        </div>
                                    </div>
                                    <button type="button"
                                        class="btn btn-outline-dark btn-sm w-100 text-uppercase fw-semibold"
                                        style="font-size: 0.75rem; letter-spacing: 0.5px;" data-bs-toggle="modal"
                                        data-bs-target="#bulkUploadModal" data-section="departments"
                                        data-section-name="Departments" data-section-number="5" data-form-action="#"
                                        data-excel-link="#" data-csv-link="#">
                                        <i class="bi bi-upload me-2"></i>Upload
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Section 6 - Sections -->
                        <div class="col-md-4">
                            <div class="card h-100 border shadow-sm professional-card">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-start mb-3">
                                        <span
                                            class="badge bg-dark text-white rounded-circle me-3 d-flex align-items-center justify-content-center fw-bold"
                                            style="width: 36px; height: 36px; font-size: 14px;">6</span>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fw-semibold text-dark">Sections</h6>
                                            <p class="text-muted small mb-0" style="font-size: 0.813rem;">Section
                                                information</p>
                                        </div>
                                    </div>
                                    <button type="button"
                                        class="btn btn-outline-dark btn-sm w-100 text-uppercase fw-semibold"
                                        style="font-size: 0.75rem; letter-spacing: 0.5px;" data-bs-toggle="modal"
                                        data-bs-target="#bulkUploadModal" data-section="sections"
                                        data-section-name="Sections" data-section-number="6" data-form-action="#"
                                        data-excel-link="#" data-csv-link="#"><i class="bi bi-upload me-2"></i>Upload
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Section 7 - Designations -->
                        <div class="col-md-4">
                            <div class="card h-100 border shadow-sm professional-card">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-start mb-3">
                                        <span
                                            class="badge bg-dark text-white rounded-circle me-3 d-flex align-items-center justify-content-center fw-bold"
                                            style="width: 36px; height: 36px; font-size: 14px;">7</span>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fw-semibold text-dark">Designations</h6>
                                            <p class="text-muted small mb-0" style="font-size: 0.813rem;">Job designation
                                                details</p>
                                        </div>
                                    </div>
                                    <button type="button"
                                        class="btn btn-outline-dark btn-sm w-100 text-uppercase fw-semibold"
                                        style="font-size: 0.75rem; letter-spacing: 0.5px;" data-bs-toggle="modal"
                                        data-bs-target="#bulkUploadModal" data-section="designations"
                                        data-section-name="Designations" data-section-number="7" data-form-action="#"
                                        data-excel-link="#" data-csv-link="#">
                                        <i class="bi bi-upload me-2"></i>Upload
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Section 8 - Salary Acts -->
                        <div class="col-md-4">
                            <div class="card h-100 border shadow-sm professional-card">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-start mb-3">
                                        <span
                                            class="badge bg-dark text-white rounded-circle me-3 d-flex align-items-center justify-content-center fw-bold"
                                            style="width: 36px; height: 36px; font-size: 14px;">8</span>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fw-semibold text-dark">Salary Acts</h6>
                                            <p class="text-muted small mb-0" style="font-size: 0.813rem;">Tofsil
                                                information</p>
                                        </div>
                                    </div>
                                    <button type="button"
                                        class="btn btn-outline-dark btn-sm w-100 text-uppercase fw-semibold"
                                        style="font-size: 0.75rem; letter-spacing: 0.5px;" data-bs-toggle="modal"
                                        data-bs-target="#bulkUploadModal" data-section="salary-acts"
                                        data-section-name="Salary Acts" data-section-number="8" data-form-action="#"
                                        data-excel-link="#" data-csv-link="#">
                                        <i class="bi bi-upload me-2"></i>Upload
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Section 9 - Salary Grades -->
                        <div class="col-md-4">
                            <div class="card h-100 border shadow-sm professional-card">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-start mb-3">
                                        <span
                                            class="badge bg-dark text-white rounded-circle me-3 d-flex align-items-center justify-content-center fw-bold"
                                            style="width: 36px; height: 36px; font-size: 14px;">9</span>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fw-semibold text-dark">Salary Grades</h6>
                                            <p class="text-muted small mb-0" style="font-size: 0.813rem;">Salary grade
                                                details</p>
                                        </div>
                                    </div>
                                    <button type="button"
                                        class="btn btn-outline-dark btn-sm w-100 text-uppercase fw-semibold"
                                        style="font-size: 0.75rem; letter-spacing: 0.5px;" data-bs-toggle="modal"
                                        data-bs-target="#bulkUploadModal" data-section="salary-grades"
                                        data-section-name="Salary Grades" data-section-number="9" data-form-action="#"
                                        data-excel-link="#" data-csv-link="#">
                                        <i class="bi bi-upload me-2"></i>Upload
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Section 10 - Banks -->
                        <div class="col-md-4">
                            <div class="card h-100 border shadow-sm professional-card">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-start mb-3">
                                        <span
                                            class="badge bg-dark text-white rounded-circle me-3 d-flex align-items-center justify-content-center fw-bold"
                                            style="width: 36px; height: 36px; font-size: 14px;">10</span>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fw-semibold text-dark">Banks</h6>
                                            <p class="text-muted small mb-0" style="font-size: 0.813rem;">Bank
                                                information</p>
                                        </div>
                                    </div>
                                    <button type="button"
                                        class="btn btn-outline-dark btn-sm w-100 text-uppercase fw-semibold"
                                        style="font-size: 0.75rem; letter-spacing: 0.5px;" data-bs-toggle="modal"
                                        data-bs-target="#bulkUploadModal" data-section="banks" data-section-name="Banks"
                                        data-section-number="10" data-form-action="#" data-excel-link="#"
                                        data-csv-link="#">
                                        <i class="bi bi-upload me-2"></i>Upload
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Section 11 - Bank Branches -->
                        <div class="col-md-4">
                            <div class="card h-100 border shadow-sm professional-card">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-start mb-3">
                                        <span
                                            class="badge bg-dark text-white rounded-circle me-3 d-flex align-items-center justify-content-center fw-bold"
                                            style="width: 36px; height: 36px; font-size: 14px;">11</span>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fw-semibold text-dark">Bank Branches</h6>
                                            <p class="text-muted small mb-0" style="font-size: 0.813rem;">Bank branch
                                                details</p>
                                        </div>
                                    </div>
                                    <button type="button"
                                        class="btn btn-outline-dark btn-sm w-100 text-uppercase fw-semibold"
                                        style="font-size: 0.75rem; letter-spacing: 0.5px;" data-bs-toggle="modal"
                                        data-bs-target="#bulkUploadModal" data-section="bank-branches"
                                        data-section-name="Bank Branches" data-section-number="11" data-form-action="#"
                                        data-excel-link="#" data-csv-link="#">
                                        <i class="bi bi-upload me-2"></i>Upload
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Section 12 - Bank Accounts -->
                        <div class="col-md-4">
                            <div class="card h-100 border shadow-sm professional-card">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-start mb-3">
                                        <span
                                            class="badge bg-dark text-white rounded-circle me-3 d-flex align-items-center justify-content-center fw-bold"
                                            style="width: 36px; height: 36px; font-size: 14px;">12</span>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fw-semibold text-dark">Bank Accounts</h6>
                                            <p class="text-muted small mb-0" style="font-size: 0.813rem;">Bank account
                                                information</p>
                                        </div>
                                    </div>
                                    <button type="button"
                                        class="btn btn-outline-dark btn-sm w-100 text-uppercase fw-semibold"
                                        style="font-size: 0.75rem; letter-spacing: 0.5px;" data-bs-toggle="modal"
                                        data-bs-target="#bulkUploadModal" data-section="bank-accounts"
                                        data-section-name="Bank Accounts" data-section-number="12" data-form-action="#"
                                        data-excel-link="#" data-csv-link="#">
                                        <i class="bi bi-upload me-2"></i>Upload
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Section 13 - Job Creations -->
                        <div class="col-md-4">
                            <div class="card h-100 border shadow-sm professional-card">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-start mb-3">
                                        <span
                                            class="badge bg-dark text-white rounded-circle me-3 d-flex align-items-center justify-content-center fw-bold"
                                            style="width: 36px; height: 36px; font-size: 14px;">13</span>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fw-semibold text-dark">Job Creations</h6>
                                            <p class="text-muted small mb-0" style="font-size: 0.813rem;">Job creation
                                                details</p>
                                        </div>
                                    </div>
                                    <button type="button"
                                        class="btn btn-outline-dark btn-sm w-100 text-uppercase fw-semibold"
                                        style="font-size: 0.75rem; letter-spacing: 0.5px;" data-bs-toggle="modal"
                                        data-bs-target="#bulkUploadModal" data-section="job-creations"
                                        data-section-name="Job Creations" data-section-number="13" data-form-action="#"
                                        data-excel-link="#" data-csv-link="#">
                                        <i class="bi bi-upload me-2"></i>Upload
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Section 14 - Gazette Locations -->
                        <div class="col-md-4">
                            <div class="card h-100 border shadow-sm professional-card">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-start mb-3">
                                        <span
                                            class="badge bg-dark text-white rounded-circle me-3 d-flex align-items-center justify-content-center fw-bold"
                                            style="width: 36px; height: 36px; font-size: 14px;">14</span>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fw-semibold text-dark">Gazette Locations</h6>
                                            <p class="text-muted small mb-0" style="font-size: 0.813rem;">Gazette location
                                                details</p>
                                        </div>
                                    </div>
                                    <button type="button"
                                        class="btn btn-outline-dark btn-sm w-100 text-uppercase fw-semibold"
                                        style="font-size: 0.75rem; letter-spacing: 0.5px;" data-bs-toggle="modal"
                                        data-bs-target="#bulkUploadModal" data-section="gazette-locations"
                                        data-section-name="Gazette Locations" data-section-number="15"
                                        data-form-action="#" data-excel-link="#" data-csv-link="#">
                                        <i class="bi bi-upload me-2"></i>Upload
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>


                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Include the modal -->
    @include('company.bulk_uploads.import')

    <style>
        .professional-card {
            transition: all 0.3s ease;
            background-color: #ffffff;
        }

        .professional-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
            border-color: #495057 !important;
        }

        .letter-spacing {
            letter-spacing: 0.5px;
        }

        .btn-outline-dark:hover {
            background-color: #212529;
            border-color: #212529;
        }
    </style>

    <style>
        /* Dark mode fixes for bulk upload pages */
        [data-bs-theme="dark"] .bulk-upload {
            color: var(--bs-dashboard-accent);
        }

        [data-bs-theme="dark"] .bulk-upload .card,
        [data-bs-theme="dark"] .bulk-upload .card-header,
        [data-bs-theme="dark"] .bulk-upload .alert {
            background-color: var(--bs-dark-bg-subtle) !important;
            border-color: var(--bs-dark-border-subtle) !important;
        }

        [data-bs-theme="dark"] .bulk-upload .bg-white {
            background-color: var(--bs-dark-bg-subtle) !important;
        }

        [data-bs-theme="dark"] .bulk-upload .text-muted,
        [data-bs-theme="dark"] .bulk-upload .text-dark,
        [data-bs-theme="dark"] .bulk-upload .text-secondary,
        [data-bs-theme="dark"] .bulk-upload h1,
        [data-bs-theme="dark"] .bulk-upload h2,
        [data-bs-theme="dark"] .bulk-upload h3,
        [data-bs-theme="dark"] .bulk-upload h4,
        [data-bs-theme="dark"] .bulk-upload h5,
        [data-bs-theme="dark"] .bulk-upload h6,
        [data-bs-theme="dark"] .bulk-upload p,
        [data-bs-theme="dark"] .bulk-upload span,
        [data-bs-theme="dark"] .bulk-upload label,
        [data-bs-theme="dark"] .bulk-upload i {
            color: var(--bs-dashboard-accent) !important;
        }

        [data-bs-theme="dark"] .bulk-upload .btn-outline-dark {
            color: var(--bs-dashboard-accent) !important;
            border-color: var(--bs-dashboard-accent) !important;
        }

        [data-bs-theme="dark"] .bulk-upload .badge.bg-dark {
            background-color: var(--bs-dashboard-accent) !important;
            color: #000 !important;
        }
    </style>
@endsection

