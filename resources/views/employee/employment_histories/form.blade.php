@extends('structure.master')
@section('content')
    <div class="mt-4">
        {{-- Display All Validation Errors Summary --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><i class="mdi mdi-alert-circle me-2"></i>Please fix the following errors:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form id="employmentHistoryForm" method="POST"
            action="{{ isset($historyData) ? route('employee.employment_history.update', $employee->id) : route('employee.employment_history.store') }}">
            @if (isset($historyData))
                @method('PUT')
            @endif
            @csrf
            <input type="hidden" name="employee_id" value="{{ $employee->id }}">

            <!-- Employment History Section -->
            <div class="row mt-3">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0"><i class="mdi mdi-briefcase me-2"></i>Employment History</h5>
                            <button type="button" class="btn btn-sm btn-light" id="addRow">
                                <i class="mdi mdi-plus"></i> Add Row
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="historyContainer">
                                @php
                                    $histories = old('histories', $historyData->histories ?? []);
                                @endphp

                                @if (empty($histories))
                                    <!-- Initial Empty Row for Create -->
                                    <div class="history-row border rounded p-3 mb-3 bg-light" data-row="0">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label text-dark fw-semibold">Company Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="histories[0][company_name]" placeholder="e.g., Acme Corp">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label text-dark fw-semibold">Designation <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="histories[0][designation]" placeholder="e.g., Software Engineer">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label text-dark fw-semibold">Joining Date <span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" name="histories[0][joining_date]">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label text-dark fw-semibold">End Date</label>
                                                <input type="date" class="form-control" name="histories[0][end_date]">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label text-dark fw-semibold">Job Description</label>
                                                <textarea class="form-control" name="histories[0][job_description]" rows="2" placeholder="Key responsibilities..."></textarea>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label text-dark fw-semibold">Achievements</label>
                                                <textarea class="form-control" name="histories[0][achievements]" rows="2" placeholder="Key achievements..."></textarea>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    @foreach ($histories as $index => $history)
                                        <div class="history-row border rounded p-3 mb-3 bg-light position-relative" data-row="{{ $index }}">
                                            @if ($index > 0)
                                                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2" onclick="removeRow(this)">
                                                    <i class="mdi mdi-delete"></i> Remove
                                                </button>
                                            @endif
                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <label class="form-label text-dark fw-semibold">Company Name <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="histories[{{ $index }}][company_name]" value="{{ $history['company_name'] ?? '' }}" placeholder="e.g., Acme Corp">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label text-dark fw-semibold">Designation <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="histories[{{ $index }}][designation]" value="{{ $history['designation'] ?? '' }}" placeholder="e.g., Software Engineer">
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label text-dark fw-semibold">Joining Date <span class="text-danger">*</span></label>
                                                    <input type="date" class="form-control" name="histories[{{ $index }}][joining_date]" value="{{ $history['joining_date'] ?? '' }}">
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label text-dark fw-semibold">End Date</label>
                                                    <input type="date" class="form-control" name="histories[{{ $index }}][end_date]" value="{{ $history['end_date'] ?? '' }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label text-dark fw-semibold">Job Description</label>
                                                    <textarea class="form-control" name="histories[{{ $index }}][job_description]" rows="2" placeholder="Key responsibilities...">{{ $history['job_description'] ?? '' }}</textarea>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label text-dark fw-semibold">Achievements</label>
                                                    <textarea class="form-control" name="histories[{{ $index }}][achievements]" rows="2" placeholder="Key achievements...">{{ $history['achievements'] ?? '' }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="row mt-4 mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('employee.profile.general_informations', $employee->id) }}" class="btn btn-secondary">
                            <i class="mdi mdi-arrow-left me-1"></i> Back to Profile
                        </a>
                        <button type="button" id="previewBtn" class="btn btn-info text-white">
                            <i class="mdi mdi-eye me-1"></i> Preview
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-content-save me-1"></i>
                            {{ isset($historyData) ? 'Update History' : 'Save History' }}
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    @include('employee.partials.preview_modal')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let rowCount = document.querySelectorAll('.history-row').length;
            const maxRows = 10;

            document.getElementById('addRow').addEventListener('click', function() {
                if (rowCount >= maxRows) {
                    alert(`Maximum ${maxRows} rows allowed`);
                    return;
                }

                const container = document.getElementById('historyContainer');
                const newRow = document.createElement('div');
                newRow.className = 'history-row border rounded p-3 mb-3 bg-light position-relative';
                newRow.setAttribute('data-row', rowCount);

                newRow.innerHTML = `
                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2" onclick="removeRow(this)">
                        <i class="mdi mdi-delete"></i> Remove
                    </button>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label text-dark fw-semibold">Company Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="histories[${rowCount}][company_name]" placeholder="e.g., Acme Corp">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-dark fw-semibold">Designation <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="histories[${rowCount}][designation]" placeholder="e.g., Software Engineer">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label text-dark fw-semibold">Joining Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="histories[${rowCount}][joining_date]">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label text-dark fw-semibold">End Date</label>
                            <input type="date" class="form-control" name="histories[${rowCount}][end_date]">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-dark fw-semibold">Job Description</label>
                            <textarea class="form-control" name="histories[${rowCount}][job_description]" rows="2" placeholder="Key responsibilities..."></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-dark fw-semibold">Achievements</label>
                            <textarea class="form-control" name="histories[${rowCount}][achievements]" rows="2" placeholder="Key achievements..."></textarea>
                        </div>
                    </div>
                `;

                container.appendChild(newRow);
                rowCount++;
            });

            window.removeRow = function(button) {
                const row = button.closest('.history-row');
                row.remove();
                rowCount--;
            };
        });
    </script>
@endsection

