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

         .placeholder {
             display: inline-block;
             background: #e3e3e3;
             border-radius: 4px;
             animation: pulse 1.5s infinite ease-in-out;
         }

        @keyframes pulse {
            0% { opacity: 0.6; }
            50% { opacity: 1; }
            100% { opacity: 0.6; }
        }
    </style>
    {{-- Leave Application Form --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm rounded">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-file-document-edit-outline me-2"></i>
                        Create Leave Application
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form id="leaveApplicationForm" method="POST" action="{{route('leaves.store')}}">
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
                                    @foreach ($employees as $employee)
                                        <option value="{{ $employee->id }}"
                                            {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                            {{ $employee->full_name }}
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

                                </select>
                                @error('plan_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Leave Plan Card (Hidden by default) --}}
                            <div class="col-md-12" id="leave-plan-card-container" style="display:none;">

                                <div class="leave-card">

                                    <!-- Skeleton Loader -->
                                    <div id="leave-plan-skeleton" style="display:none;">
                                        <div class="placeholder-glow">
                                            <div class="placeholder col-6 mb-2" style="height:20px;"></div>
                                            <div class="placeholder col-4 mb-4" style="height:20px;"></div>

                                            <div class="row">
                                                <div class="col-4">
                                                    <div class="placeholder col-12 mb-2" style="height:40px;"></div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="placeholder col-12 mb-2" style="height:40px;"></div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="placeholder col-12 mb-2" style="height:40px;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- REAL CONTENT -->
                                    <div class="leave-card-content" style="display:none;">
                                        <div class="leave-card-header">
                                            <h5 class="mb-0" id="card-plan-name">-</h5>
                                            <span class="badge leave-type-badge" id="card-plan-type-badge">-</span>
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

                            </div>


                            {{-- Leave Count (Days) --}}
                            <div class="col-md-4">
                                <label for="leave_count" class="form-label fw-semibold">
                                    <i class="mdi mdi-counter text-info me-1"></i>
                                    Leave Count (Days) <span class="text-danger">*</span>
                                </label>
                                <input type="number" id="leave_count" name="leave_count" class="form-control"
                                    placeholder="Enter number of days" min="1"
                                    value="{{ old('leave_count') }}" required>
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
                                    value="{{ old('from') ?? date('Y-m-d') }}" required>
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
                                    value="{{ old('to') }}" required>
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
                                    placeholder="Enter reason for leave application" required>{{ old('reason') }}</textarea>
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
                                        {{ old('status') == 'pending' ? 'selected' : (!old('status') ? 'selected' : '') }}>
                                        Pending</option>
                                    <option value="approved"
                                        {{ old('status') == 'approved' ? 'selected' : '' }}>
                                        Approved</option>
                                    <option value="rejected"
                                        {{ old('status') == 'rejected' ? 'selected' : '' }}>
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
                                    <a href="{{ route('leaves.index') }}" class="btn btn-outline-secondary">
                                        <i class="mdi mdi-arrow-left me-1"></i> Back to List
                                    </a>
                                    <button type="reset" class="btn btn-secondary">
                                        <i class="mdi mdi-refresh me-1"></i> Reset
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="mdi mdi-check-circle me-1"></i>
                                        Submit Application
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
        $(function () {

            // Load Leave Plans
            function loadPlans(employeeId, selectedPlan = null) {
                if (employeeId) {
                    $.get('/get-leave-plans/' + employeeId, function (data) {
                        let $planSelect = $('#plan_id');
                        $planSelect.html('<option value="">-- Select --</option>');

                        $.each(data, function (key, value) {
                            let isSelected = (selectedPlan == value.plan_id) ? 'selected' : '';
                            $planSelect.append('<option value="' + value.plan_id + '" ' + isSelected + '>' +
                                value.get_plan.name +
                                '</option>');
                        });
                    });
                }
            }

            $('#employee_id').on('change', function () {
                loadPlans($(this).val());
            });

            let employeeId = "{{ old('employee_id') }}";
            let planId = "{{ old('plan_id') }}";

            if (employeeId) {
                loadPlans(employeeId, planId);
            }

            // Load Leave Plan Details
            $(document).on('change', '#plan_id', function () {

                let planId = $(this).val();
                let employeeId = $('#employee_id').val();

                if (!planId || !employeeId) {
                    $('#leave-plan-card-container').hide();
                    return;
                }

                // Show card + skeleton only
                $('#leave-plan-card-container').show();
                $('#leave-plan-skeleton').show();
                $('.leave-card-content').hide();

                $.ajax({
                    url: "/get-leave-details/" + employeeId + "/" + planId,
                    type: "GET",
                    success: function (data) {

                        // Hide loader
                        $('#leave-plan-skeleton').hide();

                        // Show actual content
                        $('.leave-card-content').show();

                        // Fill content
                        $('#card-plan-name').text(data.name ?? '-');
                        $('#card-plan-limit').text(data.limit ?? 0);
                        $('#card-plan-taken').text(data.taken ?? 0);

                        let remaining = (data.limit ?? 0) - (data.taken ?? 0);
                        $('#card-plan-remaining').text(remaining >= 0 ? remaining : 0);
                    }
                });

            });

        });
    </script>



@endsection
