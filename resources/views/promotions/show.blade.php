@extends('structure.master')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4>Promotion Details</h4>
        </div>
        <div class="card-body">
            <table class="table">
                <tr>
                    <th>Employee Name</th>
                    <td>{{ $promotion['full_name'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Current Designation</th>
                    <td>{{ $promotion['office_info']['current_designation_id'] ?? 'Senior Dev' }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td><span class="badge bg-warning">Pending</span></td>
                </tr>
            </table>
            <div class="mt-3">
                <form action="{{ route('promotion.approve', 1) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PUT')
                    <button class="btn btn-success">Approve</button>
                </form>
                <form action="{{ route('promotion.reject', 1) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PUT')
                    <button class="btn btn-danger">Reject</button>
                </form>
                <a href="{{ route('promotion.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </div>
    </div>
</div>
@endsection
