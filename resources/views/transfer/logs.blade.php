@extends('structure.master')

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card glass-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0 text-white">Transfer Logs</h5>
                @can('transfers.create')
                <a href="{{ route('transfer.create') }}" class="btn btn-sm btn-warning">
                    <i data-feather="plus" class="me-1"></i> New Application
                </a>
                @endcan
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0 text-white">
                        <thead class="bg-white-10">
                            <tr>
                                <th>#</th>
                                <th>Employee</th>
                                <th>Requested Unit</th>
                                <th>Designation</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="transferTableBody">
                            <tr>
                                <td colspan="7" class="text-center py-4">Loading transfers...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination Placeholder -->
                <div id="pagination" class="mt-3 d-flex justify-content-end"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tableBody = document.getElementById('transferTableBody');
    const pagination = document.getElementById('pagination');

    fetchTransfers();

    function fetchTransfers(page = 1) {
        axios.get(`{{ route('transfer.api.list') }}?page=${page}`)
            .then(res => {
                const transfers = res.data.data.data;
                renderTable(transfers);
                renderPagination(res.data.data);
            })
            .catch(err => {
                tableBody.innerHTML = '<tr><td colspan="7" class="text-center text-danger">Failed to load data.</td></tr>';
            });
    }

    function renderTable(data) {
        if (data.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="7" class="text-center py-4">No transfer records found.</td></tr>';
            return;
        }

        tableBody.innerHTML = '';
        data.forEach((item, index) => {
            const statusBadge = getStatusBadge(item.status);
            const row = `
                <tr>
                    <td>${index + 1}</td>
                    <td>
                        <div class="fw-bold">${item.employee.full_name}</div>
                        <small class="text-white-50">${item.employee.applicant_id}</small>
                    </td>
                    <td>${item.requested_company.name}</td>
                    <td>${item.requested_designation.company_designation}</td>
                    <td>${statusBadge}</td>
                    <td>${new Date(item.created_at).toLocaleDateString()}</td>
                    <td>
                        <a href="{{ url('transfer/view') }}/${item.id}" class="btn btn-sm btn-info">
                            <i data-feather="eye" style="width: 14px; height: 14px;"></i>
                        </a>
                    </td>
                </tr>
            `;
            tableBody.insertAdjacentHTML('beforeend', row);
        });
        feather.replace();
    }

    function getStatusBadge(status) {
        switch(status) {
            case 'pending': return '<span class="badge bg-warning text-dark">Pending</span>';
            case 'approved': return '<span class="badge bg-info">Approved</span>';
            case 'completed': return '<span class="badge bg-success">Completed</span>';
            case 'rejected': return '<span class="badge bg-danger">Rejected</span>';
            default: return `<span class="badge bg-secondary">${status}</span>`;
        }
    }

    function renderPagination(meta) {
        pagination.innerHTML = '';
        if (meta.last_page <= 1) return;

        const nav = document.createElement('nav');
        const ul = document.createElement('ul');
        ul.className = 'pagination pagination-sm mb-0';

        for (let i = 1; i <= meta.last_page; i++) {
            const li = document.createElement('li');
            li.className = `page-item ${meta.current_page === i ? 'active' : ''}`;
            li.innerHTML = `<button class="page-link" onclick="window.fetchTransfers(${i})">${i}</button>`;
            ul.appendChild(li);
        }

        nav.appendChild(ul);
        pagination.appendChild(nav);
    }

    // Expose to global for onclick
    window.fetchTransfers = fetchTransfers;
});
</script>
@endpush
