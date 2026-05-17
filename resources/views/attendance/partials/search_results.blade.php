{{-- Attendance Table --}}
<div class="table-responsive">
    <table class="table table-hover table-borderless align-middle mb-0" id="attendanceTable">
        <thead class="border-bottom" style="background-color: var(--bs-tertiary-bg);">
            <tr class="text-uppercase small fw-semibold text-muted">
                <th scope="col" class="py-3 ps-4">
                    <i class="bi bi-person-badge me-1"></i>Employee Name
                </th>
                <th scope="col" class="py-3 text-center">
                    <i class="bi bi-calendar-check me-1"></i>Shift Type
                </th>
                <th scope="col" class="py-3 text-center">
                    <i class="bi bi-clock-history me-1"></i>Clock In
                </th>
                <th scope="col" class="py-3 text-center">
                    <i class="bi bi-arrow-down-circle me-1"></i>In Status
                </th>
                <th scope="col" class="py-3 text-center">
                    <i class="bi bi-clock me-1"></i>Clock Out
                </th>
                <th scope="col" class="py-3 text-center">
                    <i class="bi bi-arrow-up-circle me-1"></i>Out Status
                </th>
                <th scope="col" class="py-3 text-center">
                    <i class="bi bi-check-circle me-1"></i>Status
                </th>
                <th scope="col" class="py-3 pe-4 text-center">
                    <i class="bi bi-gear me-1"></i>Action
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($attendanceRecords as $record)
                <tr class="border-bottom attendance-row">
                    {{-- Employee Name --}}
                    <td class="ps-4 py-3">
                        <span class="fw-semibold text-dark">{{ $record->getEmployee->full_name }}</span>
                    </td>

                    {{-- Shift Type --}}
                    <td class="py-3 text-center">
                        <span class="badge bg-info text-white px-2 py-1">
                            {{ $record->shift_type }}
                        </span>
                    </td>

                    {{-- Clock In --}}
                    <td class="py-3 text-center">
                        @if ($record->in_time)
                            <div class="small text-muted" style="font-size: 0.7rem;">
                                {{ \Carbon\Carbon::parse($record->in_time)->format('d M, Y') }}</div>
                            <span
                                class="fw-semibold">{{ \Carbon\Carbon::parse($record->in_time)->format('h:i A') }}</span>
                        @else
                            <span class="fw-medium">—</span>
                        @endif
                    </td>

                    {{-- Clock In Status --}}
                    <td class="py-3 text-center">
                        <span
                            class="badge rounded-pill
                                                @if ($record->in_status == 'On-Time') bg-success
                                                @elseif($record->in_status == 'Excessive-Late') bg-danger
                                                @elseif($record->in_status == 'Late') bg-warning text-dark
                                                @else bg-secondary @endif px-2 py-1">
                            {{ $record->in_status ?? 'N/A' }}
                        </span>
                    </td>

                    {{-- Clock Out --}}
                    <td class="py-3 text-center">
                        @if ($record->out_time)
                            <div class="small text-muted" style="font-size: 0.7rem;">
                                {{ \Carbon\Carbon::parse($record->out_time)->format('d M, Y') }}</div>
                            <span
                                class="fw-semibold">{{ \Carbon\Carbon::parse($record->out_time)->format('h:i A') }}</span>
                        @else
                            <span class="fw-medium">—</span>
                        @endif
                    </td>

                    {{-- Clock Out Status --}}
                    <td class="py-3 text-center">
                        <span
                            class="badge rounded-pill
                                                @if ($record->out_status == 'On-Time') bg-success
                                                @elseif($record->out_status == 'Early-Exit') bg-danger
                                                @else bg-secondary @endif px-2 py-1">
                            {{ $record->out_status ?? 'N/A' }}
                        </span>
                    </td>

                    {{-- Status --}}
                    <td class="py-3 text-center">
                        <span
                            class="badge rounded-pill
                                                bg-secondary px-2 py-1 fw-semibold">
                            {{ $record->attendance_status }}
                        </span>
                    </td>

                    {{-- Action --}}
                    <td class="py-3 pe-4 text-center">
                        @can('attendance.view')
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                            data-bs-target="#viewAttendanceModal{{ $record->id }}" title="View Details">
                            <i class="bi bi-eye"></i>
                        </button>
                        @endcan
                    </td>
                </tr>

                {{-- Include View Modal --}}
                @include('attendance.view_modal', ['record' => $record])
            @endforeach
        </tbody>
    </table>
</div>

{{-- Table Footer with Pagination --}}
<div class="border-top p-4" style="background-color: var(--bs-tertiary-bg);">
    <div class="row align-items-center g-3">
        <div class="col-md-6">
            <div class="text-muted small">
                Showing <strong>{{ $attendanceRecords->firstItem() ?? 0 }}</strong> to
                <strong>{{ $attendanceRecords->lastItem() ?? 0 }}</strong> of
                <strong>{{ $attendanceRecords->total() }}</strong> total records
            </div>
        </div>
        <div class="col-md-6">
            <nav aria-label="Attendance pagination">
                {{ $attendanceRecords->links('pagination::bootstrap-5') }}
            </nav>
        </div>
    </div>
</div>
