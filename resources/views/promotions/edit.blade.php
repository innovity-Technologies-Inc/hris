@extends('structure.master')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4>Edit Promotion Request</h4>
        </div>
        <div class="card-body">
            <p>This is a dummy edit form for {{ $promotion['full_name'] ?? 'Employee' }}.</p>
            <form action="{{ route('promotion.update', 1) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label>Proposed Designation</label>
                    <input type="text" class="form-control" value="Tech Lead">
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('promotion.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
