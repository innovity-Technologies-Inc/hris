<div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
            <tr>
                <th>Grade Code</th>
                <th>Grade Name</th>
                <th>Status</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($salary_grades as $grade)
                <tr>
                    <td><span class="fw-bold text-dark">{{ $grade->grade_code }}</span></td>
                    <td>{{ $grade->grade_name }}</td>
                    <td>
                        <span class="badge {{ $grade->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                            {{ ucfirst($grade->status) }}
                        </span>
                    </td>
                    <td class="text-end">
                        @can('salary-grades.edit')
                        <button type="button" class="btn btn-sm btn-soft-primary me-1 edit-salary-grade" 
                                data-id="{{ $grade->id }}" title="Edit">
                            <i class="mdi mdi-pencil fs-16"></i>
                        </button>
                        @endcan
                        @can('salary-grades.delete')
                        <button type="button" class="btn btn-sm btn-soft-danger delete-salary-grade" 
                                data-id="{{ $grade->id }}" title="Delete">
                            <i class="mdi mdi-delete fs-16"></i>
                        </button>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">No salary grades found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">
    {{ $salary_grades->links('pagination::bootstrap-5') }}
</div>
