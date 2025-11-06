@extends('structure.master')
@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-xl-12">
            <!-- Main Card -->
            <div class="card shadow-sm border">
                <!-- Header -->
                <div class="card-header bg-white border-bottom py-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-dark bg-opacity-10 rounded p-2 me-3">
                            <i class="bi bi-file-earmark-arrow-up fs-3 text-dark"></i>
                        </div>
                        <div>
                            <h4 class="mb-1 fw-bold text-dark">Plan Information Import</h4>
                            <p class="mb-0 text-muted small">Complete all sections to create comprehensive plan
                                configurations</p>
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
                                Import Plan Information
                            </h6>
                            <span class="badge bg-secondary">3 Sections</span>
                        </div>
                    </div>

                    <!-- First Row -->
                    <div class="row g-3 mb-3">
                        <!-- Section 1 - Meal Plan -->
                        <div class="col-md-4">
                            <div class="card h-100 border shadow-sm professional-card">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-start mb-3">
                                        <span
                                            class="badge bg-dark text-white rounded-circle me-3 d-flex align-items-center justify-content-center fw-bold"
                                            style="width: 36px; height: 36px; font-size: 14px;">1</span>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fw-semibold text-dark">Meal Plan</h6>
                                            <p class="text-muted small mb-0" style="font-size: 0.813rem;">Employee meal plan
                                                details</p>
                                        </div>
                                    </div>
                                    <button type="button"
                                        class="btn btn-outline-dark btn-sm w-100 text-uppercase fw-semibold"
                                        style="font-size: 0.75rem; letter-spacing: 0.5px;" data-bs-toggle="modal"
                                        data-bs-target="#bulkUploadModal" data-section="meal-plan"
                                        data-section-name="Meal Plan" data-section-number="1" data-form-action="#"
                                        data-excel-link="#" data-csv-link="#">
                                        <i class="bi bi-upload me-2"></i>Upload
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Section 2 - Shift Plan -->
                        <div class="col-md-4">
                            <div class="card h-100 border shadow-sm professional-card">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-start mb-3">
                                        <span
                                            class="badge bg-dark text-white rounded-circle me-3 d-flex align-items-center justify-content-center fw-bold"
                                            style="width: 36px; height: 36px; font-size: 14px;">2</span>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fw-semibold text-dark">Shift Plan</h6>
                                            <p class="text-muted small mb-0" style="font-size: 0.813rem;">Employee shift
                                                plan details</p>
                                        </div>
                                    </div>
                                    <button type="button"
                                        class="btn btn-outline-dark btn-sm w-100 text-uppercase fw-semibold"
                                        style="font-size: 0.75rem; letter-spacing: 0.5px;" data-bs-toggle="modal"
                                        data-bs-target="#bulkUploadModal" data-section="shift-plan"
                                        data-section-name="Shift Plan" data-section-number="2" data-form-action="#"
                                        data-excel-link="#" data-csv-link="#">
                                        <i class="bi bi-upload me-2"></i>Upload
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Section 3 - Leave Plan -->
                        <div class="col-md-4">
                            <div class="card h-100 border shadow-sm professional-card">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-start mb-3">
                                        <span
                                            class="badge bg-dark text-white rounded-circle me-3 d-flex align-items-center justify-content-center fw-bold"
                                            style="width: 36px; height: 36px; font-size: 14px;">3</span>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fw-semibold text-dark">Leave Plan</h6>
                                            <p class="text-muted small mb-0" style="font-size: 0.813rem;">Employee leave
                                                plan details</p>
                                        </div>
                                    </div>
                                    <button type="button"
                                        class="btn btn-outline-dark btn-sm w-100 text-uppercase fw-semibold"
                                        style="font-size: 0.75rem; letter-spacing: 0.5px;" data-bs-toggle="modal"
                                        data-bs-target="#bulkUploadModal" data-section="leave-plan"
                                        data-section-name="Leave Plan" data-section-number="3" data-form-action="#"
                                        data-excel-link="#" data-csv-link="#">
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

    <!-- Include the modal -->
    @include('plans.bulk_uploads.import')

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
@endsection
