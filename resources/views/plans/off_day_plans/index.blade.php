@php
    // Dummy data - In production, this would come from the controller
    $offDayPlans = collect([
        (object)[
            'id' => 1,
            'name' => 'Friday Off-Day Coverage',
            'short_name' => 'FRI-OFF',
            'start_time' => '00:00:00',
            'end_time' => '23:59:59',
            'grace_time_before' => 30,
            'grace_time_after' => 30,
            'remuneration_amount' => 1500.00,
            'status' => 'active',
        ],
        (object)[
            'id' => 2,
            'name' => 'Weekend Emergency Plan',
            'short_name' => 'WKD-EMG',
            'start_time' => '08:00:00',
            'end_time' => '20:00:00',
            'grace_time_before' => 15,
            'grace_time_after' => 45,
            'remuneration_amount' => 2000.00,
            'status' => 'active',
        ],
        (object)[
            'id' => 3,
            'name' => 'Holiday Special Coverage',
            'short_name' => null,
            'start_time' => '06:00:00',
            'end_time' => '18:00:00',
            'grace_time_before' => 0,
            'grace_time_after' => 60,
            'remuneration_amount' => 2500.00,
            'status' => 'inactive',
        ],
    ]);
@endphp

@extends('structure.master')
@section('content')
    {{-- Off-Day Plans List --}}

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <a type="button" class="btn btn-warning btn-sm" href="#">
                        <i style="height: 12px; width: 12px" data-feather="plus"></i> Create
                    </a>
                </div><!-- end card header -->

                {{-- Search Filter Form --}}
                <form id="filterForm">
                    <div class="row mb-1 mt-2 mx-4">
                        <div class="col-12">
                            <div class="input-group input-group-md">
                                <input type="text" class="form-control border-end-0" id="keywordSearch" name="keyword"
                                    placeholder="Search off-day plans by name" aria-label="Keyword Search">
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
                                    <th scope="col">Plan Name</th>
                                    <th scope="col">Short Name</th>
                                    <th scope="col">Remuneration</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $sl = 1; // In production: $sl = \App\HelperClass::indexNumberSerialization($offDayPlans);
                                @endphp
                                @foreach ($offDayPlans as $item)
                                    <tr>
                                        <th scope="row">{{ $sl++ }}</th>
                                        <td>{{ $item->name }}</td>
                                        <td>
                                            @if ($item->short_name)
                                                <span class="badge text-bg-secondary">{{ $item->short_name }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <strong class="text-success">
                                                {{ \App\HelperClass::getGeneralSetting()->currency ?? '৳' }}
                                                {{ number_format($item->remuneration_amount, 2) }}
                                            </strong>

                                        </td>
                                        <td>
                                            @if ($item->status == 'active')
                                                <span class="badge text-bg-success">Active</span>
                                            @else
                                                <span class="badge text-bg-danger">Inactive</span>
                                            @endif
                                        </td>

                                        
                                        <td>
                                            <a type="button" class="btn btn-primary btn-sm"
                                                href="#" title="View">
                                                <i style="height: 12px; width: 12px" data-feather="eye"></i>
                                            </a>

                                            <a type="button" class="btn btn-warning btn-sm"
                                                href="#" title="Edit">
                                                <i style="height: 12px; width: 12px" data-feather="edit"></i>
                                            </a>

                                            <form action="#" method="POST"
                                                style="display: inline-block">
                                                @csrf
                                                @method('DELETE')

                                                <button class="btn btn-sm btn-danger confirmDelete" title="Delete"
                                                    type="submit">
                                                    <i style="height: 12px; width: 12px" data-feather="trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>

                        {{-- Pagination - Uncomment in production --}}
                        {{-- <div class="mt-3">
                            {{ $offDayPlans->links() }}
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
