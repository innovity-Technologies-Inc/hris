@extends('structure.master')

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0 fw-semibold text-dark">
                            <i class="mdi mdi-receipt me-2 text-warning"></i>Bill Pay Management
                        </h5>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" id="exportExcelBtn" class="btn btn-success btn-sm no-loader">
                            <i class="bi bi-file-earmark-excel me-1"></i> Export
                        </button>
                        <button type="button" id="printBtn" class="btn btn-secondary btn-sm no-loader">
                            <i class="bi bi-printer me-1"></i> Print
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <form id="filterForm">
                        <div class="row mb-3 g-2">
                            <div class="col-md-9 col-12">
                                <div class="input-group input-group-md">
                                    <input type="text" class="form-control border-end-0" id="searchKeyword"
                                           name="keyword" placeholder="Search by employee, type, expense or payment status"
                                           aria-label="Keyword Search">
                                    <span class="input-group-text border-start-0 input-group-bg">
                                        <i class="mdi mdi-magnify text-muted"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
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

    <!-- Pay Bill Modal -->
    <div class="modal fade" id="payBillModal" tabindex="-1" aria-labelledby="payBillModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="payBillModalLabel">Pay Bill</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="payBillForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="payBillId">
                    <input type="hidden" name="payment_status" value="paid">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-muted mb-1">Amount to Pay</label>
                            <input type="text" class="form-control" id="payBillAmount" readonly disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-muted mb-1">Payment Method *</label>
                            <select name="payment_method" id="payBillMethod" class="form-select" required>
                                <option value="">Select Method</option>
                                <option value="Cash">Cash</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                                <option value="Mobile Banking">Mobile Banking</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-muted mb-1">Remarks</label>
                            <textarea name="remarks" id="payBillRemarks" class="form-control" rows="3" placeholder="Add remarks..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-muted mb-1">File Attachment (Receipt)</label>
                            <input type="file" name="attachment" id="payBillAttachment" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success" id="paySubmitBtn">Confirm Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- View Payment Modal -->
    <div class="modal fade" id="viewPaymentModal" tabindex="-1" aria-labelledby="viewPaymentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="viewPaymentModalLabel">
                        <i class="bi bi-info-circle me-2"></i>Payment Information
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3 d-flex justify-content-between border-bottom pb-2">
                        <span class="fw-semibold text-muted">Amount Paid:</span>
                        <span class="fw-bold text-dark fs-5" id="viewAmount">N/A</span>
                    </div>
                    <div class="mb-3 d-flex justify-content-between border-bottom pb-2">
                        <span class="fw-semibold text-muted">Payment Method:</span>
                        <span class="badge bg-success-subtle text-success px-2 py-1" id="viewMethod">N/A</span>
                    </div>
                    <div class="mb-3 d-flex justify-content-between border-bottom pb-2">
                        <span class="fw-semibold text-muted">Payment Date:</span>
                        <span class="text-dark" id="viewDate">N/A</span>
                    </div>
                    <div class="mb-3">
                        <span class="fw-semibold text-muted d-block mb-1">Remarks:</span>
                        <div class="p-3 bg-light rounded text-dark text-wrap small" id="viewRemarks" style="min-height: 50px;">N/A</div>
                    </div>
                    <div class="mb-0 text-center" id="viewAttachmentContainer">
                        <!-- Attachment Link -->
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
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

    // Pay button opens Modal
    $(document).on('click', '.pay-bill-btn', function(e) {
        e.preventDefault();
        
        // Reset form inputs first
        document.getElementById('payBillForm').reset();

        const id = $(this).data('id');
        const amount = $(this).data('amount');

        document.getElementById('payBillId').value = id;
        document.getElementById('payBillAmount').value = '৳' + parseFloat(amount).toFixed(2);
        
        const modal = new bootstrap.Modal(document.getElementById('payBillModal'));
        modal.show();
    });

    // View Payment Details Modal
    $(document).on('click', '.view-payment-btn', function(e) {
        e.preventDefault();
        const btn = $(this);
        const amount = btn.data('amount');
        const method = btn.data('method');
        const remarks = btn.data('remarks');
        const attachment = btn.data('attachment');
        const date = btn.data('date');

        document.getElementById('viewAmount').innerText = '৳' + parseFloat(amount).toFixed(2);
        document.getElementById('viewMethod').innerText = method;
        document.getElementById('viewDate').innerText = date;
        document.getElementById('viewRemarks').innerText = remarks || 'N/A';

        const attachContainer = document.getElementById('viewAttachmentContainer');
        attachContainer.innerHTML = '';
        if (attachment) {
            attachContainer.innerHTML = `
                <a href="${attachment}" target="_blank" class="btn btn-outline-primary w-100 py-2">
                    <i class="bi bi-file-earmark-pdf me-2"></i>View Receipt Attachment
                </a>
            `;
        } else {
            attachContainer.innerHTML = `
                <div class="alert alert-secondary small mb-0 py-2">
                    <i class="bi bi-exclamation-circle me-2"></i>No Receipt Attached
                </div>
            `;
        }

        const modal = new bootstrap.Modal(document.getElementById('viewPaymentModal'));
        modal.show();
    });

    // Form submit payment logic via Axios
    document.getElementById('payBillForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const submitBtn = document.getElementById('paySubmitBtn');
        const origText = submitBtn.innerHTML;

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Processing...';

        const formData = new FormData(form);
        formData.append('_method', 'PUT');

        axios.post("{{ route('bills.change_payment_status') }}", formData)
        .then(response => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = origText;
            
            if (response.data.success) {
                // Hide modal
                const modalEl = document.getElementById('payBillModal');
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) {
                    modal.hide();
                }
                
                Swal.fire({
                    icon: 'success',
                    title: 'Payment Successful',
                    text: response.data.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    fetchLogs();
                });
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: response.data.message });
            }
        })
        .catch(error => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = origText;

            const errMsg = error.response && error.response.data && error.response.data.message
                ? error.response.data.message
                : 'Failed to process payment.';
            
            Swal.fire({ icon: 'error', title: 'Error', text: errMsg });
        });
    });

    // Toggle Payment Status back to unpaid via Axios directly
    $(document).on('click', '.toggle-payment-status', function(e) {
        e.preventDefault();
        const btn = $(this);
        const id = btn.data('id');
        const nextStatus = btn.data('status');

        Swal.fire({
            title: 'Are you sure?',
            text: "Mark this bill back as unpaid? All payment records for this bill will be cleared.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, unpay it!'
        }).then((result) => {
            if (result.isConfirmed) {
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
            }
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

    // Excel export
    const exportBtn = document.getElementById('exportExcelBtn');
    if (exportBtn) {
        exportBtn.addEventListener('click', function(e) {
            e.preventDefault();
            window.ignoreBeforeUnload = true;
            setTimeout(() => { window.ignoreBeforeUnload = false; }, 2000);
            
            const keyword = searchInput.value;
            const status = statusSelect.value;
            window.location.href = "{{ route('bills.export.excel') }}?keyword=" + encodeURIComponent(keyword) + "&payment_status=" + encodeURIComponent(status);
        });
    }

    // Print
    const printBtn = document.getElementById('printBtn');
    if (printBtn) {
        printBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const keyword = searchInput.value;
            const status = statusSelect.value;
            window.open("{{ route('bills.print') }}?keyword=" + encodeURIComponent(keyword) + "&payment_status=" + encodeURIComponent(status), '_blank');
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
