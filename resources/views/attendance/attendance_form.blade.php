@extends('structure.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border">

                    <!-- Header -->
                    <div class="card-header py-4">
                        <h4 class="fw-bold mb-0">Employee Attendance</h4>
                    </div>

                    <!-- Body -->
                    <div class="card-body p-4">

                        <form action="{{ route('attendance.store') }}" method="POST">
                            @csrf

                            {{-- GLOBAL ERRORS --}}
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div id="attendanceEntriesContainer">

                                @php
                                    $entries = old('attendance', [ [] ]);
                                @endphp

                                @foreach ($entries as $index => $entry)
                                    <div class="attendance-entry-wrapper mb-4">
                                        <div class="card border-2">
                                            <div class="card-body p-4">

                                                <div class="d-flex justify-content-between mb-3">
                                            <span class="badge bg-primary">
                                                Entry {{ $index + 1 }}
                                            </span>

                                                    <button type="button"
                                                            class="btn btn-sm btn-outline-danger remove-entry-btn"
                                                            style="{{ $index === 0 ? 'display:none' : '' }}">
                                                        Remove
                                                    </button>
                                                </div>

                                                <div class="row g-3">

                                                    {{-- Employee --}}
                                                    <div class="col-md-8">
                                                        <label class="form-label fw-semibold">Employee *</label>
                                                        <select name="attendance[{{ $index }}][employee_id]"
                                                                class="form-select @error("attendance.$index.employee_id") is-invalid @enderror">
                                                            <option value="">Select Employee</option>
                                                            @foreach ($employees as $employee)
                                                                <option value="{{ $employee->id }}"
                                                                    {{ old("attendance.$index.employee_id") == $employee->id ? 'selected' : '' }}>
                                                                    {{ $employee->full_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error("attendance.$index.employee_id")
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    {{-- Workstation --}}
                                                    <div class="col-md-4">
                                                        <label class="form-label fw-semibold">Work Station</label>
                                                        <select name="attendance[{{ $index }}][workstation]"
                                                                class="form-select">
                                                            <option value="">Select</option>
                                                            <option
                                                                value="Remote" {{ old("attendance.$index.workstation") == 'Remote' ? 'selected' : '' }}>
                                                                Remote
                                                            </option>
                                                            <option
                                                                value="On-Site" {{ old("attendance.$index.workstation") == 'On-Site' ? 'selected' : '' }}>
                                                                On-Site
                                                            </option>
                                                        </select>
                                                    </div>

                                                    {{-- Clock In --}}
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold">Clock In *</label>
                                                        <input type="datetime-local"
                                                               name="attendance[{{ $index }}][clock_in]"
                                                               value="{{ old("attendance.$index.clock_in") }}"
                                                               class="form-control @error("attendance.$index.clock_in") is-invalid @enderror">
                                                        @error("attendance.$index.clock_in")
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    {{-- Clock Out --}}
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-semibold">Clock Out *</label>
                                                        <input type="datetime-local"
                                                               name="attendance[{{ $index }}][clock_out]"
                                                               value="{{ old("attendance.$index.clock_out") }}"
                                                               class="form-control @error("attendance.$index.clock_out") is-invalid @enderror">
                                                        @error("attendance.$index.clock_out")
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </div>

                            <button type="button" id="addEntryBtn" class="btn btn-outline-primary mb-3">
                                Add Another Entry
                            </button>

                            <div class="text-end">
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
        document.addEventListener('DOMContentLoaded', function () {

            let index = {{ count(old('attendance', [ [] ])) - 1 }};
            const container = document.getElementById('attendanceEntriesContainer');

            document.getElementById('addEntryBtn').addEventListener('click', () => {
                index++;

                const div = document.createElement('div');
                div.classList.add('attendance-entry-wrapper', 'mb-4');

                div.innerHTML = `
        <div class="card border-2">
            <div class="card-body p-4">

                <div class="d-flex justify-content-between mb-3">
                    <span class="badge bg-primary">Entry ${index + 1}</span>
                    <button type="button"
                        class="btn btn-sm btn-outline-danger remove-entry-btn">
                        Remove
                    </button>
                </div>

                <div class="row g-3">

                    <!-- Employee -->
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
                </option>
@endforeach
                </select>
            </div>

            <!-- Work Station -->
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

                    <!-- Clock In -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Clock In <span class="text-danger">*</span>
                        </label>
                        <input type="datetime-local"
                            name="attendance[${index}][clock_in]"
                            class="form-control" required>
                    </div>

                    <!-- Clock Out -->
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
            });

            container.addEventListener('click', e => {
                if (e.target.closest('.remove-entry-btn')) {
                    e.target.closest('.attendance-entry-wrapper').remove();
                }
            });

        });
    </script>

@endpush
