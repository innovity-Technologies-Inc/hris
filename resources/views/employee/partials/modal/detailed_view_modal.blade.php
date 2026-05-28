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
                <div id="detailedLoading" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Fetching employee details...</p>
                </div>

                <!-- Error State -->
                <div id="detailedError" class="text-center py-5 d-none">
                    <i class="mdi mdi-alert-circle text-danger fs-1"></i>
                    <p class="mt-2 text-dark fw-semibold" id="detailedErrorMessage">Failed to load employee details.</p>
                </div>

                <!-- Content State -->
                <div id="detailedContent" class="d-none">

                    <!-- 1. Header Information -->
                    <div class="card border shadow-none mb-3">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-2 text-center">
                                    <img id="detailed_photo" src="" alt="Profile Photo" class="img-fluid rounded-circle shadow-sm border" style="width: 120px; height: 120px; object-fit: cover;">
                                </div>
                                <div class="col-md-7 text-center text-md-start mt-3 mt-md-0">
                                    <h3 class="fw-bold text-dark mb-1" id="detailed_full_name"></h3>
                                    <p class="text-muted mb-2" id="detailed_ids"></p>
                                    <div class="d-flex flex-wrap gap-3 justify-content-center justify-content-md-start">
                                        <span class="small d-inline-flex align-items-center gap-1">
                                            <i class="mdi mdi-phone text-primary"></i>
                                            <span id="detailed_header_mobile" class="d-inline"></span>
                                        </span>
                                        <span class="small d-inline-flex align-items-center gap-1">
                                            <i class="mdi mdi-email text-primary"></i>
                                            <span id="detailed_header_email" class="d-inline"></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-3 text-center text-md-end mt-3 mt-md-0">
                                    <a id="downloadPdfBtn" href="" class="btn btn-danger btn-sm px-4">
                                        <i class="mdi mdi-file-pdf-box me-1"></i> Download PDF
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Personal Information -->
                    <div class="card border shadow-none mb-3">
                        <div class="card-header bg-light py-2">
                            <h6 class="mb-0 fw-bold">Personal Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-3"><label class="data-label">First Name</label><span class="data-value" id="detailed_first_name"></span></div>
                                <div class="col-md-3"><label class="data-label">Middle Name</label><span class="data-value" id="detailed_middle_name"></span></div>
                                <div class="col-md-3"><label class="data-label">Last Name</label><span class="data-value" id="detailed_last_name"></span></div>
                                <div class="col-md-3"><label class="data-label">Gender</label><span class="data-value" id="detailed_gender"></span></div>
                                <div class="col-md-3"><label class="data-label">Marital Status</label><span class="data-value" id="detailed_marital_status"></span></div>
                                <div class="col-md-3"><label class="data-label">Religion</label><span class="data-value" id="detailed_religion"></span></div>
                                <div class="col-md-3"><label class="data-label">Nationality</label><span class="data-value" id="detailed_nationality"></span></div>
                                <div class="col-md-3"><label class="data-label">Blood Group</label><span class="data-value" id="detailed_blood_group"></span></div>
                                <div class="col-md-3"><label class="data-label">Height</label><span class="data-value" id="detailed_height"></span></div>
                                <div class="col-md-3"><label class="data-label">Number of Children</label><span class="data-value" id="detailed_children_count"></span></div>
                                <div class="col-md-3"><label class="data-label">Father's Name</label><span class="data-value" id="detailed_father_name"></span></div>
                                <div class="col-md-3"><label class="data-label">Mother's Name</label><span class="data-value" id="detailed_mother_name"></span></div>
                                <div class="col-md-3"><label class="data-label">Spouse Name</label><span class="data-value" id="detailed_spouse_name"></span></div>
                                <div class="col-md-3"><label class="data-label">Status</label><span class="data-value" id="detailed_status"></span></div>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Birth & Identification Documents -->
                    <div class="card border shadow-none mb-3">
                        <div class="card-header bg-light py-2">
                            <h6 class="mb-0 fw-bold">Birth & Identification Documents</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-3"><label class="data-label">Date of Birth</label><span class="data-value" id="detailed_dob"></span></div>
                                <div class="col-md-3"><label class="data-label">Birth Country</label><span class="data-value" id="detailed_birth_country"></span></div>
                                <div class="col-md-3"><label class="data-label">Birth Registration No</label><span class="data-value" id="detailed_birth_reg_no"></span></div>
                                <div class="col-md-3"><label class="data-label">TIN Number</label><span class="data-value" id="detailed_tin"></span></div>
                                <div class="col-md-3"><label class="data-label">BGMEA ID</label><span class="data-value" id="detailed_bgmea_id"></span></div>
                                <div class="col-md-3"><label class="data-label">Residency ID / NID</label><span class="data-value" id="detailed_residency_id"></span></div>
                                <div class="col-md-3"><label class="data-label">Passport Number</label><span class="data-value" id="detailed_passport_no"></span></div>
                                <div class="col-md-3"><label class="data-label">Passport Expiry</label><span class="data-value" id="detailed_passport_expiry"></span></div>
                                <div class="col-md-3"><label class="data-label">Visa Expiry</label><span class="data-value" id="detailed_visa_expiry"></span></div>
                                <div class="col-md-3"><label class="data-label">Work Permit Expiry</label><span class="data-value" id="detailed_work_expiry"></span></div>
                                <div class="col-md-3"><label class="data-label">License Number</label><span class="data-value" id="detailed_license_no"></span></div>
                                <div class="col-md-3"><label class="data-label">License Expiry</label><span class="data-value" id="detailed_license_expiry"></span></div>
                            </div>
                        </div>
                    </div>

                    <!-- 4. Contact Information -->
                    <div class="card border shadow-none mb-3">
                        <div class="card-header bg-light py-2">
                            <h6 class="mb-0 fw-bold">Contact Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4"><label class="data-label">Personal Mobile</label><span class="data-value" id="detailed_personal_mobile"></span></div>
                                <div class="col-md-4"><label class="data-label">Home Phone</label><span class="data-value" id="detailed_home_phone"></span></div>
                                <div class="col-md-4"><label class="data-label">Personal Email</label><span class="data-value" id="detailed_personal_email"></span></div>
                                <div class="col-md-4"><label class="data-label">Work Mobile</label><span class="data-value" id="detailed_work_mobile"></span></div>
                                <div class="col-md-4"><label class="data-label">Work Phone</label><span class="data-value" id="detailed_work_phone"></span></div>
                                <div class="col-md-4"><label class="data-label">Work Email</label><span class="data-value" id="detailed_work_email"></span></div>
                            </div>
                        </div>
                    </div>

                    <!-- 5. Present Address -->
                    <div class="card border shadow-none mb-3">
                        <div class="card-header bg-light py-2">
                            <h6 class="mb-0 fw-bold">Present Address</h6>
                        </div>
                        <div class="card-body">
                            <div id="detailed_present_address_fields" class="row g-3"></div>
                        </div>
                    </div>

                    <!-- 6. Permanent Address -->
                    <div class="card border shadow-none mb-3">
                        <div class="card-header bg-light py-2">
                            <h6 class="mb-0 fw-bold">Permanent Address</h6>
                        </div>
                        <div class="card-body">
                            <div id="detailed_permanent_address_fields" class="row g-3"></div>
                        </div>
                    </div>

                    <!-- 7. Reference Information -->
                    <div class="card border shadow-none mb-3">
                        <div class="card-header bg-light py-2">
                            <h6 class="mb-0 fw-bold">Reference / Emergency Contact</h6>
                        </div>
                        <div class="card-body">
                            <div id="detailed_reference_address_fields" class="row g-3"></div>
                        </div>
                    </div>

                    <!-- 8. Office Information -->
                    <div id="section_office_info_card" class="card border shadow-none mb-3">
                        <div class="card-header bg-light py-2">
                            <h6 class="mb-0 fw-bold">Office Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-3"><label class="data-label">Employee Type</label><span class="data-value" id="detailed_emp_type"></span></div>
                                <div class="col-md-3"><label class="data-label">HR File Number</label><span class="data-value" id="detailed_hr_file_no"></span></div>
                                <div class="col-md-3"><label class="data-label">Pay Grade</label><span class="data-value" id="detailed_pay_grade"></span></div>
                                <div class="col-md-3"><label class="data-label">Act / Tofsil</label><span class="data-value" id="detailed_tofsil"></span></div>
                                <div class="col-md-12"><label class="data-label">File Note</label><span class="data-value" id="detailed_file_note"></span></div>
                                <div class="col-12"><hr class="my-1"></div>
                                <div class="col-md-4"><label class="data-label">Joining Company</label><span class="data-value" id="detailed_joining_company"></span></div>
                                <div class="col-md-4"><label class="data-label">Business Unit</label><span class="data-value" id="detailed_joining_bu"></span></div>
                                <div class="col-md-4"><label class="data-label">Division</label><span class="data-value" id="detailed_joining_division"></span></div>
                                <div class="col-md-4"><label class="data-label">Department</label><span class="data-value" id="detailed_joining_department"></span></div>
                                <div class="col-md-4"><label class="data-label">Section</label><span class="data-value" id="detailed_joining_section"></span></div>
                                <div class="col-md-4"><label class="data-label">Joining Designation</label><span class="data-value" id="detailed_joining_designation"></span></div>
                                <div class="col-md-4"><label class="data-label">Date of Join</label><span class="data-value" id="detailed_join_date"></span></div>
                                <div class="col-12"><hr class="my-1"></div>
                                <div class="col-md-4"><label class="data-label">Current Company</label><span class="data-value" id="detailed_current_company"></span></div>
                                <div class="col-md-4"><label class="data-label">Business Unit</label><span class="data-value" id="detailed_current_bu"></span></div>
                                <div class="col-md-4"><label class="data-label">Division</label><span class="data-value" id="detailed_current_division"></span></div>
                                <div class="col-md-4"><label class="data-label">Department</label><span class="data-value" id="detailed_current_department"></span></div>
                                <div class="col-md-4"><label class="data-label">Section</label><span class="data-value" id="detailed_current_section"></span></div>
                                <div class="col-md-4"><label class="data-label">Current Designation</label><span class="data-value" id="detailed_current_designation"></span></div>
                                <div class="col-12"><hr class="my-1"></div>
                                <div class="col-md-3"><label class="data-label">Orientation Required</label><span class="data-value" id="detailed_orientation_required"></span></div>
                                <div class="col-md-3"><label class="data-label">Orientation From</label><span class="data-value" id="detailed_orientation_from"></span></div>
                                <div class="col-md-3"><label class="data-label">Orientation To</label><span class="data-value" id="detailed_orientation_to"></span></div>
                                <div class="col-md-2"><label class="data-label">Orientation Type</label><span class="data-value" id="detailed_orientation_type"></span></div>
                                <div class="col-md-1"><label class="data-label">Days</label><span class="data-value" id="detailed_orientation_days"></span></div>
                                <div class="col-12"><hr class="my-1"></div>
                                <div class="col-md-3"><label class="data-label">Confirmation Date</label><span class="data-value" id="detailed_confirmation_date"></span></div>
                                <div class="col-md-3"><label class="data-label">Probation Duration</label><span class="data-value" id="detailed_probation"></span></div>
                                <div class="col-md-3"><label class="data-label">Next Promotion Date</label><span class="data-value" id="detailed_next_promotion"></span></div>
                                <div class="col-md-3"><label class="data-label">Promotion Cycle</label><span class="data-value" id="detailed_promotion_cycle"></span></div>
                                <div class="col-md-3"><label class="data-label">Increment Cycle</label><span class="data-value" id="detailed_increment_cycle"></span></div>
                                <div class="col-12"><hr class="my-1"></div>
                                <div class="col-md-3"><label class="data-label">Salary Type</label><span class="data-value" id="detailed_salary_type"></span></div>
                                <div class="col-md-3"><label class="data-label">Weekends</label><span class="data-value" id="detailed_weekends"></span></div>
                                <div class="col-md-3"><label class="data-label">Alternate Off Day</label><span class="data-value" id="detailed_alternate_off"></span></div>
                                <div class="col-12"><hr class="my-1"></div>
                                <div class="col-md-2"><label class="data-label">OT Allowed</label><span class="data-value" id="detailed_ot_allowed"></span></div>
                                <div class="col-md-2"><label class="data-label">PF Eligible</label><span class="data-value" id="detailed_pf_eligible"></span></div>
                                <div class="col-md-2"><label class="data-label">Transport Eligible</label><span class="data-value" id="detailed_transport_eligible"></span></div>
                                <div class="col-md-2"><label class="data-label">Gratuity Eligible</label><span class="data-value" id="detailed_gratuity_eligible"></span></div>
                                <div class="col-md-2"><label class="data-label">Can Apply Loan</label><span class="data-value" id="detailed_can_loan"></span></div>
                                <div class="col-md-2"><label class="data-label">Can Apply Advance</label><span class="data-value" id="detailed_can_advance"></span></div>
                                <div class="col-md-4"><label class="data-label">PF Effective Date</label><span class="data-value" id="detailed_pf_effective"></span></div>
                            </div>
                        </div>
                    </div>

                    <!-- 9. Education Information -->
                    <div id="section_education" class="card border shadow-none mb-3 d-none">
                        <div class="card-header bg-light py-2">
                            <h6 class="mb-0 fw-bold">Education Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3" id="detailed_education_body"></div>
                        </div>
                    </div>

                    <!-- 10. Training Information -->
                    <div id="section_training" class="card border shadow-none mb-3 d-none">
                        <div class="card-header bg-light py-2">
                            <h6 class="mb-0 fw-bold">Training Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3" id="detailed_training_body"></div>
                        </div>
                    </div>

                    <!-- 11. Employment History -->
                    <div id="section_history" class="card border shadow-none mb-3 d-none">
                        <div class="card-header bg-light py-2">
                            <h6 class="mb-0 fw-bold">Employment History</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3" id="detailed_history_body"></div>
                        </div>
                    </div>

                    <!-- 12. Nominee Information -->
                    <div id="section_nominee" class="card border shadow-none mb-3 d-none">
                        <div class="card-header bg-light py-2">
                            <h6 class="mb-0 fw-bold">Nominee Information</h6>
                        </div>
                        <div class="card-body" id="detailed_nominee_body"></div>
                    </div>

                    <!-- 13. Bank Account Information -->
                    <div id="section_bank" class="card border shadow-none mb-3 d-none">
                        <div class="card-header bg-light py-2">
                            <h6 class="mb-0 fw-bold">Accounts Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4"><label class="data-label">Bank Name</label><span class="data-value" id="detailed_bank_name"></span></div>
                                <div class="col-md-4"><label class="data-label">Branch Name</label><span class="data-value" id="detailed_bank_branch"></span></div>
                                <div class="col-md-4"><label class="data-label">Account Holder</label><span class="data-value" id="detailed_account_name"></span></div>
                                <div class="col-md-4"><label class="data-label">Account Number</label><span class="data-value" id="detailed_account_number"></span></div>
                                <div class="col-md-4"><label class="data-label">Bank Status</label><span class="data-value" id="detailed_bank_status"></span></div>
                                <div class="col-md-4"><label class="data-label">Remarks</label><span class="data-value" id="detailed_bank_remarks"></span></div>
                            </div>
                        </div>
                    </div>

                    <!-- 14. Salary Breakdown -->
                    <div id="section_salary" class="card border shadow-none mb-3 d-none">
                        <div class="card-header bg-light py-2">
                            <h6 class="mb-0 fw-bold">Salary Breakdown</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3" id="detailed_salary_body"></div>
                        </div>
                    </div>

                    <!-- 15. Policies & Current Plans -->
                    <div class="card border shadow-none mb-3">
                        <div class="card-header bg-light py-2">
                            <h6 class="mb-0 fw-bold">Policies & Current Plans</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6 border-end">
                                    <label class="data-label">Active Policies</label>
                                    <div id="detailed_policy_body"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="data-label">Current Assigned Plans</label>
                                    <div id="detailed_plans_body"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 16. Leave Information -->
                    <div class="card border shadow-none mb-3">
                        <div class="card-header bg-light py-2">
                            <h6 class="mb-0 fw-bold">Leave Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6 border-end">
                                    <label class="data-label">Leave Balances</label>
                                    <div id="detailed_leave_info_body"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="data-label">Recent Leave History</label>
                                    <div id="detailed_leave_history_body"></div>
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
    #detailedViewModalBody label.data-label {
        color: #974063 !important;
        font-weight: 600;
        font-size: 0.7rem;
        margin-bottom: 2px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: block;
    }
    #detailedViewModalBody span.data-value {
        display: block;
        color: #333;
        font-weight: 500;
        font-size: 0.85rem;
    }
    /* Fixed Icon Alignment: Ensure icons and text stay side-by-side in header */
    #detailedViewModalBody .row .card-body .row .col-md-7 .d-inline-flex {
        display: inline-flex !important;
        align-items: center;
        gap: 0.5rem;
    }
    #detailedViewModalBody .row .card-body .row .col-md-7 span.d-inline {
        display: inline !important;
    }
    .card-body {
        padding: 1.25rem;
    }
    .badge-soft-success {
        background-color: rgba(40, 167, 69, 0.1);
        color: #28a745;
    }
    .badge-soft-danger {
        background-color: rgba(220, 53, 69, 0.1);
        color: #dc3545;
    }
</style>
