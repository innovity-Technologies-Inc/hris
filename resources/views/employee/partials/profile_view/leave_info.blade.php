<style>
    .calendar-day-cell {
        flex: 1 0 14.28%;
        max-width: 14.28%;
        min-height: 80px;
        border: 1px solid rgba(0, 0, 0, 0.05);
        background: rgba(255, 255, 255, 0.5);
        border-radius: 6px;
        position: relative;
        transition: all 0.2s ease;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        align-items: flex-start;
        padding: 6px;
    }
    [data-bs-theme=dark] .calendar-day-cell {
        border-color: rgba(255, 255, 255, 0.05);
        background: rgba(40, 40, 40, 0.5);
    }
    .calendar-day-cell:hover {
        background: rgba(255, 255, 255, 0.8);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    [data-bs-theme=dark] .calendar-day-cell:hover {
        background: rgba(50, 50, 50, 0.8);
    }
    .calendar-day-cell.other-month {
        opacity: 0.35;
        background: rgba(0, 0, 0, 0.02);
    }
    .calendar-day-number {
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 4px;
        color: #6c757d;
    }
    [data-bs-theme=dark] .calendar-day-number {
        color: #adb5bd;
    }
    .calendar-day-cell.today {
        border-color: #0d6efd;
        background: rgba(13, 110, 253, 0.05);
    }
    .calendar-day-cell.today .calendar-day-number {
        color: #0d6efd;
    }
    .calendar-leave-badge {
        font-size: 0.7rem;
        padding: 2px 4px;
        border-radius: 4px;
        width: 100%;
        text-align: left;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-weight: 500;
        margin-top: 2px;
    }
    .leave-approved {
        background-color: rgba(25, 135, 84, 0.12) !important;
        border: 1px solid rgba(25, 135, 84, 0.3) !important;
        color: #198754 !important;
    }
    .leave-pending {
        background-color: rgba(255, 193, 7, 0.12) !important;
        border: 1px solid rgba(255, 193, 7, 0.3) !important;
        color: #b58100 !important;
    }
    .leave-rejected {
        background-color: rgba(220, 53, 69, 0.12) !important;
        border: 1px solid rgba(220, 53, 69, 0.3) !important;
        color: #dc3545 !important;
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body pt-0">
                {{-- Tab Navigation --}}
                <ul class="nav nav-underline border-bottom pt-2 mb-3" id="leave-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active p-2" id="details-tab" data-bs-toggle="tab" href="#leave-details"
                            role="tab" aria-controls="leave-details" aria-selected="true">
                            <span class="d-none d-sm-block">Leave Details</span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link p-2" id="history-tab" data-bs-toggle="tab" href="#leave-history"
                            role="tab" aria-controls="leave-history" aria-selected="false">
                            <span class="d-none d-sm-block">Leave History</span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link p-2" id="calendar-tab" data-bs-toggle="tab" href="#leave-calendar"
                            role="tab" aria-controls="leave-calendar" aria-selected="false">
                            <span class="d-none d-sm-block">Leave Calendar</span>
                        </a>
                    </li>
                </ul>

                {{-- Tab Content --}}
                <div class="tab-content" id="leave-tab-content">
                    {{-- Leave Details Tab --}}
                    <div class="tab-pane fade show active" id="leave-details" role="tabpanel"
                        aria-labelledby="details-tab">
                        <div class="row g-4">
                            @forelse($leaveDetails as $leave)
                                <div class="col-md-6 col-lg-4">
                                    <div class="leave-card">
                                        <div class="leave-card-header">
                                            <h5 class="mb-0">{{ $leave->getPlan->name ?? 'N/A' }}</h5>
                                            <span class="badge bg-success">
                                                {{ $leave->getPlan->leave_type ?? 'N/A' }}
                                            </span>
                                        </div>

                                        <div class="mb-2">
                                            <span
                                                class="badge bg-success">
                                                Active
                                            </span>
                                        </div>

                                        <div class="leave-stats">
                                            <div class="stat-item">
                                                <span class="stat-value text-primary">{{ $leave->getPlan->leave_limit ?? 'N/A' }}</span>
                                                <span class="stat-label">Limit</span>
                                            </div>
                                            @php
                                                $taken = $leave->taken_current_year ?? 0;
                                            @endphp
                                            <div class="stat-item">
                                                <span class="stat-value text-danger">{{ $taken }}</span>
                                                <span class="stat-label">Taken</span>
                                            </div>
                                            <div class="stat-item">
                                                <span class="stat-value text-success">{{ ($leave->getPlan->leave_limit ?? 0) - $taken }}</span>
                                                <span class="stat-label">Remaining</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <i data-feather="info" class="me-2"></i>
                                        No leave details found.
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Leave History Tab --}}
                    <div class="tab-pane fade" id="leave-history" role="tabpanel" aria-labelledby="history-tab">

                        <div class="table-responsive">
                            <table class="table table-hover history-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Leave Name</th>
                                        <th>Type</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Leave Days</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @php $sl = 1; @endphp
                                    @forelse($leaveHistory as $item)
                                        <tr>
                                            <td>{{ $sl++ }}</td>
                                            <td>
                                                <strong>{{ $item->getPlan->name ?? 'N/A' }}</strong>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-success">
                                                    {{ $item->getPlan->leave_type ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td>{{ !empty($item->from) ? \Carbon\Carbon::parse($item->from)->format('jS F, Y') : 'N/A' }}</td>
                                            <td>{{ !empty($item->to) ? \Carbon\Carbon::parse($item->to)->format('jS F, Y') : 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-primary rounded-pill">
                                                    {{ $item->leave_count ?? 0 }} days
                                                </span>
                                            </td>
                                            <td>
                                                @if(($item->status ?? 'pending') == 'pending')
                                                    <span class="badge bg-warning text-dark">
                                                        {{ ucwords($item->status ?? 'pending') }}
                                                    </span>
                                                @elseif(($item->status ?? 'pending') == 'approved')
                                                    <span class="badge bg-success">
                                                        {{ ucwords($item->status ?? 'pending') }}
                                                    </span>
                                                @elseif(($item->status ?? 'pending') == 'rejected')
                                                    <span class="badge bg-danger">
                                                        {{ ucwords($item->status ?? 'pending') }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-info">
                                                        {{ ucwords($item->status ?? 'pending') }}
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <div class="alert alert-info mb-0">
                                                    <i data-feather="info" class="me-2"></i>
                                                    No leave history found.
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Leave Calendar Tab --}}
                    <div class="tab-pane fade" id="leave-calendar" role="tabpanel" aria-labelledby="calendar-tab">
                        <div class="card border mb-3">
                            <div class="card-body">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
                                    <h5 class="mb-0 fw-semibold text-primary"><i class="bi bi-calendar3 me-2"></i>Leave Calendar</h5>
                                    <div class="d-flex gap-2">
                                        <select id="calendarMonthSelect" class="form-select form-select-sm" style="width: 140px;">
                                            @for ($m = 1; $m <= 12; $m++)
                                                <option value="{{ $m - 1 }}" {{ $m == now()->month ? 'selected' : '' }}>
                                                    {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                                </option>
                                            @endfor
                                        </select>
                                        <select id="calendarYearSelect" class="form-select form-select-sm" style="width: 100px;">
                                            @for ($y = now()->year - 3; $y <= now()->year + 2; $y++)
                                                <option value="{{ $y }}" {{ $y == now()->year ? 'selected' : '' }}>
                                                    {{ $y }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>

                                <div class="calendar-grid-wrapper">
                                    <!-- Days of the week headers -->
                                    <div class="row g-1 text-center fw-bold text-muted mb-2">
                                        <div class="col" style="flex: 1 0 14%; max-width: 14%;">Sun</div>
                                        <div class="col" style="flex: 1 0 14%; max-width: 14%;">Mon</div>
                                        <div class="col" style="flex: 1 0 14%; max-width: 14%;">Tue</div>
                                        <div class="col" style="flex: 1 0 14%; max-width: 14%;">Wed</div>
                                        <div class="col" style="flex: 1 0 14%; max-width: 14%;">Thu</div>
                                        <div class="col" style="flex: 1 0 14%; max-width: 14%;">Fri</div>
                                        <div class="col" style="flex: 1 0 14%; max-width: 14%;">Sat</div>
                                    </div>

                                    <!-- Calendar days grid container -->
                                    <div id="calendarDaysGrid" class="row g-1 text-center">
                                        <!-- Days dynamically rendered here by JS -->
                                    </div>
                                </div>

                                <!-- Legend -->
                                <div class="d-flex flex-wrap gap-3 mt-4 pt-3 border-top justify-content-center">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="d-inline-block rounded shadow-sm" style="width: 16px; height: 16px; background-color: rgba(25, 135, 84, 0.15); border: 1px solid #198754;"></span>
                                        <span class="small text-muted fw-semibold">Approved</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="d-inline-block rounded shadow-sm" style="width: 16px; height: 16px; background-color: rgba(255, 193, 7, 0.15); border: 1px solid #ffc107;"></span>
                                        <span class="small text-muted fw-semibold">Pending</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="d-inline-block rounded shadow-sm" style="width: 16px; height: 16px; background-color: rgba(220, 53, 69, 0.15); border: 1px solid #dc3545;"></span>
                                        <span class="small text-muted fw-semibold">Rejected</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Initialize Feather Icons when tab content changes
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });

    // Re-initialize Feather Icons when switching tabs
    const tabButtons = document.querySelectorAll('[data-bs-toggle="tab"]');
    tabButtons.forEach(button => {
        button.addEventListener('shown.bs.tab', function() {
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        });
    });

    // Leave Calendar Rendering Script
    document.addEventListener('DOMContentLoaded', function() {
        const monthSelect = document.getElementById('calendarMonthSelect');
        const yearSelect = document.getElementById('calendarYearSelect');
        const daysGrid = document.getElementById('calendarDaysGrid');
        
        // Leaves data passed from controller
        const leaves = @json($leaveHistory);

        function renderCalendar() {
            const year = parseInt(yearSelect.value);
            const month = parseInt(monthSelect.value);
            
            daysGrid.innerHTML = '';
            
            // First day of the selected month
            const firstDay = new Date(year, month, 1);
            // Day of the week for the first day (0 = Sun, 6 = Sat)
            const firstDayOfWeek = firstDay.getDay();
            // Total days in the selected month
            const totalDays = new Date(year, month + 1, 0).getDate();
            // Total days in the previous month
            const prevMonthTotalDays = new Date(year, month, 0).getDate();
            
            // 1. Render previous month's trailing days
            for (let i = firstDayOfWeek - 1; i >= 0; i--) {
                const dayNum = prevMonthTotalDays - i;
                const cell = createDayCell(dayNum, true);
                daysGrid.appendChild(cell);
            }
            
            // 2. Render current month's days
            const today = new Date();
            for (let d = 1; d <= totalDays; d++) {
                const isToday = today.getDate() === d && today.getMonth() === month && today.getFullYear() === year;
                const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
                
                const cell = createDayCell(d, false, isToday, dateStr);
                daysGrid.appendChild(cell);
            }
            
            // 3. Render next month's leading days to make a complete grid row
            const totalRendered = firstDayOfWeek + totalDays;
            const remainingCells = (7 - (totalRendered % 7)) % 7;
            for (let n = 1; n <= remainingCells; n++) {
                const cell = createDayCell(n, true);
                daysGrid.appendChild(cell);
            }
        }

        function createDayCell(dayNum, isOtherMonth = false, isToday = false, dateStr = null) {
            const cell = document.createElement('div');
            cell.className = 'col calendar-day-cell';
            if (isOtherMonth) {
                cell.classList.add('other-month');
            }
            if (isToday) {
                cell.classList.add('today');
            }
            
            const numDiv = document.createElement('div');
            numDiv.className = 'calendar-day-number';
            numDiv.innerText = dayNum;
            cell.appendChild(numDiv);
            
            // If it's the current month, check for leaves on this date
            if (!isOtherMonth && dateStr && leaves) {
                const matchingLeaves = leaves.filter(leave => {
                    const fromDate = leave.from;
                    const toDate = leave.to;
                    return dateStr >= fromDate && dateStr <= toDate;
                });
                
                matchingLeaves.forEach(leave => {
                    const badge = document.createElement('div');
                    badge.className = 'calendar-leave-badge';
                    
                    const status = (leave.status || 'pending').toLowerCase();
                    if (status === 'approved') {
                        badge.classList.add('leave-approved');
                    } else if (status === 'rejected') {
                        badge.classList.add('leave-rejected');
                    } else {
                        badge.classList.add('leave-pending');
                    }
                    
                    const planName = leave.get_plan ? leave.get_plan.name : 'Leave';
                    badge.innerText = `${planName}`;
                    badge.title = `${planName}\nStatus: ${leave.status}\nDuration: ${leave.from} to ${leave.to}\nDays: ${leave.leave_count}`;
                    
                    cell.appendChild(badge);
                });
            }
            
            return cell;
        }

        // Attach listeners
        monthSelect.addEventListener('change', renderCalendar);
        yearSelect.addEventListener('change', renderCalendar);

        // Initial render
        renderCalendar();
        
        // Re-render when calendar tab is shown
        const calendarTabButton = document.getElementById('calendar-tab');
        if (calendarTabButton) {
            calendarTabButton.addEventListener('shown.bs.tab', renderCalendar);
        }
    });
</script>

