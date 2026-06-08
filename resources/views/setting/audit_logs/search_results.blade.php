<div class="table-responsive">
    <table class="table table-bordered table-hover mb-0">
        <thead class="table-light">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Date</th>
                <th scope="col">User</th>
                <th scope="col">Action</th>
                <th scope="col">Module / Record</th>
                <th scope="col">Changes</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $index => $log)
                <tr>
                    <th scope="row">{{ $logs->firstItem() + $index }}</th>
                    <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                    <td>
                        @if($log->causer)
                            @if($log->causer->employee_id)
                                <a href="{{ route('employee.profile.general_informations', $log->causer->employee_id) }}" class="text-decoration-none fw-semibold">
                                    {{ $log->causer->name ?? 'System' }}
                                </a>
                            @else
                                <span class="fw-semibold">{{ $log->causer->name ?? 'System' }}</span>
                            @endif
                        @else
                            <span class="text-muted">System</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $badgeClass = 'bg-secondary';
                            if($log->event === 'created') $badgeClass = 'bg-success';
                            elseif($log->event === 'updated') $badgeClass = 'bg-info text-dark';
                            elseif($log->event === 'deleted') $badgeClass = 'bg-danger';
                        @endphp
                        <span class="badge px-3 py-2 {{ $badgeClass }}">{{ ucfirst($log->event) }}</span>
                    </td>
                    <td>
                        <small class="text-muted d-block">{{ class_basename($log->subject_type) }}</small>
                        <span class="fw-bold">ID: {{ $log->subject_id }}</span>
                    </td>
                    <td>
                        @if($log->properties && $log->properties->count() > 0)
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="collapse" data-bs-target="#log-details-{{ $log->id }}">
                                View Details
                            </button>
                            <div class="collapse mt-2" id="log-details-{{ $log->id }}">
                                @if(isset($log->properties['old']))
                                    <div class="mb-1">
                                        <strong>Old:</strong>
                                        <pre class="bg-light p-1 mb-0 rounded" style="font-size: 11px;">{{ json_encode($log->properties['old'], JSON_PRETTY_PRINT) }}</pre>
                                    </div>
                                @endif
                                @if(isset($log->properties['attributes']))
                                    <div>
                                        <strong>New:</strong>
                                        <pre class="bg-light p-1 mb-0 rounded" style="font-size: 11px;">{{ json_encode($log->properties['attributes'], JSON_PRETTY_PRINT) }}</pre>
                                    </div>
                                @endif
                            </div>
                        @else
                            <span class="text-muted">No details</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">No audit logs found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">
    {{ $logs->links('pagination::bootstrap-5') }}
</div>