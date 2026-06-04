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
                        @can('general-settings.edit')
                        <button type="button" class="btn btn-sm btn-soft-primary me-1 edit-pay-group" 
                                data-id="{{ $group->id }}" title="Edit">
                            <i class="mdi mdi-pencil fs-16"></i>
                        </button>
                        @endcan
                        @can('general-settings.delete')
                        <button type="button" class="btn btn-sm btn-soft-danger delete-pay-group" 
                                data-id="{{ $group->id }}" title="Delete">
                            <i class="mdi mdi-delete fs-16"></i>
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
