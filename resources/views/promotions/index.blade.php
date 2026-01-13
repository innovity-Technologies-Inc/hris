@extends('structure.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Employee Promotions (Dummy Data)</h4>
                    <a href="{{ route('promotion.create') }}" class="btn btn-primary float-end">Create Promotion</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Employee ID</th>
                                    <th>Name</th>
                                    <th>Current Designation</th>
                                    <th>Proposed Designation</th>
                                    <th>Proposed Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($promotions as $promotion)
                                <tr>
                                    <td>{{ $promotion['employee_id'] }}</td>
                                    <td>{{ $promotion['employee_name'] }}</td>
                                    <td>{{ $promotion['current_designation'] }}</td>
                                    <td>{{ $promotion['proposed_designation'] }}</td>
                                    <td>{{ $promotion['proposed_date'] }}</td>
                                    <td>
                                        <span class="badge bg-warning">{{ $promotion['status'] }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('promotion.show', $promotion['id']) }}" class="btn btn-sm btn-info">View</a>
                                        <a href="{{ route('promotion.edit', $promotion['id']) }}" class="btn btn-sm btn-primary">Edit</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
