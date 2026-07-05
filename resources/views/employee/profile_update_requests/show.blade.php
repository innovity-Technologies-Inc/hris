@extends('structure.master')
@section('title', 'Review Profile Update Request')

@section('content')
<div class="row g-4">
    <!-- Left Column: Employee Info Profile Card -->
    <div class="col-lg-4">
        <div class="card border shadow-sm rounded-3">
            <div class="card-body text-center p-4">
                <div class="mb-3 d-flex justify-content-center">
                    {!! \App\HelperClass::generateAvatar(
                        $updateRequest->employee?->photo_path ?? null,
                        $updateRequest->employee?->full_name ?? 'N/A',
                        90,
                        '#974063',
                        'img-thumbnail rounded-circle shadow-sm border border-2 border-primary',
                        $updateRequest->employee?->id ?? 0,
                    ) !!}
                </div>
                <h4 class="mb-1 fw-bold text-dark">{{ $updateRequest->employee?->full_name ?? 'N/A' }}</h4>
                <p class="text-muted font-14 mb-3"><i class="mdi mdi-card-account-details-outline me-1"></i>{{ $updateRequest->employee?->punch_card_no ?? 'N/A' }}</p>
                
                <hr class="my-3 opacity-50">

                <div class="text-start mt-3">
                    <div class="d-flex justify-content-between align-items-center mb-2 font-13 text-muted">
                        <strong>Request Type:</strong>
                        @if(($updateRequest->type ?? 'employee') === 'admin')
                            <span class="badge bg-primary-subtle text-primary text-capitalize px-2.5 py-1 fw-normal">Admin Edit</span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary text-capitalize px-2.5 py-1 fw-normal">Employee Submission</span>
                        @endif
                    </div>

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
                                    function formatProfileUpdateData($val, $key = null) {
                                        if (is_array($val)) {
                                            if (empty($val)) {
                                                return '<span class="text-muted font-12">Empty</span>';
                                            }

                                            // If it is a simple list of values (e.g. weekends: ["Friday", "Saturday"])
                                            if (array_is_list($val) && !is_array($val[0])) {
                                                $formattedList = array_map(function($item) {
                                                    $itemStr = is_array($item) ? json_encode($item) : (string)$item;
                                                    if (in_array(strtolower($itemStr), ['yes', 'no', 'permanent', 'contractual', 'active', 'inactive'])) {
                                                        return ucfirst(strtolower($itemStr));
                                                    }
                                                    return $itemStr;
                                                }, $val);
                                                return e(implode(', ', $formattedList));
                                            }

                                            // If it is a sequential list of objects (e.g. educations, trainings, histories)
                                            if (isset($val[0]) && is_array($val[0])) {
                                                $html = '<div class="d-flex flex-column gap-3">';
                                                foreach ($val as $index => $row) {
                                                    if ($key === 'educations') {
                                                        $title = $row['education_title'] ?? $row['exam_degree_title'] ?? $row['degree'] ?? 'Degree/Exam';
                                                        $institute = $row['institute'] ?? $row['board'] ?? $row['university'] ?? 'Institution';
                                                        $passingYear = $row['passing_year'] ?? $row['year'] ?? '';
                                                        $result = $row['result_grade'] ?? $row['result'] ?? $row['gpa_cgpa'] ?? '';

                                                        $html .= '<div class="card border border-light-subtle shadow-sm rounded-3 p-3 mb-0 bg-white text-start">';
                                                        $html .= '  <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">';
                                                        $html .= '    <div class="d-flex align-items-center gap-2">';
                                                        $html .= '      <span class="avatar-title rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;"><i class="mdi mdi-school font-16"></i></span>';
                                                        $html .= '      <h6 class="mb-0 fw-bold text-dark text-wrap">' . e($title) . '</h6>';
                                                        $html .= '    </div>';
                                                        $html .= '    <span class="badge bg-primary-subtle text-primary fw-semibold">#' . ($index + 1) . '</span>';
                                                        $html .= '  </div>';
                                                        $html .= '  <div class="font-12 text-muted mb-2"><i class="mdi mdi-office-building me-1 text-muted"></i>' . e($institute) . '</div>';
                                                        $html .= '  <div class="d-flex flex-wrap gap-2 mt-1">';
                                                        if ($passingYear) {
                                                            $html .= '    <span class="badge bg-light text-dark border"><i class="mdi mdi-calendar me-1 text-muted"></i>Year: ' . e($passingYear) . '</span>';
                                                        }
                                                        if ($result) {
                                                            $html .= '    <span class="badge bg-success-subtle text-success border border-success-subtle"><i class="mdi mdi-certificate me-1 text-success"></i>Result: ' . e($result) . '</span>';
                                                        }
                                                        $html .= '  </div>';
                                                        $html .= '</div>';
                                                    } elseif ($key === 'trainings') {
                                                        $title = $row['training_title'] ?? $row['course'] ?? $row['title'] ?? 'Training Course';
                                                        $institute = $row['institute'] ?? $row['organization'] ?? 'Institution';
                                                        $duration = $row['duration'] ?? $row['passing_year'] ?? '';

                                                        $html .= '<div class="card border border-light-subtle shadow-sm rounded-3 p-3 mb-0 bg-white text-start">';
                                                        $html .= '  <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">';
                                                        $html .= '    <div class="d-flex align-items-center gap-2">';
                                                        $html .= '      <span class="avatar-title rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;"><i class="mdi mdi-certificate font-16"></i></span>';
                                                        $html .= '      <h6 class="mb-0 fw-bold text-dark text-wrap">' . e($title) . '</h6>';
                                                        $html .= '    </div>';
                                                        $html .= '    <span class="badge bg-success-subtle text-success fw-semibold">#' . ($index + 1) . '</span>';
                                                        $html .= '  </div>';
                                                        $html .= '  <div class="font-12 text-muted mb-2"><i class="mdi mdi-office-building me-1 text-muted"></i>' . e($institute) . '</div>';
                                                        if ($duration) {
                                                            $html .= '  <div class="d-flex flex-wrap gap-2 mt-1">';
                                                            $html .= '    <span class="badge bg-light text-dark border"><i class="mdi mdi-clock-outline me-1 text-muted"></i>' . e($duration) . '</span>';
                                                            $html .= '  </div>';
                                                        }
                                                        $html .= '</div>';
                                                    } elseif ($key === 'histories') {
                                                        $company = $row['company_name'] ?? $row['company'] ?? 'Company';
                                                        $designation = $row['designation'] ?? 'Designation';
                                                        $period = isset($row['start_date']) ? ($row['start_date'] . ' - ' . ($row['end_date'] ?? 'Present')) : ($row['duration'] ?? '');

                                                        $html .= '<div class="card border border-light-subtle shadow-sm rounded-3 p-3 mb-0 bg-white text-start">';
                                                        $html .= '  <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">';
                                                        $html .= '    <div class="d-flex align-items-center gap-2">';
                                                        $html .= '      <span class="avatar-title rounded-circle bg-info bg-opacity-10 text-info d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;"><i class="mdi mdi-briefcase font-16"></i></span>';
                                                        $html .= '      <h6 class="mb-0 fw-bold text-dark text-wrap">' . e($company) . '</h6>';
                                                        $html .= '    </div>';
                                                        $html .= '    <span class="badge bg-info-subtle text-info fw-semibold">#' . ($index + 1) . '</span>';
                                                        $html .= '  </div>';
                                                        $html .= '  <div class="font-12 text-muted mb-2"><i class="mdi mdi-account-tie me-1 text-muted"></i>' . e($designation) . '</div>';
                                                        if ($period) {
                                                            $html .= '  <div class="d-flex flex-wrap gap-2 mt-1">';
                                                            $html .= '    <span class="badge bg-light text-dark border"><i class="mdi mdi-calendar-range me-1 text-muted"></i>' . e($period) . '</span>';
                                                            $html .= '  </div>';
                                                        }
                                                        $html .= '</div>';
                                                    } else {
                                                        // Generic fallback card formatting
                                                        $html .= '<div class="card border border-light-subtle shadow-none bg-light-subtle p-2.5 rounded-3 mb-0 font-12 text-start">';
                                                        $html .= '<div class="fw-bold border-bottom pb-1 mb-2 text-primary"># ' . ($index + 1) . '</div>';
                                                        foreach ($row as $k => $v) {
                                                            $label = ucwords(str_replace('_', ' ', $k));
                                                            if (is_array($v)) {
                                                                $v = json_encode($v);
                                                            }
                                                            $displayVal = (string)$v;
                                                            if (in_array(strtolower($displayVal), ['yes', 'no', 'permanent', 'contractual', 'active', 'inactive'])) {
                                                                $displayVal = ucfirst(strtolower($displayVal));
                                                            }
                                                            $html .= '<div class="mb-1 text-wrap"><strong>' . $label . ':</strong> ' . e($displayVal) . '</div>';
                                                        }
                                                        $html .= '</div>';
                                                    }
                                                }
                                                $html .= '</div>';
                                                return $html;
                                            }

                                            // If it is an associative array (e.g. addresses)
                                            $html = '<ul class="list-unstyled mb-0 font-12">';
                                            foreach ($val as $k => $v) {
                                                if (is_array($v)) {
                                                    $v = json_encode($v);
                                                }
                                                $displayVal = (string)$v;
                                                if (in_array(strtolower($displayVal), ['yes', 'no', 'permanent', 'contractual', 'active', 'inactive'])) {
                                                    $displayVal = ucfirst(strtolower($displayVal));
                                                }
                                                $html .= '<li class="mb-1"><strong>' . ucfirst(str_replace('_', ' ', $k)) . ':</strong> ' . e($displayVal) . '</li>';
                                            }
                                            $html .= '</ul>';
                                            return $html;
                                        }

                                        if (is_scalar($val)) {
                                            $valStr = (string)$val;
                                            if (in_array(strtolower($valStr), ['yes', 'no', 'permanent', 'contractual', 'active', 'inactive'])) {
                                                return ucfirst(strtolower($valStr));
                                            }
                                            return e($valStr);
                                        }

                                        return e((string)$val);
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
                                    <td class="py-2.5 text-muted">{!! formatProfileUpdateData($prevVal, $key) !!}</td>
                                    <td class="py-2.5 {{ $hasChanged ? 'text-primary fw-bold bg-light-subtle' : 'text-muted' }}">
                                        {!! formatProfileUpdateData($reqVal, $key) !!}
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