@extends('structure.master')

@section('content')
    <a href="{{ route('organization-structure.create') }}" class="btn btn-warning btn-sm mb-3">
        <i style="height: 12px; width: 12px" data-feather="plus"></i> Create
    </a>

    <ul class="nav nav-tabs mb-3" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ request()->has('key_page') ? '' : 'active' }}" id="tab-board-tab" data-bs-toggle="tab"
                data-bs-target="#tab-board" type="button" role="tab" aria-controls="tab-board"
                aria-selected="{{ request()->has('key_page') ? 'false' : 'true' }}">
                <i class="fas fa-users-cog me-2"></i>Board Members
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ request()->has('key_page') ? 'active' : '' }}" id="tab-key-tab" data-bs-toggle="tab"
                data-bs-target="#tab-key" type="button" role="tab" aria-controls="tab-key"
                aria-selected="{{ request()->has('key_page') ? 'true' : 'false' }}">
                <i class="fas fa-user-tie me-2"></i>Key Peoples
            </button>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane fade {{ request()->has('key_page') ? '' : 'show active' }}" id="tab-board" role="tabpanel"
            aria-labelledby="tab-board-tab">
            {{-- Board Members Section --}}
            <div class="row mb-4">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-users-cog me-2"></i>Board Members</h5>
                        </div><!-- end card header -->

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Profile</th>
                                            <th scope="col">Type</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Position</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php($i = ($boardMembers->currentPage() - 1) * $boardMembers->perPage() + 1)
                                        @foreach ($boardMembers as $member)
                                            <tr>
                                                <th scope="row">{{ $i++ }}</th>
                                                <td>
                                                    {!! \App\HelperClass::generateAvatar($member->photo_path ?? null, $member->name, 32, '#974063', '') !!}
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge
                                                @if ($member->type_form == 'group') bg-primary
                                                @elseif($member->type_form == 'company') bg-success
                                                @else bg-secondary @endif">
                                                        {{ $member->type }}
                                                    </span>
                                                </td>
                                                <td>{{ $member->name }}</td>
                                                <td>{{ $member->position }}</td>
                                                <td>
                                                    @if ($member->status_form == 'active')
                                                        <span class="badge bg-success">Active</span>
                                                    @else
                                                        <span class="badge bg-danger">Inactive</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('organization-structure.show', $member->id) }}"
                                                        class="btn btn-info btn-sm" title="View">
                                                        <i style="height: 12px; width: 12px" data-feather="eye"></i>
                                                    </a>

                                                    <a href="{{ route('organization-structure.edit', $member->id) }}"
                                                        class="btn btn-primary btn-sm" title="Edit">
                                                        <i style="height: 12px; width: 12px" data-feather="edit"></i>
                                                    </a>

                                                    <form
                                                        action="{{ route('organization-structure.destroy', $member->id) }}"
                                                        method="POST" style="display: inline-block">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button class="btn btn-sm btn-danger confirmDelete" title="Delete">
                                                            <i style="height: 12px; width: 12px" data-feather="trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                        @if ($boardMembers->isEmpty())
                                            <tr>
                                                <td colspan="7" class="text-center text-muted">No Board Members found
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-end mt-3">
                                {{ $boardMembers->links() }}
                            </div>
                        </div>
                    </div><!-- end card -->
                </div>
            </div><!-- end row -->

        </div> <!-- end tab-board -->

        <div class="tab-pane fade {{ request()->has('key_page') ? 'show active' : '' }}" id="tab-key" role="tabpanel"
            aria-labelledby="tab-key-tab">
            {{-- Key Members Section --}}
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="fas fa-user-tie me-2"></i>Key Members</h5>
                        </div><!-- end card header -->

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Profile</th>
                                            <th scope="col">Type</th>
                                            <th scope="col">Employee</th>
                                            <th scope="col">Position</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php($i = ($keyMembers->currentPage() - 1) * $keyMembers->perPage() + 1)
                                        @foreach ($keyMembers as $member)
                                            <tr>
                                                <th scope="row">{{ $i++ }}</th>
                                                <td>
                                                    @php($displayName = $member->getEmployee ? $member->getEmployee->full_name : $member->name)
                                                    @php($photoPath = $member->getEmployee ? $member->getEmployee->photo_path : $member->photo_path)
                                                    @php($employeeId = $member->getEmployee ? $member->getEmployee->id : null)
                                                    {!! \App\HelperClass::generateAvatar($photoPath ?? null, $displayName, 32, '#974063', '', $employeeId) !!}
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge
                                                @if ($member->type_form == 'location') bg-danger
                                                @elseif($member->type_form == 'division') bg-warning text-dark
                                                @elseif($member->type_form == 'department') bg-info
                                                @else bg-secondary @endif">
                                                        {{ $member->type }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if ($member->getEmployee)
                                                        <a href="{{ route('employee.profile.general_informations', $member->getEmployee->id) }}"
                                                            class="text-decoration-none text-dark">
                                                            {{ $member->name }}
                                                        </a>
                                                        <br><small class="text-muted">ID:
                                                            {{ $member->getEmployee->system_id }}</small>
                                                    @else
                                                        {{ $member->name }}
                                                    @endif
                                                </td>
                                                <td>{{ $member->position }}</td>
                                                <td>
                                                    @if ($member->status_form == 'active')
                                                        <span class="badge bg-success">Active</span>
                                                    @else
                                                        <span class="badge bg-danger">Inactive</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('organization-structure.show', $member->id) }}"
                                                        class="btn btn-info btn-sm" title="View">
                                                        <i style="height: 12px; width: 12px" data-feather="eye"></i>
                                                    </a>

                                                    <a href="{{ route('organization-structure.edit', $member->id) }}"
                                                        class="btn btn-primary btn-sm" title="Edit">
                                                        <i style="height: 12px; width: 12px" data-feather="edit"></i>
                                                    </a>

                                                    <form
                                                        action="{{ route('organization-structure.destroy', $member->id) }}"
                                                        method="POST" style="display: inline-block">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button class="btn btn-sm btn-danger confirmDelete"
                                                            title="Delete">
                                                            <i style="height: 12px; width: 12px" data-feather="trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                        @if ($keyMembers->isEmpty())
                                            <tr>
                                                <td colspan="7" class="text-center text-muted">No Key Members found
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-end mt-3">
                                {{ $keyMembers->links() }}
                            </div>
                        </div>
                    </div><!-- end card -->
                </div>
            </div><!-- end row -->
        </div> <!-- end tab-key -->
    </div> <!-- end tab-content -->
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            // Add data attribute to pagination links to track which tab they belong to
            // Board Members pagination links
            $('#tab-board').find('.pagination a').each(function() {
                var href = $(this).attr('href');
                if (href && href.includes('board_page')) {
                    $(this).attr('data-tab', 'board');
                }
            });

            // Key Members pagination links
            $('#tab-key').find('.pagination a').each(function() {
                var href = $(this).attr('href');
                if (href && href.includes('key_page')) {
                    $(this).attr('data-tab', 'key');
                }
            });

            // Handle pagination link clicks to maintain tab state
            $(document).on('click', '.pagination a', function(e) {
                var $tab = $(this).closest('.tab-pane');
                var tabId = $tab.attr('id');

                // If clicking key members pagination, add key_page to the URL
                if (tabId === 'tab-key') {
                    var href = $(this).attr('href');
                    // URL already has key_page parameter, just let it navigate
                }
            });
        });
    </script>
@endpush

