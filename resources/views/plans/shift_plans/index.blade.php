@extends('structure.master')
@section('content')

    @php
        $shiftPlans = collect([
            (object)[
                'id' => 1,
                'shift_name' => 'Morning Shift - Head Office',
                'clock_in_time' => '09:00:00',
                'clock_out_time' => '18:00:00',
                'treat_as_full_day_minutes' => 480,
                'treat_as_half_day_minutes' => 240,
                'grace_time' => '00:15:00',
                'late_after_minutes' => 15,
                'excessive_late_after_minutes' => 30,
                'early_out_grace_minutes' => 5,
                'early_out_before' => '17:55:00',
                'breakfast_status' => 'active',
                'breakfast_start_time' => '10:00:00',
                'breakfast_end_time' => '10:15:00',
                'lunch_status' => 'active',
                'lunch_start_time' => '13:00:00',
                'lunch_end_time' => '14:00:00',
                'snacks_status' => 'active',
                'snacks_start_time' => '16:00:00',
                'snacks_end_time' => '16:15:00',
                'dinner_status' => 'inactive',
                'dinner_start_time' => null,
                'dinner_end_time' => null,
                'active_ind' => 'active',
                'created_at' => '2025-11-01 10:30:00',
                'updated_at' => '2025-11-04 12:45:00',
            ],
            (object)[
                'id' => 2,
                'shift_name' => 'Evening Shift - Branch A',
                'clock_in_time' => '14:00:00',
                'clock_out_time' => '22:00:00',
                'treat_as_full_day_minutes' => 480,
                'treat_as_half_day_minutes' => 240,
                'grace_time' => '00:10:00',
                'late_after_minutes' => 10,
                'excessive_late_after_minutes' => 30,
                'early_out_grace_minutes' => 5,
                'early_out_before' => '21:50:00',
                'breakfast_status' => 'inactive',
                'breakfast_start_time' => null,
                'breakfast_end_time' => null,
                'lunch_status' => 'active',
                'lunch_start_time' => '17:00:00',
                'lunch_end_time' => '18:00:00',
                'snacks_status' => 'inactive',
                'snacks_start_time' => null,
                'snacks_end_time' => null,
                'dinner_status' => 'active',
                'dinner_start_time' => '20:00:00',
                'dinner_end_time' => '20:15:00',
                'active_ind' => 'active',
                'created_at' => '2025-10-28 14:15:00',
                'updated_at' => '2025-11-03 09:20:00',
            ],
            (object)[
                'id' => 3,
                'shift_name' => 'Night Shift - Branch B',
                'clock_in_time' => '22:00:00',
                'clock_out_time' => '06:00:00',
                'treat_as_full_day_minutes' => 480,
                'treat_as_half_day_minutes' => 240,
                'grace_time' => '00:15:00',
                'late_after_minutes' => 15,
                'excessive_late_after_minutes' => 30,
                'early_out_grace_minutes' => 5,
                'early_out_before' => '05:55:00',
                'breakfast_status' => 'active',
                'breakfast_start_time' => '04:00:00',
                'breakfast_end_time' => '04:30:00',
                'lunch_status' => 'inactive',
                'lunch_start_time' => null,
                'lunch_end_time' => null,
                'snacks_status' => 'active',
                'snacks_start_time' => '01:00:00',
                'snacks_end_time' => '01:15:00',
                'dinner_status' => 'inactive',
                'dinner_start_time' => null,
                'dinner_end_time' => null,
                'active_ind' => 'inactive',
                'created_at' => '2025-10-15 08:00:00',
                'updated_at' => '2025-10-20 11:30:00',
            ],
            (object)[
                'id' => 4,
                'shift_name' => 'Flexible Shift - Remote',
                'clock_in_time' => '08:00:00',
                'clock_out_time' => '17:00:00',
                'treat_as_full_day_minutes' => 480,
                'treat_as_half_day_minutes' => 240,
                'grace_time' => '00:30:00',
                'late_after_minutes' => 30,
                'excessive_late_after_minutes' => 60,
                'early_out_grace_minutes' => 10,
                'early_out_before' => '16:50:00',
                'breakfast_status' => 'active',
                'breakfast_start_time' => '09:00:00',
                'breakfast_end_time' => '09:15:00',
                'lunch_status' => 'active',
                'lunch_start_time' => '12:30:00',
                'lunch_end_time' => '13:30:00',
                'snacks_status' => 'active',
                'snacks_start_time' => '15:00:00',
                'snacks_end_time' => '15:15:00',
                'dinner_status' => 'inactive',
                'dinner_start_time' => null,
                'dinner_end_time' => null,
                'active_ind' => 'active',
                'created_at' => '2025-09-20 09:45:00',
                'updated_at' => '2025-11-02 14:20:00',
            ],
            (object)[
                'id' => 5,
                'shift_name' => 'Half Day Shift - Support',
                'clock_in_time' => '12:00:00',
                'clock_out_time' => '18:00:00',
                'treat_as_full_day_minutes' => 360,
                'treat_as_half_day_minutes' => 180,
                'grace_time' => '00:10:00',
                'late_after_minutes' => 10,
                'excessive_late_after_minutes' => 30,
                'early_out_grace_minutes' => 5,
                'early_out_before' => '17:50:00',
                'breakfast_status' => 'inactive',
                'breakfast_start_time' => null,
                'breakfast_end_time' => null,
                'lunch_status' => 'active',
                'lunch_start_time' => '13:00:00',
                'lunch_end_time' => '14:00:00',
                'snacks_status' => 'active',
                'snacks_start_time' => '15:30:00',
                'snacks_end_time' => '15:45:00',
                'dinner_status' => 'inactive',
                'dinner_start_time' => null,
                'dinner_end_time' => null,
                'active_ind' => 'active',
                'created_at' => '2025-09-10 11:30:00',
                'updated_at' => '2025-11-04 08:15:00',
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
                                <th scope="col">Shift Plan Name</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                            </thead>
                            <tbody>
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
