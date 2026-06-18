@extends('structure.master')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm rounded mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-gift me-2"></i>Bonus & Reward Details - {{ $bonus->getEmployee->full_name }}
                    </h5>
                    <div>
                        <a href="{{ url()->previous() }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    {{-- Employee & Batch Header --}}
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    {!! \App\HelperClass::generateAvatar(
                                        $bonus->getEmployee->photo_path,
                                        $bonus->getEmployee->full_name,
                                        80,
                                        '#974063',
                                        'rounded-circle shadow-sm',
                                        $bonus->getEmployee->id,
                                    ) !!}
                                </div>
                                <div>
                                    <h4 class="mb-1 text-primary">{{ $bonus->getEmployee->full_name }}</h4>
                                    <p class="text-muted mb-0"><strong>ID:</strong> {{ $bonus->getEmployee->applicant_id }}</p>
                                    <p class="text-muted mb-0"><strong>System ID:</strong> {{ $bonus->getEmployee->system_id }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 text-md-end mt-3 mt-md-0">
                            <div class="p-3 bg-light rounded shadow-sm border-start border-4 border-primary d-inline-block text-start">
                                <h6 class="text-muted small text-uppercase fw-bold mb-2">Bonus Information</h6>
                                <p class="mb-1"><strong>Batch ID:</strong> <span class="text-primary">{{ $bonus->batch_id }}</span></p>
                                <p class="mb-0"><strong>Salary Month:</strong> {{ $bonus->getBatch ? \Carbon\Carbon::parse($bonus->getBatch->salary_month)->format('F, Y') : 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        {{-- Bonus Breakdown --}}
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 border-0 shadow-sm bg-success bg-opacity-10 border-top border-4 border-success">
                                <div class="card-body">
                                    <h6 class="card-title text-success fw-bold mb-3">
                                        <i class="fas fa-plus-circle me-2"></i>Bonus Breakdown
                                    </h6>
                                    <ul class="list-group list-group-flush bg-transparent">
                                        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0 border-light">
                                            Total Bonus Amount
                                            <span class="fw-bold fs-5 text-success">৳ {{ number_format($bonus->amount, 2) }}</span>
                                        </li>
                                    </ul>
                                    
                                    <div class="mt-4 pt-3 border-top border-success">
                                        <h6 class="text-muted small text-uppercase fw-bold mb-3">Applicable Plans in this Batch</h6>
                                        @if($bonusPlans->count() > 0)
                                            <div class="list-group list-group-flush bg-transparent">
                                                @foreach($bonusPlans as $plan)
                                                    <div class="list-group-item bg-transparent px-0 border-light py-2">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div>
                                                                <span class="fw-bold d-block">{{ $plan->name }}</span>
                                                                <small class="text-muted">{{ $plan->bonus_config_type }} ({{ $plan->salary_rate_type ?? 'Fixed' }})</small>
                                                            </div>
                                                            <span class="badge bg-success bg-opacity-75">Active</span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-muted small">No specific plans recorded for this batch.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Batch Summary --}}
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 border-0 shadow-sm border-top border-4 border-info bg-info bg-opacity-10">
                                <div class="card-body">
                                    <h6 class="card-title text-info fw-bold mb-3">
                                        <i class="fas fa-info-circle me-2"></i>Batch Summary
                                    </h6>
                                    @if($bonus->getBatch)
                                        <div class="row g-3">
                                            <div class="col-6">
                                                <div class="p-3 rounded shadow-sm">
                                                    <small class="text-muted d-block">Process Type</small>
                                                    <span class="fw-bold text-uppercase">{{ $bonus->getBatch->type }}</span>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="p-3 rounded shadow-sm">
                                                    <small class="text-muted d-block">Approval Status</small>
                                                    @php
                                                        $statusClass = [
                                                            'pending' => 'bg-warning',
                                                            'approved' => 'bg-success',
                                                            'rejected' => 'bg-danger'
                                                        ][$bonus->getBatch->approval_status] ?? 'bg-secondary';
                                                    @endphp
                                                    <span class="badge {{ $statusClass }} text-uppercase">{{ $bonus->getBatch->approval_status }}</span>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="p-3 rounded shadow-sm">
                                                    <small class="text-muted d-block">Generated By</small>
                                                    <span class="fw-bold">{{ $bonus->getBatch->generatedBy->name ?? 'System' }}</span>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="p-3 rounded shadow-sm">
                                                    <small class="text-muted d-block">Generation Date</small>
                                                    <span class="fw-bold">{{ $bonus->getBatch->created_at->format('d M, Y') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <p class="text-muted">Batch information not available.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
