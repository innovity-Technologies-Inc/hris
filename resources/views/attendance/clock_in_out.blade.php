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

                                        <!-- Employee -->
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

            // --- Load Attendance Status ---
            function loadAttendanceStatus(employeeId){
                if(!employeeId) return;
                $('#clockInContainer,#clockOutContainer').hide();
                $('#attendanceMessage').hide();
                $('#clockInTimeDisplay').hide();
                $('#workstationDiv').show();
                $('#attendanceIdInput').val(''); // clear old id

                $.ajax({
                    url:"{{ url('get-attendance-details') }}/"+employeeId,
                    type:"GET",
                    success:function(res){
                        if(res.status==='clock_in'){
                            $('#clockInContainer').fadeIn();
                            // No need to set attendanceId for Clock In
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
                if(!$('#employeeSelect').val() || !$('#workstationSelect').val()){
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
                        employee_id: $('#employeeSelect').val(),
                        workstation: $('#workstationSelect').val(),
                        in_time: clockIn
                    },
                    success: function(res){
                        Swal.fire({ icon:'success', title: res.message, timer:1500, showConfirmButton:false });
                        $('#attendanceIdInput').val(res.attendance_id); // save attendance_id for clock out
                        loadAttendanceStatus($('#employeeSelect').val());
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
                if(!$('#employeeSelect').val()){
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
                        employee_id: $('#employeeSelect').val(),
                        attendance_id: attendanceId,
                        out_time: getCurrentDateTime()
                    },
                    success: function(res){
                        Swal.fire({ icon:'success', title: res.message, timer:1500, showConfirmButton:false });
                        loadAttendanceStatus($('#employeeSelect').val());
                    },
                    error: function(xhr){
                        let msg = 'Something went wrong';
                        if(xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                        Swal.fire('Error', msg,'error');
                    }
                });
            });


            // --- On Employee Change ---
            $('#employeeSelect').on('change', function () {
                loadAttendanceStatus($(this).val());
            });

            // --- Initial Load ---
            if ($('#employeeSelect').val()) loadAttendanceStatus($('#employeeSelect').val());

        });
    </script>
@endpush
