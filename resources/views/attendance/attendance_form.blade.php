@extends('structure.master')

@section('content')
    @php
        // ========== SAMPLE EMPLOYEE DATA ==========
        $employees = [
            (object) [
                'id' => 1,
                'employee_id' => 'EMP-2024-001',
                'name' => 'Mohammad Rahman',
                'designation' => 'Senior Software Engineer',
                'department' => 'Engineering',
            ],
            (object) [
                'id' => 2,
                'employee_id' => 'EMP-2024-002',
                'name' => 'Fatima Ahmed',
                'designation' => 'HR Manager',
                'department' => 'Human Resources',
            ],
            (object) [
                'id' => 3,
                'employee_id' => 'EMP-2024-003',
                'name' => 'Karim Hassan',
                'designation' => 'Financial Analyst',
                'department' => 'Finance',
            ],
            (object) [
                'id' => 4,
                'employee_id' => 'EMP-2024-004',
                'name' => 'Ayesha Khan',
                'designation' => 'Marketing Executive',
                'department' => 'Marketing',
            ],
            (object) [
                'id' => 5,
                'employee_id' => 'EMP-2024-005',
                'name' => 'Abdullah Islam',
                'designation' => 'Sales Manager',
                'department' => 'Sales',
            ],
            (object) [
                'id' => 6,
                'employee_id' => 'EMP-2024-006',
                'name' => 'Nadia Sultana',
                'designation' => 'Project Manager',
                'department' => 'Operations',
            ],
            (object) [
                'id' => 7,
                'employee_id' => 'EMP-2024-007',
                'name' => 'Mahmudul Hasan',
                'designation' => 'Business Analyst',
                'department' => 'Business Development',
            ],
            (object) [
                'id' => 8,
                'employee_id' => 'EMP-2024-008',
                'name' => 'Rukhsana Begum',
                'designation' => 'Quality Assurance',
                'department' => 'Engineering',
            ],
        ];
    @endphp

    <div class="container-fluid">
        <!-- Page Title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">HRMS</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Attendance</a></li>
                            <li class="breadcrumb-item active">Add Attendance</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Add Attendance Records</h4>
                </div>
            </div>
        </div>

        <!-- Main Card -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border">
                    <!-- Card Header -->
                    <div class="card-header border-bottom py-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 rounded p-2 me-3">
                                    <i class="bi bi-clock-history fs-3 text-primary"></i>
                                </div>
                                <div>
                                    <h4 class="mb-1 fw-bold">Employee Attendance Entry</h4>
                                    <p class="mb-0 text-muted small">Record employee clock-in and clock-out times</p>
                                </div>
                            </div>
                            <div>
                                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                                    data-bs-target="#bulkUploadModal">
                                    <i class="bi bi-upload me-2"></i>Bulk Upload
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body p-4">
                        <form id="attendanceForm" action="#" method="POST">
                            @csrf

                            <!-- Attendance Entries Container -->
                            <div id="attendanceEntriesContainer">
                                <!-- Initial Entry Row -->
                                <div class="attendance-entry-wrapper mb-4" data-entry-index="0">
                                    <div class="card border-2">
                                        <div class="card-body p-4">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h6 class="mb-0 fw-semibold">
                                                    <span class="badge bg-primary me-2">Entry 1</span>
                                                    Attendance Record
                                                </h6>
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-danger remove-entry-btn"
                                                    style="display: none;">
                                                    <i class="bi bi-trash"></i> Remove
                                                </button>
                                            </div>

                                            <div class="row g-3">
                                                <!-- Employee Name - Full Width -->
                                                <div class="col-12">
                                                    <label for="employee_id_0" class="form-label fw-semibold">
                                                        Employee Name <span class="text-danger">*</span>
                                                    </label>
                                                    <select name="attendance[0][employee_id]" id="employee_id_0"
                                                        class="form-select select2_list" required>
                                                        <option value="">Select Employee</option>
                                                        @foreach ($employees as $employee)
                                                            <option value="{{ $employee->id }}"
                                                                data-employee-id="{{ $employee->employee_id }}"
                                                                data-designation="{{ $employee->designation }}"
                                                                data-department="{{ $employee->department }}">
                                                                {{ $employee->name }} ({{ $employee->employee_id }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <div class="invalid-feedback">Please select an employee.</div>
                                                </div>

                                                <!-- Clock In DateTime -->
                                                <div class="col-md-6">
                                                    <label for="clock_in_0" class="form-label fw-semibold">
                                                        Clock In <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="datetime-local" name="attendance[0][clock_in]"
                                                        id="clock_in_0" class="form-control" required>
                                                    <div class="invalid-feedback">Please enter clock in time.</div>
                                                </div>

                                                <!-- Clock Out DateTime -->
                                                <div class="col-md-6">
                                                    <label for="clock_out_0" class="form-label fw-semibold">
                                                        Clock Out <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="datetime-local" name="attendance[0][clock_out]"
                                                        id="clock_out_0" class="form-control" required>
                                                    <div class="invalid-feedback">Please enter clock out time.</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Add More Button -->
                            <div class="mb-4">
                                <button type="button" id="addEntryBtn" class="btn btn-outline-primary">
                                    <i class="bi bi-plus-circle me-2"></i>Add Another Entry
                                </button>
                            </div>

                            <!-- Form Actions -->
                            <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                                <button type="button" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle me-2"></i>Cancel
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-2"></i>Submit Attendance
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bulk Upload Modal -->
    @include('attendance.partials.bulk_upload_modal', ['employees' => $employees])
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            let entryIndex = 0;
            const employeesData = @json($employees);

          

            // Add New Entry
            $('#addEntryBtn').on('click', function() {
                entryIndex++;
                addNewEntry(entryIndex);
            });

            // Remove Entry
            $(document).on('click', '.remove-entry-btn', function() {
                const entryWrapper = $(this).closest('.attendance-entry-wrapper');
                entryWrapper.fadeOut(300, function() {
                    $(this).remove();
                    updateEntryNumbers();
                });
            });

            // Form Submission
            $('#attendanceForm').on('submit', function(e) {
                e.preventDefault();

                if (this.checkValidity()) {
                    const formData = $(this).serialize();
                    console.log('Form Data:', formData);

                    // Show success message
                    toastr.success('Attendance records submitted successfully!', 'Success');

                    // Reset form after submission (optional)
                    // this.reset();
                    // $('.select2-employee').val('').trigger('change');
                } else {
                    this.classList.add('was-validated');
                    toastr.error('Please fill all required fields correctly.', 'Validation Error');
                }
            });

            // Function to add new entry
            function addNewEntry(index) {
                const newEntry = `
                            <div class="attendance-entry-wrapper mb-4" data-entry-index="${index}">
                                <div class="card border-2">
                                    <div class="card-body p-4">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="mb-0 fw-semibold">
                                                <span class="badge bg-primary me-2">Entry ${index + 1}</span>
                                                Attendance Record
                                            </h6>
                                            <button type="button" class="btn btn-sm btn-outline-danger remove-entry-btn">
                                                <i class="bi bi-trash"></i> Remove
                                            </button>
                                        </div>

                                        <div class="row g-3">
                                            <!-- Employee Name - Full Width -->
                                            <div class="col-12">
                                                <label for="employee_id_${index}" class="form-label fw-semibold">
                                                    Employee Name <span class="text-danger">*</span>
                                                </label>
                                                <select name="attendance[${index}][employee_id]" id="employee_id_${index}"
                                                    class="form-select select2_list" required>
                                                    <option value="">Select Employee</option>
                                                    ${generateEmployeeOptions()}
                                                </select>
                                                <div class="invalid-feedback">Please select an employee.</div>
                                            </div>

                                            <!-- Clock In DateTime -->
                                            <div class="col-md-6">
                                                <label for="clock_in_${index}" class="form-label fw-semibold">
                                                    Clock In <span class="text-danger">*</span>
                                                </label>
                                                <input type="datetime-local" name="attendance[${index}][clock_in]"
                                                    id="clock_in_${index}" class="form-control" required>
                                                <div class="invalid-feedback">Please enter clock in time.</div>
                                            </div>

                                            <!-- Clock Out DateTime -->
                                            <div class="col-md-6">
                                                <label for="clock_out_${index}" class="form-label fw-semibold">
                                                    Clock Out <span class="text-danger">*</span>
                                                </label>
                                                <input type="datetime-local" name="attendance[${index}][clock_out]"
                                                    id="clock_out_${index}" class="form-control" required>
                                                <div class="invalid-feedback">Please enter clock out time.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;

                $('#attendanceEntriesContainer').append(newEntry);
                initializeSelect2ForEntry(index);
                updateRemoveButtons();

                // Smooth scroll to new entry
                $('html, body').animate({
                    scrollTop: $(`[data-entry-index="${index}"]`).offset().top - 100
                }, 500);
            }

            // Generate employee options for dynamic entries
            function generateEmployeeOptions() {
                let options = '';
                employeesData.forEach(employee => {
                    options += `<option value="${employee.id}"
                                data-employee-id="${employee.employee_id}"
                                data-designation="${employee.designation}"
                                data-department="${employee.department}">
                                ${employee.name} (${employee.employee_id})
                            </option>`;
                });
                return options;
            }

            // Update entry numbers after removal
            function updateEntryNumbers() {
                $('.attendance-entry-wrapper').each(function(idx) {
                    $(this).find('.badge').text(`Entry ${idx + 1}`);
                });
                updateRemoveButtons();
            }

            // Update remove button visibility
            function updateRemoveButtons() {
                const totalEntries = $('.attendance-entry-wrapper').length;
                if (totalEntries === 1) {
                    $('.remove-entry-btn').hide();
                } else {
                    $('.remove-entry-btn').show();
                }
            }

            
        });
    </script>
@endpush
