@extends('structure.master')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        /* Clock Button Styles */
        .clock-button {
            position: relative;
            width: 260px !important;
            height: 260px !important;
            border-radius: 50% !important;
            border: 4px solid rgba(255, 255, 255, 0.4) !important;
            cursor: pointer !important;
            overflow: visible !important;
            transition: all 0.3s ease !important;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2), 0 8px 20px rgba(0, 0, 0, 0.15) !important;
        }

        .clock-in-btn {
            background: #0d6efd !important;
            position: relative;
        }

        .clock-in-btn::after {
            content: '';
            position: absolute;
            top: -8px;
            left: -8px;
            right: -8px;
            bottom: -8px;
            border-radius: 50%;
            background: #0d6efd;
            z-index: -1;
            filter: blur(15px);
            opacity: 0.5;
        }

        .clock-out-btn {
            background: #dc3545 !important;
            position: relative;
        }

        .clock-out-btn::after {
            content: '';
            position: absolute;
            top: -8px;
            left: -8px;
            right: -8px;
            bottom: -8px;
            border-radius: 50%;
            background: #dc3545;
            z-index: -1;
            filter: blur(15px);
            opacity: 0.5;
            color: white !important;
        }

        .icon-wrapper {
            font-size: 5rem !important;
            margin-bottom: 0.8rem !important;
            filter: drop-shadow(0 5px 10px rgba(0, 0, 0, 0.3));
            transition: all 0.3s ease !important;
        }

        /* Entry Animation for Clock In */
        .clock-in-btn .icon-wrapper {
            animation: slideInRight 2s ease-in-out infinite;
        }

        @keyframes slideInRight {

            0%,
            100% {
                transform: translateX(-20px);
                opacity: 0.6;
            }

            50% {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* Exit Animation for Clock Out */
        .clock-out-btn .icon-wrapper {
            animation: slideOutLeft 2s ease-in-out infinite;
        }

        @keyframes slideOutLeft {

            0%,
            100% {
                transform: translateX(0);
                opacity: 1;
            }

            50% {
                transform: translateX(-20px);
                opacity: 0.6;
            }
        }

        }

        /* Active/Click Effect */
        .clock-button:active {
            transform: scale(0.95) !important;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3), inset 0 3px 15px rgba(0, 0, 0, 0.15) !important;
        }

        /* Select Box Custom Styling */
        .form-select:focus {
            border-color: #4facfe !important;
            box-shadow: 0 0 0 0.25rem rgba(79, 172, 254, 0.25) !important;
        }

        .form-select {
            transition: all 0.3s ease !important;
        }

        .form-select:hover {
            border-color: #4facfe !important;
        }

        /* Fade In Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.5s ease-out !important;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(50px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .slide-in {
            animation: slideIn 0.5s ease-out !important;
        }

        /* Responsive Design */
        @media (max-width: 991px) {
            .clock-button {
                width: 220px !important;
                height: 220px !important;
            }

            .icon-wrapper {
                font-size: 3.5rem !important;
            }

            .button-text {
                font-size: 1.5rem !important;
            }
        }
    </style>


    <div class="container-fluid p-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-lg border-0 rounded-4">

                    <!-- Header -->
                    <div class="card-header bg-primary text-white py-4">
                        <h3 class="fw-bold mb-0">Attendance Clock System</h3>
                        <small>Mark your attendance easily</small>
                    </div>

                    <!-- Body -->
                    <div class="card-body p-5">
                        <form id="attendanceForm" method="POST" action="{{ route('attendance.clock_in_out_store') }}">
                            @csrf

                            <div class="row g-4">

                                <!-- LEFT -->
                                <div class="col-lg-7">

                                    <!-- Employee -->
                                    <div class="mb-4">
                                        <label class="fw-bold mb-2">Select Employee</label>
                                        <select id="employeeSelect" name="employee_id" class="form-select form-select-lg" required>
                                            <option value="">-- Choose Employee --</option>
                                            @foreach($employees as $employee)
                                                <option value="{{ $employee->id }}">
                                                    {{ $employee->full_name }} ({{ $employee->applicant_id }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Workstation -->
                                    <div class="mb-4">
                                        <label class="fw-bold mb-2">Work Station</label>
                                        <select id="workstationSelect" name="workstation" class="form-select form-select-lg" required>
                                            <option value="">-- Select Work Station --</option>
                                            <option value="Remote">Remote</option>
                                            <option value="On-Site">On-Site</option>
                                            <option value="Work-From-Home">Work From Home</option>
                                        </select>
                                    </div>

                                    <!-- Message -->
                                    <div id="attendanceMessage"
                                         class="alert alert-warning text-center fw-bold"
                                         style="display:none;">
                                    </div>

                                    <!-- Current Time Display -->
                                    <div class="mb-4">
                                        <div class="card border-0 shadow-sm"
                                             style="background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);">
                                            <div class="card-body text-center py-4">
                                                <p class="text-dark mb-2 fw-semibold"
                                                   style="font-size: 0.9rem; opacity: 0.8;">
                                                    <i class="bi bi-clock me-1"></i> Current Time
                                                </p>
                                                <h2 id="currentTime" class="mb-2 fw-bold"
                                                    style="color: #2d3436; font-size: 2.5rem; letter-spacing: 2px;">--:--:--
                                                </h2>
                                                <p id="currentDate" class="mb-0"
                                                   style="color: #636e72; font-size: 0.95rem;">-- -- --</p>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <!-- RIGHT -->
                                <div class="col-lg-5 d-flex align-items-center justify-content-center">

                                    <!-- CLOCK IN -->
                                    <div id="clockInContainer" style="display:none;">
                                        <button type="button" id="clockInBtn" class="clock-button clock-in-btn">
                                            <div class="button-content">
                                                <div class="icon-wrapper">
                                                    <i class="bi bi-box-arrow-in-right"></i>
                                                </div>
                                                <div class="button-text"
                                                     style="color: white; font-size: 2rem; font-weight: 900; letter-spacing: 5px; text-shadow: 0 4px 15px rgba(0, 0, 0, 0.5), 0 2px 5px rgba(255, 255, 255, 0.2); text-transform: uppercase;">
                                                    CLOCK IN</div>
                                            </div>
                                        </button>
                                    </div>

                                    <!-- CLOCK OUT -->
                                    <div id="clockOutContainer" style="display:none;">
                                        <button type="button" id="clockOutBtn" class="clock-button clock-out-btn">
                                            <div class="button-content">
                                                <div class="icon-wrapper">
                                                    <i class="bi bi-box-arrow-left"></i>
                                                </div>
                                                <div class="button-text"
                                                     style="color: white; font-size: 2rem; font-weight: 900; letter-spacing: 5px; text-shadow: 0 4px 15px rgba(0, 0, 0, 0.5), 0 2px 5px rgba(255, 255, 255, 0.2); text-transform: uppercase;">
                                                    CLOCK OUT</div>
                                            </div>
                                        </button>
                                    </div>

                                </div>
                            </div>

                            <!-- Hidden Inputs -->
                            <input type="hidden" name="clock_in" id="clockInInput">
                            <input type="hidden" name="clock_out" id="clockOutInput">

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {

            $('#employeeSelect').on('change', function () {

                let employeeId = $(this).val();

                console.log('Employee selected:', employeeId);

                $('#clockInContainer').hide();
                $('#clockOutContainer').hide();
                $('#attendanceMessage').hide();

                if (!employeeId) return;

                $.ajax({
                    url: "{{ url('get-attendance-details') }}/" + employeeId,
                    type: 'GET',
                    success: function (res) {

                        console.log('Response:', res);

                        if (res.status === 'clock_in') {
                            $('#clockInContainer').fadeIn();
                        }
                        else if (res.status === 'clock_out') {
                            $('#clockOutContainer').fadeIn();
                        }
                        else if (res.status === 'completed') {
                            $('#attendanceMessage')
                                .text('⚠️ Employee already clocked out today.')
                                .fadeIn();
                        }
                    },
                    error: function (xhr) {
                        console.error(xhr.responseText);
                        alert('AJAX error');
                    }
                });
            });

        });
    </script>


@endpush
