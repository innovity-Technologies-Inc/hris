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
                                if (!function_exists('resolveRelationName')) {
                                    function resolveRelationName($key, $id) {
                                        if (!$id || !is_numeric($id)) {
                                            return $id;
                                        }

                                        $normalizedKey = strtolower($key);
                                        
                                        $modelMap = [
                                            'company_id'            => [\App\Models\Company\Company::class, 'name'],
                                            'joining_company_id'    => [\App\Models\Company\Company::class, 'name'],
                                            'current_company_id'    => [\App\Models\Company\Company::class, 'name'],
                                            
                                            'business_unit_id'      => [\App\Models\Company\CompanyLocation::class, 'name'],
                                            'joining_business_unit_id' => [\App\Models\Company\CompanyLocation::class, 'name'],
                                            'current_business_unit_id' => [\App\Models\Company\CompanyLocation::class, 'name'],
                                            
                                            'division_id'           => [\App\Models\Company\Division::class, 'name'],
                                            'joining_division_id'   => [\App\Models\Company\Division::class, 'name'],
                                            'current_division_id'   => [\App\Models\Company\Division::class, 'name'],
                                            
                                            'department_id'         => [\App\Models\Company\Department::class, 'name'],
                                            'joining_department_id' => [\App\Models\Company\Department::class, 'name'],
                                            'current_department_id' => [\App\Models\Company\Department::class, 'name'],
                                            
                                            'section_id'            => [\App\Models\Company\Section::class, 'name'],
                                            'joining_section_id'    => [\App\Models\Company\Section::class, 'name'],
                                            'current_section_id'    => [\App\Models\Company\Section::class, 'name'],
                                            
                                            'designation_id'        => [\App\Models\Company\Designation::class, 'name'],
                                            'joining_designation_id'=> [\App\Models\Company\Designation::class, 'name'],
                                            'current_designation_id'=> [\App\Models\Company\Designation::class, 'name'],
                                            
                                            'grade_id'              => [\App\Models\Company\SalaryGrade::class, 'name'],
                                            'salary_grade_id'       => [\App\Models\Company\SalaryGrade::class, 'name'],
                                        ];

                                        if (array_key_exists($normalizedKey, $modelMap)) {
                                            [$class, $field] = $modelMap[$normalizedKey];
                                            try {
                                                $record = $class::find($id);
                                                if ($record) {
                                                    return $record->{$field} ?? $record->title ?? $record->name ?? $id;
                                                }
                                            } catch (\Exception $e) {
                                                // Fail silently
                                            }
                                        }

                                        if (str_ends_with($normalizedKey, '_id')) {
                                            $possibleModelName = substr($normalizedKey, 0, -3);
                                            if (str_starts_with($possibleModelName, 'joining_')) {
                                                $possibleModelName = substr($possibleModelName, 8);
                                            } elseif (str_starts_with($possibleModelName, 'current_')) {
                                                $possibleModelName = substr($possibleModelName, 8);
                                            }
                                            $possibleClass = 'App\\Models\\Company\\' . ucfirst(\Illuminate\Support\Str::camel($possibleModelName));
                                            if (class_exists($possibleClass)) {
                                                try {
                                                    $record = $possibleClass::find($id);
                                                    if ($record) {
                                                        return $record->name ?? $record->title ?? $id;
                                                    }
                                                } catch (\Exception $e) {
                                                    // Fail silently
                                                }
                                            }
                                        }

                                        return $id;
                                    }
                                }

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
                                                $html = '<div class="d-flex flex-column gap-2">';
                                                foreach ($val as $index => $row) {
                                                    if ($key === 'educations') {
                                                        $title = $row['education_title'] ?? $row['exam_degree_title'] ?? $row['degree'] ?? 'Degree/Exam';

                                                        if ($index > 0) {
                                                            $html .= '<hr class="my-2 opacity-50">';
                                                        }
                                                        $html .= '<div class="text-start font-12">';
                                                        $html .= '  <div class="fw-bold text-primary mb-1">Education #' . ($index + 1) . ': ' . e($title) . '</div>';
                                                        foreach ($row as $k => $v) {
                                                            if (in_array(strtolower($k), ['id', 'employee_id', 'created_at', 'updated_at'])) {
                                                                continue;
                                                            }
                                                            $cleanK = $k;
                                                            if (str_ends_with(strtolower($cleanK), '_id')) {
                                                                $cleanK = substr($cleanK, 0, -3);
                                                            }
                                                            $label = ucwords(str_replace('_', ' ', $cleanK));
                                                            if (is_array($v)) {
                                                                $v = json_encode($v);
                                                            }
                                                            $displayVal = (string)$v;
                                                            if (str_ends_with(strtolower($k), '_id') && is_numeric($displayVal)) {
                                                                $displayVal = resolveRelationName($k, $displayVal);
                                                            }
                                                            if (in_array(strtolower($displayVal), ['yes', 'no', 'permanent', 'contractual', 'active', 'inactive'])) {
                                                                $displayVal = ucfirst(strtolower($displayVal));
                                                            }
                                                            $html .= '<div class="mb-0.5"><strong>' . $label . ':</strong> ' . e($displayVal) . '</div>';
                                                        }
                                                        $html .= '</div>';
                                                    } elseif ($key === 'trainings') {
                                                        $title = $row['training_title'] ?? $row['course'] ?? $row['title'] ?? 'Training Course';

                                                        if ($index > 0) {
                                                            $html .= '<hr class="my-2 opacity-50">';
                                                        }
                                                        $html .= '<div class="text-start font-12">';
                                                        $html .= '  <div class="fw-bold text-success mb-1">Training #' . ($index + 1) . ': ' . e($title) . '</div>';
                                                        foreach ($row as $k => $v) {
                                                            if (in_array(strtolower($k), ['id', 'employee_id', 'created_at', 'updated_at'])) {
                                                                continue;
                                                            }
                                                            $cleanK = $k;
                                                            if (str_ends_with(strtolower($cleanK), '_id')) {
                                                                $cleanK = substr($cleanK, 0, -3);
                                                            }
                                                            $label = ucwords(str_replace('_', ' ', $cleanK));
                                                            if (is_array($v)) {
                                                                $v = json_encode($v);
                                                            }
                                                            $displayVal = (string)$v;
                                                            if (str_ends_with(strtolower($k), '_id') && is_numeric($displayVal)) {
                                                                $displayVal = resolveRelationName($k, $displayVal);
                                                            }
                                                            if (in_array(strtolower($displayVal), ['yes', 'no', 'permanent', 'contractual', 'active', 'inactive'])) {
                                                                $displayVal = ucfirst(strtolower($displayVal));
                                                            }
                                                            $html .= '<div class="mb-0.5"><strong>' . $label . ':</strong> ' . e($displayVal) . '</div>';
                                                        }
                                                        $html .= '</div>';
                                                    } elseif ($key === 'histories') {
                                                        $company = $row['company_name'] ?? $row['company'] ?? 'Company';

                                                        if ($index > 0) {
                                                            $html .= '<hr class="my-2 opacity-50">';
                                                        }
                                                        $html .= '<div class="text-start font-12">';
                                                        $html .= '  <div class="fw-bold text-info mb-1">History #' . ($index + 1) . ': ' . e($company) . '</div>';
                                                        foreach ($row as $k => $v) {
                                                            if (in_array(strtolower($k), ['id', 'employee_id', 'created_at', 'updated_at'])) {
                                                                continue;
                                                            }
                                                            $cleanK = $k;
                                                            if (str_ends_with(strtolower($cleanK), '_id')) {
                                                                $cleanK = substr($cleanK, 0, -3);
                                                            }
                                                            $label = ucwords(str_replace('_', ' ', $cleanK));
                                                            if (is_array($v)) {
                                                                $v = json_encode($v);
                                                            }
                                                            $displayVal = (string)$v;
                                                            if (str_ends_with(strtolower($k), '_id') && is_numeric($displayVal)) {
                                                                $displayVal = resolveRelationName($k, $displayVal);
                                                            }
                                                            if (in_array(strtolower($displayVal), ['yes', 'no', 'permanent', 'contractual', 'active', 'inactive'])) {
                                                                $displayVal = ucfirst(strtolower($displayVal));
                                                            }
                                                            $html .= '<div class="mb-0.5"><strong>' . $label . ':</strong> ' . e($displayVal) . '</div>';
                                                        }
                                                        $html .= '</div>';
                                                    } else {
                                                        // Generic fallback flat formatting
                                                        if ($index > 0) {
                                                            $html .= '<hr class="my-2 opacity-50">';
                                                        }
                                                        $html .= '<div class="text-start font-12">';
                                                        $title = $row['name'] ?? $row['full_name'] ?? $row['nominee_name'] ?? $row['contact_name'] ?? $row['relation_type'] ?? '';
                                                        $titleStr = $title ? ': ' . $title : '';
                                                        $html .= '  <div class="fw-bold text-primary mb-1">Item #' . ($index + 1) . $titleStr . '</div>';
                                                        foreach ($row as $k => $v) {
                                                            if (in_array(strtolower($k), ['id', 'employee_id', 'created_at', 'updated_at'])) {
                                                                continue;
                                                            }
                                                            $cleanK = $k;
                                                            if (str_ends_with(strtolower($cleanK), '_id')) {
                                                                $cleanK = substr($cleanK, 0, -3);
                                                            }
                                                            $label = ucwords(str_replace('_', ' ', $cleanK));
                                                            if (is_array($v)) {
                                                                $v = json_encode($v);
                                                            }
                                                            $displayVal = (string)$v;
                                                            if (str_ends_with(strtolower($k), '_id') && is_numeric($displayVal)) {
                                                                $displayVal = resolveRelationName($k, $displayVal);
                                                            }
                                                            if (in_array(strtolower($displayVal), ['yes', 'no', 'permanent', 'contractual', 'active', 'inactive'])) {
                                                                $displayVal = ucfirst(strtolower($displayVal));
                                                            }
                                                            $html .= '<div class="mb-0.5"><strong>' . $label . ':</strong> ' . e($displayVal) . '</div>';
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
                                                if (in_array(strtolower($k), ['id', 'employee_id', 'created_at', 'updated_at'])) {
                                                    continue;
                                                }
                                                $cleanK = $k;
                                                if (str_ends_with(strtolower($cleanK), '_id')) {
                                                    $cleanK = substr($cleanK, 0, -3);
                                                }
                                                $label = ucwords(str_replace('_', ' ', $cleanK));
                                                if (is_array($v)) {
                                                    $v = json_encode($v);
                                                }
                                                $displayVal = (string)$v;
                                                if (str_ends_with(strtolower($k), '_id') && is_numeric($displayVal)) {
                                                    $displayVal = resolveRelationName($k, $displayVal);
                                                }
                                                if (in_array(strtolower($displayVal), ['yes', 'no', 'permanent', 'contractual', 'active', 'inactive'])) {
                                                    $displayVal = ucfirst(strtolower($displayVal));
                                                }
                                                $html .= '<li class="mb-1"><strong>' . $label . ':</strong> ' . e($displayVal) . '</li>';
                                            }
                                            $html .= '</ul>';
                                            return $html;
                                        }

                                        if (is_scalar($val)) {
                                            $valStr = (string)$val;
                                            if ($key && str_ends_with(strtolower($key), '_id') && is_numeric($valStr)) {
                                                $valStr = resolveRelationName($key, $valStr);
                                            }
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
                                    
                                    $cleanKey = $key;
                                    if (str_ends_with(strtolower($cleanKey), '_id')) {
                                        $cleanKey = substr($cleanKey, 0, -3);
                                    }
                                    $displayKey = ucwords(str_replace('_', ' ', $cleanKey));
                                @endphp
                                <tr class="{{ $hasChanged ? 'table-warning-subtle' : '' }}">
                                    <td class="fw-semibold text-capitalize text-dark py-2.5 px-3">{{ $displayKey }}</td>
                                    <td class="py-2.5 px-3 text-muted">{!! formatProfileUpdateData($prevVal, $key) !!}</td>
                                    <td class="py-2.5 px-3 {{ $hasChanged ? 'text-primary fw-bold bg-light-subtle' : 'text-muted' }}">
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