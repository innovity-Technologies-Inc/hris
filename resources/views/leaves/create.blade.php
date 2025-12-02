@extends('structure.master')

@section('content')
    <style>
        .leave-card {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 20px;
            transition: all 0.3s ease;
            height: 100%;
        }

        .leave-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .leave-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .leave-type-badge {
            font-size: 0.75rem;
            padding: 4px 10px;
            border-radius: 12px;
        }

        .leave-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-top: 15px;
        }

        .stat-item {
            text-align: center;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 6px;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 0.85rem;
            color: #6c757d;
        }
    </style>

    @php
        // Dummy employee data as objects
        $dummyEmployees = collect([
            (object) [
                'id' => 1,
                'full_name' => 'John Doe',
                'applicant_id' => 'EMP001',
                'system_id' => 'SYS001',
                'photo_path' => null,
            ],
            (object) [
                'id' => 2,
                'full_name' => 'Jane Smith',
                'applicant_id' => 'EMP002',
                'system_id' => 'SYS002',
                'photo_path' => null,
            ],
            (object) [
                'id' => 3,
                'full_name' => 'Mike Johnson',
                'applicant_id' => 'EMP003',
                'system_id' => 'SYS003',
                'photo_path' => null,
            ],
            (object) [
                'id' => 4,
                'full_name' => 'Sarah Williams',
                'applicant_id' => 'EMP004',
                'system_id' => 'SYS004',
                'photo_path' => null,
            ],
            (object) [
                'id' => 5,
                'full_name' => 'David Brown',
                'applicant_id' => 'EMP005',
                'system_id' => 'SYS005',
                'photo_path' => null,
            ],
        ]);

        // Dummy leave plans as objects with more details for leave card
        $dummyLeavePlans = collect([
            (object) [
                'id' => 1,
                'name' => 'Annual Leave',
                'type' => 'Paid',
                'status' => 'Active',
                'limit' => 20,
                'taken' => 8,
                'remaining' => 12,
                'badge_color' => 'success',
            ],
            (object) [
                'id' => 2,
                'name' => 'Sick Leave',
                'type' => 'Paid',
                'status' => 'Active',
                'limit' => 14,
                'taken' => 5,
                'remaining' => 9,
                'badge_color' => 'info',
            ],
            (object) [
                'id' => 3,
                'name' => 'Casual Leave',
                'type' => 'Paid',
                'status' => 'Active',
                'limit' => 10,
                'taken' => 7,
                'remaining' => 3,
                'badge_color' => 'primary',
            ],
            (object) [
                'id' => 4,
                'name' => 'Maternity Leave',
                'type' => 'Paid',
                'status' => 'Active',
                'limit' => 120,
                'taken' => 0,
                'remaining' => 120,
                'badge_color' => 'warning',
            ],
            (object) [
                'id' => 5,
                'name' => 'Paternity Leave',
                'type' => 'Paid',
                'status' => 'Active',
                'limit' => 7,
                'taken' => 0,
                'remaining' => 7,
                'badge_color' => 'secondary',
            ],
        ]);

        // Dummy leave application for edit mode (set to null for create mode)
        // To test edit mode, uncomment the object below
        $leave = null;
        /*
        $leave = (object) [
            'id' => 1,
            'plan_id' => 1,
            'employee_id' => 1,
            'leave_count' => 5,
            'from' => '2025-12-05',
            'to' => '2025-12-10',
            'reason' => 'Family vacation to visit relatives',
            'status' => 'pending',
        ];
        */

        $isEdit = isset($leave) && $leave !== null;
    @endphp

    {{-- Leave Application Form --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm rounded">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-file-document-edit-outline me-2"></i>
                        {{ $isEdit ? 'Edit Leave Application' : 'Create Leave Application' }}
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form id="leaveApplicationForm" method="POST" action="{{ $isEdit ? '#' : '#' }}">
                        @csrf
                        @if ($isEdit)
                            @method('PUT')
                        @endif

                        <div class="row g-3">
                            {{-- Select Employee --}}
                            <div class="col-md-6">
                                <label for="employee_id" class="form-label fw-semibold">
                                    <i class="mdi mdi-account text-primary me-1"></i>
                                    Select Employee <span class="text-danger">*</span>
                                </label>
                                <select id="employee_id" name="employee_id" class="form-select select2_list" required>
                                    <option value="">-- Select Employee --</option>
                                    @foreach ($dummyEmployees as $employee)
                                        <option value="{{ $employee->id }}"
                                            {{ ($isEdit && $leave->employee_id == $employee->id) || old('employee_id') == $employee->id ? 'selected' : '' }}>
                                            {{ $employee->full_name }} ({{ $employee->applicant_id }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('employee_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Select Leave Plan --}}
                            <div class="col-md-6">
                                <label for="plan_id" class="form-label fw-semibold">
                                    <i class="mdi mdi-calendar-clock text-success me-1"></i>
                                    Leave Plan <span class="text-danger">*</span>
                                </label>
                                <select id="plan_id" name="plan_id" class="form-select select2_list" required>
                                    <option value="">-- Select Leave Plan --</option>
                                    @foreach ($dummyLeavePlans as $plan)
                                        <option value="{{ $plan->id }}" data-name="{{ $plan->name }}"
                                            data-type="{{ $plan->type }}" data-status="{{ $plan->status }}"
                                            data-limit="{{ $plan->limit }}" data-taken="{{ $plan->taken }}"
                                            data-remaining="{{ $plan->remaining }}" data-badge="{{ $plan->badge_color }}"
                                            {{ ($isEdit && $leave->plan_id == $plan->id) || old('plan_id') == $plan->id ? 'selected' : '' }}>
                                            {{ $plan->name }} ({{ $plan->remaining }} days remaining)
                                        </option>
                                    @endforeach
                                </select>
                                @error('plan_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Leave Plan Card (Hidden by default) --}}
                            <div class="col-md-12" id="leave-plan-card-container" style="display: none;">
                                <div class="leave-card">
                                    <div class="leave-card-header">
                                        <h5 class="mb-0" id="card-plan-name">-</h5>
                                        <span class="badge leave-type-badge" id="card-plan-type-badge">-</span>
                                    </div>

                                    <div class="mb-2">
                                        <span class="badge" id="card-plan-status-badge">-</span>
                                    </div>

                                    <div class="leave-stats">
                                        <div class="stat-item">
                                            <span class="stat-value text-primary" id="card-plan-limit">0</span>
                                            <span class="stat-label">Limit</span>
                                        </div>
                                        <div class="stat-item">
                                            <span class="stat-value text-danger" id="card-plan-taken">0</span>
                                            <span class="stat-label">Taken</span>
                                        </div>
                                        <div class="stat-item">
                                            <span class="stat-value text-success" id="card-plan-remaining">0</span>
                                            <span class="stat-label">Remaining</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Leave Count (Days) --}}
                            <div class="col-md-4">
                                <label for="leave_count" class="form-label fw-semibold">
                                    <i class="mdi mdi-counter text-info me-1"></i>
                                    Leave Count (Days) <span class="text-danger">*</span>
                                </label>
                                <input type="number" id="leave_count" name="leave_count" class="form-control"
                                    placeholder="Enter number of days" min="1"
                                    value="{{ $isEdit ? $leave->leave_count : old('leave_count') }}" required>
                                @error('leave_count')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- From Date --}}
                            <div class="col-md-4">
                                <label for="from" class="form-label fw-semibold">
                                    <i class="mdi mdi-calendar-start text-success me-1"></i>
                                    From Date <span class="text-danger">*</span>
                                </label>
                                <input type="date" id="from" name="from" class="form-control"
                                    value="{{ $isEdit ? $leave->from : old('from') ?? date('Y-m-d') }}" required>
                                @error('from')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- To Date --}}
                            <div class="col-md-4">
                                <label for="to" class="form-label fw-semibold">
                                    <i class="mdi mdi-calendar-end text-danger me-1"></i>
                                    To Date <span class="text-danger">*</span>
                                </label>
                                <input type="date" id="to" name="to" class="form-control"
                                    value="{{ $isEdit ? $leave->to : old('to') }}" required>
                                @error('to')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Reason --}}
                            <div class="col-md-8">
                                <label for="reason" class="form-label fw-semibold">
                                    <i class="mdi mdi-text-box-outline text-warning me-1"></i>
                                    Reason <span class="text-danger">*</span>
                                </label>
                                <textarea id="reason" name="reason" class="form-control" rows="3"
                                    placeholder="Enter reason for leave application" required>{{ $isEdit ? $leave->reason : old('reason') }}</textarea>
                                @error('reason')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Status --}}
                            <div class="col-md-4">
                                <label for="status" class="form-label fw-semibold">
                                    <i class="mdi mdi-flag text-primary me-1"></i>
                                    Status <span class="text-danger">*</span>
                                </label>
                                <select id="status" name="status" class="form-select" required>
                                    <option value="pending"
                                        {{ ($isEdit && $leave->status == 'pending') || old('status') == 'pending' ? 'selected' : (!$isEdit && !old('status') ? 'selected' : '') }}>
                                        Pending</option>
                                    <option value="approved"
                                        {{ ($isEdit && $leave->status == 'approved') || old('status') == 'approved' ? 'selected' : '' }}>
                                        Approved</option>
                                    <option value="rejected"
                                        {{ ($isEdit && $leave->status == 'rejected') || old('status') == 'rejected' ? 'selected' : '' }}>
                                        Rejected</option>
                                </select>
                                @error('status')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        {{-- Form Actions --}}
                        <div class="row mt-4">
                            <div class="col-12">
                                <hr class="my-3">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ url('leaves') }}" class="btn btn-outline-secondary">
                                        <i class="mdi mdi-arrow-left me-1"></i> Back to List
                                    </a>
                                    <button type="reset" class="btn btn-secondary">
                                        <i class="mdi mdi-refresh me-1"></i> Reset
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="mdi mdi-check-circle me-1"></i>
                                        {{ $isEdit ? 'Update Application' : 'Submit Application' }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script>
        $(document).ready(function() {
            // Function to update leave card
            function updateLeaveCard(option) {
                if (option && option.value) {
                    var $option = $(option);
                    var name = $option.data('name');
                    var type = $option.data('type');
                    var status = $option.data('status');
                    var limit = $option.data('limit');
                    var taken = $option.data('taken');
                    var remaining = $option.data('remaining');
                    var badgeColor = $option.data('badge');

                    // Update card content
                    $('#card-plan-name').text(name);
                    $('#card-plan-type-badge').text(type).removeClass().addClass('badge leave-type-badge bg-' +
                        badgeColor);
                    $('#card-plan-status-badge').text(status).removeClass().addClass('badge bg-' + (status ===
                        'Active' ? 'success' : 'secondary'));
                    $('#card-plan-limit').text(limit);
                    $('#card-plan-taken').text(taken);
                    $('#card-plan-remaining').text(remaining);

                    // Show the card
                    $('#leave-plan-card-container').slideDown();
                } else {
                    // Hide the card
                    $('#leave-plan-card-container').slideUp();
                }
            }

            // Handle plan selection change
            $('#plan_id').on('change', function() {
                var selectedOption = $(this).find('option:selected')[0];
                updateLeaveCard(selectedOption);
            });

            // Initialize card if editing (plan already selected)
            @if ($isEdit)
                var selectedOption = $('#plan_id').find('option:selected')[0];
                updateLeaveCard(selectedOption);
            @endif

            // Auto calculate days based on from and to date
            $('#from, #to').on('change', function() {
                var fromDate = new Date($('#from').val());
                var toDate = new Date($('#to').val());

                if (fromDate && toDate && toDate >= fromDate) {
                    var timeDiff = toDate.getTime() - fromDate.getTime();
                    var daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1;
                    $('#leave_count').val(daysDiff);
                }
            });

            // Auto set to date based on days and from date
            $('#leave_count').on('change', function() {
                var fromDate = new Date($('#from').val());
                var days = parseInt($(this).val());

                if (fromDate && days > 0) {
                    var toDate = new Date(fromDate);
                    toDate.setDate(toDate.getDate() + days - 1);
                    $('#to').val(toDate.toISOString().split('T')[0]);
                }
            });

            // Reset form handler
            $('button[type="reset"]').on('click', function() {
                setTimeout(function() {
                    $('#leave-plan-card-container').slideUp();
                    $('.select2_list').val(null).trigger('change');
                }, 10);
            });

            // Form submission handler (for demo purposes)
            $('#leaveApplicationForm').on('submit', function(e) {
                e.preventDefault();
                var message =
                    '{{ $isEdit ? 'Leave application updated successfully!' : 'Leave application submitted successfully!' }}';
                alert(message + ' (Demo mode)');
                window.location.href = '{{ url('leaves') }}';
            });
        });
    </script>
@endsection
