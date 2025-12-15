@extends('structure.master')

@section('content')
    <style>
        /* Calendar container - Clean professional styling */
        #calendar {
            max-width: 1200px;
            margin: 0 auto;
            border-radius: 8px;
            padding: 24px;
        }

        html[data-bs-theme='light'] #calendar {
            background: #ffffff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border: 1px solid #e5e7eb;
        }

        html[data-bs-theme='dark'] #calendar {
            background: #1e293b;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            border: 1px solid #334155;
        }

        .fc {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        /* Event styling - Professional minimal */
        .fc-event {
            cursor: pointer;
            border: none !important;
            padding: 5px 8px;
            border-radius: 4px;
            background: #6b7280;
            transition: all 0.2s ease;
        }

        .fc-event:hover {
            background: #4b5563;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
        }

        /* Today cell - Subtle highlight */
        html[data-bs-theme='light'] .fc-daygrid-day.fc-day-today {
            background: #eff6ff !important;
            border: 2px solid #0284c7 !important;
            box-shadow: inset 0 0 8px rgba(2, 132, 199, 0.1);
        }

        html[data-bs-theme='dark'] .fc-daygrid-day.fc-day-today {
            background: #1e3a5f !important;
            border: 2px solid #60a5fa !important;
            box-shadow: inset 0 0 8px rgba(96, 165, 250, 0.1);
        }

        /* Day cell hover - Minimal effect */
        html[data-bs-theme='light'] .fc-daygrid-day:hover {
            background: #f9fafb;
        }

        html[data-bs-theme='dark'] .fc-daygrid-day:hover {
            background: #2d3748;
        }

        .fc-event-title {
            font-size: 0.75rem;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            font-weight: 500;
            color: #ffffff;
        }

        /* Calendar header - Professional */
        html[data-bs-theme='light'] .fc-toolbar-title {
            color: #1f2937;
        }

        html[data-bs-theme='dark'] .fc-toolbar-title {
            color: #f3f4f6;
        }

        .fc-toolbar-title {
            font-size: 1.5rem !important;
            font-weight: 600;
            letter-spacing: -0.025em;
        }

        .fc .fc-toolbar {
            margin-bottom: 1.5rem;
            gap: 0.75rem;
            padding: 16px;
            border-radius: 6px;
        }

        html[data-bs-theme='light'] .fc .fc-toolbar {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
        }

        html[data-bs-theme='dark'] .fc .fc-toolbar {
            background: #0f172a;
            border: 1px solid #334155;
        }

        /* Button styling - Clean and professional */
        .fc .fc-button {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            border-radius: 4px;
            font-weight: 500;
            text-transform: capitalize;
            transition: all 0.2s ease;
            border: 1px solid transparent;
        }

        .fc .fc-button:hover {
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .fc .fc-button-primary {
            background: #374151;
            border-color: #374151;
            color: #ffffff;
        }

        .fc .fc-button-primary:hover {
            background: #1f2937;
            border-color: #1f2937;
        }

        .fc .fc-button-primary:not(:disabled):active,
        .fc .fc-button-primary:not(:disabled).fc-button-active {
            background: #111827;
            border-color: #111827;
        }

        /* Day number styling */
        html[data-bs-theme='light'] .fc .fc-daygrid-day-number {
            color: #374151;
        }

        html[data-bs-theme='dark'] .fc .fc-daygrid-day-number {
            color: #d1d5db;
        }

        .fc .fc-daygrid-day-number {
            padding: 8px;
            font-weight: 500;
        }

        /* Table header - Clean professional */
        .fc-theme-standard th {
            padding: 12px;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        html[data-bs-theme='light'] .fc-theme-standard th {
            background: #f3f4f6;
            color: #6b7280;
            border-color: #e5e7eb;
        }

        html[data-bs-theme='dark'] .fc-theme-standard th {
            background: #111827;
            color: #9ca3af;
            border-color: #374151;
        }

        /* Table cell borders - Enhanced visibility */
        html[data-bs-theme='light'] .fc-theme-standard td {
            border-color: #d1d5db;
            background: #ffffff;
            border-width: 1px;
        }

        html[data-bs-theme='dark'] .fc-theme-standard td {
            border-color: #475569;
            background: #1e293b;
            border-width: 1px;
        }

        .fc-theme-standard .fc-scrollgrid {
            border-radius: 6px;
            overflow: hidden;
        }

        html[data-bs-theme='light'] .fc-theme-standard .fc-scrollgrid {
            border-color: #cbd5e1;
            border-width: 1px;
        }

        html[data-bs-theme='dark'] .fc-theme-standard .fc-scrollgrid {
            border-color: #475569;
            border-width: 1px;
        }

        /* Row separators */
        .fc .fc-daygrid-body .fc-row {
            border-width: 1px;
        }

        /* Day cell borders */
        .fc .fc-daygrid-day {
            border-width: 1px;
        }

        /* Today indicator - More prominent */
        html[data-bs-theme='light'] .fc-daygrid-day.fc-day-today {
            background-color: #eff6ff !important;
            border-color: #0284c7 !important;
            border-width: 2px !important;
            position: relative;
        }

        html[data-bs-theme='dark'] .fc-daygrid-day.fc-day-today {
            background-color: #1e3a5f !important;
            border-color: #60a5fa !important;
            border-width: 2px !important;
            position: relative;
        }

        /* Today pseudo-element for corner mark */
        .fc-daygrid-day.fc-day-today::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 8px;
            height: 8px;
            background: #0284c7;
        }

        html[data-bs-theme='dark'] .fc-daygrid-day.fc-day-today::before {
            background: #60a5fa;
        }

        /* Date selector - Professional minimal */
        .date-selector-group {
            display: flex;
            gap: 12px;
            align-items: center;
            margin-bottom: 1.5rem;
            padding: 16px;
            border-radius: 6px;
        }

        html[data-bs-theme='light'] .date-selector-group {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
        }

        html[data-bs-theme='dark'] .date-selector-group {
            background: #0f172a;
            border: 1px solid #334155;
        }

        .date-selector-group label {
            font-weight: 500;
            font-size: 0.875rem;
        }

        html[data-bs-theme='light'] .date-selector-group label {
            color: #6b7280;
        }

        html[data-bs-theme='dark'] .date-selector-group label {
            color: #9ca3af;
        }

        .date-selector-group select {
            padding: 0.5rem 1rem;
            border-radius: 4px;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        html[data-bs-theme='light'] .date-selector-group select {
            border: 1px solid #d1d5db;
            background: #ffffff;
            color: #374151;
        }

        html[data-bs-theme='dark'] .date-selector-group select {
            border: 1px solid #4b5563;
            background: #1e293b;
            color: #e5e7eb;
        }

        .date-selector-group select:hover {
            border-color: #6b7280;
        }

        .date-selector-group select:focus {
            border-color: #374151;
            outline: none;
            box-shadow: 0 0 0 3px rgba(55, 65, 81, 0.1);
        }

        .date-selector-group .btn {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: 4px;
            transition: all 0.2s ease;
        }

        .date-selector-group .btn:hover {
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        /* FullCalendar dark mode overrides */
        html[data-bs-theme='dark'] .fc {
            --fc-border-color: #374151;
            --fc-page-bg-color: #1e293b;
        }

        html[data-bs-theme='dark'] .fc .fc-col-header-cell-cushion,
        html[data-bs-theme='dark'] .fc .fc-daygrid-day-number {
            color: #d1d5db;
        }

        html[data-bs-theme='dark'] .fc-daygrid-day-frame {
            background: #1e293b;
        }
    </style>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <h5 class="card-title mb-0">
                            Holiday Calendar
                        </h5>
                        <a href="{{ route('holidays.index') }}" class="btn btn-secondary btn-sm">
                            <i style="height: 12px; width: 12px" data-feather="list"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Month and Year Selector -->
                    <div class="date-selector-group">
                        <label class="mb-0 fw-semibold">Jump to:</label>
                        <select id="monthSelector" class="form-select form-select-sm" style="width: auto;">
                            <option value="0">January</option>
                            <option value="1">February</option>
                            <option value="2">March</option>
                            <option value="3">April</option>
                            <option value="4">May</option>
                            <option value="5">June</option>
                            <option value="6">July</option>
                            <option value="7">August</option>
                            <option value="8">September</option>
                            <option value="9">October</option>
                            <option value="10">November</option>
                            <option value="11">December</option>
                        </select>
                        <select id="yearSelector" class="form-select form-select-sm" style="width: auto;">
                            <!-- Years will be populated by JavaScript -->
                        </select>
                        <button id="jumpToDate" class="btn btn-primary btn-sm">
                            <i style="height: 12px; width: 12px" data-feather="arrow-right"></i> Go
                        </button>
                    </div>

                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Holiday Details Modal -->
    <div class="modal fade" id="holidayModal" tabindex="-1" aria-labelledby="holidayModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="holidayModalLabel">Holiday Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="holiday-details">
                        <div class="mb-3">
                            <label class="text-muted small mb-1">Holiday Title</label>
                            <h5 class="mb-0" id="modalHolidayTitle"></h5>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="text-muted small mb-1">Start Date</label>
                                <p class="mb-0 fw-semibold" id="modalStartDate"></p>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="text-muted small mb-1">End Date</label>
                                <p class="mb-0 fw-semibold" id="modalEndDate"></p>
                            </div>
                        </div>
                        <div>
                            <label class="text-muted small mb-1">Duration</label>
                            <p class="mb-0 fw-semibold" id="modalDuration"></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            let calendar;

            // Populate year selector
            const yearSelector = document.getElementById('yearSelector');
            const currentYear = new Date().getFullYear();
            for (let year = currentYear - 5; year <= currentYear + 5; year++) {
                const option = document.createElement('option');
                option.value = year;
                option.textContent = year;
                if (year === currentYear) option.selected = true;
                yearSelector.appendChild(option);
            }

            // Set current month
            const monthSelector = document.getElementById('monthSelector');
            monthSelector.value = new Date().getMonth();

            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,dayGridWeek'
                },
                buttonText: {
                    today: 'Today',
                    month: 'Month',
                    week: 'Week'
                },
                height: 'auto',
                contentHeight: 650,
                aspectRatio: 1.8,
                fixedWeekCount: false,
                showNonCurrentDates: true,
                events: function(info, successCallback, failureCallback) {
                    fetch('{{ route('holidays.get_holidays') }}')
                        .then(response => response.json())
                        .then(data => {
                            successCallback(data);
                        })
                        .catch(error => {
                            console.error('Error fetching holidays:', error);
                            failureCallback(error);
                        });
                },
                eventDisplay: 'block',
                eventColor: '#dc3545',
                eventTextColor: '#ffffff',
                displayEventTime: false,
                eventContent: function(arg) {
                    // Limit title to 15 characters
                    let title = arg.event.title;
                    if (title.length > 15) {
                        title = title.substring(0, 15);
                    }

                    return {
                        html: '<div class="fc-event-main-frame"><div class="fc-event-title-container"><div class="fc-event-title fc-sticky">' +
                            title + '</div></div></div>'
                    };
                },
                eventClick: function(info) {
                    info.jsEvent.preventDefault();

                    // Calculate duration
                    const startDate = new Date(info.event.start);
                    const endDate = new Date(info.event.end.getTime() -
                        86400000); // FullCalendar end is exclusive
                    const duration = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24)) + 1;

                    // Format dates
                    const options = {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    };
                    const startFormatted = startDate.toLocaleDateString('en-US', options);
                    const endFormatted = endDate.toLocaleDateString('en-US', options);

                    // Populate modal
                    document.getElementById('modalHolidayTitle').textContent = info.event.title;
                    document.getElementById('modalStartDate').textContent = startFormatted;
                    document.getElementById('modalEndDate').textContent = endFormatted;
                    document.getElementById('modalDuration').textContent = duration + (duration === 1 ?
                        ' day' :
                        ' days');

                    // Show modal
                    const modal = new bootstrap.Modal(document.getElementById('holidayModal'));
                    modal.show();
                },
                datesSet: function(info) {
                    // Update selectors when calendar changes
                    const currentDate = calendar.getDate();
                    monthSelector.value = currentDate.getMonth();
                    yearSelector.value = currentDate.getFullYear();
                }
            });

            calendar.render();

            // Jump to date functionality
            document.getElementById('jumpToDate').addEventListener('click', function() {
                const month = parseInt(monthSelector.value);
                const year = parseInt(yearSelector.value);
                const newDate = new Date(year, month, 1);
                calendar.gotoDate(newDate);

                // Re-initialize feather icons if needed
                if (typeof feather !== 'undefined') {
                    feather.replace();
                }
            });

            // Initialize feather icons
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        });
    </script>
@endsection
