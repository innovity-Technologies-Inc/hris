@extends('structure.master')
@section('title', 'Review Profile Update Request')

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title text-dark fw-bold">Review Profile Update Request</h4>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Employee Info Profile Card -->
    <div class="col-lg-4">
        <div class="card border shadow-sm rounded-3">
            <div class="card-body text-center p-4">
                <div class="mb-3 d-flex justify-content-center">
                    {!! \App\HelperClass::generateAvatar(
                        $updateRequest->employee->photo_path ?? null,
                        $updateRequest->employee->full_name ?? 'N/A',
                        90,
                        '#974063',
                        'img-thumbnail rounded-circle shadow-sm border border-2 border-primary',
                        $updateRequest->employee->id ?? 0,
                    ) !!}
                </div>
                <h4 class="mb-1 fw-bold text-dark">{{ $updateRequest->employee->full_name }}</h4>
                <p class="text-muted font-14 mb-3"><i class="mdi mdi-card-account-details-outline me-1"></i>{{ $updateRequest->employee->punch_card_no }}</p>
                
                <hr class="my-3 opacity-50">

                <div class="text-start mt-3">
                    <div class="d-flex justify-content-between align-items-center mb-2 font-13 text-muted">
                        <strong>Requested Section:</strong>
                        <span class="badge bg-info-subtle text-info text-capitalize px-2.5 py-1 fw-normal">
                            {{ str_replace('_', ' ', $updateRequest->section) }}
                        </span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-2 font-13 text-muted">
                        <strong>Status:</strong>
                        @if($updateRequest->status === 'pending')
                            <span class="badge bg-warning-subtle text-warning px-2.5 py-1 fw-normal">Pending Approval</span>
                        @elseif($updateRequest->status === 'approved')
                            <span class="badge bg-success-subtle text-success px-2.5 py-1 fw-normal">Approved</span>
                        @else
                            <span class="badge bg-danger-subtle text-danger px-2.5 py-1 fw-normal">Rejected</span>
                        @endif
                    </div>

                    <div class="d-flex justify-content-between align-items-center font-13 text-muted">
                        <strong>Submitted Date:</strong>
                        <span class="fw-semibold text-dark">{{ $updateRequest->created_at->format('d M Y, h:i A') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Data Comparison and Actions -->
    <div class="col-lg-8">
        <div class="card border shadow-sm rounded-3">
            <div class="card-header bg-transparent border-bottom py-3">
                <h5 class="card-title mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                    <i class="mdi mdi-swap-horizontal text-primary fs-4"></i> Data Comparison & Changes
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive rounded border">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="py-2.5 fw-bold text-dark" style="width: 25%;">Field Name</th>
                                <th class="py-2.5 fw-bold text-dark" style="width: 37.5%;">Previous Profile Value</th>
                                <th class="py-2.5 fw-bold text-dark" style="width: 37.5%;">Requested New Value</th>
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
                                <tr class="{{ $hasChanged ? 'table-warning-subtle' : '' }}">
                                    <td class="fw-semibold text-capitalize text-dark py-2.5">{{ str_replace('_', ' ', $key) }}</td>
                                    <td class="py-2.5 text-muted">{!! formatProfileUpdateData($prevVal) !!}</td>
                                    <td class="py-2.5 {{ $hasChanged ? 'text-primary fw-bold bg-light-subtle' : 'text-muted' }}">
                                        {!! formatProfileUpdateData($reqVal) !!}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Approval Workflow Actions -->
                <div class="mt-4 pt-3">
                    @include('approval_engine.workflow_history', ['approvable' => $updateRequest])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection