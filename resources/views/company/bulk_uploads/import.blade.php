<!-- Bulk Upload Modal -->
<div class="modal fade" id="bulkUploadModal" tabindex="-1" aria-labelledby="bulkUploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <!-- Modal Header -->
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title fw-semibold" id="bulkUploadModalLabel">
                    <i class="mdi mdi-file-upload-outline me-2"></i>Bulk Company Upload - <span
                        id="activeSectionName">Groups</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form id="bulkUploadForm" action="" enctype="multipart/form-data" method="POST">
                @csrf

                <!-- Hidden input to store selected section -->
                <input type="hidden" name="section_type" id="sectionType" value="">

                <!-- Modal Body -->
                <div class="modal-body p-4">

                    <!-- Active Section Badge -->
                    <div class="alert alert-info border-0 shadow-sm mb-3" id="activeSectionBadge">
                        <div class="d-flex align-items-center">
                            <span class="badge bg-info rounded-circle me-3 fs-6" id="activeSectionNumber">1</span>
                            <div>
                                <strong>Currently Uploading For:</strong>
                                <span id="activeSectionText">Groups</span>
                            </div>
                        </div>
                    </div>

                    <!-- Danger Alert -->
                    <div class="alert alert-danger border-0 shadow-sm d-flex align-items-start" role="alert">
                        <i class="mdi mdi-alert-circle-outline fs-4 me-3 mt-1"></i>
                        <div>
                            <h6 class="alert-heading fw-bold mb-2">Important Instructions</h6>
                            <p class="mb-2">Please follow the bulk company upload format carefully to ensure
                                successful
                                data import.</p>
                            <ul class="mb-0 ps-3">
                                <li>Download the format template before uploading</li>
                                <li>Do not modify column headers</li>
                                <li>Ensure all required fields are filled</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Download Format Section -->
                    <div class="card bg-light border-0 mb-4">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                                <div>
                                    <h6 class="fw-semibold mb-2">
                                        <i class="mdi mdi-download-circle-outline text-primary me-2"></i>Download Upload
                                        Format
                                    </h6>
                                    <p class="text-muted small mb-0">Get the official template to fill company
                                        information
                                    </p>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="#" class="btn btn-success text-decoration-none" id="downloadExcel">
                                        <i class="mdi mdi-file-excel me-1"></i> Excel Format
                                    </a>
                                    <a href="#" class="btn btn-info text-white text-decoration-none"
                                        id="downloadCsv">
                                        <i class="mdi mdi-file-delimited me-1"></i> CSV Format
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- File Upload Section -->
                    <div class="mb-3">
                        <label for="fileUpload" class="form-label fw-semibold">
                            <i class="mdi mdi-file-upload me-2"></i>Upload File
                        </label>
                        <div class="border border-2 border-dashed rounded-3 p-4 text-center bg-light">
                            <i class="mdi mdi-cloud-upload-outline text-primary mb-3" style="font-size: 3rem;"></i>
                            <h6 class="fw-semibold mb-2">Drag and drop your file here</h6>
                            <p class="text-muted small mb-3">or click to browse</p>
                            <input type="file" class="form-control w-auto mx-auto" id="fileUpload"
                                accept=".csv,.xlsx,.xls" name="file" required>
                            <p class="text-muted small mt-3 mb-0">Supported formats: Excel (.xlsx, .xls) and CSV (.csv)
                            </p>
                        </div>
                        @error('file')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- File Info Display (Hidden by default, shown when file is selected) -->
                    <div class="card border-success d-none" id="fileInfo">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <i class="mdi mdi-file-check-outline text-success fs-4 me-3"></i>
                                    <div>
                                        <p class="mb-0 fw-semibold" id="fileName">company_data.xlsx</p>
                                        <small class="text-muted" id="fileSize">2.5 MB</small>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-danger" id="removeFile">
                                    <i class="mdi mdi-close"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="mdi mdi-close-circle-outline me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary shadow-sm">
                        <i class="mdi mdi-upload me-1"></i>Upload & Process
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Handle modal show event to update active section
    document.getElementById('bulkUploadModal').addEventListener('show.bs.modal', function(event) {
        // Button that triggered the modal
        const button = event.relatedTarget;

        // Extract info from data-* attributes
        const section = button.getAttribute('data-section');
        const sectionName = button.getAttribute('data-section-name');
        const sectionNumber = button.getAttribute('data-section-number');
        const formAction = button.getAttribute('data-form-action');
        const excelLink = button.getAttribute('data-excel-link');
        const csvLink = button.getAttribute('data-csv-link');

        // Update modal content
        document.getElementById('activeSectionName').textContent = sectionName;
        document.getElementById('activeSectionText').textContent = sectionName;
        document.getElementById('activeSectionNumber').textContent = sectionNumber;
        document.getElementById('sectionType').value = section;

        // Update form action dynamically
        document.getElementById('bulkUploadForm').action = formAction;

        // Update download links with direct URLs
        document.getElementById('downloadExcel').href = excelLink;
        document.getElementById('downloadCsv').href = csvLink;
    });

    // Handle file selection
    document.getElementById('fileUpload').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            document.getElementById('fileName').textContent = file.name;
            document.getElementById('fileSize').textContent = (file.size / (1024 * 1024)).toFixed(2) + ' MB';
            document.getElementById('fileInfo').classList.remove('d-none');
        }
    });

    // Handle file removal
    document.getElementById('removeFile').addEventListener('click', function() {
        document.getElementById('fileUpload').value = '';
        document.getElementById('fileInfo').classList.add('d-none');
    });

    // Reset form when modal is closed
    document.getElementById('bulkUploadModal').addEventListener('hidden.bs.modal', function() {
        document.getElementById('fileUpload').value = '';
        document.getElementById('fileInfo').classList.add('d-none');
    });
</script>

