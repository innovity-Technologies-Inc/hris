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
                                @php
                                    $attributes = $log->properties['attributes'] ?? [];
                                    $old = $log->properties['old'] ?? [];
                                    
                                    $formatValue = function($val) {
                                        if (is_array($val) || is_object($val)) return json_encode($val);
                                        if (is_bool($val)) return $val ? 'True' : 'False';
                                        if (is_null($val) || $val === '') return '<em class="text-muted">Empty</em>';
                                        return htmlspecialchars($val);
                                    };
                                @endphp
                                @if(!empty($attributes) || !empty($old))
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered mb-0" style="font-size: 0.85rem;">
                                            <thead class="table-light">
                                                <tr>
                                                    <th width="30%">Field</th>
                                                    @if(!empty($old)) <th width="35%">Old Value</th> @endif
                                                    @if(!empty($attributes)) <th width="35%">New Value</th> @endif
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $allKeys = array_unique(array_merge(array_keys($attributes), array_keys($old)));
                                                @endphp
                                                @foreach($allKeys as $key)
                                                    @if(in_array($key, ['created_at', 'updated_at', 'created_by', 'updated_by'])) @continue @endif
                                                    <tr>
                                                        <td class="fw-semibold text-muted">{{ str_replace('_', ' ', Str::title($key)) }}</td>
                                                        @if(!empty($old))
                                                            <td class="text-danger"><del>{!! $formatValue($old[$key] ?? null) !!}</del></td>
                                                        @endif
                                                        @if(!empty($attributes))
                                                            <td class="text-success">{!! $formatValue($attributes[$key] ?? null) !!}</td>
                                                        @endif
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
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