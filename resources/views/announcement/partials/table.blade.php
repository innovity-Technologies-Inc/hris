@php
    $generalSettings = \App\HelperClass::getGeneralSetting();
@endphp

@if (session('error'))
    <div class="alert alert-danger mb-3">{{ session('error') }}</div>
@endif
@if (session('success'))
    <div class="alert alert-success mb-3">{{ session('success') }}</div>
@endif

<div class="table-responsive">
    <table class="table table-hover mb-0">
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Company</th>
                <th>Posted Date</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($announcements as $announcement)
                <tr>
                    <td>{{ $loop->iteration + ($announcements->currentPage() - 1) * $announcements->perPage() }}</td>
                    <td>
                        <a href="{{ route('announcements.show', $announcement->id) }}" class="fw-semibold text-dark">
                            {{ Str::limit($announcement->title, 50) }}
                        </a>
                    </td>
                    <td>{{ $announcement->company->name ?? 'Global (All)' }}</td>
                    <td>{{ $announcement->created_at->format('M d, Y') }}</td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-1">
                            <a href="{{ route('announcements.show', $announcement->id) }}" class="btn btn-info btn-sm" title="View Details">
                                <i style="height: 12px; width: 12px" data-feather="eye"></i>
                            </a>
                            <a href="{{ route('announcements.pdf', $announcement->id) }}" class="btn btn-success btn-sm no-loader" title="Download PDF">
                                <i style="height: 12px; width: 12px" data-feather="download"></i>
                            </a>
                            @can('announcements.edit')
                                <a href="{{ route('announcements.edit', $announcement->id) }}" class="btn btn-primary btn-sm" title="Edit">
                                    <i style="height: 12px; width: 12px" data-feather="edit"></i>
                                </a>
                            @endcan
                            @can('announcements.delete')
                                <button type="button" class="btn btn-danger btn-sm delete-btn" data-url="{{ route('announcements.destroy', $announcement->id) }}" title="Delete">
                                    <i style="height: 12px; width: 12px" data-feather="trash-2"></i>
                                </button>
                            @endcan
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                        No announcements or notices found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="mt-4" id="paginationContainer">
    {{ $announcements->appends(request()->query())->links() }}
</div>
