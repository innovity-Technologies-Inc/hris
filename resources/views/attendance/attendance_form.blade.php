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
                                    <i class="bi bi-clock-history fs-3 text-primary"></i>
                                </div>
                                <div>
                                    <h4 class="mb-1 fw-bold">Employee Attendance</h4>
                                    <p class="mb-0 text-muted small">
                                        Record employee attendance
                                    </p>
                                </div>
                            </div>
                            <div>
                                <a href="{{ route('attendance.bulk-upload') }}" class="btn btn-outline-primary">
                                    <i class="bi bi-upload me-2"></i>Bulk Upload
                                </a>
                            </div>
                        </div>
                    </div>


                    <!-- Body -->
                    <div class="card-body p-4">
                        <form action="{{ route('attendance.store') }}" method="POST">
                            @csrf


                            <div id="attendanceEntriesContainer">


                                <!-- ENTRY 1 -->
                                <div class="attendance-entry-wrapper mb-4">
                                    <div class="card border-2">
                                        <div class="card-body p-4">


                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h6 class="fw-semibold mb-0">
                                                    <span class="badge bg-primary me-2">Entry 1</span>
                                                    Attendance Record
                                                </h6>
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-danger remove-entry-btn"
                                                        style="display:none">
                                                    <i class="bi bi-trash"></i> Remove
                                                </button>
                                            </div>


                                            <div class="row g-3">


                                                <!-- Employee -->
                                                <div class="col-md-8">
                                                    <label class="form-label fw-semibold">
                                                        Employee <span class="text-danger">*</span>
                                                    </label>
                                                    <select name="attendance[0][employee_id]" class="form-select" required>
                                                        <option value="">Select Employee</option>
                                                        @foreach ($employees as $employee)
                                                            <option value="{{ $employee->id }}">
                                                                {{ $employee->full_name }}
                                                                ({{ $employee->applicant_id }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <!-- Work Station -->
                                                <div class="col-md-4">
                                                    <label class="form-label fw-semibold">
                                                        Work Station
                                                    </label>
                                                    <select name="attendance[0][workstation]" class="form-select">
                                                        <option value="">Select Work Station</option>
                                                        <option value="Remote">Remote</option>
                                                        <option value="On-Site">On-Site</option>
                                                        <option value="Work-From-Home">Work-From-Home</option>
                                                    </select>
                                                </div>


                                                <!-- Clock In -->
                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold">
                                                        Clock In <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="datetime-local" name="attendance[0][clock_in]"
                                                           class="form-control" required>
                                                </div>


                                                <!-- Clock Out -->
                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold">
                                                        Clock Out <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="datetime-local" name="attendance[0][clock_out]"
                                                           class="form-control" required>
                                                </div>





                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>


                            <!-- Add Button -->
                            <button type="button" id="addEntryBtn" class="btn btn-outline-primary mb-4">
                                <i class="bi bi-plus-circle me-2"></i>
                                Add Another Entry
                            </button>


                            <!-- Submit -->
                            <div class="d-flex justify-content-end gap-2 border-top pt-3">
                                <button type="submit" class="btn btn-primary">
                                    Submit Attendance
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


            let index = 0;
            const container = document.getElementById('attendanceEntriesContainer');


            document.getElementById('addEntryBtn').addEventListener('click', function() {
                index++;


                const div = document.createElement('div');
                div.classList.add('attendance-entry-wrapper', 'mb-4');


                div.innerHTML = `
        <div class="card border-2">
            <div class="card-body p-4">


                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-semibold mb-0">
                        <span class="badge bg-primary me-2">Entry ${index + 1}</span>
                        Attendance Record
                    </h6>
                    <button type="button"
                        class="btn btn-sm btn-outline-danger remove-entry-btn">
                        <i class="bi bi-trash"></i> Remove
                    </button>
                </div>


                <div class="row g-3">


                    <div class="col-md-8">
                        <label class="form-label fw-semibold">
                            Employee <span class="text-danger">*</span>
                        </label>
                        <select name="attendance[${index}][employee_id]"
                            class="form-select" required>
                            <option value="">Select Employee</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}">
                                    {{ $employee->full_name }}
                                    ({{ $employee->applicant_id }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">
                            Work Station
                        </label>
                        <select name="attendance[${index}][workstation]"
                            class="form-select">
                            <option value="">Select Work Station</option>
                            <option value="Remote">Remote</option>
                            <option value="On-Site">On-Site</option>
                            <option value="Work-From-Home">Work-From-Home</option>
                        </select>
                    </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">
                    Work Station
                </label>
                <select name="attendance[${index}][workstation]"
                            class="form-select">
                            <option value="">Select Work Station</option>
                            <option value="Remote">Remote</option>
                            <option value="On-Site">On-Site</option>
                            <option value="Work-From-Home">Work-From-Home</option>
                        </select>
                    </div>


                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Clock In <span class="text-danger">*</span>
                        </label>
                        <input type="datetime-local"
                            name="attendance[${index}][clock_in]"
                            class="form-control" required>
                    </div>


                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Clock Out <span class="text-danger">*</span>
                        </label>
                        <input type="datetime-local"
                            name="attendance[${index}][clock_out]"
                            class="form-control" required>
                    </div>





                </div>
            </div>
        </div>
        `;


                container.appendChild(div);
                toggleRemove();
            });


            container.addEventListener('click', function(e) {
                if (e.target.closest('.remove-entry-btn')) {
                    e.target.closest('.attendance-entry-wrapper').remove();
                    toggleRemove();
                }
            });


            function toggleRemove() {
                const rows = document.querySelectorAll('.attendance-entry-wrapper');
                rows.forEach((row, i) => {
                    row.querySelector('.badge').textContent = `Entry ${i + 1}`;
                    row.querySelector('.remove-entry-btn').style.display =
                        rows.length > 1 ? 'inline-block' : 'none';
                });
            }


        });
    </script>
@endpush
