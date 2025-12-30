@extends('structure.master')

@section('content')
    <!-- SweetAlert2 CSS -->
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

    @php
        // Dummy employee data for testing
        $employees = [
            (object) ['id' => 1, 'full_name' => 'John Doe', 'applicant_id' => 'EMP001'],
            (object) ['id' => 2, 'full_name' => 'Jane Smith', 'applicant_id' => 'EMP002'],
            (object) ['id' => 3, 'full_name' => 'Michael Johnson', 'applicant_id' => 'EMP003'],
            (object) ['id' => 4, 'full_name' => 'Sarah Williams', 'applicant_id' => 'EMP004'],
            (object) ['id' => 5, 'full_name' => 'Robert Brown', 'applicant_id' => 'EMP005'],
            (object) ['id' => 6, 'full_name' => 'Emily Davis', 'applicant_id' => 'EMP006'],
            (object) ['id' => 7, 'full_name' => 'David Wilson', 'applicant_id' => 'EMP007'],
            (object) ['id' => 8, 'full_name' => 'Jessica Martinez', 'applicant_id' => 'EMP008'],
            (object) ['id' => 9, 'full_name' => 'Christopher Taylor', 'applicant_id' => 'EMP009'],
            (object) ['id' => 10, 'full_name' => 'Amanda Anderson', 'applicant_id' => 'EMP010'],
        ];
    @endphp

    <div class="container-fluid p-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-lg border-0 rounded-4">

                    <!-- Header -->
                    <div class="card-header border-0 py-4 bg-primary" style="border-radius: 1rem 1rem 0 0 !important;">
                        <div class="d-flex align-items-center">
                            <div class="bg-white bg-opacity-25 p-3 rounded-3 me-3">
                                <i class="bi bi-clock-history fs-2 text-white"></i>
                            </div>
                            <div>
                                <h3 class="mb-1 fw-bold text-white">
                                    Attendance Clock System</h3>
                                <p class="mb-0 text-white" style="opacity: 0.9; font-size: 0.95rem;">Mark your attendance
                                    with ease</p>
                            </div>
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="card-body p-4 p-md-5">
                        <form id="attendanceForm">
                            @csrf

                            <div class="row g-4">
                                <!-- Left Side: Form Fields -->
                                <div class="col-lg-7">
                                    <!-- Employee Selection -->
                                    <div class="mb-4">
                                        <label class="form-label fw-bold text-dark mb-3" style="font-size: 1.1rem;">
                                            <i class="bi bi-person-circle text-primary me-2 fs-4"></i>
                                            Select Employee <span class="text-danger">*</span>
                                        </label>
                                        <select name="employee_id" id="employeeSelect"
                                            class="form-select form-select-lg border-2 shadow-sm"
                                            style="border-color: #e0e0e0; padding: 0.75rem 1rem;" required>
                                            <option value="">-- Choose Employee --</option>
                                            @foreach ($employees as $employee)
                                                <option value="{{ $employee->id }}">
                                                    {{ $employee->full_name }} ({{ $employee->applicant_id }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Work Station Selection -->
                                    <div class="mb-4">
                                        <label class="form-label fw-bold text-dark mb-3" style="font-size: 1.1rem;">
                                            <i class="bi bi-geo-alt-fill text-success me-2 fs-4"></i>
                                            Work Station <span class="text-danger">*</span>
                                        </label>
                                        <select name="workstation" id="workstationSelect"
                                            class="form-select form-select-lg border-2 shadow-sm"
                                            style="border-color: #e0e0e0; padding: 0.75rem 1rem;" required>
                                            <option value="">-- Select Work Station --</option>
                                            <option value="Remote">🏠 Remote</option>
                                            <option value="On-Site">🏢 On-Site</option>
                                            <option value="Work-From-Home">💻 Work-From-Home</option>
                                        </select>
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

                                    <!-- Clock In Time Display (Hidden Initially) -->
                                    <div id="clockInTimeDisplay" class="card border-0 shadow-sm"
                                        style="display: none; background: linear-gradient(135deg, #d4fc79 0%, #96e6a1 100%);">
                                        <div class="card-body text-center py-3">
                                            <p class="mb-2 fw-bold text-success">
                                                <i class="bi bi-check-circle-fill me-2 fs-5"></i>Successfully Clocked In
                                            </p>
                                            <p id="clockInTime" class="mb-0 fw-bold text-dark fs-5">--:--:--</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Side: Clock Buttons -->
                                <div class="col-lg-5">
                                    <div class="d-flex flex-column align-items-center justify-content-center h-100 py-4">

                                        <!-- Clock In Button -->
                                        <div id="clockInContainer" class="mb-3">
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

                                        <!-- Clock Out Button (Hidden Initially) -->
                                        <div id="clockOutContainer" style="display: none;">
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
                            </div>

                            <!-- Hidden Inputs for Form Submission -->
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
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Update Current Time
            function updateTime() {
                const now = new Date();
                const timeString = now.toLocaleTimeString('en-US', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                });
                const dateString = now.toLocaleDateString('en-US', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });

                document.getElementById('currentTime').textContent = timeString;
                document.getElementById('currentDate').textContent = dateString;
            }

            // Update time immediately and then every second
            updateTime();
            setInterval(updateTime, 1000);

            // Clock In Button Handler
            document.getElementById('clockInBtn').addEventListener('click', function() {
                const employee = document.getElementById('employeeSelect').value;
                const workstation = document.getElementById('workstationSelect').value;

                // Validation
                if (!employee) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Employee Required',
                        text: 'Please select an employee first!',
                        confirmButtonColor: '#4facfe'
                    });
                    document.getElementById('employeeSelect').focus();
                    return;
                }

                if (!workstation) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Work Station Required',
                        text: 'Please select a work station!',
                        confirmButtonColor: '#4facfe'
                    });
                    document.getElementById('workstationSelect').focus();
                    return;
                }

                // Record clock in time
                const clockInTime = new Date();
                const clockInString = clockInTime.toISOString();
                document.getElementById('clockInInput').value = clockInString;

                // Display clock in time
                document.getElementById('clockInTime').textContent = clockInTime.toLocaleTimeString(
                    'en-US', {
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit'
                    });

                // Show success message with animation
                const timeDisplay = document.getElementById('clockInTimeDisplay');
                timeDisplay.style.display = 'block';
                timeDisplay.classList.add('fade-in');

                // Hide Clock In button and show Clock Out button with animation
                const clockInContainer = document.getElementById('clockInContainer');
                const clockOutContainer = document.getElementById('clockOutContainer');

                clockInContainer.style.transition = 'all 0.5s ease';
                clockInContainer.style.opacity = '0';
                clockInContainer.style.transform = 'scale(0.5)';

                setTimeout(() => {
                    clockInContainer.style.display = 'none';
                    clockOutContainer.style.display = 'block';
                    clockOutContainer.style.opacity = '0';
                    clockOutContainer.style.transform = 'scale(0.5)';

                    setTimeout(() => {
                        clockOutContainer.classList.add('slide-in');
                        clockOutContainer.style.transition = 'all 0.5s ease';
                        clockOutContainer.style.opacity = '1';
                        clockOutContainer.style.transform = 'scale(1)';
                    }, 50);
                }, 500);

                // Disable employee and workstation selection
                document.getElementById('employeeSelect').disabled = true;
                document.getElementById('workstationSelect').disabled = true;

                // Success notification
                console.log('✅ Clocked In Successfully:', {
                    employee: employee,
                    workstation: workstation,
                    clockIn: clockInString
                });
            });

            // Clock Out Button Handler
            document.getElementById('clockOutBtn').addEventListener('click', function() {
                // Record clock out time
                const clockOutTime = new Date();
                const clockOutString = clockOutTime.toISOString();
                document.getElementById('clockOutInput').value = clockOutString;

                // Get clock in time from hidden input
                const clockInValue = document.getElementById('clockInInput').value;
                const clockInDate = new Date(clockInValue);

                // Debug log
                console.log('Clock In:', clockInDate);
                console.log('Clock Out:', clockOutTime);

                // Calculate duration in milliseconds
                const durationMs = clockOutTime.getTime() - clockInDate.getTime();
                console.log('Duration (ms):', durationMs);

                // Convert to total minutes
                const totalMinutes = Math.floor(durationMs / (1000 * 60));

                // Calculate hours and minutes
                const hours = Math.floor(totalMinutes / 60);
                const minutes = totalMinutes % 60;

                console.log('Total Minutes:', totalMinutes, 'Hours:', hours, 'Minutes:', minutes);

                // Show confirmation dialog
                Swal.fire({
                    title: 'Clock Out Confirmation',
                    html: `
                        <div style="text-align: left; padding: 10px;">
                            <p><strong>⏰ Time:</strong> ${clockOutTime.toLocaleTimeString()}</p>
                            <p><strong>⏱️ Working Duration:</strong> ${hours}h ${minutes}m</p>
                        </div>
                    `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#4facfe',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Clock Out',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Submit form or make AJAX request here
                        // document.getElementById('attendanceForm').submit();

                        Swal.fire({
                            icon: 'success',
                            title: 'Attendance Recorded!',
                            text: 'Thank you for your hard work today. Have a great evening!',
                            confirmButtonColor: '#4facfe'
                        }).then(() => {
                            // Reset form (optional)
                            location.reload();
                        });
                    }
                });
            });

            // Prevent form submission on enter
            document.getElementById('attendanceForm').addEventListener('submit', function(e) {
                e.preventDefault();
            });
        });
    </script>
@endpush
