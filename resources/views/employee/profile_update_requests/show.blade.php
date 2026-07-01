@extends('structure.master')
@section('title', 'Review Profile Update Request')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Review Profile Update Request</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <img src="{{ $updateRequest->employee->photo_path ? asset('storage/' . $updateRequest->employee->photo_path) : asset('assets/images/users/avatar-1.jpg') }}" class="rounded-circle avatar-lg img-thumbnail" alt="profile-image">
                <h4 class="mb-0 mt-2">{{ $updateRequest->employee->full_name }}</h4>
                <p class="text-muted font-14">{{ $updateRequest->employee->punch_card_no }}</p>
                <div class="text-start mt-3">
                    <p class="text-muted mb-2 font-13"><strong>Section Requested:</strong> <span class="badge bg-info text-capitalize ms-2">{{ str_replace('_', ' ', $updateRequest->section) }}</span></p>
                    <p class="text-muted mb-2 font-13"><strong>Status:</strong> 
                        @if($updateRequest->status === 'pending')
                            <span class="badge bg-warning ms-2">Pending</span>
                        @elseif($updateRequest->status === 'approved')
                            <span class="badge bg-success ms-2">Approved</span>
                        @else
                            <span class="badge bg-danger ms-2">Rejected</span>
                        @endif
                    </p>
                    <p class="text-muted mb-2 font-13"><strong>Requested At:</strong> <span class="ms-2">{{ $updateRequest->created_at->format('d M Y, h:i A') }}</span></p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header border-bottom">
                <h4 class="header-title">Data Comparison</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Field Name</th>
                                <th>Previous Data</th>
                                <th>Requested Changes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                // We combine keys from both to handle additions and removals
                                $previous = is_array($updateRequest->previous_data) ? $updateRequest->previous_data : [];
                                $requested = is_array($updateRequest->requested_data) ? $updateRequest->requested_data : [];
                                $allKeys = array_unique(array_merge(array_keys($previous), array_keys($requested)));
                            @endphp
                            
                            @foreach($allKeys as $key)
                                @php
                                    $prevVal = $previous[$key] ?? '';
                                    $reqVal = $requested[$key] ?? '';
                                    
                                    // Handle array values gracefully (like JSON arrays inside)
                                    if(is_array($prevVal)) $prevVal = json_encode($prevVal);
                                    if(is_array($reqVal)) $reqVal = json_encode($reqVal);
                                    
                                    $hasChanged = $prevVal != $reqVal;
                                @endphp
                                <tr class="{{ $hasChanged ? 'table-warning' : '' }}">
                                    <td class="fw-semibold text-capitalize">{{ str_replace('_', ' ', $key) }}</td>
                                    <td>{{ $prevVal }}</td>
                                    <td class="{{ $hasChanged ? 'text-danger fw-bold' : '' }}">{{ $reqVal }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Approval Workflow Actions -->
                <div class="mt-4 border-top pt-3">
                    <h5 class="mb-3">Approval Workflow Actions</h5>
                    <!-- The Approval Engine will hook into this if the traits/components are used. -->
                    <!-- Otherwise, you can place standard Approve/Reject buttons here -->
                    @include('components.approval_timeline', ['model' => $updateRequest])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection