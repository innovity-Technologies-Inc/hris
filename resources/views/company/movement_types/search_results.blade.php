<div class="table-responsive">
    <table class="table table-bordered mb-0">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Description</th>
                <th scope="col">Status</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @php
                $sl = \App\HelperClass::indexNumberSerialization($movementTypes);
            @endphp
            @forelse($movementTypes as $type)
                <tr>
                    <th scope="row">{{ $sl++ }}</th>
                    <td class="fw-bold">{{ $type->name }}</td>
                    <td>{{ $type->description ?? 'No Description' }}</td>
                    <td>
                        @if ($type->status === 'active')
                            <span class="badge text-bg-success">Active</span>
                        @else
                            <span class="badge text-bg-danger">Inactive</span>
                        @endif
                    </td>
                    <td class="text-start">
                        @can('movement-types.edit')
                        <button type="button" class="btn btn-primary btn-sm me-1 edit-movement-type" 
                                data-id="{{ $type->id }}" title="Edit">
                            <i style="height: 12px; width: 12px" data-feather="edit"></i>
                        </button>
                        @endcan
                        @can('movement-types.delete')
                        <button type="button" class="btn btn-danger btn-sm delete-movement-type" 
                                data-id="{{ $type->id }}" title="Delete">
                            <i style="height: 12px; width: 12px" data-feather="trash"></i>
                        </button>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">No movement types found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">
    {{ $movementTypes->links('pagination::bootstrap-5') }}
</div>
