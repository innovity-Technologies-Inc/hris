@extends('structure.master')
@section('title', 'Review Profile Update Request')

@section('content')
<div class="row g-4">
    <!-- Left Column: Employee Info Profile Card -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-lg rounded-4 overflow-hidden" style="background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(15px); border: 1px solid rgba(255, 255, 255, 0.4);">
            <div class="card-header bg-gradient-primary py-2.5 px-3 border-0" style="background: linear-gradient(135deg, var(--bs-primary, #5b73e8), #3b50c0);"></div>
            <div class="card-body text-center p-4">
                <div class="mb-3 d-flex justify-content-center">
                    <div class="position-relative" style="margin-top: -45px;">
                        {!! \App\HelperClass::generateAvatar(
                            $updateRequest->employee?->photo_path ?? null,
                            $updateRequest->employee?->full_name ?? 'N/A',
                            90,
                            '#974063',
                            'img-thumbnail rounded-circle shadow-lg border border-3 border-white',
                            $updateRequest->employee?->id ?? 0,
                        ) !!}
                    </div>
                </div>
                <h4 class="mb-1 fw-bold text-dark mt-2">{{ $updateRequest->employee?->full_name ?? 'N/A' }}</h4>
                <p class="text-muted font-13 mb-3"><i class="mdi mdi-card-account-details-outline me-1"></i>{{ $updateRequest->employee?->punch_card_no ?? 'N/A' }}</p>
                
                <hr class="my-3 opacity-25">

                <div class="text-start mt-3">
                    <div class="d-flex justify-content-between align-items-center mb-2.5 pb-2 border-bottom border-light">
                        <span class="text-muted font-13 fw-semibold"><i class="mdi mdi-account-cog me-1.5 text-primary"></i>Request Type</span>
                        @if(($updateRequest->type ?? 'employee') === 'admin')
                            <span class="badge bg-primary bg-opacity-10 text-primary text-capitalize px-2.5 py-1 fw-bold border border-primary border-opacity-25">Admin Edit</span>
                        @else
                            <span class="badge bg-secondary bg-opacity-10 text-secondary text-capitalize px-2.5 py-1 fw-bold border border-secondary border-opacity-25">Employee Submission</span>
                        @endif
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-2.5 pb-2 border-bottom border-light">
                        <span class="text-muted font-13 fw-semibold"><i class="mdi mdi-view-list me-1.5 text-info"></i>Requested Section</span>
                        <span class="badge bg-info bg-opacity-10 text-info text-capitalize px-2.5 py-1 fw-bold border border-info border-opacity-25">
                            {{ str_replace('_', ' ', $updateRequest->section) }}
                        </span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-2.5 pb-2 border-bottom border-light">
                        <span class="text-muted font-13 fw-semibold"><i class="mdi mdi-check-circle-outline me-1.5 text-warning"></i>Status</span>
                        @if($updateRequest->status === 'pending')
                            <span class="badge bg-warning bg-opacity-10 text-warning px-2.5 py-1 fw-bold border border-warning border-opacity-25">Pending Approval</span>
                        @elseif($updateRequest->status === 'approved')
                            <span class="badge bg-success bg-opacity-10 text-success px-2.5 py-1 fw-bold border border-success border-opacity-25">Approved</span>
                        @else
                            <span class="badge bg-danger bg-opacity-10 text-danger px-2.5 py-1 fw-bold border border-danger border-opacity-25">Rejected</span>
                        @endif
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted font-13 fw-semibold"><i class="mdi mdi-calendar-clock me-1.5 text-muted"></i>Submitted Date</span>
                        <span class="fw-semibold text-dark font-13">{{ $updateRequest->created_at->format('d M Y, h:i A') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Data Comparison and Actions -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="card-header bg-gradient-light py-3 px-4 border-bottom border-light" style="background: linear-gradient(180deg, #ffffff, #f9fbfd);">
                <h5 class="card-title mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                    <i class="mdi mdi-swap-horizontal text-primary fs-4"></i> Data Comparison & Changes
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive rounded-3 border border-light shadow-sm">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="py-3 px-3 fw-bold text-dark font-13" style="width: 25%;">Field Name</th>
                                <th class="py-3 px-3 fw-bold text-dark font-13" style="width: 37.5%;">Previous Profile Value</th>
                                <th class="py-3 px-3 fw-bold text-dark font-13" style="width: 37.5%;">Requested New Value</th>
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

                                                        $html .= '<div class="card border-0 border-start border-primary border-3 shadow-none bg-light p-2.5 rounded-3 mb-0 font-12 text-start">';
                                                        $html .= '  <div class="d-flex align-items-center justify-content-between border-bottom pb-1 mb-2">';
                                                        $html .= '    <h6 class="mb-0 fw-bold text-dark text-wrap">' . e($title) . '</h6>';
                                                        $html .= '    <span class="badge bg-primary text-white fw-semibold px-2 py-0.5">Education #' . ($index + 1) . '</span>';
                                                        $html .= '  </div>';
                                                        $html .= '  <div class="mb-1 text-dark"><strong>Institution:</strong> ' . e($institute) . '</div>';
                                                        if ($passingYear) {
                                                            $html .= '  <div class="mb-1 text-dark"><strong>Passing Year:</strong> ' . e($passingYear) . '</div>';
                                                        }
                                                        if ($result) {
                                                            $html .= '  <div class="mb-1 text-dark"><strong>Result:</strong> ' . e($result) . '</div>';
                                                        }
                                                        $html .= '</div>';
                                                    } elseif ($key === 'trainings') {
                                                        $title = $row['training_title'] ?? $row['course'] ?? $row['title'] ?? 'Training Course';
                                                        $institute = $row['institute'] ?? $row['organization'] ?? 'Institution';
                                                        $duration = $row['duration'] ?? $row['passing_year'] ?? '';

                                                        $html .= '<div class="card border-0 border-start border-success border-3 shadow-none bg-light p-2.5 rounded-3 mb-0 font-12 text-start">';
                                                        $html .= '  <div class="d-flex align-items-center justify-content-between border-bottom pb-1 mb-2">';
                                                        $html .= '    <h6 class="mb-0 fw-bold text-dark text-wrap">' . e($title) . '</h6>';
                                                        $html .= '    <span class="badge bg-success text-white fw-semibold px-2 py-0.5">Training #' . ($index + 1) . '</span>';
                                                        $html .= '  </div>';
                                                        $html .= '  <div class="mb-1 text-dark"><strong>Institution:</strong> ' . e($institute) . '</div>';
                                                        if ($duration) {
                                                            $html .= '  <div class="mb-1 text-dark"><strong>Duration/Year:</strong> ' . e($duration) . '</div>';
                                                        }
                                                        $html .= '</div>';
                                                    } elseif ($key === 'histories') {
                                                        $company = $row['company_name'] ?? $row['company'] ?? 'Company';
                                                        $designation = $row['designation'] ?? 'Designation';
                                                        $period = isset($row['start_date']) ? ($row['start_date'] . ' - ' . ($row['end_date'] ?? 'Present')) : ($row['duration'] ?? '');

                                                        $html .= '<div class="card border-0 border-start border-info border-3 shadow-none bg-light p-2.5 rounded-3 mb-0 font-12 text-start">';
                                                        $html .= '  <div class="d-flex align-items-center justify-content-between border-bottom pb-1 mb-2">';
                                                        $html .= '    <h6 class="mb-0 fw-bold text-dark text-wrap">' . e($company) . '</h6>';
                                                        $html .= '    <span class="badge bg-info text-white fw-semibold px-2 py-0.5">History #' . ($index + 1) . '</span>';
                                                        $html .= '  </div>';
                                                        $html .= '  <div class="mb-1 text-dark"><strong>Designation:</strong> ' . e($designation) . '</div>';
                                                        if ($period) {
                                                            $html .= '  <div class="mb-1 text-dark"><strong>Period:</strong> ' . e($period) . '</div>';
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
                                <tr class="{{ $hasChanged ? 'table-warning-subtle' : '' }}" style="{{ $hasChanged ? 'border-left: 3px solid #ffc107;' : '' }}">
                                    <td class="fw-semibold text-capitalize text-dark py-3 px-3">{{ str_replace('_', ' ', $key) }}</td>
                                    <td class="py-3 px-3 text-muted">{!! formatProfileUpdateData($prevVal, $key) !!}</td>
                                    <td class="py-3 px-3 {{ $hasChanged ? 'text-primary fw-bold bg-light-subtle' : 'text-muted' }}">
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