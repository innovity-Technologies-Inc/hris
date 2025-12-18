@extends('structure.master')

@section('content')

    <div class="container-fluid">
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
                                    <h4 class="mb-1 fw-bold">Employee Attendance</h4>
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
                        <form id="attendanceForm" action="{{route('attendance.store')}}" method="POST">
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
                                                            <option value="{{ $employee->id }}">
                                                                {{ $employee->full_name }} ({{ $employee->applicant_id }})
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
    @include('attendance.partials.bulk_upload_modal')
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            let entryIndex = 0;
            const container = document.getElementById('attendanceEntriesContainer');
            const addBtn = document.getElementById('addEntryBtn');

            addBtn.addEventListener('click', function () {
                entryIndex++;

                const newEntry = document.createElement('div');
                newEntry.classList.add('attendance-entry-wrapper', 'mb-4');
                newEntry.setAttribute('data-entry-index', entryIndex);

                newEntry.innerHTML = `
            <div class="card border-2">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0 fw-semibold">
                            <span class="badge bg-primary me-2">Entry ${entryIndex + 1}</span>
                            Attendance Record
                        </h6>
                        <button type="button"
                                class="btn btn-sm btn-outline-danger remove-entry-btn">
                            <i class="bi bi-trash"></i> Remove
                        </button>
                    </div>

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                Employee Name <span class="text-danger">*</span>
                            </label>
                            <select name="attendance[${entryIndex}][employee_id]"
                                    class="form-select select2_list" required>
                                <option value="">Select Employee</option>
                                @foreach ($employees as $employee)
                <option value="{{ $employee->id }}">
                                        {{ $employee->full_name }} ({{ $employee->applicant_id }})
                                    </option>
                                @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">
                    Clock In <span class="text-danger">*</span>
                </label>
                <input type="datetime-local"
                       name="attendance[${entryIndex}][clock_in]"
                                   class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Clock Out <span class="text-danger">*</span>
                            </label>
                            <input type="datetime-local"
                                   name="attendance[${entryIndex}][clock_out]"
                                   class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>
        `;

                container.appendChild(newEntry);
                toggleRemoveButtons();
            });

            // Remove entry (event delegation)
            container.addEventListener('click', function (e) {
                if (e.target.closest('.remove-entry-btn')) {
                    e.target.closest('.attendance-entry-wrapper').remove();
                    toggleRemoveButtons();
                }
            });

            function toggleRemoveButtons() {
                const entries = document.querySelectorAll('.attendance-entry-wrapper');
                entries.forEach((entry, index) => {
                    const btn = entry.querySelector('.remove-entry-btn');
                    const badge = entry.querySelector('.badge');

                    badge.textContent = `Entry ${index + 1}`;

                    if (btn) {
                        btn.style.display = entries.length > 1 ? 'inline-block' : 'none';
                    }
                });
            }
        });
    </script>
@endpush
