@extends('structure.master')
@section('content')

    {{--    list--}}

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <a type="button" class="btn btn-warning btn-sm" href="{{route('bank_accounts.create')}}">
                        <i style="height: 12px; width: 12px" data-feather="plus"></i> Create
                    </a>
                </div><!-- end card header -->
                {{-- Search Filter Form --}}
                <form id="filterForm">
                    <div class="row mb-1 mt-2 mx-4">
                        <div class="col-12">
                            <div class="input-group input-group-md">
                                <input type="text" class="form-control border-end-0" id="keywordSearch" name="keyword"
                                       placeholder="Search bank accounts by keyword" aria-label="Keyword Search">
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
                                <th scope="col">Account No</th>
                                <th scope="col">Account Holder Name</th>
                                <th scope="col">Bank</th>
                                <th scope="col">Branch</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $sl = \App\HelperClass::indexNumberSerialization($bank_accounts);
                            @endphp
                            @foreach($bank_accounts as $item)
                                <tr>
                                    <th scope="row">{{$sl++}}</th>
                                    <td>{{$item->account_no}}</td>
                                    <td>{{$item->holder_name}}</td>
                                    <td>{{$item->getBank->name}}</td>
                                    <td>{{$item->getBranch->name}}</td>

                                    <td>
                                        @if($item->status == 'active')
                                            <span class="badge text-bg-success">Active</span>
                                        @else
                                            <span class="badge text-bg-danger">Inactive</span>
                                    @endif
                                    <td>
                                        <a type="button" class="btn btn-primary btn-sm" href="{{route('bank_accounts.edit', $item->id)}}">
                                            <i style="height: 12px; width: 12px" data-feather="edit"></i>
                                        </a>

                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#bankAccountsView{{$item->id}}">
                                            <i style="height: 12px; width: 12px" data-feather="eye"></i>
                                        </button>

                                        @include('company_setup.bank_accounts.modal.view')


                                        <form action="{{route('bank_accounts.delete', $item->id)}}" method="POST" style="display: inline-block">
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
                            {{$bank_accounts->links()}}
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>




@endsection
