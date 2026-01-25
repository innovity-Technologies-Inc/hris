<table class="table table-bordered table-hover mb-0">
    <thead class="table-light">
        <tr>
            <th scope="col" class="text-center">#</th>
            <th scope="col" class="text-center" style="width: 80px;">Image</th>
            <th scope="col">Category</th>
            <th scope="col">Model</th>
            <th scope="col">Ownership</th>
            <th scope="col" class="text-center">Status</th>
            <th scope="col" class="text-center" style="width: 150px;">Action</th>
        </tr>
    </thead>
    <tbody>
        @php
            $sl = ($vehicles->currentPage() - 1) * $vehicles->perPage() + 1;
        @endphp
        @forelse($vehicles as $item)
            <tr>
                <th scope="row" class="text-center">{{ $sl++ }}</th>
                <td class="text-center">
                    @if ($item->vehicle_image)
                        <img src="{{ asset('storage/' . $item->vehicle_image) }}" alt="{{ $item->model_number }}"
                            class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                    @else
                        <div class="bg-light rounded d-flex align-items-center justify-content-center"
                            style="width: 60px; height: 60px;">
                            <i data-feather="image" class="text-muted" style="width: 24px; height: 24px;"></i>
                        </div>
                    @endif
                </td>
                <td>
                    <span class="badge bg-info text-dark">{{ $item->vehicle_category }}</span>
                </td>
                <td>
                    <strong>{{ $item->model_number }}</strong>
                </td>
                <td>{{ $item->ownership_type }}</td>
                <td class="text-center">
                    @if ($item->status == 'Active')
                        <span class="badge text-bg-success">Active</span>
                    @else
                        <span class="badge text-bg-danger">Inactive</span>
                    @endif
                </td>
                <td class="text-center">
                    <a href="{{ route('transport.vehicles.show', $item->id) }}" class="btn btn-info btn-sm"
                        title="View">
                        <i style="height: 12px; width: 12px" data-feather="eye"></i>
                    </a>

                    <a href="{{ route('transport.vehicles.history', $item->id) }}" class="btn btn-success btn-sm"
                        title="See History">
                        <i style="height: 12px; width: 12px" data-feather="clock"></i>
                    </a>

                    <a type="button" class="btn btn-primary btn-sm"
                        href="{{ route('transport.vehicles.edit', $item->id) }}" title="Edit">
                        <i style="height: 12px; width: 12px" data-feather="edit"></i>
                    </a>

                    <form action="{{ route('transport.vehicles.destroy', $item->id) }}" method="POST"
                        style="display: inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger confirmDelete" title="Delete">
                            <i style="height: 12px; width: 12px" data-feather="trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center py-4">
                    <div class="text-muted">
                        <i data-feather="inbox" style="width: 48px; height: 48px;"></i>
                        <p class="mt-2 mb-0">No vehicle acquisitions found</p>
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="mt-3">
    {{ $vehicles->links() }}
</div>
