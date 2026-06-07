<div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
            <tr>
                <th>Grade</th>
                <th>Pay Group</th>
                <th>Min Salary</th>
                <th>Max Salary</th>
                <th>Status</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payScales as $scale)
                <tr>
                    <td>
                        <span class="fw-bold text-dark">{{ $scale->grade->grade_code }}</span><br>
                        <small class="text-muted">{{ $scale->grade->grade_name }}</small>
                    </td>
                    <td>
                        <span class="badge bg-soft-info text-info">{{ $scale->payGroup->title }}</span>
                    </td>
                    <td>{{ number_format($scale->min_salary, 2) }}</td>
                    <td>{{ number_format($scale->max_salary, 2) }}</td>
                    <td>
                        <span class="badge {{ $scale->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                            {{ ucfirst($scale->status) }}
                        </span>
                    </td>
                    <td class="text-end">
                        @can('general-settings.edit')
                        <button type="button" class="btn btn-primary btn-sm me-1 edit-pay-scale" 
                                data-id="{{ $scale->id }}" title="Edit">
                            <i style="height: 12px; width: 12px" data-feather="edit"></i>
                        </button>
                        @endcan
                        @can('general-settings.delete')
                        <button type="button" class="btn btn-danger btn-sm delete-pay-scale" 
                                data-id="{{ $scale->id }}" title="Delete">
                            <i style="height: 12px; width: 12px" data-feather="trash"></i>
                        </button>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">No pay scales found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">
    {{ $payScales->links('pagination::bootstrap-5') }}
</div>
