@extends('structure.master')
@section('content')
    {{-- Form --}}

    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0">{{ isset($holiday) ? 'Edit' : 'Add' }} Holiday</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form
                                action="{{ isset($holiday) ? route('holidays.update', $holiday->id) : route('holidays.store') }}"
                                method="post" id="holidayForm">
                                @csrf
                                @if (isset($holiday))
                                    @method('PUT')
                                @endif

                                <div id="holidayRows">
                                    @if (isset($holiday))
                                        {{-- Edit mode: Single holiday --}}
                                        <div class="holiday-row border p-3 mb-3 rounded">
                                            <div class="row">
                                                <div class="col-lg-12 mb-2">
                                                    <label for="title" class="form-label">Holiday Title <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" id="title"
                                                        class="form-control @error('title') is-invalid @enderror"
                                                        name="title" placeholder="Enter Holiday Title"
                                                        value="{{ $holiday->title }}" required maxlength="255">
                                                    @error('title')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>

                                                <div class="col-lg-4 mb-2">
                                                    <label for="start_date" class="form-label">From Date <span
                                                            class="text-danger">*</span></label>
                                                    <input type="date" id="start_date"
                                                        class="form-control @error('start_date') is-invalid @enderror start-date"
                                                        name="start_date"
                                                        value="{{ $holiday->start_date->format('Y-m-d') }}" required>
                                                    @error('start_date')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>

                                                <div class="col-lg-4 mb-2">
                                                    <label for="end_date" class="form-label">To Date</label>
                                                    <input type="date" id="end_date"
                                                        class="form-control @error('end_date') is-invalid @enderror end-date"
                                                        name="end_date" value="{{ $holiday->end_date->format('Y-m-d') }}">
                                                    @error('end_date')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>

                                                <div class="col-lg-4 mb-2">
                                                    <label for="duration" class="form-label">Duration (Days)</label>
                                                    <input type="text" class="form-control duration-field" readonly
                                                        value="{{ $holiday->start_date->diffInDays($holiday->end_date) + 1 }}">
                                                </div>

                                                <div class="col-lg-12 mb-2">
                                                    <label for="status" class="form-label">Status</label>
                                                    <select class="form-select" name="status">
                                                        <option value="active"
                                                            {{ $holiday->status == 'active' ? 'selected' : '' }}>Active
                                                        </option>
                                                        <option value="inactive"
                                                            {{ $holiday->status == 'inactive' ? 'selected' : '' }}>Inactive
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        {{-- Create mode: Initial row --}}
                                        <div class="holiday-row border p-3 mb-3 rounded" data-row="0">
                                            <div class="row">
                                                <div class="col-lg-12 mb-2">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <label class="form-label mb-0">Holiday Title <span
                                                                class="text-danger">*</span></label>
                                                        <button type="button" class="btn btn-sm btn-danger remove-row"
                                                            style="display:none;">
                                                            <i style="height: 12px; width: 12px" data-feather="x"></i>
                                                            Remove
                                                        </button>
                                                    </div>
                                                    <input type="text"
                                                        class="form-control @error('holidays.0.title') is-invalid @enderror"
                                                        name="holidays[0][title]" placeholder="Enter Holiday Title"
                                                        value="{{ old('holidays.0.title') }}" required maxlength="255">
                                                    @error('holidays.0.title')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>

                                                <div class="col-lg-4 mb-2">
                                                    <label class="form-label">From Date <span
                                                            class="text-danger">*</span></label>
                                                    <input type="date"
                                                        class="form-control start-date @error('holidays.0.start_date') is-invalid @enderror"
                                                        name="holidays[0][start_date]"
                                                        value="{{ old('holidays.0.start_date') }}" required>
                                                    @error('holidays.0.start_date')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>

                                                <div class="col-lg-4 mb-2">
                                                    <label class="form-label">To Date</label>
                                                    <input type="date"
                                                        class="form-control end-date @error('holidays.0.end_date') is-invalid @enderror"
                                                        name="holidays[0][end_date]"
                                                        value="{{ old('holidays.0.end_date') }}">
                                                    @error('holidays.0.end_date')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>

                                                <div class="col-lg-4 mb-2">
                                                    <label class="form-label">Duration (Days)</label>
                                                    <input type="text" class="form-control duration-field" readonly
                                                        placeholder="Auto-calculated">
                                                </div>

                                                <div class="col-lg-12 mb-2">
                                                    <label class="form-label">Status</label>
                                                    <select class="form-select" name="holidays[0][status]">
                                                        <option value="active" selected>Active</option>
                                                        <option value="inactive">Inactive</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                @if (!isset($holiday))
                                    <button type="button" class="btn btn-secondary mb-3" id="addRowBtn">
                                        <i style="height: 12px; width: 12px" data-feather="plus"></i> Add Row
                                    </button>
                                @endif

                                <div>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <a href="{{ route('holidays.index') }}" class="btn btn-secondary">Cancel</a>
                                </div>

                            </form>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        let rowCount = 1;

        // Add new holiday row
        document.addEventListener('DOMContentLoaded', function() {
            const addRowBtn = document.getElementById('addRowBtn');

            if (addRowBtn) {
                addRowBtn.addEventListener('click', function() {
                    const container = document.getElementById('holidayRows');
                    const newRow = document.createElement('div');
                    newRow.className = 'holiday-row border p-3 mb-3 rounded';
                    newRow.setAttribute('data-row', rowCount);

                    newRow.innerHTML = `
                <div class="row">
                    <div class="col-lg-12 mb-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <label class="form-label mb-0">Holiday Title <span class="text-danger">*</span></label>
                            <button type="button" class="btn btn-sm btn-danger remove-row">
                                <i style="height: 12px; width: 12px" data-feather="x"></i> Remove
                            </button>
                        </div>
                        <input type="text" class="form-control" name="holidays[${rowCount}][title]"
                            placeholder="Enter Holiday Title" required maxlength="255">
                    </div>

                    <div class="col-lg-4 mb-2">
                        <label class="form-label">From Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control start-date" name="holidays[${rowCount}][start_date]" required>
                    </div>

                    <div class="col-lg-4 mb-2">
                        <label class="form-label">To Date</label>
                        <input type="date" class="form-control end-date" name="holidays[${rowCount}][end_date]">
                    </div>

                    <div class="col-lg-4 mb-2">
                        <label class="form-label">Duration (Days)</label>
                        <input type="text" class="form-control duration-field" readonly placeholder="Auto-calculated">
                    </div>

                    <div class="col-lg-12 mb-2">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="holidays[${rowCount}][status]">
                            <option value="active" selected>Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
            `;

                    container.appendChild(newRow);

                    // Re-initialize feather icons
                    if (typeof feather !== 'undefined') {
                        feather.replace();
                    }

                    // Show remove button for first row if more than one row exists
                    updateRemoveButtons();

                    rowCount++;
                });
            }

            // Initial remove button visibility
            updateRemoveButtons();
        });

        // Remove holiday row
        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-row')) {
                e.target.closest('.holiday-row').remove();
                updateRemoveButtons();
            }
        });

        // Update remove button visibility
        function updateRemoveButtons() {
            const rows = document.querySelectorAll('.holiday-row');
            const removeButtons = document.querySelectorAll('.remove-row');

            if (rows.length > 1) {
                removeButtons.forEach(btn => btn.style.display = 'inline-block');
            } else {
                removeButtons.forEach(btn => btn.style.display = 'none');
            }
        }

        // Calculate duration when dates change
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('start-date') || e.target.classList.contains('end-date')) {
                const row = e.target.closest('.holiday-row');
                const startDate = row.querySelector('.start-date').value;
                const endDateInput = row.querySelector('.end-date');
                const endDate = endDateInput.value;
                const durationField = row.querySelector('.duration-field');

                if (startDate) {
                    if (endDate) {
                        // Both dates provided
                        const start = new Date(startDate);
                        const end = new Date(endDate);
                        const diffTime = end - start;
                        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;

                        if (diffDays > 0) {
                            durationField.value = diffDays;
                        } else {
                            durationField.value = '';
                            alert('End date must be equal to or after start date.');
                        }
                    } else {
                        // Only start date provided, duration is 1 day
                        durationField.value = 1;
                    }
                }
            }
        });

        // Initial calculation for edit mode
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.holiday-row').forEach(row => {
                const startDate = row.querySelector('.start-date')?.value;
                const endDate = row.querySelector('.end-date')?.value;
                const durationField = row.querySelector('.duration-field');

                if (startDate && durationField && !durationField.value) {
                    if (endDate) {
                        const start = new Date(startDate);
                        const end = new Date(endDate);
                        const diffTime = end - start;
                        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;

                        if (diffDays > 0) {
                            durationField.value = diffDays;
                        }
                    } else {
                        durationField.value = 1;
                    }
                }
            });
        });
    </script>
@endsection
