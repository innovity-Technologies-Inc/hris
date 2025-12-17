@extends('structure.master')

@section('content')
    {{-- List of Holidays --}}
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <a type="button" class="btn btn-warning btn-sm" href="{{ route('holidays.create') }}">
                                <i style="height: 12px; width: 12px" data-feather="plus"></i> Create Holiday
                            </a>
                        </div>
                        <div>
                            <a type="button" class="btn btn-secondary btn-sm" href="{{ route('holidays.calendar') }}">
                                <i style="height: 12px; width: 12px" data-feather="calendar"></i> View Calendar
                            </a>
                        </div>
                    </div>
                </div>
                {{-- Search Filter Form --}}
                <form id="filterForm">
                    <div class="row mb-1 mt-2 mx-4">
                        <div class="col-12">
                            <div class="input-group input-group-md">
                                <input type="text" class="form-control border-end-0" id="keywordSearch" name="keyword"
                                    placeholder="Search holidays by keyword" aria-label="Keyword Search">
                                <span class="input-group-text border-start-0 input-group-bg">
                                    <i class="mdi mdi-magnify text-muted"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="card-body" id="search-result">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">SL</th>
                                    <th scope="col">Title</th>
                                    <th scope="col">From Date</th>
                                    <th scope="col">To Date</th>
                                    <th scope="col">Duration (Days)</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $sl = \App\HelperClass::indexNumberSerialization($holidays);
                                @endphp
                                @foreach ($holidays as $holiday)
                                    @php
                                        $duration = $holiday->start_date->diffInDays($holiday->end_date) + 1;
                                    @endphp
                                    <tr>
                                        <th scope="row">{{ $sl++ }}</th>
                                        <td>{{ $holiday->title }}</td>
                                        <td>{{ $holiday->start_date->format('d M Y') }}</td>
                                        <td>{{ $holiday->end_date->format('d M Y') }}</td>
                                        <td>{{ $duration }}</td>
                                        <td>
                                            @if ($holiday->status == 'active')
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('holidays.edit', $holiday->id) }}"
                                                class="btn btn-primary btn-sm">
                                                <i style="height: 12px; width: 12px" data-feather="edit"></i>
                                            </a>

                                            <form action="{{ route('holidays.delete', $holiday->id) }}" method="POST"
                                                style="display: inline-block">
                                                @csrf
                                                @method('DELETE')

                                                <button class="btn btn-sm btn-danger confirmDelete">
                                                    <i style="height: 12px; width: 12px" data-feather="trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mt-3">
                            {{ $holidays->links() }}
                        </div>
                    </div>
                </div>
            </div><!-- end card -->
        </div>
    </div><!-- end row -->
@endsection
