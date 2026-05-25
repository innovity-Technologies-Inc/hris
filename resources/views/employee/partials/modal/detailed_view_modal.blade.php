<!-- Detailed View Modal -->
<div class="modal fade" id="detailedViewModal" tabindex="-1" aria-labelledby="detailedViewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content glass-modal">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold text-dark" id="detailedViewModalLabel">
                    <i class="mdi mdi-account-details me-2"></i>Employee Detailed Profile
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="detailedViewModalBody">
                <!-- Loading State -->
                <div id="modalLoading" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Fetching employee details...</p>
                </div>

                <!-- Error State -->
                <div id="modalError" class="text-center py-5 d-none">
                    <i class="mdi mdi-alert-circle text-danger fs-1"></i>
                    <p class="mt-2 text-dark fw-semibold" id="errorMessage">Failed to load employee details.</p>
                </div>

                <!-- Content State -->
                <div id="modalContent" class="d-none">
                    <div class="row mb-4">
                        <div class="col-md-3 text-center">
                            <img id="detailed_photo" src="" alt="Profile Photo" class="img-fluid rounded-3 shadow-sm border" style="max-height: 200px; width: 100%; object-fit: cover;">
                        </div>
                        <div class="col-md-9">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h3 class="fw-bold text-dark mb-1" id="detailed_full_name"></h3>
                                    <p class="text-muted mb-3" id="detailed_ids"></p>
                                </div>
                                <a id="downloadPdfBtn" href="" class="btn btn-danger d-flex align-items-center">
                                    <i class="mdi mdi-file-pdf-box me-1"></i> Download PDF
                                </a>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1 text-muted small">Personal Email</p>
                                    <p class="fw-semibold text-dark mb-3" id="detailed_personal_email"></p>
                                    
                                    <p class="mb-1 text-muted small">Personal Mobile</p>
                                    <p class="fw-semibold text-dark mb-3" id="detailed_personal_mobile"></p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1 text-muted small">Work Email</p>
                                    <p class="fw-semibold text-dark mb-3" id="detailed_work_email"></p>
                                    
                                    <p class="mb-1 text-muted small">Work Mobile</p>
                                    <p class="fw-semibold text-dark mb-3" id="detailed_work_mobile"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="hando-main-sections">
                        <!-- Personal Info Section -->
                        <div class="card border shadow-none mb-3">
                            <div class="card-header bg-light py-2">
                                <h6 class="mb-0 fw-bold">Personal Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="text-muted small d-block">Father's Name</label>
                                        <span class="text-dark" id="detailed_father_name"></span>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="text-muted small d-block">Mother's Name</label>
                                        <span class="text-dark" id="detailed_mother_name"></span>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="text-muted small d-block">Spouse Name</label>
                                        <span class="text-dark" id="detailed_spouse_name"></span>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="text-muted small d-block">Date of Birth</label>
                                        <span class="text-dark" id="detailed_dob"></span>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="text-muted small d-block">Gender</label>
                                        <span class="text-dark" id="detailed_gender"></span>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="text-muted small d-block">Marital Status</label>
                                        <span class="text-dark" id="detailed_marital_status"></span>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="text-muted small d-block">Religion</label>
                                        <span class="text-dark" id="detailed_religion"></span>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="text-muted small d-block">Nationality</label>
                                        <span class="text-dark" id="detailed_nationality"></span>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="text-muted small d-block">Blood Group</label>
                                        <span class="text-dark" id="detailed_blood_group"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Documents Section -->
                        <div class="card border shadow-none mb-3">
                            <div class="card-header bg-light py-2">
                                <h6 class="mb-0 fw-bold">Documents & Identifiers</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="text-muted small d-block">TIN</label>
                                        <span class="text-dark" id="detailed_tin"></span>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="text-muted small d-block">Passport No</label>
                                        <span class="text-dark" id="detailed_passport_no"></span>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="text-muted small d-block">Passport Expiry</label>
                                        <span class="text-dark" id="detailed_passport_expiry"></span>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="text-muted small d-block">NID / Residency ID</label>
                                        <span class="text-dark" id="detailed_residency_id"></span>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="text-muted small d-block">License No</label>
                                        <span class="text-dark" id="detailed_license_no"></span>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="text-muted small d-block">License Expiry</label>
                                        <span class="text-dark" id="detailed_license_expiry"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Address Section -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card border shadow-none mb-3">
                                    <div class="card-header bg-light py-2">
                                        <h6 class="mb-0 fw-bold">Present Address</h6>
                                    </div>
                                    <div class="card-body">
                                        <p class="mb-0 text-dark" id="detailed_present_address"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border shadow-none mb-3">
                                    <div class="card-header bg-light py-2">
                                        <h6 class="mb-0 fw-bold">Permanent Address</h6>
                                    </div>
                                    <div class="card-body">
                                        <p class="mb-0 text-dark" id="detailed_permanent_address"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<style>
    .glass-modal {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    .hando-main-sections label {
        color: #974063 !important;
        font-weight: 600;
    }
</style>
