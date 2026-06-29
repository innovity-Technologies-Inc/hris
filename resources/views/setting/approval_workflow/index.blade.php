@extends('structure.master')

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    @can('approval-workflows.create')
                        <a type="button" class="btn btn-warning btn-sm" href="{{ route('setting.approval_workflows.create') }}">
                            <i style="height: 12px; width: 12px" data-feather="plus"></i> Create
                        </a>
                    @endcan
                    
                    @if (session('error'))
                        <div class="alert alert-danger mt-3">{{ session('error') }}</div>
                    @endif
                    @if (session('success'))
                        <div class="alert alert-success mt-3">{{ session('success') }}</div>
                    @endif
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Module Name</th>
                                    <th>Type</th>
                                    <th>Total Steps</th>
                                    <th>Required Approvals</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($workflows as $workflow)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="text-capitalize">{{ str_replace('_', ' ', $workflow->module) }}</td>
                                        <td>
                                            <span class="badge {{ $workflow->type->value === 'sequential' ? 'bg-info' : 'bg-warning text-dark' }}">
                                                {{ ucfirst($workflow->type->value) }}
                                            </span>
                                        </td>
                                        <td>{{ $workflow->total_steps }}</td>
                                        <td>{{ $workflow->type->value === 'random' ? $workflow->required_approvals : 'N/A' }}</td>
                                        <td>
                                            <span class="badge {{ $workflow->is_active ? 'bg-success' : 'bg-danger' }}">
                                                {{ $workflow->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            @can('approval-workflows.edit')
                                                <a href="{{ route('setting.approval_workflows.edit', $workflow->id) }}" class="btn btn-sm btn-outline-secondary">
                                                    <i style="height: 14px; width: 14px" data-feather="edit"></i>
                                                </a>
                                            @endcan
                                            @can('approval-workflows.delete')
                                                <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-url="{{ route('setting.approval_workflows.destroy', $workflow->id) }}">
                                                    <i style="height: 14px; width: 14px" data-feather="trash-2"></i>
                                                </button>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            No approval workflows found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteBtns = document.querySelectorAll('.delete-btn');
        deleteBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                if (confirm('Are you sure you want to delete this workflow?')) {
                    const url = this.getAttribute('data-url');
                    
                    axios.delete(url, {
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => {
                        window.location.reload();
                    })
                    .catch(error => {
                        let errorMsg = 'Something went wrong. Please try again later.';
                        if (error.response && error.response.data && error.response.data.message) {
                            errorMsg = error.response.data.message;
                        }
                        alert(errorMsg);
                    });
                }
            });
        });
    });
</script>
@endpush
