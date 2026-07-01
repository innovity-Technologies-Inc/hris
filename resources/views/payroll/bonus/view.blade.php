@extends('structure.master')
@section('content')
    {{-- Search Section --}}
    <div class="row">
{{--
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm rounded">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i data-feather="dollar-sign" class="me-2"></i>Search Bonus Process Batch
                    </h5>
                </div>
                <div class="card-header border-bottom p-4">
                    <div class="row align-items-start">
                        --}}
{{-- Filter Section --}}{{--

                        <div class="col-md-12">
                            <div class="border rounded shadow-sm p-3 filter-section-bg">
                                <form id="filterForm">
                                    --}}
{{-- Keyword Search --}}{{--

                                    <div class="row mb-2">
                                        <div class="col-12">
                                            <label for="keywordSearch" class="form-label text-muted small fw-semibold mb-1">
                                                Keyword Search
                                            </label>
                                            <div class="input-group input-group-md">
                                                <input type="text" class="form-control border-end-0" id="keywordSearch"
                                                    name="keyword"
                                                    placeholder="Search by batch ID, salary month, approval status..."
                                                    aria-label="Keyword Search" value="{{ request('keyword') }}">
                                                <span class="input-group-text border-start-0 input-group-bg">
                                                    <i class="mdi mdi-magnify text-muted"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    --}}
{{-- Reset Button --}}{{--

                                    <div class="row">
                                        <div class="col-12 text-end">
                                            <button type="button" id="resetFilters"
                                                class="btn btn-outline-secondary btn-sm">
                                                <i class="mdi mdi-refresh"></i> Reset
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
--}}

        {{-- List Section --}}
        <div class="col-lg-12 mt-3">
            <div class="card border-0 shadow-sm rounded">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-money-check-alt me-2"></i>
                            <h5 class="mb-0">Eligible Employees for Bonus & Reward</h5>
                        </div>
                        <a href="{{route('bonus.index')}}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    {{-- Action Buttons --}}
                    <div class="d-flex justify-content-between mb-3">
                        <h6>Salary Month {{ \Carbon\Carbon::parse($salary_month)->format('M, Y') }}</h6>
                    </div>

                    {{-- Approval Engine Workflow History --}}
                    @include('approval_engine.workflow_history', ['model' => $process])

                    <div class="table-responsive" id="search-result">
                        <table class="table table-bordered table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" class="text-center">#</th>
                                    <th scope="col">Profile</th>
                                    <th scope="col">Employee ID</th>
                                    <th scope="col">Employee Name</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @php
                                $sl = \App\HelperClass::indexNumberSerialization($bonuses);
                            @endphp
                                @foreach($bonuses as $item)
                                    <tr>
                                        <th scope="row" class="text-center">{{ $sl++ }}</th>
                                        <td>
                                            {!! \App\HelperClass::generateAvatar(
                                                $item->getEmployee->photo_path,
                                                $item->getEmployee->full_name,
                                                32,
                                                '#974063',
                                                '',
                                                $item->getEmployee->id,
                                            ) !!}
                                        </td>
                                        <td>
                                            <strong class="text-primary">{{ $item->getEmployee->applicant_id }}</strong>
                                        </td>
                                        <td>
                                            <a href="{{ route('employee.profile.general_informations', $item->employee_id) }}">
                                            {{ $item->getEmployee->full_name }}
                                            </a>
                                        </td>
                                        <td>
                                            {{ $item->amount }}
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('bonus.individual_view', $item->id) }}" class="btn btn-outline-info btn-sm">
                                                <i class="fas fa-eye me-1"></i>Details
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{ $bonuses->links() }}

                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
@endsection

