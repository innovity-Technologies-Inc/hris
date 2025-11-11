@extends('structure.master')
@section('content')

    {{--    list--}}

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <a type="button" class="btn btn-warning btn-sm" href="{{route('banks.create')}}">
                        <i style="height: 12px; width: 12px" data-feather="plus"></i> Create
                    </a>
                </div><!-- end card header -->
                {{-- Search Filter Form --}}
                <form id="filterForm">
                    <div class="row mb-1 mt-2 mx-4">
                        <div class="col-12">
                            <div class="input-group input-group-md">
                                <input type="text" class="form-control border-end-0" id="keywordSearch" name="keyword"
                                       placeholder="Search banks by keyword" aria-label="Keyword Search">
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
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Short Name</th>
                                <th scope="col">Code</th>
                                <th scope="col">Contact No</th>
                                <th scope="col">Contact Person</th>
                                <th scope="col">Contact Person No</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $sl = \App\HelperClass::indexNumberSerialization($banks);
                            @endphp
                            @foreach($banks as $item)
                                <tr>
                                    <th scope="row">{{$sl++}}</th>
                                    <td>{{$item->name}}</td>
                                    <td>{{$item->short_name}}</td>
                                    <td>{{$item->bank_code}}</td>
                                    <td>{{$item->contact_no}}</td>
                                    <td>{{$item->contact_person}}</td>
                                    <td>{{$item->contact_person_no}}</td>

                                    <td>
                                        @if($item->status == 'active')
                                            <span class="badge text-bg-success">Active</span>
                                        @else
                                            <span class="badge text-bg-danger">Inactive</span>

                                    @endif
                                    <td>
                                        <a type="button" class="btn btn-primary btn-sm" href="{{route('banks.edit', $item->id)}}">
                                            <i style="height: 12px; width: 12px" data-feather="edit"></i>
                                        </a>


                                        <form action="{{route('banks.delete', $item->id)}}" method="POST" style="display: inline-block">
                                            @csrf
                                            @method('DELETE')

                                            <button class ="btn btn-sm btn-danger confirmDelete">
                                                <i style="height: 12px; width: 12px" data-feather="trash"></i>
                                            </button>


                                        </form>

                                    </td>

                                </tr>
                            @endforeach



                            </tbody>
                        </table>


                        <div class="mt-3">
                            {{$banks->links()}}
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>




@endsection
