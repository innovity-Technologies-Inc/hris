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
                                if (!function_exists('formatProfileUpdateData')) {
                                    function formatProfileUpdateData($val) {
                                        if (is_array($val)) {
                                            if (empty($val)) {
                                                return '<span class="text-muted font-12">Empty</span>';
                                            }

                                            // If it is a sequential list of objects (e.g. educations, trainings, histories)
                                            if (isset($val[0]) && is_array($val[0])) {
                                                $html = '<div class="table-responsive"><table class="table table-sm table-bordered m-0 font-12 bg-white">';
                                                $headers = array_keys($val[0]);
                                                $html .= '<thead class="table-light"><tr>';
                                                foreach ($headers as $h) {
                                                    $html .= '<th class="py-1">' . ucfirst(str_replace('_', ' ', $h)) . '</th>';
                                                }
                                                $html .= '</tr></thead><tbody>';
                                                foreach ($val as $row) {
                                                    $html .= '<tr>';
                                                    foreach ($headers as $h) {
                                                        $html .= '<td class="py-1">' . e($row[$h] ?? 'N/A') . '</td>';
                                                    }
                                                    $html .= '</tr>';
                                                }
                                                $html .= '</tbody></table></div>';
                                                return $html;
                                            }

                                            // If it is an associative array (e.g. addresses)
                                            $html = '<ul class="list-unstyled mb-0 font-12">';
                                            foreach ($val as $k => $v) {
                                                if (is_array($v)) {
                                                    $v = json_encode($v);
                                                }
                                                $html .= '<li class="mb-1"><strong>' . ucfirst(str_replace('_', ' ', $k)) . ':</strong> ' . e($v) . '</li>';
                                            }
                                            $html .= '</ul>';
                                            return $html;
                                        }
                                        return e($val);
                                    }
                                }

                                $previous = is_array($updateRequest->previous_data) ? $updateRequest->previous_data : [];
                                $requested = is_array($updateRequest->requested_data) ? $updateRequest->requested_data : [];
                                $allKeys = array_unique(array_merge(array_keys($previous), array_keys($requested)));
                            @endphp
                            
                            @foreach($allKeys as $key)
                                @php
                                    $prevVal = $previous[$key] ?? '';
                                    $reqVal = $requested[$key] ?? '';
                                    
                                    $prevStr = is_array($prevVal) ? json_encode($prevVal) : $prevVal;
                                    $reqStr = is_array($reqVal) ? json_encode($reqVal) : $reqVal;
                                    $hasChanged = $prevStr != $reqStr;
                                @endphp
                                <tr class="{{ $hasChanged ? 'table-warning' : '' }}">
                                    <td class="fw-semibold text-capitalize align-middle" style="width: 200px;">{{ str_replace('_', ' ', $key) }}</td>
                                    <td class="align-middle">{!! formatProfileUpdateData($prevVal) !!}</td>
                                    <td class="align-middle {{ $hasChanged ? 'text-danger fw-bold' : '' }}">{!! formatProfileUpdateData($reqVal) !!}</td>
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