@extends('structure.master')
@section('content')

    {{--    list--}}

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <a type="button" class="btn btn-warning btn-sm" href="{{ route('plans.shift_plans.create') }}">
                        <i style="height: 12px; width: 12px" data-feather="plus"></i> Create
                    </a>
                </div><!-- end card header -->
                {{-- Search Filter Form --}}
                <form id="filterForm">
                    <div class="row mb-1 mt-2 mx-4">
                        <div class="col-12">
                            <div class="input-group input-group-md">
                                <input type="text" class="form-control border-end-0" id="keywordSearch" name="keyword"
                                    placeholder="Search Shift Plans by Names " aria-label="Keyword Search">
                                <span class="input-group-text border-start-0 input-group-bg">
                                    <i class="mdi mdi-magnify text-muted"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Shift Plan Name</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                @php($i = 1)
                            @foreach($plans as $item)
                                <tr>
                                    <th scope="row">{{ $i++ }}</th>
                                    <td>{{ $item->name }}</td>
                                    <td>
                                        @if($item->active_ind == 'active')
                                            <span class="badge text-bg-success">Active</span>
                                        @else
                                            <span class="badge text-bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a type="button" class="btn btn-primary btn-sm" href="{{ route('plans.shift_plans.show', $item->id) }}" title="View">
                                            <i style="height: 12px; width: 12px" data-feather="eye"></i>
                                        </a>

                                        <a type="button" class="btn btn-warning btn-sm" href="{{ route('plans.shift_plans.edit', $item->id) }}" title="Edit">
                                            <i style="height: 12px; width: 12px" data-feather="edit"></i>
                                        </a>

                                        <form action="{{ route('plans.shift_plans.delete', $item->id) }}" method="POST" style="display: inline-block">
                                            @csrf
                                            @method('DELETE')

                                            <button class="btn btn-sm btn-danger confirmDelete" title="Delete" type="submit">
                                                <i style="height: 12px; width: 12px" data-feather="trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>

                        <div class="mt-3">
                            {{ $plans->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
