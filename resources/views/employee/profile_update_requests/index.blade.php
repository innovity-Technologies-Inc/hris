@extends('structure.master')
@section('title', 'Profile Update Requests')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Profile Update Requests</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="header-title">List of Requests</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Employee Name</th>
                            <th>Section</th>
                            <th>Status</th>
                            <th>Requested At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($requests as $index => $request)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $request->employee->full_name ?? 'N/A' }} ({{ $request->employee->punch_card_no ?? '' }})</td>
                            <td><span class="badge bg-info text-capitalize">{{ str_replace('_', ' ', $request->section) }}</span></td>
                            <td>
                                @if($request->status === 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif($request->status === 'approved')
                                    <span class="badge bg-success">Approved</span>
                                @else
                                    <span class="badge bg-danger">Rejected</span>
                                @endif
                            </td>
                            <td>{{ $request->created_at->format('d M Y, h:i A') }}</td>
                            <td>
                                <a href="{{ route('profile_update_requests.show', $request->id) }}" class="btn btn-sm btn-primary">
                                    <i class="mdi mdi-eye"></i> View
                                </a>
                                @if(auth()->user()->can('profile-update-requests.delete'))
                                <button type="button" class="btn btn-sm btn-danger" onclick="deleteRequest({{ $request->id }})">
                                    <i class="mdi mdi-trash-can"></i> Delete
                                </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
                @if(method_exists($requests, 'links'))
                <div class="mt-3">
                    {{ $requests->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    function deleteRequest(id) {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                axios.delete('{{ url('employees/update-requests') }}/' + id)
                    .then(response => {
                        if(response.data.success) {
                            Swal.fire("Deleted!", response.data.message, "success").then(() => location.reload());
                        }
                    })
                    .catch(error => {
                        Swal.fire("Error!", "Something went wrong.", "error");
                    });
            }
        });
    }
</script>
@endsection