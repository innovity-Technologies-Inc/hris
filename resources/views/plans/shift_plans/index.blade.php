@extends('structure.master')
@section('content')

    {{--    list--}}

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <a type="button" class="btn btn-primary btn-sm" href="{{ route('plans.shift_plans.create') }}">
                        <i style="height: 12px; width: 12px" data-feather="plus"></i> Create
                    </a>
                </div><!-- end card header -->

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
                            @foreach($shiftPlans as $item)
                                <tr>
                                    <th scope="row">{{ $i++ }}</th>
                                    <td>{{ $item->shift_name }}</td>
                                    <td>
                                        @if($item->active_ind == 'active')
                                            <span class="badge text-bg-success">Active</span>
                                        @else
                                            <span class="badge text-bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a type="button" class="btn btn-primary btn-sm" href="#" title="View">
                                            <i style="height: 12px; width: 12px" data-feather="eye"></i>
                                        </a>

                                        <a type="button" class="btn btn-warning btn-sm" href="#" title="Edit">
                                            <i style="height: 12px; width: 12px" data-feather="edit"></i>
                                        </a>

                                        <form action="#" method="POST" style="display: inline-block">
                                            @csrf
                                            @method('DELETE')

                                            <button class="btn btn-sm btn-danger confirmDelete" title="Delete">
                                                <i style="height: 12px; width: 12px" data-feather="trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>

                        <div class="mt-3">
                            {{-- $shiftPlans->links() --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
