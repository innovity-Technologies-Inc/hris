@extends('structure.master')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 rounded-4 my-4">
            <div class="card-body p-4 p-md-5">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3 d-inline-flex align-items-center justify-content-center">
                            <i class="bi bi-journal-text text-primary fs-4"></i>
                        </div>
                        <h2 class="fs-4 fw-bold text-dark mb-0">Career Movement Logs</h2>
                    </div>
                    @can('transfers.create')
                    <a href="{{ route('transfer.create') }}" class="btn btn-dark btn-lg rounded-3 shadow px-4">
                        <i class="bi bi-plus-circle me-2"></i>New Application
                    </a>
                    @endcan
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle border-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 rounded-start ps-4">#</th>
                                <th class="border-0">Employee</th>
                                <th class="border-0">Requested Unit</th>
                                <th class="border-0">Status</th>
                                <th class="border-0">Created At</th>
                                <th class="border-0 rounded-end text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody id="transferTableBody" class="border-top-0">
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="spinner-border spinner-border-sm text-primary me-2"></div>
                                    Loading transfers...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination Placeholder -->
                <div id="pagination" class="mt-4 d-flex justify-content-end"></div>
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

    fetchCareerMovements();

    function fetchCareerMovements(page = 1) {
        axios.get(`{{ route('transfer.api.list') }}?page=${page}`)
            .then(res => {
                const transfers = res.data.data.data;
                renderTable(transfers);
                renderPagination(res.data.data);
            })
            .catch(err => {
                tableBody.innerHTML = '<tr><td colspan="6" class="text-center text-danger py-5">Failed to load data.</td></tr>';
            });
    }

    function renderTable(data) {
        if (data.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="6" class="text-center py-5 text-muted">No transfer records found.</td></tr>';
            return;
        }

        tableBody.innerHTML = '';
        data.forEach((item, index) => {
            const statusBadge = getStatusBadge(item.status);
            const row = `
                <tr>
                    <td class="ps-4 text-muted">${index + 1}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="bg-light rounded-circle p-2 me-3">
                                <i class="bi bi-person text-secondary"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-dark">${item.employee.full_name}</div>
                                <small class="text-muted">${item.employee.applicant_id}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="text-dark fw-medium">${item.requested_company.name}</div>
                    </td>
                    <td>${statusBadge}</td>
                    <td class="text-muted">${new Date(item.created_at).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' })}</td>
                    <td class="text-end pe-4">
                        <a href="{{ url('transfer/view') }}/${item.id}" class="btn btn-sm btn-light border rounded-pill px-3">
                            <i class="bi bi-eye me-1"></i> View
                        </a>
                    </td>
                </tr>
            `;
            tableBody.insertAdjacentHTML('beforeend', row);
        });
        if (typeof feather !== 'undefined') feather.replace();
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
            li.innerHTML = `<button class="page-link" onclick="window.fetchCareerMovements(${i})">${i}</button>`;
            ul.appendChild(li);
        }

        nav.appendChild(ul);
        pagination.appendChild(nav);
    }

    // Expose to global for onclick
    window.fetchCareerMovements = fetchCareerMovements;
});
</script>
@endpush
