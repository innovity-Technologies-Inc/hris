@extends('structure.master')

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0 fw-semibold text-dark">
                        <i class="mdi mdi-receipt me-2 text-primary"></i>Bill Pay Management
                    </h5>
                </div>

                <div class="card-body">
                    <form id="filterForm">
                        <div class="row mb-3">
                            <div class="col-md-9 mb-2 mb-md-0">
                                <div class="input-group input-group-md">
                                    <input type="text" class="form-control border-end-0" id="searchKeyword"
                                           name="keyword" placeholder="Search by employee, type, expense or payment status"
                                           aria-label="Keyword Search">
                                    <span class="input-group-text border-start-0 input-group-bg">
                                        <i class="mdi mdi-magnify text-muted"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select name="payment_status" id="filterPaymentStatus" class="form-select">
                                    <option value="">All Payment Status</option>
                                    <option value="unpaid">Unpaid</option>
                                    <option value="paid">Paid</option>
                                </select>
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
    const statusSelect = document.getElementById('filterPaymentStatus');

    // Initial Load
    fetchLogs();

    // Search & Filter Logic
    let filterTimer;
    const triggerSearch = () => {
        clearTimeout(filterTimer);
        filterTimer = setTimeout(fetchLogs, 400);
    };

    searchInput.addEventListener('input', triggerSearch);
    statusSelect.addEventListener('change', triggerSearch);

    function fetchLogs(url = "{{ route('bills.index') }}") {
        const keyword = searchInput.value;
        const status = statusSelect.value;
        const fullUrl = `${url}${url.includes('?') ? '&' : '?'}keyword=${encodeURIComponent(keyword)}&payment_status=${encodeURIComponent(status)}`;

        axios.get(fullUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(response => {
                container.innerHTML = response.data;
                if (window.feather) {
                    feather.replace();
                }
            })
            .catch(error => {
                console.error(error);
                container.innerHTML = '<div class="text-danger text-center py-4">Failed to load data.</div>';
            });
    }

    // Toggle Payment Status via Axios
    $(document).on('click', '.toggle-payment-status', function(e) {
        e.preventDefault();
        const btn = $(this);
        const id = btn.data('id');
        const nextStatus = btn.data('status');

        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');

        axios.put("{{ route('bills.change_payment_status') }}", {
            id: id,
            payment_status: nextStatus,
            _token: "{{ csrf_token() }}"
        })
        .then(response => {
            if (response.data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Status Updated',
                    text: response.data.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    fetchLogs();
                });
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: response.data.message });
                fetchLogs();
            }
        })
        .catch(error => {
            const errMsg = error.response && error.response.data && error.response.data.message
                ? error.response.data.message
                : 'Failed to update payment status.';
            Swal.fire({ icon: 'error', title: 'Error', text: errMsg });
            fetchLogs();
        });
    });

    // Delete Bill Confirmation & Axios Delete request
    $(document).on('click', '.delete-bill', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this bill record!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                axios.delete(`/bills/${id}`, {
                    data: { _token: "{{ csrf_token() }}" }
                }).then(response => {
                    Swal.fire('Deleted!', response.data.message, 'success');
                    fetchLogs();
                }).catch(error => {
                    const errMsg = error.response && error.response.data && error.response.data.message
                        ? error.response.data.message
                        : 'Deletion failed';
                    Swal.fire('Error!', errMsg, 'error');
                });
            }
        });
    });

    // Pagination
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        fetchLogs($(this).attr('href'));
    });
});
</script>
@endpush
