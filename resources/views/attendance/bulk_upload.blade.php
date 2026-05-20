@extends('structure.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border">

                    <!-- Header -->
                    <div class="card-header border-bottom py-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 rounded p-2 me-3">
                                    <i class="bi bi-file-earmark-arrow-up fs-3 text-primary"></i>
                                </div>
                                <div>
                                    <h4 class="mb-1 fw-bold">Bulk Attendance Upload</h4>
                                    <p class="mb-0 text-muted small">
                                        Upload multiple attendance records at once
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="card-body p-4">

                        <form id="bulkAttendanceUploadForm" action="{{ route('attendance.import') }}"
                            enctype="multipart/form-data" method="POST">
                            @csrf

                            <!-- Information Alert -->
                            <div class="alert alert-info border-0 shadow-sm d-flex align-items-start mb-4" role="alert">
                                <i class="bi bi-info-circle fs-4 me-3 mt-1"></i>
                                <div>
                                    <h6 class="alert-heading fw-bold mb-2">Upload Instructions</h6>
                                    <p class="mb-2">Please follow the bulk attendance upload format carefully to ensure
                                        successful data import.</p>
                                    <ul class="mb-0 ps-3 small">
                                        <li>Download the format template before uploading</li>
                                        <li>Do not modify column headers</li>
                                        <li>Ensure all required fields are filled (Employee ID, Clock In, Clock Out)</li>
                                        <li>Use proper datetime format: YYYY-MM-DD HH:MM:SS or YYYY-MM-DD HH:MM</li>
                                        <li>Clock Out time must be after Clock In time</li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Download Format Section -->
                            <div class="card border-0 mb-4 bg-light">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                                        <div>
                                            <h6 class="fw-semibold mb-2">
                                                <i class="bi bi-download text-primary me-2"></i>Download Upload Format
                                            </h6>
                                            <p class="text-muted small mb-0">Get the official template to fill attendance
                                                records
                                            </p>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <a href="{{ asset('assets/excel/attendance_bulk.xlsx') }}"
                                                class="btn btn-success text-decoration-none" id="downloadExcel">
                                                <i class="bi bi-file-earmark-excel me-1"></i> Excel Format
                                            </a>
                                            <a href="{{ asset('assets/csv/attendance_bulk.csv') }}"
                                                class="btn btn-info text-white text-decoration-none" id="downloadCsv">
                                                <i class="bi bi-file-earmark-text me-1"></i> CSV Format
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sample Data Preview -->
                            <div class="card border mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0 fw-semibold">
                                        <i class="bi bi-table me-2"></i>Sample Format Preview
                                    </h6>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="small">Employee ID</th>
                                                    <th class="small">Clock In</th>
                                                    <th class="small">Clock Out</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="small">EMP-2024-001</td>
                                                    <td class="small">2024-12-14 09:00:00</td>
                                                    <td class="small">2024-12-14 17:30:00</td>
                                                </tr>
                                                <tr>
                                                    <td class="small">EMP-2024-002</td>
                                                    <td class="small">2024-12-14 08:45:00</td>
                                                    <td class="small">2024-12-14 17:15:00</td>
                                                </tr>
                                                <tr>
                                                    <td class="small">EMP-2024-003</td>
                                                    <td class="small">2024-12-14 09:15:00</td>
                                                    <td class="small">2024-12-14 18:00:00</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- File Upload Section -->
                            <div class="mb-4">
                                <label for="fileUpload" class="form-label fw-semibold">
                                    <i class="bi bi-upload me-2"></i>Upload File <span class="text-danger">*</span>
                                </label>
                                <div class="border-2 border-dashed rounded-3 p-5 text-center bg-body-secondary"
                                    style="cursor: pointer;" id="dropZone">
                                    <i class="bi bi-cloud-upload text-primary mb-3" style="font-size: 3rem;"></i>
                                    <h6 class="fw-semibold mb-2">Drag and drop your file here</h6>
                                    <p class="text-muted small mb-3">or click to browse</p>
                                    <input type="file" class="form-control w-auto mx-auto" id="fileUpload"
                                        accept=".csv,.xlsx,.xls" name="file" required>
                                    <p class="text-muted small mt-3 mb-0">Supported formats: Excel (.xlsx, .xls) and CSV
                                        (.csv)
                                    </p>
                                </div>
                                @error('file')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- File Info Display (Hidden by default, shown when file is selected) -->
                            <div class="card border-success d-none mb-4" id="fileInfo">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-file-earmark-check text-success fs-4 me-3"></i>
                                            <div>
                                                <p class="mb-0 fw-semibold" id="fileName">attendance_records.xlsx</p>
                                                <small class="text-muted" id="fileSize">2.5 MB</small>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-danger" id="removeFile">
                                            <i class="bi bi-x-lg"></i> Remove
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Validation Options -->
                            <div class="card border mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0 fw-semibold">
                                        <i class="bi bi-shield-check me-2"></i>Validation Options
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="skipDuplicates"
                                            name="skip_duplicates" checked>
                                        <label class="form-check-label" for="skipDuplicates">
                                            Skip duplicate records (same employee and date)
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="validateTiming"
                                            name="validate_timing" checked>
                                        <label class="form-check-label" for="validateTiming">
                                            Validate clock-in/out timing logic
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Section -->
                            <div class="d-flex justify-content-end gap-2 border-top pt-3">
                                <a href="{{ route('attendance.create') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle me-1"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-primary shadow-sm">
                                    <i class="bi bi-upload me-1"></i>Upload & Process
                                </button>
                            </div>

                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle file selection
            const fileUploadInput = document.getElementById('fileUpload');
            const fileInfo = document.getElementById('fileInfo');
            const fileName = document.getElementById('fileName');
            const fileSize = document.getElementById('fileSize');
            const dropZone = document.getElementById('dropZone');

            if (fileUploadInput) {
                fileUploadInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        fileName.textContent = file.name;
                        fileSize.textContent = (file.size / (1024 * 1024)).toFixed(2) + ' MB';
                        fileInfo.classList.remove('d-none');
                    }
                });
            }

            // Handle file removal
            const removeFileBtn = document.getElementById('removeFile');
            if (removeFileBtn) {
                removeFileBtn.addEventListener('click', function() {
                    fileUploadInput.value = '';
                    fileInfo.classList.add('d-none');
                });
            }

            // Drag and drop functionality
            if (dropZone) {
                dropZone.addEventListener('click', function() {
                    fileUploadInput.click();
                });

                dropZone.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    dropZone.classList.add('border-primary', 'bg-primary', 'bg-opacity-10');
                });

                dropZone.addEventListener('dragleave', function(e) {
                    e.preventDefault();
                    dropZone.classList.remove('border-primary', 'bg-primary', 'bg-opacity-10');
                });

                dropZone.addEventListener('drop', function(e) {
                    e.preventDefault();
                    dropZone.classList.remove('border-primary', 'bg-primary', 'bg-opacity-10');

                    const files = e.dataTransfer.files;
                    if (files.length > 0) {
                        fileUploadInput.files = files;
                        const event = new Event('change', {
                            bubbles: true
                        });
                        fileUploadInput.dispatchEvent(event);
                    }
                });
            }
        });
    </script>
@endpush

