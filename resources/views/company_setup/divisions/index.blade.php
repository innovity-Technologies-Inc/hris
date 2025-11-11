@extends('structure.master')

@section('content')
    {{-- List of Company Locations --}}
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                 <div class="card-header">
                    <a type="button" class="btn btn-warning btn-sm" href="{{route('divisions.create')}}">
                        <i style="height: 12px; width: 12px" data-feather="plus"></i> Create
                    </a>
                </div><!-- end card header -->


                <div class="card-body">
                    <form id="filterForm">
                        {{-- First Row: Keyword Search --}}
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="input-group input-group-md">
                                    <input type="text" class="form-control border-end-0" id="keywordSearch"
                                           name="keyword" placeholder="Search divisions by name"
                                           aria-label="Keyword Search" value="{{ request('keyword') }}">
                                    <span class="input-group-text border-start-0 input-group-bg">
                                                    <i class="mdi mdi-magnify text-muted"></i>
                                                </span>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive" id="search-result">
                            @include('company_setup.divisions.search_results')
                        <div class="mt-3">
                            {{$divisions->links()}}
                        </div>
                    </div>
                </div>
            </div><!-- end card -->
        </div>
    </div><!-- end row -->


    
@endsection
