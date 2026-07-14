@extends('structure.master')

@section('content')
    @php
        $generalSettings = \App\HelperClass::getGeneralSetting();
    @endphp

    <div class="row">
        <div class="col-xl-12">
            <!-- Filter Card -->
            <div class="card mb-4 border-dashed bg-light">
                <div class="card-body">
                    <form method="GET" action="{{ route('announcements.index') }}" class="row g-3 align-items-center">
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Search Keywords</label>
                            <input type="text" name="keyword" class="form-control form-control-sm" placeholder="Title or content..." value="{{ request('keyword') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Company</label>
                            <select name="company_id" class="form-select form-select-sm">
                                <option value="">All Companies</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if($generalSettings->branch_status == 1)
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Branch</label>
                                <select name="branch_id" class="form-select form-select-sm">
                                    <option value="">All Branches</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        @if($generalSettings->division_status == 1)
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Division</label>
                                <select name="division_id" class="form-select form-select-sm">
                                    <option value="">All Divisions</option>
                                    @foreach($divisions as $division)
                                        <option value="{{ $division->id }}" {{ request('division_id') == $division->id ? 'selected' : '' }}>{{ $division->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        @if($generalSettings->department_status == 1)
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Department</label>
                                <select name="department_id" class="form-select form-select-sm">
                                    <option value="">All Departments</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->department_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        @if($generalSettings->section_status == 1)
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Section</label>
                                <select name="section_id" class="form-select form-select-sm">
                                    <option value="">All Sections</option>
                                    @foreach($sections as $sec)
                                        <option value="{{ $sec->id }}" {{ request('section_id') == $sec->id ? 'selected' : '' }}>{{ $sec->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <div class="col-md-2 d-flex align-items-end gap-2 pt-4">
                            <button type="submit" class="btn btn-primary btn-sm flex-grow-1">Search</button>
                            <a href="{{ route('announcements.index') }}" class="btn btn-secondary btn-sm flex-grow-1">Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table Card -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-semibold text-primary">Announcements & Notices</h5>
                    @can('announcements.create')
                        <a class="btn btn-warning btn-sm" href="{{ route('announcements.create') }}">
                            <i style="height: 12px; width: 12px" data-feather="plus"></i> Post Announcement
                        </a>
                    @endcan
                </div>

                <div class="card-body">
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
                                        <td>{{ $loop->iteration }}</td>
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
                    <div class="mt-4">
                        {{ $announcements->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Delete Handler
        const deleteBtns = document.querySelectorAll('.delete-btn');
        deleteBtns.forEach(btn => {
            btn.addEventListener('click', function(event) {
                event.preventDefault();
                const url = this.getAttribute('data-url');
                
                Swal.fire({
                    title: 'Are you sure you want to delete?',
                    text: 'This action cannot be reverted!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirm'
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.delete(url, {
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(response => {
                            Swal.fire({
                                title: 'Deleted!',
                                text: response.data.message || 'Announcement has been deleted.',
                                icon: 'success'
                            }).then(() => {
                                window.location.reload();
                            });
                        })
                        .catch(error => {
                            let errorMsg = 'Something went wrong. Please try again later.';
                            if (error.response && error.response.data && error.response.data.message) {
                                errorMsg = error.response.data.message;
                            }
                            Swal.fire({
                                title: 'Error!',
                                text: errorMsg,
                                icon: 'error'
                            });
                        });
                    }
                });
            });
        });
    });
</script>
@endpush
