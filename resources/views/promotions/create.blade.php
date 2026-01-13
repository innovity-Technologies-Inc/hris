@extends('structure.master')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4>Create Promotion Request</h4>
        </div>
        <div class="card-body">
            <p>This is a dummy create form.</p>
            <form action="{{ route('promotion.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label>Employee Name</label>
                    <input type="text" class="form-control" value="{{ $employees[0]['full_name'] ?? 'John Doe' }}" readonly>
                </div>
                <button type="submit" class="btn btn-success">Submit Request</button>
                <a href="{{ route('promotion.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
