@extends('structure.master')

@section('content')
<div class="container-fluid px-0" id="batch-overview-container">
    {{-- Header Section --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0" style="border-radius: 12px;">
                <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center" style="border-radius: 12px;">
                    <div>
                        <h5 class="card-title mb-1 fw-bold text-dark">
                            <i data-feather="bar-chart-2" class="me-2 text-info" style="width: 20px;"></i>
                            Batch Disbursement Overview
                        </h5>
                        <p class="text-muted small mb-0 ms-4">
                            Batch ID: <span class="fw-bold text-primary" id="header-batch-id">Loading...</span> | 
                            Month: <span class="fw-bold" id="header-month">...</span>
                        </p>
                    </div>
                    <a href="{{ route('disbursement.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 shadow-sm">
                        <i data-feather="arrow-left" class="me-1" style="width: 14px;"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="row g-4 mb-4" id="stats-container">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center p-3 stat-card" style="border-radius: 12px; border-left: 5px solid #6366f1 !important;">
                <small class="text-muted text-uppercase fw-bold ls-1 d-block mb-1">Total Employees</small>
                <h3 class="fw-bold mb-0 text-dark" id="stat-total-emp">-</h3>
                <small class="text-muted mt-1 d-block" id="stat-eligible-emp">Eligible: -</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center p-3 stat-card" style="border-radius: 12px; border-left: 5px solid #10b981 !important;">
                <small class="text-muted text-uppercase fw-bold ls-1 d-block mb-1">Total Paid</small>
                <h3 class="fw-bold mb-0 text-success" id="stat-paid-emp">-</h3>
                <small class="text-muted mt-1 d-block" id="stat-completion-pct">Completion: -%</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center p-3 stat-card" style="border-radius: 12px; border-left: 5px solid #f59e0b !important;">
                <small class="text-muted text-uppercase fw-bold ls-1 d-block mb-1">Pending Pay</small>
                <h3 class="fw-bold mb-0 text-warning" id="stat-pending-emp">-</h3>
                <small class="text-muted mt-1 d-block">Remaining to disburse</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center p-3 stat-card" style="border-radius: 12px; border-left: 5px solid #ef4444 !important;">
                <small class="text-muted text-uppercase fw-bold ls-1 d-block mb-1">Total Value</small>
                <h3 class="fw-bold mb-0 text-primary" id="stat-total-amt">৳ -</h3>
                <small class="text-muted mt-1 d-block text-truncate" id="stat-paid-amt">Paid: ৳ -</small>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="row g-4">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-header bg-white pt-4 px-4 border-bottom-0">
                    <h6 class="fw-bold mb-1 text-dark">Disbursement Transactions</h6>
                    <p class="text-muted small">History of all payment events for this batch.</p>
                </div>
                <div class="card-body p-0" id="history-container">
                    <div class="text-center py-5" id="loader">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-2 text-muted">Fetching history...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mb-5 pb-5"></div>
</div>

<style>
    .bg-soft-info { background-color: rgba(13, 202, 240, 0.1); }
    .italic { font-style: italic; }
    .btn-xs { padding: .2rem .5rem; font-size: .75rem; }
    .disbursement-block { transition: all 0.3s ease; }
    .disbursement-block:hover { background-color: #fafbfc; }
    .ls-1 { letter-spacing: 0.5px; }
    .pager-link { 
        display: inline-block; padding: 2px 10px; margin: 0 2px; border-radius: 20px; 
        font-size: 11px; font-weight: bold; color: #6c757d; background: #fff;
        text-decoration: none; border: 1px solid #dee2e6; transition: all 0.2s;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .pager-link:hover { background: #108dff; color: #fff; border-color: #108dff; transform: translateY(-1px); }
    .pager-link.active { background: #108dff; color: #fff; border-color: #108dff; }
</style>

@push('scripts')
<script>
    $(document).ready(function() {
        const batchId = "{{ $id }}";
        const apiUrl = "{{ route('disbursement.api.batch_data', ':id') }}".replace(':id', batchId);

        function formatCurrency(amt) {
            return '৳ ' + parseFloat(amt).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        function loadBatchData() {
            axios.get(apiUrl)
            .then(function (response) {
                const data = response.data;
                renderPage(data);
            })
            .catch(function (error) {
                console.error(error);
                $('#history-container').html('<div class="text-center py-5 text-danger">Failed to load data.</div>');
            });
        }

        function renderPage(data) {
            const process = data.process;
            const stats = data.stats;
            const disbursements = data.disdisbursements || data.disbursements; // Handle possible typo fix

            // Populate Headers
            $('#header-batch-id').text(process.batch_id);
            const monthDate = new Date(process.salary_month + '-01');
            $('#header-month').text(monthDate.toLocaleString('default', { month: 'long', year: 'numeric' }));

            // Populate Stats
            $('#stat-total-emp').text(stats.total_employees);
            $('#stat-eligible-emp').text('Eligible: ' + stats.eligible_employees);
            $('#stat-paid-emp').text(stats.paid_employees);
            const completion = Math.round((stats.paid_employees / (stats.eligible_employees || 1)) * 100);
            $('#stat-completion-pct').text('Completion: ' + completion + '%');
            $('#stat-pending-emp').text(stats.pending_employees);
            $('#stat-total-amt').text(formatCurrency(stats.total_amount));
            $('#stat-paid-amt').text('Paid: ' + formatCurrency(stats.paid_amount));

            // Render History
            const $container = $('#history-container');
            $container.empty();

            if (disdisbursements.length === 0) {
                $container.html('<div class="text-center py-5"><i data-feather="inbox" class="text-muted mb-3" style="width: 48px; height: 48px;"></i><p class="text-muted mb-0">No disbursements recorded yet.</p></div>');
                if (typeof feather !== 'undefined') feather.replace();
                return;
            }

            disdisbursements.forEach((disb, index) => {
                const isLast = index === disdisbursements.length - 1;
                const borderClass = isLast ? '' : 'border-bottom';
                
                let attachmentsHtml = '';
                if (disb.attachments && disb.attachments.length > 0) {
                    attachmentsHtml = `<div class="attachments-area mt-2"><small class="d-block text-uppercase fw-bold text-muted mb-2 ls-1" style="font-size: 10px;">Payment Proofs</small>`;
                    disb.attachments.forEach(file => {
                        attachmentsHtml += `<a href="/storage/${file.file_path}" target="_blank" class="btn btn-light btn-xs rounded-pill px-3 mb-1 small d-inline-flex align-items-center me-1"><i data-feather="paperclip" class="me-1" style="width: 12px;"></i> ${file.original_name.substring(0, 15)}...</a>`;
                    });
                    attachmentsHtml += `</div>`;
                }

                let employeesHtml = '';
                disb.items.forEach(item => {
                    const avatar = item.employee.photo_path 
                        ? `<img src="/storage/${item.employee.photo_path}" alt="img" class="rounded-circle shadow-sm" style="width: 30px; height: 30px; object-fit: cover;">`
                        : `<div class="avatar-title rounded-circle bg-white text-primary d-flex align-items-center justify-content-center fw-bold border" style="width: 30px; height: 30px; font-size: 10px;">${item.employee.full_name.charAt(0).toUpperCase()}</div>`;

                    employeesHtml += `
                        <tr>
                            <td class="px-2">
                                <div class="d-flex align-items-center py-1">
                                    <div class="avatar-xs me-2">${avatar}</div>
                                    <div>
                                        <span class="fw-bold small d-block text-dark">${item.employee.full_name}</span>
                                        <span class="text-muted" style="font-size: 10px;">${item.employee.system_id} | ${item.employee.office_info?.get_current_designation?.company_designation || 'N/A'}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="text-end px-2 fw-bold text-dark small">${formatCurrency(item.amount)}</td>
                        </tr>`;
                });

                const blockHtml = `
                    <div class="disbursement-block p-4 ${borderClass}">
                        <div class="row align-items-start g-4">
                            <div class="col-md-3">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle bg-soft-info p-2 me-3"><i data-feather="check-square" class="text-info" style="width: 18px;"></i></div>
                                    <div><h6 class="mb-0 fw-bold">${disb.batch_id}</h6><small class="text-muted">${new Date(disb.created_at).toLocaleString()}</small></div>
                                </div>
                                <div class="ps-1">
                                    <p class="mb-1 small"><strong>Method:</strong> ${disb.payment_method}</p>
                                    <p class="mb-1 small"><strong>Disbursed By:</strong> ${disb.disbursed_by?.name || 'System'}</p>
                                    <p class="mb-3 small text-muted italic">"${disb.note || 'No notes provided'}"</p>
                                    ${attachmentsHtml}
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="bg-light p-3 rounded-4 mb-2">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-borderless align-middle mb-0 paginated-table" data-page-size="10">
                                            <thead><tr><th class="small text-muted text-uppercase px-2">Staff Details</th><th class="text-end small text-muted text-uppercase px-2">Amount Paid</th></tr></thead>
                                            <tbody>${employeesHtml}</tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center px-2 mb-2">
                                    <div class="pagination-container"></div>
                                    <div class="text-end">
                                        <span class="small text-muted">Subtotal (${disb.total_employees} Staff): </span>
                                        <span class="fw-bold text-success fs-6">${formatCurrency(disb.total_amount)}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>`;
                
                $container.append(blockHtml);
            });

            if (typeof feather !== 'undefined') feather.replace();
            initializePagination();
        }

        function initializePagination() {
            $('.paginated-table').each(function() {
                var $table = $(this);
                var itemsPerPage = parseInt($table.data('page-size')) || 10;
                var $rows = $table.find('tbody tr');
                var totalRows = $rows.length;
                var totalPages = Math.ceil(totalRows / itemsPerPage);

                if (totalRows <= itemsPerPage) return;

                var $pagerContainer = $table.closest('.col-md-9').find('.pagination-container');
                $pagerContainer.empty();
                
                function showPage(page) {
                    $rows.hide();
                    $rows.slice(page * itemsPerPage, (page + 1) * itemsPerPage).show();
                    $pagerContainer.find('.pager-link').removeClass('active');
                    $pagerContainer.find('.pager-link[data-page="' + page + '"]').addClass('active');
                }

                for (var i = 0; i < totalPages; i++) {
                    $('<a href="javascript:void(0)" class="pager-link shadow-sm" data-page="' + i + '">' + (i + 1) + '</a>')
                        .appendTo($pagerContainer);
                }

                $pagerContainer.on('click', '.pager-link', function() {
                    showPage($(this).data('page'));
                });

                showPage(0);
            });
        }

        loadBatchData();
    });
</script>
@endpush
@endsection
