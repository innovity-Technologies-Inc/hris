@extends('structure.master')
@section('content')

    @php
        $leavePlans = collect([
            (object)[
                'id' => 1,
                'leave_name' => 'Casual Leave',
                'short_name' => 'CL',
                'applicable_gender' => 'Both',
                'day_type' => 'Calculative',
                'leave_type' => 'Casual Leave',
                'leave_limit' => 12,
                'max_no_of_days' => 3,
                'apply_limit' => 'active',
                'allow_fractional_leave' => 'inactive',
                'off_day_include' => 'Excluding',
                'active_ind' => 'active',
            ],
            (object)[
                'id' => 2,
                'leave_name' => 'Sick Leave',
                'short_name' => 'SL',
                'applicable_gender' => 'Both',
                'day_type' => 'Calculative',
                'leave_type' => 'Sick Leave',
                'leave_limit' => 10,
                'max_no_of_days' => 2,
                'apply_limit' => 'active',
                'allow_fractional_leave' => 'active',
                'off_day_include' => 'Excluding',
                'active_ind' => 'active',
            ],
            (object)[
                'id' => 3,
                'leave_name' => 'Maternal Leave',
                'short_name' => 'ML',
                'applicable_gender' => 'Female',
                'day_type' => 'Fixed',
                'leave_type' => 'Maternal Leave',
                'leave_limit' => 90,
                'max_no_of_days' => 90,
                'apply_limit' => 'inactive',
                'allow_fractional_leave' => 'inactive',
                'off_day_include' => 'In Between',
                'active_ind' => 'active',
            ],
            (object)[
                'id' => 4,
                'leave_name' => 'Paternal Leave',
                'short_name' => 'PL',
                'applicable_gender' => 'Male',
                'day_type' => 'Fixed',
                'leave_type' => 'Paternal Leave',
                'leave_limit' => 5,
                'max_no_of_days' => 5,
                'apply_limit' => 'inactive',
                'allow_fractional_leave' => 'inactive',
                'off_day_include' => 'Excluding',
                'active_ind' => 'active',
            ],
            (object)[
                'id' => 5,
                'leave_name' => 'Earned Leave',
                'short_name' => 'EL',
                'applicable_gender' => 'Both',
                'day_type' => 'Calculative',
                'leave_type' => 'Earned Leave',
                'leave_limit' => 15,
                'max_no_of_days' => 5,
                'apply_limit' => 'active',
                'allow_fractional_leave' => 'inactive',
                'off_day_include' => 'Excluding',
                'active_ind' => 'inactive',
            ],
            (object)[
                'id' => 6,
                'leave_name' => 'Compensatory Off',
                'short_name' => 'CO',
                'applicable_gender' => 'Both',
                'day_type' => 'Fixed',
                'leave_type' => 'Comp Off',
                'leave_limit' => 0,
                'max_no_of_days' => 1,
                'apply_limit' => 'inactive',
                'allow_fractional_leave' => 'inactive',
                'off_day_include' => 'Succeeding',
                'active_ind' => 'active',
            ],
        ]);
        $i = 1;
    @endphp

    {{--    list--}}

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <a type="button" class="btn btn-warning btn-sm" href="#">
                        <i style="height: 12px; width: 12px" data-feather="plus"></i> Create
                    </a>
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Leave Name</th>
                                <th scope="col">Short Name</th>
                                <th scope="col">Leave Type</th>
                                <th scope="col">Leave Limit</th>
                                <th scope="col">Applicable For</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($leavePlans as $item)
                                <tr>
                                    <th scope="row">{{ $i++ }}</th>
                                    <td>{{ $item->leave_name }}</td>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ $item->short_name }}</span>
                                    </td>
                                    <td>{{ $item->leave_type }}</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $item->leave_limit }} Days</span>
                                    </td>
                                    <td>
                                        @if($item->applicable_gender == 'Both')
                                            <span class="badge bg-info">Both</span>
                                        @elseif($item->applicable_gender == 'Male')
                                            <span class="badge bg-primary">Male</span>
                                        @else
                                            <span class="badge bg-danger">Female</span>
                                        @endif
                                    </td>
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
                            {{-- $leavePlans->links() --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
