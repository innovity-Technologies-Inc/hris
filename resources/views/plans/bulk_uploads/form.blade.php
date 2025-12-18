@extends('structure.master')
@section('content')
    <div class="bulk-upload">
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
                                <span class="badge bg-secondary">8 Sections</span>
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
                                                <p class="text-muted small mb-0" style="font-size: 0.813rem;">Employee meal
                                                    plan
                                                    details</p>
                                            </div>
                                        </div>
                                        <button type="button"
                                            class="btn btn-outline-dark btn-sm w-100 text-uppercase fw-semibold"
                                            style="font-size: 0.75rem; letter-spacing: 0.5px;" data-bs-toggle="modal"
                                            data-bs-target="#bulkUploadModal" data-section="meal-plan"
                                            data-section-name="Meal Plan" data-section-number="1"
                                            data-form-action="{{ route('plans.meal_plans.import') }}"
                                            data-excel-link="{{ asset('assets/excel/meal_plans.xlsx') }}"
                                            data-csv-link="{{ asset('assets/csv/meal_plans.csv') }}">
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
                                            data-section-name="Shift Plan" data-section-number="2"
                                            data-form-action="{{ route('plans.shift_plans.import') }}"
                                            data-excel-link="{{ asset('assets/excel/shift_plans_bulk.xlsx') }}"
                                            data-csv-link="{{ asset('assets/csv/shift_plans.csv') }}">
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
                                            data-section-name="Leave Plan" data-section-number="3"
                                            data-form-action="{{ route('plans.leave_plans.import') }}"
                                            data-excel-link="{{ asset('assets/excel/leave_plans.xlsx') }}"
                                            data-csv-link="{{ asset('assets/csv/leave_plans.csv') }}">
                                            <i class="bi bi-upload me-2"></i>Upload
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 4 - OT Plan -->
                            <div class="col-md-4">
                                <div class="card h-100 border shadow-sm professional-card">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-start mb-3">
                                            <span
                                                class="badge bg-dark text-white rounded-circle me-3 d-flex align-items-center justify-content-center fw-bold"
                                                style="width: 36px; height: 36px; font-size: 14px;">4</span>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 fw-semibold text-dark">Overtime Plans</h6>
                                                <p class="text-muted small mb-0" style="font-size: 0.813rem;">Overtime
                                                    plan details</p>
                                            </div>
                                        </div>
                                        <button type="button"
                                            class="btn btn-outline-dark btn-sm w-100 text-uppercase fw-semibold"
                                            style="font-size: 0.75rem; letter-spacing: 0.5px;" data-bs-toggle="modal"
                                            data-bs-target="#bulkUploadModal" data-section="overtime-plan"
                                            data-section-name="Overtime Plan" data-section-number="3"
                                            data-form-action="{{ route('plans.ot_plans.import') }}"
                                            data-excel-link="{{ asset('assets/excel/ot_plans.xlsx') }}"
                                            data-csv-link="{{ asset('assets/csv/ot_plans.csv') }}">
                                            <i class="bi bi-upload me-2"></i>Upload
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 5 - Roster Plan -->
                            <div class="col-md-4">
                                <div class="card h-100 border shadow-sm professional-card">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-start mb-3">
                                            <span
                                                class="badge bg-dark text-white rounded-circle me-3 d-flex align-items-center justify-content-center fw-bold"
                                                style="width: 36px; height: 36px; font-size: 14px;">5</span>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 fw-semibold text-dark">Roster Plans</h6>
                                                <p class="text-muted small mb-0" style="font-size: 0.813rem;">Roster
                                                    plan details</p>
                                            </div>
                                        </div>
                                        <button type="button"
                                            class="btn btn-outline-dark btn-sm w-100 text-uppercase fw-semibold"
                                            style="font-size: 0.75rem; letter-spacing: 0.5px;" data-bs-toggle="modal"
                                            data-bs-target="#bulkUploadModal" data-section="roster-plan"
                                            data-section-name="Overtime Plan" data-section-number="3"
                                            data-form-action="{{ route('plans.roster_plans.import') }}"
                                            data-excel-link="{{ asset('assets/excel/roster_plans.xlsx') }}"
                                            data-csv-link="{{ asset('assets/csv/roster_plans.csv') }}">
                                            <i class="bi bi-upload me-2"></i>Upload
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 6 - Off Day Plan -->
                            <div class="col-md-4">
                                <div class="card h-100 border shadow-sm professional-card">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-start mb-3">
                                            <span
                                                class="badge bg-dark text-white rounded-circle me-3 d-flex align-items-center justify-content-center fw-bold"
                                                style="width: 36px; height: 36px; font-size: 14px;">6</span>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 fw-semibold text-dark">Off Day Work Plans</h6>
                                                <p class="text-muted small mb-0" style="font-size: 0.813rem;">Off Day Work
                                                    plan details</p>
                                            </div>
                                        </div>
                                        <button type="button"
                                            class="btn btn-outline-dark btn-sm w-100 text-uppercase fw-semibold"
                                            style="font-size: 0.75rem; letter-spacing: 0.5px;" data-bs-toggle="modal"
                                            data-bs-target="#bulkUploadModal" data-section="off-day-plan"
                                            data-section-name="Overtime Plan" data-section-number="3"
                                            data-form-action="{{ route('plans.off_day_plans.import') }}"
                                            data-excel-link="{{ asset('assets/excel/off_day_plans.xlsx') }}"
                                            data-csv-link="{{ asset('assets/csv/off_day_plans.csv') }}">
                                            <i class="bi bi-upload me-2"></i>Upload
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 7 - Bonus Plan -->
                            <div class="col-md-4">
                                <div class="card h-100 border shadow-sm professional-card">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-start mb-3">
                                            <span
                                                class="badge bg-dark text-white rounded-circle me-3 d-flex align-items-center justify-content-center fw-bold"
                                                style="width: 36px; height: 36px; font-size: 14px;">7</span>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 fw-semibold text-dark">Bonus Plans</h6>
                                                <p class="text-muted small mb-0" style="font-size: 0.813rem;">Bonus
                                                    plan details</p>
                                            </div>
                                        </div>
                                        <button type="button"
                                            class="btn btn-outline-dark btn-sm w-100 text-uppercase fw-semibold"
                                            style="font-size: 0.75rem; letter-spacing: 0.5px;" data-bs-toggle="modal"
                                            data-bs-target="#bulkUploadModal" data-section="bonus-plan"
                                            data-section-name="Bonus Plan" data-section-number="6"
                                            data-form-action="{{ route('plans.bonus_plans.import') }}"
                                            data-excel-link="{{ asset('assets/excel/bonus_plans.xlsx') }}"
                                            data-csv-link="{{ asset('assets/csv/bonus_plans.csv') }}">
                                            <i class="bi bi-upload me-2"></i>Upload
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 8 - Allowance Plan -->
                            <div class="col-md-4">
                                <div class="card h-100 border shadow-sm professional-card">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-start mb-3">
                                            <span
                                                class="badge bg-dark text-white rounded-circle me-3 d-flex align-items-center justify-content-center fw-bold"
                                                style="width: 36px; height: 36px; font-size: 14px;">8</span>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 fw-semibold text-dark">Allowance Plans</h6>
                                                <p class="text-muted small mb-0" style="font-size: 0.813rem;">Allowance
                                                    plan details</p>
                                            </div>
                                        </div>
                                        <button type="button"
                                            class="btn btn-outline-dark btn-sm w-100 text-uppercase fw-semibold"
                                            style="font-size: 0.75rem; letter-spacing: 0.5px;" data-bs-toggle="modal"
                                            data-bs-target="#bulkUploadModal" data-section="allowance-plan"
                                            data-section-name="Allowance Plan" data-section-number="8"
                                            data-form-action="{{ route('plans.allowance_plans.import') }}"
                                            data-excel-link="{{ asset('assets/excel/allowance_plans.xlsx') }}"
                                            data-csv-link="{{ asset('assets/csv/allowance_plans.csv') }}">
                                            <i class="bi bi-upload me-2"></i>Upload
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 9 - TA Plan -->
                            <div class="col-md-4">
                                <div class="card h-100 border shadow-sm professional-card">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-start mb-3">
                                            <span
                                                class="badge bg-dark text-white rounded-circle me-3 d-flex align-items-center justify-content-center fw-bold"
                                                style="width: 36px; height: 36px; font-size: 14px;">9</span>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 fw-semibold text-dark">TA Plans</h6>
                                                <p class="text-muted small mb-0" style="font-size: 0.813rem;">Transport
                                                    Allowance
                                                    plan details</p>
                                            </div>
                                        </div>
                                        <button type="button"
                                            class="btn btn-outline-dark btn-sm w-100 text-uppercase fw-semibold"
                                            style="font-size: 0.75rem; letter-spacing: 0.5px;" data-bs-toggle="modal"
                                            data-bs-target="#bulkUploadModal" data-section="ta-plan"
                                            data-section-name="TA Plan" data-section-number="9"
                                            data-form-action="{{ route('plans.ta_plans.import') }}"
                                            data-excel-link="{{ asset('assets/excel/ta_plans.xlsx') }}"
                                            data-csv-link="{{ asset('assets/csv/ta_plans.csv') }}">
                                            <i class="bi bi-upload me-2"></i>Upload
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 10 - DA Plan -->
                            <div class="col-md-4">
                                <div class="card h-100 border shadow-sm professional-card">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-start mb-3">
                                            <span
                                                class="badge bg-dark text-white rounded-circle me-3 d-flex align-items-center justify-content-center fw-bold"
                                                style="width: 36px; height: 36px; font-size: 14px;">10</span>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 fw-semibold text-dark">DA Plans</h6>
                                                <p class="text-muted small mb-0" style="font-size: 0.813rem;">Dining
                                                    Allowance
                                                    plan details</p>
                                            </div>
                                        </div>
                                        <button type="button"
                                            class="btn btn-outline-dark btn-sm w-100 text-uppercase fw-semibold"
                                            style="font-size: 0.75rem; letter-spacing: 0.5px;" data-bs-toggle="modal"
                                            data-bs-target="#bulkUploadModal" data-section="da-plan"
                                            data-section-name="DA Plan" data-section-number="10"
                                            data-form-action="{{ route('plans.da_plans.import') }}"
                                            data-excel-link="{{ asset('assets/excel/da_plans.xlsx') }}"
                                            data-csv-link="{{ asset('assets/csv/da_plans.csv') }}">
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
