@extends('structure.master')

@section('content')
    <div class="container-fluid px-0">
        <form action="{{ route('disbursement.store') }}" method="POST" enctype="multipart/form-data" id="disbursementForm">
            @csrf
            <input type="hidden" name="process_id" value="{{ $process->id }}">

            <div class="row g-4">
                {{-- Header Section --}}
                <div class="col-12">
                    <div class="card shadow-sm border-0 mb-0" style="border-radius: 12px;">
                        <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center" style="border-radius: 12px;">
                            <div>
                                <h5 class="card-title mb-1 fw-bold text-dark">
                                    <i data-feather="play-circle" class="me-2 text-primary" style="width: 20px;"></i>
                                    Process Disbursement
                                </h5>
                                <p class="text-muted small mb-0 ms-4">Batch ID: <span class="fw-bold text-primary">{{ $process->batch_id }}</span> | Type: <span class="text-capitalize">{{ $process->type }}</span></p>
                            </div>
                            <a href="{{ route('disbursement.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 shadow-sm">
                                <i data-feather="arrow-left" class="me-1" style="width: 14px;"></i> Back to List
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Left Column: Employee Selection --}}
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm h-100 mb-4" style="border-radius: 12px;">
                        <div class="card-header bg-white border-bottom-0 pt-4 pb-3 px-4 d-flex justify-content-between align-items-center" style="border-top-left-radius: 12px; border-top-right-radius: 12px;">
                            <div>
                                <h6 class="fw-bold mb-1 text-dark">Eligible Employees</h6>
                                <p class="text-muted small mb-0">Select employees from this batch for payment.</p>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="selectAll" checked style="cursor: pointer;">
                                <label class="form-check-label small fw-bold text-primary" for="selectAll" style="cursor: pointer;">SELECT ALL</label>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive" style="max-height: 550px; overflow-y: auto;">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light sticky-top" style="top: 0; z-index: 1;">
                                        <tr class="border-top-0">
                                            <th class="text-center" style="width: 60px; border-top: 0;"></th>
                                            <th class="px-4 py-3" style="border-top: 0;">Employee Information</th>
                                            <th class="text-end px-4 py-3" style="border-top: 0;">Net Payable</th>
                                        </tr>
                                    </thead>
                                    <tbody class="border-top-0">
                                        @foreach($items as $item)
                                            @php
                                                $employee = $item->getEmployee;
                                                $amount = $process->type === 'salary' ? $item->total_salary : $item->amount;
                                            @endphp
                                        <tr style="cursor: pointer;" onclick="$(this).find('.employee-checkbox').click()">
                                            <td class="text-center">
                                                <div class="form-check d-flex justify-content-center" onclick="event.stopPropagation()">
                                                    <input class="form-check-input employee-checkbox" type="checkbox" name="record_ids[]" value="{{ $item->id }}" data-amount="{{ $amount }}" checked style="cursor: pointer;">
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm me-3">
                                                        @if($employee->photo_path)
                                                            <img src="{{ asset('storage/' . $employee->photo_path) }}" alt="user-img" class="rounded-circle img-thumbnail shadow-sm" style="width: 44px; height: 42px; object-fit: cover;">
                                                        @else
                                                            <div class="avatar-title rounded-circle bg-soft-primary text-primary d-flex align-items-center justify-content-center fw-bold" style="width: 44px; height: 42px; font-size: 16px; border: 1px solid rgba(16, 141, 255, 0.2);">
                                                                {{ strtoupper(substr($employee->full_name, 0, 1)) }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-bold text-dark">{{ $employee->full_name }}</h6>
                                                        <small class="text-muted fw-medium">{{ $employee->system_id }} | {{ $employee->officeInfo->getCurrentDesignation->company_designation ?? 'N/A' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-end px-4 py-3">
                                                <span class="fw-bold text-success fs-5">
                                                    ৳ {{ number_format($amount, 2) }}
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-top-0 py-3 px-4 text-end" style="border-bottom-left-radius: 12px; border-bottom-right-radius: 12px;">
                            <div class="d-flex justify-content-end align-items-center">
                                <div class="me-4 text-start">
                                    <small class="text-muted d-block text-uppercase fw-bold ls-1" style="font-size: 10px;">Selected Staff</small>
                                    <h5 class="mb-0 fw-bold" id="selectedCount">{{ count($items) }}</h5>
                                </div>
                                <div class="text-end">
                                    <small class="text-muted d-block text-uppercase fw-bold ls-1" style="font-size: 10px;">Total Disbursement</small>
                                    <h5 class="mb-0 fw-bold text-success" id="selectedTotal">৳ {{ number_format($items->sum($process->type === 'salary' ? 'total_salary' : 'amount'), 2) }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Column: Payment Details --}}
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm sticky-top" style="top: 85px; border-radius: 12px; z-index: 10;">
                        <div class="card-header bg-white pt-4 pb-3 px-4 border-bottom-0" style="border-top-left-radius: 12px; border-top-right-radius: 12px;">
                            <h6 class="fw-bold mb-1 text-dark">Payment Details</h6>
                            <p class="text-muted small mb-0">Enter payment proof and method.</p>
                        </div>
                        <div class="card-body px-4 pb-4">
                            <div class="mb-4">
                                <label class="form-label fw-bold text-muted small text-uppercase ls-1">Payment Method <span class="text-danger">*</span></label>
                                <select class="form-select select2" name="payment_method" required>
                                    <option value="">Select Method</option>
                                    <option value="Bank Transfer">Bank Transfer</option>
                                    <option value="Cash">Cash</option>
                                    <option value="Mobile Banking">Mobile Banking</option>
                                    <option value="Cheque">Cheque</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold text-muted small text-uppercase ls-1">Upload Proofs</label>
                                <div class="file-upload-wrapper border-dashed rounded-3 p-4 text-center bg-light" style="transition: all 0.3s ease;">
                                    <i data-feather="upload-cloud" class="text-muted mb-2" style="width: 30px; height: 30px;"></i>
                                    <input type="file" class="form-control form-control-sm opacity-0 position-absolute" name="attachments[]" id="attachments" multiple accept=".pdf,.jpg,.jpeg,.png,.zip" style="width: 1px;">
                                    <label for="attachments" class="d-block text-primary small fw-bold mb-1" style="cursor: pointer;">Click to Upload</label>
                                    <div class="form-text small" style="font-size: 11px;">PDF, Images, or ZIP (Max 5MB each)</div>
                                    <div id="file-list" class="mt-2 text-start small text-dark fw-medium"></div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold text-muted small text-uppercase ls-1">Internal Note</label>
                                <textarea class="form-control bg-light border-0 p-3" name="note" rows="4" placeholder="Transaction IDs, cheque numbers, or general remarks..." style="border-radius: 8px; font-size: 13px;"></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-lg" id="submitBtn" style="letter-spacing: 1px;">
                                <i data-feather="check-circle" class="me-2" style="width: 18px;"></i> CONFIRM PAYMENT
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Spacer for bottom margin --}}
            <div class="mb-5 pb-5"></div>
        </form>
    </div>

<style>
    .bg-soft-primary { background-color: rgba(16, 141, 255, 0.08); }
    .border-dashed { border: 2px dashed #dee2e6 !important; }
    .ls-1 { letter-spacing: 0.5px; }
    .ls-2 { letter-spacing: 1px; }
    .img-thumbnail { padding: .2rem; background-color: #fff; border: 1px solid #dee2e6; border-radius: 50%; }
    .table > :not(caption) > * > * { padding: 1rem 0.5rem; }
    .file-upload-wrapper:hover { background-color: #f1f4f9 !important; border-color: #108dff !important; }
</style>

@push('scripts')
<script>
    $(document).ready(function() {
        const $checkboxes = $('.employee-checkbox');
        const $selectAll = $('#selectAll');
        const $selectedCount = $('#selectedCount');
        const $selectedTotal = $('#selectedTotal');
        const $submitBtn = $('#submitBtn');
        const $fileInput = $('#attachments');
        const $fileList = $('#file-list');

        function updateTotals() {
            let count = 0;
            let total = 0;
            
            $checkboxes.each(function() {
                if ($(this).is(':checked')) {
                    count++;
                    total += parseFloat($(this).data('amount'));
                }
            });

            $selectedCount.text(count);
            $selectedTotal.text('৳ ' + total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));

            $submitBtn.prop('disabled', count === 0);
        }

        $selectAll.on('change', function() {
            $checkboxes.prop('checked', $(this).is(':checked'));
            updateTotals();
        });

        $checkboxes.on('change', function() {
            if (!$(this).is(':checked')) {
                $selectAll.prop('checked', false);
            } else if ($('.employee-checkbox:checked').length === $checkboxes.length) {
                $selectAll.prop('checked', true);
            }
            updateTotals();
        });

        // File upload visual feedback
        $fileInput.on('change', function() {
            let files = this.files;
            if (files.length > 0) {
                $fileList.empty();
                for (let i = 0; i < files.length; i++) {
                    $fileList.append(`<div><i data-feather="file" class="me-1" style="width:12px;"></i> ${files[i].name}</div>`);
                }
                if (typeof feather !== 'undefined') feather.replace();
            }
        });

        $('#disbursementForm').on('submit', function() {
            $submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Processing...');
        });
    });
</script>
@endpush
@endsection
