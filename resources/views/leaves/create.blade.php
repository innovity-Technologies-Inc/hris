@extends('structure.master')

@section('content')
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

        // Dummy leave plans as objects
        $dummyLeavePlans = collect([
            (object) ['id' => 1, 'name' => 'Annual Leave', 'days' => 20],
            (object) ['id' => 2, 'name' => 'Sick Leave', 'days' => 14],
            (object) ['id' => 3, 'name' => 'Casual Leave', 'days' => 10],
            (object) ['id' => 4, 'name' => 'Maternity Leave', 'days' => 120],
            (object) ['id' => 5, 'name' => 'Paternity Leave', 'days' => 7],
        ]);
    @endphp

    {{-- Leave Application Create --}}
    <div class="row">
        {{-- Leave Application Form --}}
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm rounded">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-file-document-edit-outline me-2"></i>Leave Application Form
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form id="leaveApplicationForm" method="POST" action="#">
                        @csrf
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
                                        <option value="{{ $employee->id }}">
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
                                <label for="leave_plan_id" class="form-label fw-semibold">
                                    <i class="mdi mdi-calendar-clock text-success me-1"></i>
                                    Leave Plan <span class="text-danger">*</span>
                                </label>
                                <select id="leave_plan_id" name="leave_plan_id" class="form-select select2_list" required>
                                    <option value="">-- Select Leave Plan --</option>
                                    @foreach ($dummyLeavePlans as $plan)
                                        <option value="{{ $plan->id }}" data-days="{{ $plan->days }}">
                                            {{ $plan->name }} ({{ $plan->days }} days)
                                        </option>
                                    @endforeach
                                </select>
                                @error('leave_plan_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Days --}}
                            <div class="col-md-4">
                                <label for="days" class="form-label fw-semibold">
                                    <i class="mdi mdi-counter text-info me-1"></i>
                                    Days <span class="text-danger">*</span>
                                </label>
                                <input type="number" id="days" name="days" class="form-control"
                                    placeholder="Enter number of days" min="1" required>
                                @error('days')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- From Date --}}
                            <div class="col-md-4">
                                <label for="from_date" class="form-label fw-semibold">
                                    <i class="mdi mdi-calendar-start text-success me-1"></i>
                                    From Date <span class="text-danger">*</span>
                                </label>
                                <input type="date" id="from_date" name="from_date" class="form-control"
                                    value="{{ date('Y-m-d') }}" required>
                                @error('from_date')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- To Date --}}
                            <div class="col-md-4">
                                <label for="to_date" class="form-label fw-semibold">
                                    <i class="mdi mdi-calendar-end text-danger me-1"></i>
                                    To Date <span class="text-danger">*</span>
                                </label>
                                <input type="date" id="to_date" name="to_date" class="form-control" required>
                                @error('to_date')
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
                                    placeholder="Enter reason for leave application" required></textarea>
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
                                    <option value="pending" selected>Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
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
                                        <i class="mdi mdi-check-circle me-1"></i> Submit Application
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
            // Reset filters
            $('#resetFilters').on('click', function() {
                $('#filterForm')[0].reset();
                $('.select2_list').val(null).trigger('change');
            });

            // Auto calculate days based on from and to date
            $('#from_date, #to_date').on('change', function() {
                var fromDate = new Date($('#from_date').val());
                var toDate = new Date($('#to_date').val());

                if (fromDate && toDate && toDate >= fromDate) {
                    var timeDiff = toDate.getTime() - fromDate.getTime();
                    var daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1;
                    $('#days').val(daysDiff);
                }
            });

            // Auto set to_date based on days and from_date
            $('#days').on('change', function() {
                var fromDate = new Date($('#from_date').val());
                var days = parseInt($(this).val());

                if (fromDate && days > 0) {
                    var toDate = new Date(fromDate);
                    toDate.setDate(toDate.getDate() + days - 1);
                    $('#to_date').val(toDate.toISOString().split('T')[0]);
                }
            });

            // Form submission handler (for demo purposes)
            $('#leaveApplicationForm').on('submit', function(e) {
                e.preventDefault();
                alert('Leave application submitted successfully! (Demo mode)');
                window.location.href = '{{ url('leaves') }}';
            });
        });
    </script>
@endsection
