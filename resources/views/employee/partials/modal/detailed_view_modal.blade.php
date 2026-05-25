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
                    <!-- Header Info -->
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
                                    <div class="col-md-4"><label>Father's Name</label><span id="detailed_father_name"></span></div>
                                    <div class="col-md-4"><label>Mother's Name</label><span id="detailed_mother_name"></span></div>
                                    <div class="col-md-4"><label>Spouse Name</label><span id="detailed_spouse_name"></span></div>
                                    <div class="col-md-4"><label>Date of Birth</label><span id="detailed_dob"></span></div>
                                    <div class="col-md-4"><label>Gender</label><span id="detailed_gender"></span></div>
                                    <div class="col-md-4"><label>Marital Status</label><span id="detailed_marital_status"></span></div>
                                    <div class="col-md-4"><label>Religion</label><span id="detailed_religion"></span></div>
                                    <div class="col-md-4"><label>Nationality</label><span id="detailed_nationality"></span></div>
                                    <div class="col-md-4"><label>Blood Group</label><span id="detailed_blood_group"></span></div>
                                    <div class="col-md-4"><label>Punch Card No</label><span id="detailed_punch_card_no"></span></div>
                                </div>
                            </div>
                        </div>

                        <!-- Equal Height Row for Documents and Addresses -->
                        <div class="row g-3 mb-3 d-flex align-items-stretch">
                            <!-- Documents Section -->
                            <div class="col-md-4">
                                <div class="card border shadow-none h-100">
                                    <div class="card-header bg-light py-2">
                                        <h6 class="mb-0 fw-bold">Document Identifiers</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-2"><label class="d-block">TIN</label><span id="detailed_tin"></span></div>
                                        <div class="mb-2"><label class="d-block">Passport No</label><span id="detailed_passport_no"></span></div>
                                        <div class="mb-2"><label class="d-block">Passport Expiry</label><span id="detailed_passport_expiry"></span></div>
                                        <div class="mb-2"><label class="d-block">NID / Residency ID</label><span id="detailed_residency_id"></span></div>
                                        <div class="mb-2"><label class="d-block">License No</label><span id="detailed_license_no"></span></div>
                                        <div class="mb-0"><label class="d-block">License Expiry</label><span id="detailed_license_expiry"></span></div>
                                    </div>
                                </div>
                            </div>
                            <!-- Present Address Section -->
                            <div class="col-md-4">
                                <div class="card border shadow-none h-100">
                                    <div class="card-header bg-light py-2">
                                        <h6 class="mb-0 fw-bold">Present Address</h6>
                                    </div>
                                    <div class="card-body">
                                        <p class="mb-0 text-dark" id="detailed_present_address"></p>
                                    </div>
                                </div>
                            </div>
                            <!-- Permanent Address Section -->
                            <div class="col-md-4">
                                <div class="card border shadow-none h-100">
                                    <div class="card-header bg-light py-2">
                                        <h6 class="mb-0 fw-bold">Permanent Address</h6>
                                    </div>
                                    <div class="card-body">
                                        <p class="mb-0 text-dark" id="detailed_permanent_address"></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Office Information Section -->
                        <div id="section_office_info" class="card border shadow-none mb-3 d-none">
                            <div class="card-header bg-light py-2">
                                <h6 class="mb-0 fw-bold">Office Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-4"><label>Company</label><span id="detailed_company"></span></div>
                                    <div class="col-md-4"><label>Designation</label><span id="detailed_designation"></span></div>
                                    <div class="col-md-4"><label>Department</label><span id="detailed_department"></span></div>
                                    <div class="col-md-4"><label>Division</label><span id="detailed_division"></span></div>
                                    <div class="col-md-4"><label>Section</label><span id="detailed_section"></span></div>
                                    <div class="col-md-4"><label>Date of Join</label><span id="detailed_join_date"></span></div>
                                </div>
                            </div>
                        </div>

                        <!-- Education & History Section -->
                        <div class="row g-3 mb-3 d-flex align-items-stretch">
                            <div class="col-md-6">
                                <div id="section_education" class="card border shadow-none h-100 d-none">
                                    <div class="card-header bg-light py-2">
                                        <h6 class="mb-0 fw-bold">Education Information</h6>
                                    </div>
                                    <div class="card-body" id="detailed_education_body"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div id="section_history" class="card border shadow-none h-100 d-none">
                                    <div class="card-header bg-light py-2">
                                        <h6 class="mb-0 fw-bold">Employment History</h6>
                                    </div>
                                    <div class="card-body" id="detailed_history_body"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Nominee & Bank Section -->
                        <div class="row g-3 mb-3 d-flex align-items-stretch">
                            <div class="col-md-6">
                                <div id="section_nominee" class="card border shadow-none h-100 d-none">
                                    <div class="card-header bg-light py-2">
                                        <h6 class="mb-0 fw-bold">Nominee Information</h6>
                                    </div>
                                    <div class="card-body" id="detailed_nominee_body"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div id="section_bank" class="card border shadow-none h-100 d-none">
                                    <div class="card-header bg-light py-2">
                                        <h6 class="mb-0 fw-bold">Accounts Information</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-2"><label class="d-block">Bank</label><span id="detailed_bank_name"></span></div>
                                        <div class="mb-2"><label class="d-block">Branch</label><span id="detailed_bank_branch"></span></div>
                                        <div class="mb-2"><label class="d-block">Account Name</label><span id="detailed_account_name"></span></div>
                                        <div class="mb-0"><label class="d-block">Account Number</label><span id="detailed_account_number"></span></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Policy & Salary Section -->
                        <div class="row g-3 mb-3 d-flex align-items-stretch">
                            <div class="col-md-6">
                                <div id="section_policy" class="card border shadow-none h-100 d-none">
                                    <div class="card-header bg-light py-2">
                                        <h6 class="mb-0 fw-bold">Policy Tags</h6>
                                    </div>
                                    <div class="card-body" id="detailed_policy_body"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div id="section_salary" class="card border shadow-none h-100 d-none">
                                    <div class="card-header bg-light py-2">
                                        <h6 class="mb-0 fw-bold">Salary Breakdown</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row" id="detailed_salary_body"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Current Plans Section -->
                        <div id="section_plans" class="card border shadow-none mb-3 d-none">
                            <div class="card-header bg-light py-2">
                                <h6 class="mb-0 fw-bold">Current Plans</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3" id="detailed_plans_body"></div>
                            </div>
                        </div>

                        <!-- Leave Section -->
                        <div class="row g-3 mb-3 d-flex align-items-stretch">
                            <div class="col-md-6">
                                <div id="section_leave_info" class="card border shadow-none h-100 d-none">
                                    <div class="card-header bg-light py-2">
                                        <h6 class="mb-0 fw-bold">Leave Info / Balance</h6>
                                    </div>
                                    <div class="card-body" id="detailed_leave_info_body"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div id="section_leave_history" class="card border shadow-none h-100 d-none">
                                    <div class="card-header bg-light py-2">
                                        <h6 class="mb-0 fw-bold">Leave History</h6>
                                    </div>
                                    <div class="card-body" id="detailed_leave_history_body"></div>
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
        font-size: 0.8rem;
        margin-bottom: 2px;
    }
    .hando-main-sections span {
        display: block;
        color: #333;
        font-weight: 500;
    }
    .hando-main-sections .card-body {
        padding: 1.25rem;
    }
</style>
