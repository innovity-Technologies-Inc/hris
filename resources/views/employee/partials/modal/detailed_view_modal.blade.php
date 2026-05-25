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
                    <!-- 1. Header Information Card -->
                    <div class="row">
                        <div class="col-12">
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
                                                <span class="small"><i class="mdi mdi-phone text-primary me-1"></i><span id="detailed_header_mobile"></span></span>
                                                <span class="small"><i class="mdi mdi-email text-primary me-1"></i><span id="detailed_header_email"></span></span>
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
                        </div>
                    </div>

                    <div class="hando-main-sections">
                        
                        <!-- 2. Personal Information (Full Row Card) -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card border shadow-none mb-3">
                                    <div class="card-header bg-light py-2">
                                        <h6 class="mb-0 fw-bold">Personal Information</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-3"><label>First Name</label><span id="detailed_first_name"></span></div>
                                            <div class="col-md-3"><label>Middle Name</label><span id="detailed_middle_name"></span></div>
                                            <div class="col-md-3"><label>Last Name</label><span id="detailed_last_name"></span></div>
                                            <div class="col-md-3"><label>Gender</label><span id="detailed_gender"></span></div>
                                            <div class="col-md-3"><label>Marital Status</label><span id="detailed_marital_status"></span></div>
                                            <div class="col-md-3"><label>Religion</label><span id="detailed_religion"></span></div>
                                            <div class="col-md-3"><label>Nationality</label><span id="detailed_nationality"></span></div>
                                            <div class="col-md-3"><label>Blood Group</label><span id="detailed_blood_group"></span></div>
                                            <div class="col-md-3"><label>Height</label><span id="detailed_height"></span></div>
                                            <div class="col-md-3"><label>Number of Children</label><span id="detailed_children_count"></span></div>
                                            <div class="col-md-3"><label>Father's Name</label><span id="detailed_father_name"></span></div>
                                            <div class="col-md-3"><label>Mother's Name</label><span id="detailed_mother_name"></span></div>
                                            <div class="col-md-3"><label>Spouse Name</label><span id="detailed_spouse_name"></span></div>
                                            <div class="col-md-3"><label>Status</label><span id="detailed_status"></span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 3. Birth & Identification Documents (Full Row Card) -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card border shadow-none mb-3">
                                    <div class="card-header bg-light py-2">
                                        <h6 class="mb-0 fw-bold">Birth & Identification Documents</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-3"><label>Date of Birth</label><span id="detailed_dob"></span></div>
                                            <div class="col-md-3"><label>Birth Country</label><span id="detailed_birth_country"></span></div>
                                            <div class="col-md-3"><label>Birth Registration No</label><span id="detailed_birth_reg_no"></span></div>
                                            <div class="col-md-3"><label>TIN Number</label><span id="detailed_tin"></span></div>
                                            <div class="col-md-3"><label>BGMEA ID</label><span id="detailed_bgmea_id"></span></div>
                                            <div class="col-md-3"><label>Residency ID / NID</label><span id="detailed_residency_id"></span></div>
                                            <div class="col-md-3"><label>Passport Number</label><span id="detailed_passport_no"></span></div>
                                            <div class="col-md-3"><label>Passport Expiry</label><span id="detailed_passport_expiry"></span></div>
                                            <div class="col-md-3"><label>Visa Expiry</label><span id="detailed_visa_expiry"></span></div>
                                            <div class="col-md-3"><label>Work Permit Expiry</label><span id="detailed_work_expiry"></span></div>
                                            <div class="col-md-3"><label>License Number</label><span id="detailed_license_no"></span></div>
                                            <div class="col-md-3"><label>License Expiry</label><span id="detailed_license_expiry"></span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 4. Contact Information (Full Row Card) -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card border shadow-none mb-3">
                                    <div class="card-header bg-light py-2">
                                        <h6 class="mb-0 fw-bold">Contact Information</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-4"><label>Personal Mobile</label><span id="detailed_personal_mobile"></span></div>
                                            <div class="col-md-4"><label>Home Phone</label><span id="detailed_home_phone"></span></div>
                                            <div class="col-md-4"><label>Personal Email</label><span id="detailed_personal_email"></span></div>
                                            <div class="col-md-4"><label>Work Mobile</label><span id="detailed_work_mobile"></span></div>
                                            <div class="col-md-4"><label>Work Phone</label><span id="detailed_work_phone"></span></div>
                                            <div class="col-md-4"><label>Work Email</label><span id="detailed_work_email"></span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 5. Present Address (Full Row Card) -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card border shadow-none mb-3">
                                    <div class="card-header bg-light py-2">
                                        <h6 class="mb-0 fw-bold">Present Address</h6>
                                    </div>
                                    <div class="card-body">
                                        <div id="detailed_present_address_fields" class="row g-3"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 6. Permanent Address (Full Row Card) -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card border shadow-none mb-3">
                                    <div class="card-header bg-light py-2">
                                        <h6 class="mb-0 fw-bold">Permanent Address</h6>
                                    </div>
                                    <div class="card-body">
                                        <div id="detailed_permanent_address_fields" class="row g-3"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 7. Reference Information (Full Row Card) -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card border shadow-none mb-3">
                                    <div class="card-header bg-light py-2">
                                        <h6 class="mb-0 fw-bold">Reference / Emergency Contact</h6>
                                    </div>
                                    <div class="card-body">
                                        <div id="detailed_reference_address_fields" class="row g-3"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 8. Office Information (Full Row Card) -->
                        <div class="row">
                            <div class="col-12">
                                <div id="section_office_info_card" class="card border shadow-none mb-3">
                                    <div class="card-header bg-light py-2">
                                        <h6 class="mb-0 fw-bold">Office Information</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <!-- Payroll Info -->
                                            <div class="col-md-3"><label>Employee Type</label><span id="detailed_emp_type"></span></div>
                                            <div class="col-md-3"><label>HR File Number</label><span id="detailed_hr_file_no"></span></div>
                                            <div class="col-md-3"><label>Pay Grade</label><span id="detailed_pay_grade"></span></div>
                                            <div class="col-md-3"><label>Act / Tofsil</label><span id="detailed_tofsil"></span></div>
                                            <div class="col-md-12"><label>File Note</label><span id="detailed_file_note"></span></div>
                                            <div class="col-12"><hr class="my-1"></div>
                                            <div class="col-md-4"><label>Joining Company</label><span id="detailed_joining_company"></span></div>
                                            <div class="col-md-4"><label>Business Unit</label><span id="detailed_joining_bu"></span></div>
                                            <div class="col-md-4"><label>Division</label><span id="detailed_joining_division"></span></div>
                                            <div class="col-md-4"><label>Department</label><span id="detailed_joining_department"></span></div>
                                            <div class="col-md-4"><label>Section</label><span id="detailed_joining_section"></span></div>
                                            <div class="col-md-4"><label>Joining Designation</label><span id="detailed_joining_designation"></span></div>
                                            <div class="col-md-4"><label>Date of Join</label><span id="detailed_join_date"></span></div>
                                            <div class="col-12"><hr class="my-1"></div>
                                            <div class="col-md-4"><label>Current Company</label><span id="detailed_current_company"></span></div>
                                            <div class="col-md-4"><label>Business Unit</label><span id="detailed_current_bu"></span></div>
                                            <div class="col-md-4"><label>Division</label><span id="detailed_current_division"></span></div>
                                            <div class="col-md-4"><label>Department</label><span id="detailed_current_department"></span></div>
                                            <div class="col-md-4"><label>Section</label><span id="detailed_current_section"></span></div>
                                            <div class="col-md-4"><label>Current Designation</label><span id="detailed_current_designation"></span></div>
                                            <div class="col-12"><hr class="my-1"></div>
                                            <div class="col-md-3"><label>Orientation Required</label><span id="detailed_orientation_required"></span></div>
                                            <div class="col-md-3"><label>Orientation From</label><span id="detailed_orientation_from"></span></div>
                                            <div class="col-md-3"><label>Orientation To</label><span id="detailed_orientation_to"></span></div>
                                            <div class="col-md-2"><label>Orientation Type</label><span id="detailed_orientation_type"></span></div>
                                            <div class="col-md-1"><label>Days</label><span id="detailed_orientation_days"></span></div>
                                            <div class="col-12"><hr class="my-1"></div>
                                            <div class="col-md-3"><label>Confirmation Date</label><span id="detailed_confirmation_date"></span></div>
                                            <div class="col-md-3"><label>Probation Duration</label><span id="detailed_probation"></span></div>
                                            <div class="col-md-3"><label>Next Promotion Date</label><span id="detailed_next_promotion"></span></div>
                                            <div class="col-md-3"><label>Promotion Cycle</label><span id="detailed_promotion_cycle"></span></div>
                                            <div class="col-md-3"><label>Increment Cycle</label><span id="detailed_increment_cycle"></span></div>
                                            <div class="col-12"><hr class="my-1"></div>
                                            <div class="col-md-3"><label>Salary Type</label><span id="detailed_salary_type"></span></div>
                                            <div class="col-md-3"><label>Weekends</label><span id="detailed_weekends"></span></div>
                                            <div class="col-md-3"><label>Alternate Off Day</label><span id="detailed_alternate_off"></span></div>
                                            <div class="col-12"><hr class="my-1"></div>
                                            <div class="col-md-2"><label>OT Allowed</label><span id="detailed_ot_allowed"></span></div>
                                            <div class="col-md-2"><label>PF Eligible</label><span id="detailed_pf_eligible"></span></div>
                                            <div class="col-md-2"><label>Transport Eligible</label><span id="detailed_transport_eligible"></span></div>
                                            <div class="col-md-2"><label>Gratuity Eligible</label><span id="detailed_gratuity_eligible"></span></div>
                                            <div class="col-md-2"><label>Can Apply Loan</label><span id="detailed_can_loan"></span></div>
                                            <div class="col-md-2"><label>Can Apply Advance</label><span id="detailed_can_advance"></span></div>
                                            <div class="col-md-4"><label>PF Effective Date</label><span id="detailed_pf_effective"></span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 9. Education Information (Full Row Card) -->
                        <div class="row">
                            <div class="col-12">
                                <div id="section_education" class="card border shadow-none mb-3 d-none">
                                    <div class="card-header bg-light py-2">
                                        <h6 class="mb-0 fw-bold">Education Information</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3" id="detailed_education_body"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 10. Training Information (Full Row Card) -->
                        <div class="row">
                            <div class="col-12">
                                <div id="section_training" class="card border shadow-none mb-3 d-none">
                                    <div class="card-header bg-light py-2">
                                        <h6 class="mb-0 fw-bold">Training Information</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3" id="detailed_training_body"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 11. Employment History (Full Row Card) -->
                        <div class="row">
                            <div class="col-12">
                                <div id="section_history" class="card border shadow-none mb-3 d-none">
                                    <div class="card-header bg-light py-2">
                                        <h6 class="mb-0 fw-bold">Employment History</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3" id="detailed_history_body"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 12. Nominee Information (Full Row Card) -->
                        <div class="row">
                            <div class="col-12">
                                <div id="section_nominee" class="card border shadow-none mb-3 d-none">
                                    <div class="card-header bg-light py-2">
                                        <h6 class="mb-0 fw-bold">Nominee Information</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3" id="detailed_nominee_body"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 13. Bank Account Information (Full Row Card) -->
                        <div class="row">
                            <div class="col-12">
                                <div id="section_bank" class="card border shadow-none mb-3 d-none">
                                    <div class="card-header bg-light py-2">
                                        <h6 class="mb-0 fw-bold">Accounts Information</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-4"><label>Bank Name</label><span id="detailed_bank_name"></span></div>
                                            <div class="col-md-4"><label>Branch Name</label><span id="detailed_bank_branch"></span></div>
                                            <div class="col-md-4"><label>Account Holder</label><span id="detailed_account_name"></span></div>
                                            <div class="col-md-4"><label>Account Number</label><span id="detailed_account_number"></span></div>
                                            <div class="col-md-4"><label>Bank Status</label><span id="detailed_bank_status"></span></div>
                                            <div class="col-md-4"><label>Remarks</label><span id="detailed_bank_remarks"></span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 14. Salary Breakdown (Full Row Card) -->
                        <div class="row">
                            <div class="col-12">
                                <div id="section_salary" class="card border shadow-none mb-3 d-none">
                                    <div class="card-header bg-light py-2">
                                        <h6 class="mb-0 fw-bold">Salary Breakdown</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3" id="detailed_salary_body"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 15. Policy Tags & Current Plans (Full Row Card) -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card border shadow-none mb-3">
                                    <div class="card-header bg-light py-2">
                                        <h6 class="mb-0 fw-bold">Policies & Current Plans</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label>Active Policies</label>
                                                <div id="detailed_policy_body"></div>
                                            </div>
                                            <div class="col-12">
                                                <label>Current Assigned Plans</label>
                                                <div id="detailed_plans_body"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 16. Leave Balance & History (Full Row Card) -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card border shadow-none mb-3">
                                    <div class="card-header bg-light py-2">
                                        <h6 class="mb-0 fw-bold">Leave Information</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label>Leave Balances</label>
                                                <div id="detailed_leave_info_body"></div>
                                            </div>
                                            <div class="col-12">
                                                <label>Recent Leave History</label>
                                                <div id="detailed_leave_history_body"></div>
                                            </div>
                                        </div>
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
        font-size: 0.7rem;
        margin-bottom: 2px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .hando-main-sections span {
        display: block;
        color: #333;
        font-weight: 500;
        font-size: 0.85rem;
    }
    .hando-main-sections .card-body {
        padding: 1.25rem;
    }
    .last-child-no-border:last-child {
        border-bottom: none !important;
        margin-bottom: 0 !important;
        padding-bottom: 0 !important;
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
