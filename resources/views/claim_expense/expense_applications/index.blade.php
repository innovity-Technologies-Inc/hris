@extends('structure.master')

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    @can('claim-expenses.create')
                    <a href="{{ route('claim_expenses.create') }}" class="btn btn-warning btn-sm">
                        <i style="height: 12px; width: 12px" data-feather="plus"></i> New Claim
                    </a>
                    @endcan
                </div>

                <div class="card-body">
                    <form id="filterForm">
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="input-group input-group-md">
                                    <input type="text" class="form-control border-end-0" id="searchKeyword"
                                           name="keyword" placeholder="Search by employee, expense type, purpose or status"
                                           aria-label="Keyword Search">
                                    <span class="input-group-text border-start-0 input-group-bg">
                                        <i class="mdi mdi-magnify text-muted"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive" id="logContainer">
                        <div class="text-center py-4 text-muted">Loading Data...</div>
                    </div>
                </div>
            </div><!-- end card -->
        </div>
    </div><!-- end row -->
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('logContainer');
    const searchInput = document.getElementById('searchKeyword');

    // Initial Load
    fetchLogs();

    // Search Logic
    let searchTimer;
    searchInput.addEventListener('input', () => {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(fetchLogs, 500);
    });

    function fetchLogs(url = "{{ route('claim_expenses.index') }}") {
        const keyword = searchInput.value;
        const fullUrl = `${url}${url.includes('?') ? '&' : '?'}keyword=${keyword}`;

        axios.get(fullUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(response => {
                container.innerHTML = response.data;
                bindDeleteButtons();
                if (window.feather) {
                    feather.replace();
                }
            })
            .catch(error => {
                console.error(error);
                container.innerHTML = '<div class="text-danger text-center py-4">Failed to load data.</div>';
            });
    }

    function bindDeleteButtons() {
        document.querySelectorAll('.delete-application').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.getAttribute('data-id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.delete(`/claim-expense/applications/${id}`, {
                            data: { _token: "{{ csrf_token() }}" }
                        }).then(response => {
                            Swal.fire('Deleted!', response.data.message, 'success');
                            fetchLogs();
                        }).catch(error => {
                            Swal.fire('Error!', error.response.data.message || 'Deletion failed', 'error');
                        });
                    }
                });
            });
        });
    }

    // Pagination
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        fetchLogs($(this).attr('href'));
    });
});
</script>
@endpush
