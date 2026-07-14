<table class="table table-hover mb-0">
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Description</th>
            <th>Status</th>
            <th class="text-end">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($expenseTypes as $expenseType)
            <tr>
                <td>{{ $loop->iteration + ($expenseTypes->currentPage() - 1) * $expenseTypes->perPage() }}</td>
                <td>{{ $expenseType->name }}</td>
                <td>{{ Str::limit($expenseType->description, 50) }}</td>
                <td>
                    <span class="badge rounded-pill {{ $expenseType->status === 'active' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} px-3 py-1">
                        {{ ucfirst($expenseType->status) }}
                    </span>
                </td>
                <td class="text-end">
                    <div class="d-flex justify-content-end gap-1">
                        <button type="button" class="btn btn-info btn-sm view-expense-type" data-id="{{ $expenseType->id }}" title="View">
                            <i style="height: 12px; width: 12px" data-feather="eye"></i>
                        </button>
                        @can('expense-types.edit')
                        <button type="button" class="btn btn-primary btn-sm edit-expense-type" data-id="{{ $expenseType->id }}" title="Edit">
                            <i style="height: 12px; width: 12px" data-feather="edit"></i>
                        </button>
                        @endcan
                        @can('expense-types.delete')
                        <button type="button" class="btn btn-danger btn-sm delete-expense-type" data-id="{{ $expenseType->id }}" title="Delete">
                            <i style="height: 12px; width: 12px" data-feather="trash-2"></i>
                        </button>
                        @endcan
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center text-muted py-4">No expense types found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="mt-4 d-flex justify-content-end">
    {{ $expenseTypes->links() }}
</div>
