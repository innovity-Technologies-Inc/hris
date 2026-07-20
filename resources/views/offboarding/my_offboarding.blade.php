@extends('structure.master')

@section('content')
<div class="row justify-content-center my-5">
    <div class="col-lg-8">
        <div class="card border-0 shadow-lg rounded-4">
            <div class="card-header bg-danger text-white rounded-top-4 p-4 text-center">
                <i class="mdi mdi-account-lock fs-1 d-block mb-2 opacity-75"></i>
                <h4 class="fw-bold mb-0">Account Offboarded</h4>
                <small class="text-white-50">Portal access is restricted to offboarding details</small>
            </div>
            <div class="card-body p-4 p-md-5">
                <div class="alert alert-warning border-0 rounded-3 mb-4 d-flex align-items-center">
                    <i class="mdi mdi-alert-circle text-warning fs-3 me-3"></i>
                    <div>
                        <strong class="d-block">Notice:</strong>
                        Your employee profile status is set to <span class="badge bg-danger text-uppercase">{{ $user->employee?->status ?? 'Offboarded' }}</span>. Standard portal navigation and features are disabled.
                    </div>
                </div>

                @if($offboarding)
                <div class="card border rounded-3 mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0 fw-semibold text-primary"><i class="mdi mdi-clipboard-text-clock me-2"></i>My Offboarding Details</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless table-sm mb-0">
                            <tr>
                                <th class="text-muted" style="width: 35%;">Employee Name:</th>
                                <td class="fw-bold text-dark">{{ $user->employee?->full_name ?? $user->name }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Applicant ID:</th>
                                <td>{{ $user->employee?->applicant_id ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Offboarding Type:</th>
                                <td>
                                    @if($offboarding->offboarding_type === 'termination')
                                        <span class="badge bg-danger text-white">Termination</span>
                                    @else
                                        <span class="badge bg-primary text-white">Resignation</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="text-muted">Notice Date:</th>
                                <td>{{ \Carbon\Carbon::parse($offboarding->resignation_date)->format('M d, Y') }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Notice Period:</th>
                                <td><span class="badge bg-info text-dark">{{ $offboarding->notice_period_days }} Days</span></td>
                            </tr>
                            <tr>
                                <th class="text-muted">Last Working Day:</th>
                                <td class="fw-bold text-danger">{{ \Carbon\Carbon::parse($offboarding->last_working_day)->format('M d, Y') }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Status:</th>
                                <td><span class="badge bg-secondary text-uppercase">{{ $offboarding->status }}</span></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="fw-semibold text-dark mb-2">Reason:</h6>
                    <p class="text-secondary bg-light p-3 rounded-3 border mb-0">{{ $offboarding->reason }}</p>
                </div>
                @else
                <div class="text-center py-4 text-muted">
                    No active offboarding record found for your account. Please contact your HR administrator.
                </div>
                @endif

                <div class="text-center mt-4">
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger px-4 rounded-3">
                            <i class="mdi mdi-logout me-1"></i> Log Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
