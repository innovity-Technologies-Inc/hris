@extends('structure.master')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i data-feather="play-circle" class="me-2 text-primary"></i>
                    Process Disbursement for Batch: <span class="text-primary">{{ $process->batch_id }}</span>
                </h5>
                <a href="{{ route('disbursement.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i data-feather="arrow-left" class="me-1"></i> Back to List
                </a>
            </div>
            
            <div class="card-body p-4 bg-light rounded-bottom">
                <form action="{{ route('disbursement.store') }}" method="POST" enctype="multipart/form-data" id="disbursementForm">
                    @csrf
                    <input type="hidden" name="process_id" value="{{ $process->id }}">

                    <div class="row g-4">
                        {{-- Left Column: Employee Selection --}}
                        <div class="col-lg-8">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 d-flex justify-content-between align-items-center">
                                    <h6 class="fw-bold mb-0">Eligible Employees (Pending Payment)</h6>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="selectAll" checked>
                                        <label class="form-check-label" for="selectAll">Select All</label>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive" style="max-height: 500px;">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="table-light sticky-top">
                                                <tr>
                                                    <th class="text-center" style="width: 50px;"></th>
                                                    <th>Employee</th>
                                                    <th class="text-end">Net Payable</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($items as $item)
                                                    @php
                                                        $employee = $item->getEmployee;
                                                        $amount = $process->type === 'salary' ? $item->total_salary : $item->amount;
                                                    @endphp
                                                <tr>
                                                    <td class="text-center">
                                                        <div class="form-check d-flex justify-content-center">
                                                            <input class="form-check-input employee-checkbox" type="checkbox" name="record_ids[]" value="{{ $item->id }}" data-amount="{{ $amount }}" checked>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-sm me-3">
                                                                @if($employee->photo_path)
                                                                    <img src="{{ asset('storage/' . $employee->photo_path) }}" alt="user-img" class="rounded-circle img-thumbnail shadow-sm" style="width: 40px; height: 40px; object-fit: cover;">
                                                                @else
                                                                    <div class="avatar-title rounded-circle bg-soft-primary text-primary d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-size: 14px; border: 1px solid #dee2e6;">
                                                                        {{ strtoupper(substr($employee->full_name, 0, 1)) }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div>
                                                                <h6 class="mb-0">{{ $employee->full_name }}</h6>
                                                                <small class="text-muted">{{ $employee->system_id }} | {{ $employee->officeInfo->getCurrentDesignation->company_designation ?? 'N/A' }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-end fw-bold text-success fs-6">
                                                        {{ number_format($amount, 2) }}
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="card-footer bg-white border-top text-end">
                                    <span class="text-muted me-3">Selected Employees: <strong id="selectedCount" class="text-dark">{{ count($items) }}</strong></span>
                                    <span class="text-muted">Selected Total: <strong id="selectedTotal" class="text-success fs-5">{{ number_format($items->sum($process->type === 'salary' ? 'total_salary' : 'amount'), 2) }}</strong></span>
                                </div>
                            </div>
                        </div>

                        {{-- Right Column: Payment Details --}}
                        <div class="col-lg-4">
                            <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">
                                <div class="card-header bg-white pt-4 pb-2 border-bottom-0">
                                    <h6 class="fw-bold mb-0">Disbursement Details</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-4">
                                        <label class="form-label fw-semibold text-muted small">Payment Method <span class="text-danger">*</span></label>
                                        <select class="form-select select2" name="payment_method" required>
                                            <option value="">Select Method</option>
                                            <option value="Bank Transfer">Bank Transfer</option>
                                            <option value="Cash">Cash</option>
                                            <option value="Mobile Banking">Mobile Banking (bKash/Nagad/Rocket)</option>
                                            <option value="Cheque">Cheque</option>
                                        </select>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label fw-semibold text-muted small">Upload Proofs of Payment</label>
                                        <input type="file" class="form-control" name="attachments[]" multiple accept=".pdf,.jpg,.jpeg,.png,.zip">
                                        <div class="form-text">You can select multiple files (Max 5MB per file).</div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label fw-semibold text-muted small">Internal Note (Optional)</label>
                                        <textarea class="form-control" name="note" rows="3" placeholder="Enter transaction IDs, cheque numbers, or remarks..."></textarea>
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100 py-2 rounded-pill fw-bold shadow-sm" id="submitBtn">
                                        <i data-feather="check-circle" class="me-2"></i> Confirm Disbursement
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-soft-primary { background-color: rgba(16, 141, 255, 0.05); }
</style>

@push('scripts')
<script>
    $(document).ready(function() {
        const $checkboxes = $('.employee-checkbox');
        const $selectAll = $('#selectAll');
        const $selectedCount = $('#selectedCount');
        const $selectedTotal = $('#selectedTotal');
        const $submitBtn = $('#submitBtn');

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
            // Format number with commas and 2 decimals
            $selectedTotal.text(total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));

            if (count === 0) {
                $submitBtn.prop('disabled', true);
            } else {
                $submitBtn.prop('disabled', false);
            }
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

        $('#disbursementForm').on('submit', function() {
            $submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Processing...');
        });
    });
</script>
@endpush
@endsection
