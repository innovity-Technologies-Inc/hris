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
                <th>Target Company</th>
                @if($generalSettings->branch_status == 1)
                    <th>Target Branch</th>
                @endif
                @if($generalSettings->division_status == 1)
                    <th>Target Division</th>
                @endif
                @if($generalSettings->department_status == 1)
                    <th>Target Department</th>
                @endif
                @if($generalSettings->section_status == 1)
                    <th>Target Section</th>
                @endif
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
                    @if($generalSettings->branch_status == 1)
                        <td>{{ $announcement->branch->name ?? 'Global (All)' }}</td>
                    @endif
                    @if($generalSettings->division_status == 1)
                        <td>{{ $announcement->division->name ?? 'Global (All)' }}</td>
                    @endif
                    @if($generalSettings->department_status == 1)
                        <td>{{ $announcement->department->department_name ?? 'Global (All)' }}</td>
                    @endif
                    @if($generalSettings->section_status == 1)
                        <td>{{ $announcement->section->name ?? 'Global (All)' }}</td>
                    @endif
                    <td>{{ $announcement->created_at->format('M d, Y') }}</td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-1">
                            <a href="{{ route('announcements.show', $announcement->id) }}" class="btn btn-sm btn-outline-info" title="View Details">
                                <i style="height: 14px; width: 14px" data-feather="eye"></i>
                            </a>
                            <a href="{{ route('announcements.pdf', $announcement->id) }}" class="btn btn-sm btn-outline-danger" title="Download PDF">
                                <i style="height: 14px; width: 14px" data-feather="download"></i>
                            </a>
                            @can('announcements.edit')
                                <a href="{{ route('announcements.edit', $announcement->id) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                    <i style="height: 14px; width: 14px" data-feather="edit"></i>
                                </a>
                            @endcan
                            @can('announcements.delete')
                                <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-url="{{ route('announcements.destroy', $announcement->id) }}" title="Delete">
                                    <i style="height: 14px; width: 14px" data-feather="trash-2"></i>
                                </button>
                            @endcan
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ 4 + ($generalSettings->branch_status == 1 ? 1 : 0) + ($generalSettings->division_status == 1 ? 1 : 0) + ($generalSettings->department_status == 1 ? 1 : 0) + ($generalSettings->section_status == 1 ? 1 : 0) }}" class="text-center text-muted py-4">
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
