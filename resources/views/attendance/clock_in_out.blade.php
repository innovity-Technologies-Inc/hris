@extends('structure.master')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <div class="container-fluid p-4">
        <div class="row justify-content-center">
            <div class="col-12 attendance-container">
                <div class="card shadow-sm border-0">

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
                                        <div class="time-display">
                                            <div class="time-label"><i class="bi bi-clock me-1"></i> Current Time</div>
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

            function updateTime() {
                const now = new Date();
                let hours = now.getHours();
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const seconds = String(now.getSeconds()).padStart(2, '0');
                const ampm = hours >= 12 ? 'PM' : 'AM';
                hours = hours % 12 || 12;
                $('#currentTime').text(String(hours).padStart(2, '0') + ':' + minutes + ':' + seconds + ' ' + ampm);
                $('#currentDate').text(now.toLocaleDateString('en-US', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                }));
            }

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

