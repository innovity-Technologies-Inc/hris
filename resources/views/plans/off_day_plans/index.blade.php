@extends('structure.master')
@section('content')
    {{-- Off-Day Plans List --}}

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <a type="button" class="btn btn-warning btn-sm" href="{{route('plans.off_day_plans.create')}}">
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
                                    $sl = 1; // In production: $sl = \App\HelperClass::indexNumberSerialization($plans);
                                @endphp
                                @foreach ($plans as $item)
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
                                                {{ number_format($item->remuneration, 2) }}
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
                                                href="{{route('plans.off_day_plans.show', $item->id)}}" title="View">
                                                <i style="height: 12px; width: 12px" data-feather="eye"></i>
                                            </a>

                                            <a type="button" class="btn btn-warning btn-sm"
                                                href="{{route('plans.off_day_plans.edit', $item->id)}}" title="Edit">
                                                <i style="height: 12px; width: 12px" data-feather="edit"></i>
                                            </a>

                                            <form action="{{route('plans.off_day_plans.delete', $item->id)}}" method="POST"
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
                            {{ $plans->links() }}
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
