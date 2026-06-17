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
                <h3 class="fw-bold mb-0 text-primary" id="stat-total-amt">&#2547; -</h3>
                <small class="text-muted mt-1 d-block text-truncate" id="stat-paid-amt">Paid: &#2547; -</small>
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
                <div class="card-footer bg-white border-0 py-3" id="history-pagination"></div>
            </div>
        </div>
    </div>
    
    <div class="mb-5 pb-5"></div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        const batchId = "{{ $id }}";
        const historyApiUrl = "{{ route('disbursement.batch_details_data', ':id') }}".replace(':id', batchId);
        const itemsApiUrl = "{{ route('disbursement.items_data', ':id') }}";

        function formatCurrency(amt) {
            return '\u09F3 ' + parseFloat(amt).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        function loadHistory(page = 1) {
            $('#loader').show();
            axios.get(historyApiUrl + '?page=' + page)
            .then(function (response) {
                renderPage(response.data);
            })
            .catch(function (error) {
                console.error(error);
                $('#history-container').html('<div class="text-center py-5 text-danger">Failed to load history.</div>');
            });
        }

        function renderPage(data) {
            const process = data.process;
            const stats = data.stats;
            const disbursements = data.disbursements; // This is now a LengthAwarePaginator result

            // Populate Headers (only once)
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

            // Render History Blocks
            const $container = $('#history-container');
            $container.empty();

            if (disbursements.data.length === 0) {
                $container.html('<div class="text-center py-5"><i data-feather="inbox" class="text-muted mb-3" style="width: 48px; height: 48px;"></i><p class="text-muted mb-0">No disbursements recorded yet.</p></div>');
                if (typeof feather !== 'undefined') feather.replace();
                return;
            }

            disbursements.data.forEach((disb, index) => {
                const borderClass = (index === disbursements.data.length - 1) ? '' : 'border-bottom';
                
                let attachmentsHtml = '';
                if (disb.attachments && disb.attachments.length > 0) {
                    attachmentsHtml = `<div class="attachments-area mt-2"><small class="d-block text-uppercase fw-bold text-muted mb-2 ls-1" style="font-size: 10px;">Payment Proofs</small>`;
                    disb.attachments.forEach(file => {
                        attachmentsHtml += `<a href="/storage/${file.file_path}" target="_blank" class="btn btn-light btn-xs rounded-pill px-3 mb-1 small d-inline-flex align-items-center me-1"><i data-feather="paperclip" class="me-1" style="width: 12px;"></i> ${file.original_name.substring(0, 15)}...</a>`;
                    });
                    attachmentsHtml += `</div>`;
                }

                const blockHtml = `
                    <div class="disbursement-block p-4 ${borderClass}" id="disb-block-${disb.id}">
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
                                        <table class="table table-sm table-borderless align-middle mb-0" id="items-table-${disb.id}">
                                            <thead><tr><th class="small text-muted text-uppercase px-2">Staff Details</th><th class="text-end small text-muted text-uppercase px-2">Amount Paid</th></tr></thead>
                                            <tbody class="items-body"></tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center px-2">
                                    <div class="items-pagination" data-disb-id="${disb.id}"></div>
                                    <div class="text-end">
                                        <span class="small text-muted">Subtotal (${disb.total_employees} Staff): </span>
                                        <span class="fw-bold text-success fs-6">${formatCurrency(disb.total_amount)}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>`;
                
                $container.append(blockHtml);
                loadItems(disb.id, 1);
            });

            // History Pagination Links
            renderPagination($('#history-pagination'), disbursements, loadHistory);

            if (typeof feather !== 'undefined') feather.replace();
        }

        function loadItems(disbId, page = 1) {
            const $block = $('#disb-block-' + disbId);
            const $tbody = $block.find('.items-body');
            const url = itemsApiUrl.replace(':id', disbId) + '?page=' + page;

            if (page === 1) $tbody.html('<tr><td colspan="2" class="text-center py-3 small text-muted">Loading staff...</td></tr>');

            axios.get(url)
            .then(function (response) {
                const data = response.data;
                $tbody.empty();
                data.data.forEach(item => {
                    const avatar = item.employee.photo_path 
                        ? `<img src="/storage/${item.employee.photo_path}" alt="img" class="rounded-circle shadow-sm" style="width: 30px; height: 30px; object-fit: cover;">`
                        : `<div class="avatar-title rounded-circle bg-white text-primary d-flex align-items-center justify-content-center fw-bold border" style="width: 30px; height: 30px; font-size: 10px;">${item.employee.full_name.charAt(0).toUpperCase()}</div>`;

                    $tbody.append(`
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
                        </tr>`);
                });
                renderPagination($block.find('.items-pagination'), data, (p) => loadItems(disbId, p));
            });
        }

        function renderPagination($container, paginator, callback) {
            $container.empty();
            if (paginator.last_page <= 1) return;

            let html = '<nav><ul class="pagination pagination-sm mb-0">';
            
            // Previous
            html += `<li class="page-item ${paginator.current_page === 1 ? 'disabled' : ''}">
                        <a class="page-link" href="javascript:void(0)" data-page="${paginator.current_page - 1}">Prev</a>
                     </li>`;

            // Simple Logic for Page Numbers
            for (let i = 1; i <= paginator.last_page; i++) {
                if (i === 1 || i === paginator.last_page || (i >= paginator.current_page - 1 && i <= paginator.current_page + 1)) {
                    html += `<li class="page-item ${paginator.current_page === i ? 'active' : ''}">
                                <a class="page-link" href="javascript:void(0)" data-page="${i}">${i}</a>
                             </li>`;
                } else if (i === 2 || i === paginator.last_page - 1) {
                    html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                }
            }

            // Next
            html += `<li class="page-item ${paginator.current_page === paginator.last_page ? 'disabled' : ''}">
                        <a class="page-link" href="javascript:void(0)" data-page="${paginator.current_page + 1}">Next</a>
                     </li>`;

            html += '</ul></nav>';
            $container.html(html);

            $container.find('.page-link').on('click', function() {
                const p = $(this).data('page');
                if (p && !$(this).parent().hasClass('disabled') && !$(this).parent().hasClass('active')) {
                    callback(p);
                }
            });
        }

        loadHistory(1);
    });
</script>
@endpush
@endsection
