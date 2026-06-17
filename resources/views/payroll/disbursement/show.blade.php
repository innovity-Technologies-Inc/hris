@extends('structure.master')

@section('content')
<div class="container-fluid px-0">
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
                        <p class="text-muted small mb-0 ms-4">Batch ID: <span class="fw-bold text-primary">{{ $process->batch_id }}</span> | Month: <span class="fw-bold">{{ \Carbon\Carbon::parse($process->salary_month)->format('F Y') }}</span></p>
                    </div>
                    <a href="{{ route('disbursement.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 shadow-sm">
                        <i data-feather="arrow-left" class="me-1" style="width: 14px;"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center p-3" style="border-radius: 12px; border-left: 5px solid #6366f1 !important;">
                <small class="text-muted text-uppercase fw-bold ls-1 d-block mb-1">Total Employees</small>
                <h3 class="fw-bold mb-0 text-dark">{{ $stats['total_employees'] }}</h3>
                <small class="text-muted mt-1 d-block">Eligible for Pay: {{ $stats['eligible_employees'] }}</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center p-3" style="border-radius: 12px; border-left: 5px solid #10b981 !important;">
                <small class="text-muted text-uppercase fw-bold ls-1 d-block mb-1">Total Paid</small>
                <h3 class="fw-bold mb-0 text-success">{{ $stats['paid_employees'] }}</h3>
                <small class="text-muted mt-1 d-block">Completion: {{ round(($stats['paid_employees'] / ($stats['eligible_employees'] ?: 1)) * 100) }}%</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center p-3" style="border-radius: 12px; border-left: 5px solid #f59e0b !important;">
                <small class="text-muted text-uppercase fw-bold ls-1 d-block mb-1">Pending Pay</small>
                <h3 class="fw-bold mb-0 text-warning">{{ $stats['pending_employees'] }}</h3>
                <small class="text-muted mt-1 d-block">Remaining to disburse</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center p-3" style="border-radius: 12px; border-left: 5px solid #ef4444 !important;">
                <small class="text-muted text-uppercase fw-bold ls-1 d-block mb-1">Total Value</small>
                <h3 class="fw-bold mb-0 text-primary">৳ {{ number_format($stats['total_amount'], 2) }}</h3>
                <small class="text-muted mt-1 d-block text-truncate">Paid: ৳ {{ number_format($stats['paid_amount'], 2) }}</small>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="row g-4">
        {{-- Disbursement History --}}
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-header bg-white pt-4 px-4 border-bottom-0">
                    <h6 class="fw-bold mb-1 text-dark">Disbursement Transactions</h6>
                    <p class="text-muted small">History of all payment events for this batch.</p>
                </div>
                <div class="card-body p-0">
                    @forelse($disbursements as $index => $disb)
                    <div class="disbursement-block p-4 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div class="row align-items-start g-4">
                            {{-- Info --}}
                            <div class="col-md-3">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle bg-soft-info p-2 me-3">
                                        <i data-feather="check-square" class="text-info" style="width: 18px;"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">{{ $disb->batch_id }}</h6>
                                        <small class="text-muted">{{ $disb->created_at->format('d M, Y H:i A') }}</small>
                                    </div>
                                </div>
                                <div class="ps-1">
                                    <p class="mb-1 small"><strong>Method:</strong> {{ $disb->payment_method }}</p>
                                    <p class="mb-1 small"><strong>Disbursed By:</strong> {{ $disb->disbursedBy->name ?? 'System' }}</p>
                                    <p class="mb-3 small text-muted italic">"{{ $disb->note ?? 'No notes provided' }}"</p>
                                    
                                    @if($disb->attachments->isNotEmpty())
                                    <div class="attachments-area mt-2">
                                        <small class="d-block text-uppercase fw-bold text-muted mb-2 ls-1" style="font-size: 10px;">Payment Proofs</small>
                                        @foreach($disb->attachments as $file)
                                        <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" class="btn btn-light btn-xs rounded-pill px-3 mb-1 small d-inline-flex align-items-center me-1">
                                            <i data-feather="paperclip" class="me-1" style="width: 12px;"></i> {{ Str::limit($file->original_name, 15) }}
                                        </a>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Employee List --}}
                            <div class="col-md-9">
                                <div class="bg-light p-3 rounded-4" style="max-height: 250px; overflow-y: auto;">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-borderless align-middle mb-0">
                                            <thead>
                                                <tr>
                                                    <th class="small text-muted text-uppercase px-2">Staff Details</th>
                                                    <th class="text-end small text-muted text-uppercase px-2">Amount Paid</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($disb->items as $item)
                                                <tr>
                                                    <td class="px-2">
                                                        <div class="d-flex align-items-center py-1">
                                                            <div class="avatar-xs me-2">
                                                                @if($item->employee->photo_path)
                                                                    <img src="{{ asset('storage/' . $item->employee->photo_path) }}" alt="img" class="rounded-circle shadow-sm" style="width: 30px; height: 30px; object-fit: cover;">
                                                                @else
                                                                    <div class="avatar-title rounded-circle bg-white text-primary d-flex align-items-center justify-content-center fw-bold border" style="width: 30px; height: 30px; font-size: 10px;">
                                                                        {{ strtoupper(substr($item->employee->full_name, 0, 1)) }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div>
                                                                <span class="fw-bold small d-block text-dark">{{ $item->employee->full_name }}</span>
                                                                <span class="text-muted" style="font-size: 10px;">{{ $item->employee->system_id }} | {{ $item->employee->officeInfo->getCurrentDesignation->company_designation ?? 'N/A' }}</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-end px-2 fw-bold text-dark small">
                                                        ৳ {{ number_format($item->amount, 2) }}
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="mt-2 text-end me-3">
                                    <span class="small text-muted">Subtotal ({{ $disb->total_employees }} Staff): </span>
                                    <span class="fw-bold text-success fs-6">৳ {{ number_format($disb->total_amount, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5">
                        <i data-feather="inbox" class="text-muted mb-3" style="width: 48px; height: 48px;"></i>
                        <p class="text-muted mb-0">No disbursements have been recorded for this batch yet.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    
    {{-- Bottom Spacer --}}
    <div class="mb-5 pb-5"></div>
</div>

<style>
    .bg-soft-info { background-color: rgba(13, 202, 240, 0.1); }
    .italic { font-style: italic; }
    .btn-xs { padding: .2rem .5rem; font-size: .75rem; }
    .disbursement-block { transition: all 0.3s ease; }
    .disbursement-block:hover { background-color: #fafbfc; }
    .ls-1 { letter-spacing: 0.5px; }
</style>
@endsection
