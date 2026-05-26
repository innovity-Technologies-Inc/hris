<!-- Summary View Modal -->
<div class="modal fade" id="summaryViewModal" tabindex="-1" aria-labelledby="summaryViewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content glass-modal">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold text-dark" id="summaryViewModalLabel">
                    <i class="mdi mdi-text-box-search-outline me-2"></i>Employee Profile Summary
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="summaryViewModalBody">
                <!-- Loading State -->
                <div id="summaryLoading" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Generating profile summary...</p>
                </div>

                <!-- Error State -->
                <div id="summaryError" class="text-center py-5 d-none">
                    <i class="mdi mdi-alert-circle text-danger fs-1"></i>
                    <p class="mt-2 text-dark fw-semibold" id="summaryErrorMessage">Failed to load summary.</p>
                </div>

                <!-- Content State -->
                <div id="summaryContent" class="d-none">
                    <div class="row g-3">
                        <!-- 1. Basic Summary Info -->
                        <div class="col-12">
                            <div class="card border shadow-none">
                                <div class="card-body bg-light-subtle rounded">
                                    <div class="row align-items-center">
                                        <div class="col-md-1 text-center">
                                            <img id="summary_photo" src="" alt="Photo" class="img-fluid rounded-circle border" style="width: 60px; height: 60px; object-fit: cover;">
                                        </div>
                                        <div class="col-md-11">
                                            <h4 class="fw-bold text-dark mb-0" id="summary_full_name"></h4>
                                            <p class="text-muted mb-0 small" id="summary_basic_identifiers"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 2. Office Information (Current Only) -->
                        <div class="col-md-6">
                            <div class="card border shadow-none h-100">
                                <div class="card-header bg-light py-2"><h6 class="mb-0 fw-bold small text-uppercase">Current Office Information</h6></div>
                                <div class="card-body">
                                    <div class="mb-2"><label class="data-label">Current Company</label><span class="data-value fw-bold" id="summary_current_company"></span></div>
                                    <div class="mb-2"><label class="data-label">Department / Section</label><span class="data-value" id="summary_current_dept_section"></span></div>
                                    <div class="mb-2"><label class="data-label">Current Designation</label><span class="data-value" id="summary_current_designation"></span></div>
                                    <div class="mb-0"><label class="data-label">Join Date</label><span class="data-value" id="summary_join_date"></span></div>
                                </div>
                            </div>
                        </div>

                        <!-- 3. Compensation & Career -->
                        <div class="col-md-6">
                            <div class="card border shadow-none h-100">
                                <div class="card-header bg-light py-2"><h6 class="mb-0 fw-bold small text-uppercase">Financial & Experience Overview</h6></div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="data-label">Gross Salary</label>
                                        <span class="data-value fw-bold fs-4 text-primary" id="summary_gross_salary"></span>
                                    </div>
                                    <div class="mb-0">
                                        <label class="data-label text-dark">Total Professional Experience</label>
                                        <span class="data-value fw-bold fs-5 text-dark" id="summary_total_experience"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 4. Education (First Record Only) -->
                        <div class="col-md-6">
                            <div class="card border shadow-none h-100">
                                <div class="card-header bg-light py-2"><h6 class="mb-0 fw-bold small text-uppercase">Educational Qualification</h6></div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-soft-info rounded p-2 me-3">
                                            <i class="mdi mdi-school fs-20 text-info"></i>
                                        </div>
                                        <div>
                                            <label class="data-label">Highest / First Education</label>
                                            <span class="data-value fw-bold" id="summary_education_title"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 5. Trainings (Titles Only) -->
                        <div class="col-md-6">
                            <div class="card border shadow-none h-100">
                                <div class="card-header bg-light py-2"><h6 class="mb-0 fw-bold small text-uppercase">Professional Trainings</h6></div>
                                <div class="card-body">
                                    <div id="summary_training_titles" class="d-flex flex-wrap gap-2"></div>
                                </div>
                            </div>
                        </div>

                        <!-- 6. Nominee (Name & Relation Only) -->
                        <div class="col-12">
                            <div class="card border shadow-none">
                                <div class="card-header bg-light py-2"><h6 class="mb-0 fw-bold small text-uppercase">Nominee Summary</h6></div>
                                <div class="card-body py-2">
                                    <div class="row align-items-center">
                                        <div class="col-md-6 border-end">
                                            <label class="data-label">Nominee Name</label>
                                            <span class="data-value fw-bold" id="summary_nominee_name"></span>
                                        </div>
                                        <div class="col-md-6 ps-md-4">
                                            <label class="data-label">Relationship</label>
                                            <span class="data-value fw-bold" id="summary_nominee_relation"></span>
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
    #summaryViewModalBody .data-label {
        color: #974063 !important;
        font-weight: 600;
        font-size: 0.65rem;
        margin-bottom: 1px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: block;
    }
    #summaryViewModalBody .data-value {
        display: block;
        color: #333;
        font-weight: 500;
        font-size: 0.9rem;
    }
    .bg-soft-info {
        background-color: rgba(64, 187, 231, 0.1);
    }
</style>
