<div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
            <tr>
                <th>Title</th>
                <th>Frequency</th>
                <th>Processing Day</th>
                <th>Status</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payGroups as $group)
                <tr>
                    <td>{{ $group->title }}</td>
                    <td>
                        <span class="badge bg-soft-info text-info">{{ $group->payroll_frequency }}</span>
                    </td>
                    <td>
                        <span class="text-muted">{{ $group->salary_processing_day }}</span>
                    </td>
                    <td>
                        <span class="badge {{ $group->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                            {{ ucfirst($group->status) }}
                        </span>
                    </td>
                    <td class="text-end">
                        @can('pay-groups.edit')
                        <button type="button" class="btn btn-primary btn-sm me-1 edit-pay-group" 
                                data-id="{{ $group->id }}" title="Edit">
                            <i style="height: 12px; width: 12px" data-feather="edit"></i>
                        </button>
                        @endcan
                        @can('pay-groups.delete')
                        <button type="button" class="btn btn-danger btn-sm delete-pay-group" 
                                data-id="{{ $group->id }}" title="Delete">
                            <i style="height: 12px; width: 12px" data-feather="trash"></i>
                        </button>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">No pay groups found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">
    {{ $payGroups->links('pagination::bootstrap-5') }}
</div>
