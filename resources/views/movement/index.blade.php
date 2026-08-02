@extends('structure.master')

@section('content')

    {{-- Employee Travel Movement List --}}
    <div class="row">
        <div class="col-lg-12">
            @can('movement.view')
            <div class="card border-0 shadow-sm rounded">
                <div class="card-header">
                    <h5 class="card-title mb-0">Search Employee Travel Movement</h5>
                </div><!-- end card header -->
                <div class="card-header border-bottom p-4">
                    <div class="row align-items-start">
                        {{-- Filter Section --}}
                        <div class="col-md-12">
                            <div class="border rounded shadow-sm p-3 filter-section-bg">
                                <form id="filterForm">
                                    {{-- First Row: Keyword Search --}}
                                    <div class="row mb-2">
                                        <div class="col-12">
                                            <label for="keywordSearch" class="form-label text-muted small fw-semibold mb-1">
                                                Keyword Search
                                            </label>
                                            <div class="input-group input-group-md">
                                                <input type="text" class="form-control border-end-0" id="keywordSearch"
                                                    name="keyword"
                                                    placeholder="Search by employee name"
                                                    aria-label="Keyword Search" value="{{ request('keyword') }}">
                                                <span class="input-group-text border-start-0 input-group-bg">
                                                    <i class="mdi mdi-magnify text-muted"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Second Row: Date Range, Status & Payment --}}
                                    <div class="row mb-2">
                                        <div class="col-md-3">
                                            <label for="fromDate" class="form-label text-muted small fw-semibold mb-1">
                                                From Date
                                            </label>
                                            <input type="date" class="form-control form-control-sm" id="fromDate" name="from"
                                                value="{{ request('from') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="toDate" class="form-label text-muted small fw-semibold mb-1">
                                                To Date
                                            </label>
                                            <input type="date" class="form-control form-control-sm" id="toDate" name="to"
                                                value="{{ request('to') }}">
                                        </div>
                                        @if(auth()->user()->user_type !== \App\Enums\UserType::Employee)
                                        <div class="col-md-3">
                                            <label for="statusFilter" class="form-label text-muted small fw-semibold mb-1">
                                                Status
                                            </label>
                                            <select class="form-select form-select-sm" id="statusFilter" name="status">
                                                <option value="">All Status</option>
                                                <option value="pending">Pending</option>
                                                <option value="approved">Approved</option>
                                                <option value="rejected">Rejected</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="paymentStatusFilter" class="form-label text-muted small fw-semibold mb-1">
                                                Payment Status
                                            </label>
                                            <select class="form-select form-select-sm" id="paymentStatusFilter" name="payment_status">
                                                <option value="">All Status</option>
                                                <option value="paid">Paid</option>
                                                <option value="unpaid">Unpaid</option>
                                            </select>
                                        </div>
                                        @endif
                                    </div>

                                    {{-- Reset Button --}}
                                    <div class="row">
                                        <div class="col-12 text-end">
                                            <button type="button" id="resetFilters"
                                                class="btn btn-outline-secondary btn-sm">
                                                <i class="mdi mdi-refresh"></i> Reset
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endcan
        </div>


        <div class="col-lg-12 mt-3">
            <div class="card border-0 shadow-sm rounded">
                <div class="card-header">
                    <h5 class="card-title mb-0">Employee Travel Movement Records</h5>
                </div>
                <div class="card-body">
                    {{-- Action Buttons --}}
                    <div class="d-flex justify-content-between mb-3">
                        @can('movement.create')
                        <a class="btn btn-warning btn-sm" href="{{ route('movement.create') }}">
                            <i style="height: 12px; width: 12px" data-feather="plus"></i> Create
                        </a>
                        @else
                        <div></div>
                        @endcan
                        <div class="d-flex gap-2">
                            <button type="button" id="exportExcelBtn" class="btn btn-success btn-sm no-loader">
                                <i class="bi bi-file-earmark-excel me-1"></i> Export
                            </button>
                            <button type="button" id="printBtn" class="btn btn-secondary btn-sm no-loader">
                                <i class="bi bi-printer me-1"></i> Print
                            </button>
                        </div>
                    </div>

                    <div id="search-result">
                        @if ($movements->isEmpty())
                            <div class="text-center py-4 text-muted">No movement records found.</div>
                        @else
                            <div class="table-responsive">
                                @include('movement.partials.search_results')
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>



    {{-- Include Import Modal --}}
    @include('movement.partials.import_modal')
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Function to perform AJAX search
            function fetchData(url = "{{ route('movement.index') }}") {
                const queryString = $('#filterForm').serialize();

                $.ajax({
                    url: url,
                    method: "GET",
                    data: queryString + '&_ajax=1',
                    beforeSend: function() {
                        $('#search-result').html(
                            '<div class="text-center py-4 text-muted">Loading Data...</div>');
                    },
                    success: function(response) {
                        $('#search-result').html(response);
                        if (typeof feather !== 'undefined') {
                            feather.replace();
                        }
                        const newUrl = '?' + queryString;
                        window.history.pushState(null, '', newUrl || location.pathname);
                    },
                    error: function(xhr) {
                        console.error('AJAX Error:', xhr.responseText);
                    }
                });
            }

            // Trigger search on input or change (debounced)
            let timer;
            $(document).on('input change', '#filterForm input, #filterForm select', function() {
                clearTimeout(timer);
                timer = setTimeout(function() {
                    fetchData();
                }, 300);
            });

            // Reset filters
            $('#resetFilters').on('click', function() {
                $('#filterForm')[0].reset();
                $('.select2_list').val(null).trigger('change');
                window.location.href = "{{ route('movement.index') }}";
            });

            // Handle pagination via AJAX
            $(document).on('click', '#search-result .pagination a', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');
                fetchData(url);
            });

            // Excel export
            $(document).on('click', '#exportExcelBtn', function() {
                window.ignoreBeforeUnload = true;
                setTimeout(() => { window.ignoreBeforeUnload = false; }, 2000);
                let queryString = $('#filterForm').serialize();
                window.location.href = "{{ route('movement.export.excel') }}" + '?' + queryString;
            });

            // Print
            $(document).on('click', '#printBtn', function() {
                let queryString = $('#filterForm').serialize();
                window.open("{{ route('movement.print') }}" + '?' + queryString, '_blank');
            });

            // Allowance Setup Calculator in modal
            $(document).on('change input', '.ta-plan-select, .da-plan-select, .custom-ta-input, .custom-da-input', function() {
                const modal = $(this).closest('.modal');
                const movementId = $(this).data('movement-id');
                const distance = parseFloat(modal.find('.distance-value').data('distance')) || 0;
                const days = parseFloat(modal.find('.days-value').data('days')) || 0;

                const taPlanSelect = modal.find('.ta-plan-select');
                const taRate = parseFloat(taPlanSelect.find('option:selected').data('rate')) || 0;
                const customTaVal = modal.find('.custom-ta-input').val();
                
                const daPlanSelect = modal.find('.da-plan-select');
                const daRate = parseFloat(daPlanSelect.find('option:selected').data('rate')) || 0;
                const customDaVal = modal.find('.custom-da-input').val();

                let totalTa = 0;
                if (customTaVal !== '' && !isNaN(parseFloat(customTaVal))) {
                    totalTa = parseFloat(customTaVal);
                } else {
                    totalTa = distance * taRate;
                }

                let totalDa = 0;
                if (customDaVal !== '' && !isNaN(parseFloat(customDaVal))) {
                    totalDa = parseFloat(customDaVal);
                } else {
                    totalDa = days * daRate;
                }

                const totalAllowance = totalTa + totalDa;

                modal.find('#calc_ta_display' + movementId).text('৳' + totalTa.toFixed(2));
                modal.find('#total_ta' + movementId).val(totalTa.toFixed(2));

                modal.find('#calc_da_display' + movementId).text('৳' + totalDa.toFixed(2));
                modal.find('#total_da' + movementId).val(totalDa.toFixed(2));

                modal.find('#grand_total_display' + movementId).text('৳' + totalAllowance.toFixed(2));
                modal.find('#total_allowance' + movementId).val(totalAllowance.toFixed(2));
            });
        });
    </script>
@endpush

