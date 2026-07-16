@extends('structure.master')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

@push('styles')
    <style>
        /* Glassmorphism theme override */
        .attendance-card {
            border-radius: 16px !important;
            border: none !important;
            background: rgba(255, 255, 255, 0.4) !important;
            backdrop-filter: blur(20px) !important;
            -webkit-backdrop-filter: blur(20px) !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04) !important;
            overflow: hidden;
            margin-bottom: 2rem;
        }

        [data-bs-theme=dark] .attendance-card {
            background: rgba(15, 23, 42, 0.3) !important;
            border: 1px solid rgba(255, 255, 255, 0.05) !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2) !important;
        }

        .page-header {
            background: var(--primary-color, #974063) !important;
            color: white !important;
            padding: 2rem !important;
            border-radius: 16px 16px 0 0 !important;
            border-bottom: none !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08) !important;
        }

        [data-bs-theme=dark] .page-header {
            background: #1f2937 !important;
            border-bottom: 3px solid #374151 !important;
        }

        .form-section, .clock-section {
            background: rgba(255, 255, 255, 0.6) !important;
            backdrop-filter: blur(12px) !important;
            -webkit-backdrop-filter: blur(12px) !important;
            border: 1px solid rgba(255, 255, 255, 0.3) !important;
            border-radius: 16px !important;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.05) !important;
            padding: 2.5rem !important;
            height: 100% !important;
            transition: all 0.3s ease !important;
        }

        [data-bs-theme=dark] .form-section, 
        [data-bs-theme=dark] .clock-section {
            background: rgba(30, 41, 59, 0.5) !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.2) !important;
        }

        /* Clock redesign */
        .time-display {
            background: linear-gradient(135deg, #1e293b, #0f172a) !important;
            border: none !important;
            border-radius: 16px !important;
            padding: 2rem !important;
            color: #ffffff !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3), 0 8px 10px -6px rgba(0, 0, 0, 0.3) !important;
            margin-bottom: 2rem !important;
            width: 100% !important;
            max-width: 360px !important;
            position: relative !important;
            overflow: hidden !important;
        }

        #mainClockTzSelect {
            text-align: center !important;
            text-align-last: center !important;
            font-size: 1rem !important;
            color: #ffffff !important;
            font-weight: 700 !important;
            appearance: none !important;
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            background-image: none !important;
            padding: 0.25rem 0.5rem !important;
            cursor: pointer !important;
        }

        #mainClockTzSelect option {
            text-align: center !important;
            color: #ffffff !important;
            background-color: #1e293b !important;
        }

        .time-display::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.2) 0%, transparent 70%);
            pointer-events: none;
        }

        .time-label {
            color: #818cf8 !important;
            font-size: 0.85rem !important;
            text-transform: uppercase !important;
            letter-spacing: 0.15em !important;
            font-weight: 700 !important;
        }

        .current-time {
            font-size: 3rem !important;
            font-weight: 800 !important;
            color: #ffffff !important;
            text-shadow: 0 0 15px rgba(99, 102, 241, 0.6) !important;
            font-family: 'Outfit', 'Inter', -apple-system, sans-serif !important;
            margin: 0.75rem 0 !important;
            letter-spacing: 1px !important;
        }

        .current-date {
            font-size: 0.95rem !important;
            color: #94a3b8 !important;
            font-weight: 500 !important;
        }

        /* Clock-in info display styling */
        .clock-in-time-display {
            background: rgba(16, 185, 129, 0.1) !important;
            border: 1px solid rgba(16, 185, 129, 0.3) !important;
            border-radius: 12px !important;
            padding: 1rem 1.5rem !important;
            color: #10b981 !important;
            width: 100% !important;
            max-width: 300px !important;
            margin-bottom: 2rem !important;
            box-shadow: none !important;
        }

        .clock-in-time-display .time-label {
            color: #10b981 !important;
        }

        .clocked-time {
            font-size: 1.5rem !important;
            font-weight: 700 !important;
            color: #047857 !important;
            font-family: 'Outfit', 'Inter', sans-serif !important;
            margin-top: 0.25rem !important;
        }

        [data-bs-theme=dark] .clocked-time {
            color: #34d399 !important;
        }

        /* Interactive Clock Button */
        .clock-button {
            width: 100% !important;
            max-width: 280px !important;
            padding: 1.1rem 2rem !important;
            border-radius: 12px !important;
            font-size: 1.1rem !important;
            font-weight: 700 !important;
            letter-spacing: 0.05em !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
            border: none !important;
        }

        .clock-in-btn {
            background: linear-gradient(135deg, #10b981, #059669) !important;
            color: #ffffff !important;
        }

        .clock-in-btn:hover {
            background: linear-gradient(135deg, #059669, #047857) !important;
            transform: translateY(-3px) scale(1.02) !important;
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4) !important;
        }

        .clock-in-btn:active {
            transform: translateY(-1px) !important;
        }

        .clock-out-btn {
            background: linear-gradient(135deg, #ef4444, #dc2626) !important;
            color: #ffffff !important;
        }

        .clock-out-btn:hover {
            background: linear-gradient(135deg, #dc2626, #b91c1c) !important;
            transform: translateY(-3px) scale(1.02) !important;
            box-shadow: 0 8px 25px rgba(239, 68, 68, 0.4) !important;
        }

        .clock-out-btn:active {
            transform: translateY(-1px) !important;
        }

        /* World Clock Styles */
        .world-clock-container {
            border-top: 1px solid rgba(0, 0, 0, 0.08);
        }
        [data-bs-theme=dark] .world-clock-container {
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }
        .world-clock-card {
            background: rgba(255, 255, 255, 0.4) !important;
            border: 1px solid rgba(0, 0, 0, 0.06) !important;
            backdrop-filter: blur(5px) !important;
            -webkit-backdrop-filter: blur(5px) !important;
            transition: all 0.2s ease !important;
        }
        .world-clock-card:hover {
            transform: translateY(-2px) !important;
            background: rgba(255, 255, 255, 0.7) !important;
            box-shadow: 0 4px 10px rgba(0,0,0,0.03) !important;
        }
        [data-bs-theme=dark] .world-clock-card {
            background: rgba(15, 23, 42, 0.4) !important;
            border: 1px solid rgba(255, 255, 255, 0.05) !important;
        }
        [data-bs-theme=dark] .world-clock-card:hover {
            background: rgba(15, 23, 42, 0.6) !important;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2) !important;
        }
    </style>
@endpush

    <div class="container-fluid p-4">
        <div class="row justify-content-center">
            <div class="col-12 attendance-container">
                <div class="card attendance-card shadow-sm border-0">

                    <!-- Header -->
                    <div class="page-header">
                        <h4 class="mb-1">Attendance Management</h4>
                        <small>Mark your attendance for the day</small>
                    </div>

                    <!-- Body -->
                    <div class="card-body p-4">
                        <form id="attendanceForm" method="POST">
                            @csrf
                            <div class="row g-4">
                                <!-- LEFT SIDE -->
                                <div class="col-lg-6">
                                    <div class="form-section">
                                        <h5 class="mb-4 text-dark fw-semibold">Employee Information</h5>

                                         @php
                                             $loggedInEmployeeId = $loggedInEmployeeId ?? auth()->user()->employee_id;
                                             if (!$loggedInEmployeeId) {
                                                 $loggedInEmployeeId = \App\Models\Employee\Employee::where('user_id', auth()->id())->first()?->id;
                                             }
                                             $loggedInEmployee = $loggedInEmployee ?? ($loggedInEmployeeId ? \App\Models\Employee\Employee::with('officeInfo.getCurrentBusinessUnit')->find($loggedInEmployeeId) : null);
                                         @endphp
                                         
                                         <!-- Employee -->
                                         @if($loggedInEmployee)
                                             <!-- Employee Info Panel -->
                                             <div class="card border border-light-subtle shadow-sm bg-light-subtle p-3 mb-4 rounded-3">
                                                 <div class="d-flex align-items-center mb-3">
                                                     <div class="avatar-sm bg-primary bg-gradient text-white rounded-circle d-flex align-items-center justify-content-center me-3 fs-5" style="width: 45px; height: 45px;">
                                                         <i class="bi bi-person-fill"></i>
                                                     </div>
                                                     <div>
                                                         <h6 class="mb-0 fw-semibold text-dark">{{ $loggedInEmployee->full_name }}</h6>
                                                         <small class="text-muted">ID: {{ $loggedInEmployee->applicant_id }}</small>
                                                     </div>
                                                 </div>
                                                 <div class="border-top pt-2">
                                                     <div class="row g-2">
                                                         <div class="col-12">
                                                             <small class="text-muted d-block fs-7">Branch Location</small>
                                                             <span class="fw-medium text-dark small">
                                                                 <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                                                                 {{ $loggedInEmployee->officeInfo?->getCurrentBusinessUnit?->name ?? 'N/A' }}
                                                             </span>
                                                         </div>
                                                     </div>
                                                 </div>
                                                 <input type="hidden" id="employeeSelect" name="employee_id" value="{{ $loggedInEmployee->id }}">
                                                 <input type="hidden" id="hidden_employee_id" value="{{ $loggedInEmployee->id }}">
                                             </div>
                                         @else
                                             <!-- Fallback select dropdown -->
                                             <div class="mb-4">
                                                 <label class="form-label">Employee Name</label>
                                                 <select id="employeeSelect" name="employee_id" class="form-select">
                                                     <option value="">Select Employee</option>
                                                     @foreach ($employees as $employee)
                                                         <option value="{{ $employee->id }}">
                                                             {{ $employee->full_name }} ({{ $employee->applicant_id }})
                                                         </option>
                                                     @endforeach
                                                 </select>
                                             </div>
                                         @endif

                                        <!-- Workstation -->
                                        <div class="mb-4" id="workstationDiv">
                                            <label class="form-label">Work Station</label>
                                            <select id="workstationSelect" name="workstation" class="form-select">
                                                <option value="">Select Work Station</option>
                                                <option value="Remote">Remote</option>
                                                <option value="On-Site">On-Site</option>
                                                <option value="Work-From-Home">Work From Home</option>
                                            </select>
                                        </div>

                                        <!-- Message -->
                                        <div id="attendanceMessage" class="alert alert-warning"
                                             style="display:none;"></div>
                                    </div>
                                </div>

                                <!-- RIGHT SIDE -->
                                <div class="col-lg-6">
                                    <div class="clock-section">

                                         <!-- Current Time -->
                                         <div class="time-display d-flex flex-column align-items-center">
                                             <div class="time-label mb-2"><i class="bi bi-clock me-1"></i> Current Time</div>
                                             <div class="w-100 px-3 mb-2">
                                                 <select id="mainClockTzSelect" class="form-select form-select-sm text-center border-0 bg-transparent text-white fw-semibold" style="cursor: pointer; outline: none; box-shadow: none;">
                                                     <option value="Asia/Dhaka" selected class="bg-dark text-white">Bangladesh (Dhaka)</option>
                                                     <option value="Europe/London" class="bg-dark text-white">United Kingdom (London)</option>
                                                     <option value="America/New_York" class="bg-dark text-white">United States (New York)</option>
                                                     <option value="Asia/Dubai" class="bg-dark text-white">United Arab Emirates (Dubai)</option>
                                                     <option value="Asia/Tokyo" class="bg-dark text-white">Japan (Tokyo)</option>
                                                     <option value="Asia/Kolkata" class="bg-dark text-white">India (Kolkata)</option>
                                                     <option value="Asia/Singapore" class="bg-dark text-white">Singapore</option>
                                                     <option value="Australia/Sydney" class="bg-dark text-white">Australia (Sydney)</option>
                                                     <option value="Asia/Riyadh" class="bg-dark text-white">Saudi Arabia (Riyadh)</option>
                                                 </select>
                                             </div>
                                             <div id="currentTime" class="current-time">--:--:--</div>
                                             <div id="currentDate" class="current-date">-- -- --</div>
                                         </div>

                                         <!-- Clock In Time Display -->
                                         <div id="clockInTimeDisplay" class="clock-in-time-display"
                                              style="display:none;">
                                             <div class="time-label"><i class="bi bi-check-circle me-1"></i> Clocked In
                                                 At
                                             </div>
                                             <div id="clockedInTime" class="clocked-time">--:--:--</div>
                                         </div>

                                         <!-- Buttons -->
                                         <div id="clockInContainer" style="display:none;">
                                             <button type="button" id="clockInBtn" class="clock-button clock-in-btn">
                                                 <span class="button-icon"><i
                                                         class="bi bi-box-arrow-in-right"></i></span>
                                                 <span class="button-text">Clock In</span>
                                             </button>
                                         </div>

                                         <div id="locationWarningNote" class="alert alert-danger mt-3" style="display:none; font-size: 0.9rem;">
                                             <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                             <span id="locationWarningText">You are out of the office area.</span>
                                         </div>

                                         <div id="clockOutContainer" style="display:none;">
                                             <button type="button" id="clockOutBtn" class="clock-button clock-out-btn">
                                                 <span class="button-icon"><i class="bi bi-box-arrow-left"></i></span>
                                                 <span class="button-text">Clock Out</span>
                                             </button>
                                         </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Hidden Inputs -->
                            <input type="hidden" name="in_time" id="clockInInput">
                            <input type="hidden" name="out_time" id="clockOutInput">
                            <input type="hidden" name="attendance_id" id="attendanceIdInput">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function () {

            // --- Time Functions ---
            function getCurrentDateTime() {
                const now = new Date();
                return now.getFullYear() + '-' + String(now.getMonth() + 1).padStart(2, '0') + '-' + String(now.getDate()).padStart(2, '0') + ' ' +
                    String(now.getHours()).padStart(2, '0') + ':' + String(now.getMinutes()).padStart(2, '0') + ':' + String(now.getSeconds()).padStart(2, '0');
            }

            function formatTime12Hour(datetime) {
                const date = new Date(datetime);
                let hours = date.getHours();
                const minutes = String(date.getMinutes()).padStart(2, '0');
                const seconds = String(date.getSeconds()).padStart(2, '0');
                const ampm = hours >= 12 ? 'PM' : 'AM';
                hours = hours % 12 || 12;
                return String(hours).padStart(2, '0') + ':' + minutes + ':' + seconds + ' ' + ampm;
            }

            function detectLocalCountry() {
                const tz = Intl.DateTimeFormat().resolvedOptions().timeZone;
                const tzCountryMap = {
                    'Asia/Dhaka': 'Bangladesh',
                    'Asia/Kolkata': 'India',
                    'Asia/Karachi': 'Pakistan',
                    'Asia/Dubai': 'UAE',
                    'Asia/Riyadh': 'Saudi Arabia',
                    'Asia/Tokyo': 'Japan',
                    'Asia/Singapore': 'Singapore',
                    'Europe/London': 'United Kingdom',
                    'Europe/Paris': 'France',
                    'America/New_York': 'USA (EST)',
                    'America/Los_Angeles': 'USA (PST)',
                    'Australia/Sydney': 'Australia'
                };
                return tzCountryMap[tz] || tz.split('/')[1] || tz;
            }

            function formatTzTime(timezone) {
                const options = {
                    timeZone: timezone,
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: true
                };
                return new Date().toLocaleTimeString('en-US', options);
            }

            function formatTzDate(timezone) {
                const now = new Date();
                const options = {
                    timeZone: timezone,
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                };
                return now.toLocaleDateString('en-US', options);
            }

            function updateTime() {
                const selectedTz = $('#mainClockTzSelect').val() || 'Asia/Dhaka';
                $('#currentTime').text(formatTzTime(selectedTz));
                $('#currentDate').text(formatTzDate(selectedTz));
            }

            // Bind change listener
            $('#mainClockTzSelect').on('change', function() {
                updateTime();
            });

            updateTime();
            setInterval(updateTime, 1000);

            // --- AJAX Submission ---
            function submitAttendance(data) {
                $.ajax({
                    url: "{{ route('attendance.clock_in_out_store') }}",
                    type: "POST",
                    data: data,
                    success: function (res) {
                        Swal.fire({icon: 'success', title: res.message, timer: 1500, showConfirmButton: false});
                        loadAttendanceStatus($('#employeeSelect').val());
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            let msg = Object.values(errors)[0][0];
                            Swal.fire('Error', msg, 'error');
                        } else {
                            Swal.fire('Error', 'Something went wrong', 'error');
                        }
                    }
                });
            }

            let currentBranch = null;
            let coveringRadius = null;
            let currentStatus = null;

            function getDistance(lat1, lon1, lat2, lon2) {
                const R = 6371e3; // Earth radius in meters
                const phi1 = lat1 * Math.PI/180;
                const phi2 = lat2 * Math.PI/180;
                const deltaPhi = (lat2-lat1) * Math.PI/180;
                const deltaLambda = (lon2-lon1) * Math.PI/180;

                const a = Math.sin(deltaPhi/2) * Math.sin(deltaPhi/2) +
                          Math.cos(phi1) * Math.cos(phi2) *
                          Math.sin(deltaLambda/2) * Math.sin(deltaLambda/2);
                const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));

                return R * c; // in meters
            }

            function checkLocationRestrictions() {
                $('#locationWarningNote').hide();
                $('#clockInContainer').hide();

                if (currentStatus !== 'clock_in') {
                    return;
                }

                const workstation = $('#workstationSelect').val();
                if (!workstation) {
                    return;
                }

                if (workstation !== 'On-Site') {
                    $('#clockInContainer').show();
                    return;
                }

                if (!navigator.geolocation) {
                    $('#locationWarningText').text('Geolocation is not supported by your browser.');
                    $('#locationWarningNote').fadeIn();
                    return;
                }

                $('#locationWarningText').html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Verifying your location...');
                $('#locationWarningNote').show();

                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const userLat = position.coords.latitude;
                        const userLng = position.coords.longitude;

                        if (!currentBranch || !currentBranch.latitude || !currentBranch.longitude) {
                            $('#locationWarningText').text('Office location coordinates are not configured. Please contact administration.');
                            return;
                        }

                        const branchLat = parseFloat(currentBranch.latitude);
                        const branchLng = parseFloat(currentBranch.longitude);
                        const radius = parseFloat(coveringRadius || 100);

                        const distance = getDistance(userLat, userLng, branchLat, branchLng);

                        if (distance <= radius) {
                            $('#locationWarningNote').hide();
                            $('#clockInContainer').fadeIn();
                        } else {
                            $('#locationWarningText').text('You are out of the office area.');
                            $('#locationWarningNote').fadeIn();
                        }
                    },
                    function(error) {
                        let errMsg = 'Unable to retrieve your location. Please check permissions.';
                        if (error.code === error.PERMISSION_DENIED) {
                            errMsg = 'Location access denied. Please allow location access to clock in On-Site.';
                        }
                        $('#locationWarningText').text(errMsg);
                        $('#locationWarningNote').fadeIn();
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 0
                    }
                );
            }

            function getSelectedEmployeeId() {
                return $('#employeeSelect').val() || $('#hidden_employee_id').val();
            }

            // --- Load Attendance Status ---
            function loadAttendanceStatus(employeeId){
                if(!employeeId) return;
                $('#clockInContainer,#clockOutContainer').hide();
                $('#attendanceMessage').hide();
                $('#locationWarningNote').hide();
                $('#clockInTimeDisplay').hide();
                $('#workstationDiv').show();
                $('#workstationSelect').val(''); // Reset workstation selection
                $('#attendanceIdInput').val(''); // clear old id

                $.ajax({
                    url:"{{ url('get-attendance-details') }}/"+employeeId,
                    type:"GET",
                    success:function(res){
                        currentStatus = res.status;
                        currentBranch = res.branch;
                        coveringRadius = res.covering_radius;

                        if(res.status==='clock_in'){
                            checkLocationRestrictions();
                        }
                        if(res.status==='clock_out'){
                            $('#clockOutContainer').fadeIn();
                            $('#workstationDiv').hide();
                            $('#attendanceIdInput').val(res.record.id); // ✅ Only set ID for Clock Out
                        }
                        if(res.status==='completed'){
                            $('#attendanceMessage').text('⚠️ Attendance already completed for today.').fadeIn();
                        }
                        if(res.status==='off_day'){
                            $('#attendanceMessage').text('🚫 You cannot clock in today. Today is an off day.').fadeIn();
                        }
                        if(res.status==='leave_day'){
                            $('#attendanceMessage').text('🛌 You are on leave today. Clock-in is not allowed.').fadeIn();
                        }
                    }
                });
            }

            $('#clockInBtn').on('click', function(e){
                e.preventDefault();
                const empId = getSelectedEmployeeId();
                if(!empId || !$('#workstationSelect').val()){
                    Swal.fire('Error','Please select employee and workstation.','error');
                    return;
                }
                const clockIn = getCurrentDateTime();
                $('#clockedInTime').text(formatTime12Hour(clockIn));
                $('#clockInTimeDisplay').fadeIn();
                $('#workstationDiv').hide(); // hide workstation after clock in

                $.ajax({
                    url: "{{ route('attendance.clock_in_out_store') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        employee_id: empId,
                        workstation: $('#workstationSelect').val(),
                        in_time: clockIn
                    },
                    success: function(res){
                        Swal.fire({ icon:'success', title: res.message, timer:1500, showConfirmButton:false });
                        $('#attendanceIdInput').val(res.attendance_id); // save attendance_id for clock out
                        loadAttendanceStatus(getSelectedEmployeeId());
                    },
                    error: function(xhr){
                        let msg = 'Something went wrong';
                        if(xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                        Swal.fire('Error', msg,'error');
                    }
                });
            });


            $('#clockOutBtn').on('click', function(e){
                e.preventDefault();
                const empId = getSelectedEmployeeId();
                if(!empId){
                    Swal.fire('Error','Please select employee.','error');
                    return;
                }

                const attendanceId = $('#attendanceIdInput').val();
                if(!attendanceId){
                    Swal.fire('Error','Attendance ID missing. Cannot clock out.','error');
                    return;
                }

                $('#workstationDiv').hide(); // hide workstation on clock out

                $.ajax({
                    url: "{{ route('attendance.clock_in_out_store') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        employee_id: empId,
                        attendance_id: attendanceId,
                        out_time: getCurrentDateTime()
                    },
                    success: function(res){
                        Swal.fire({ icon:'success', title: res.message, timer:1500, showConfirmButton:false });
                        loadAttendanceStatus(getSelectedEmployeeId());
                    },
                    error: function(xhr){
                        let msg = 'Something went wrong';
                        if(xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                        Swal.fire('Error', msg,'error');
                    }
                });
            });


            // --- On Workstation Change ---
            $('#workstationSelect').on('change', function () {
                checkLocationRestrictions();
            });

            // --- On Employee Change ---
            $('#employeeSelect').on('change', function () {
                loadAttendanceStatus($(this).val());
            });

            // --- Initial Load ---
            if (getSelectedEmployeeId()) loadAttendanceStatus(getSelectedEmployeeId());

        });
    </script>
@endpush

