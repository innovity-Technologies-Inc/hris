@extends('structure.master')
@section('content')
    <div class="mt-4">
        {{-- Display All Validation Errors Summary --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><i class="mdi mdi-alert-circle me-2"></i>Please fix the following errors:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form id="employeeForm" method="POST"
            action="{{ isset($employeeData) ? route('employee.education_information.update', $employeeData->employee_id) : route('employee.education_information.store') }}">
            @if (isset($employeeData))
                @method('PUT')
            @endif
            @csrf
            <input type="hidden" name="employee_id" value="{{ $employee->id }}">

            <!-- Education Section -->
            <div class="row mt-3">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0"><i class="mdi mdi-school me-2"></i>Education Information</h5>
                            <button type="button" class="btn btn-sm btn-light" id="addEducationRow">
                                <i class="mdi mdi-plus"></i> Add Row
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="educationContainer">
                                @php
                                    $educations = old('educations', $employeeData->educations ?? []);
                                @endphp

                                @if (empty($educations))
                                    <!-- Initial Empty Row for Create -->
                                    <div class="education-row border rounded p-3 mb-3 bg-light" data-row="0">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label">Education Title</label>
                                                <input type="text"
                                                    class="form-control @error('educations.0.education_title') is-invalid @enderror"
                                                    name="educations[0][education_title]"
                                                    placeholder="e.g., Bachelor of Science"
                                                    value="{{ old('educations.0.education_title') }}">
                                                @error('educations.0.education_title')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Institute</label>
                                                <input type="text"
                                                    class="form-control @error('educations.0.institute') is-invalid @enderror"
                                                    name="educations[0][institute]" placeholder="e.g., University of Dhaka"
                                                    value="{{ old('educations.0.institute') }}">
                                                @error('educations.0.institute')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Group/Major</label>
                                                <input type="text"
                                                    class="form-control @error('educations.0.group_major') is-invalid @enderror"
                                                    name="educations[0][group_major]" placeholder="e.g., Computer Science"
                                                    value="{{ old('educations.0.group_major') }}">
                                                @error('educations.0.group_major')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Board/University</label>
                                                <input type="text"
                                                    class="form-control @error('educations.0.board_university') is-invalid @enderror"
                                                    name="educations[0][board_university]"
                                                    placeholder="e.g., Dhaka University"
                                                    value="{{ old('educations.0.board_university') }}">
                                                @error('educations.0.board_university')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Result/Grade</label>
                                                <input type="text"
                                                    class="form-control @error('educations.0.result_grade') is-invalid @enderror"
                                                    name="educations[0][result_grade]" placeholder="e.g., First Class"
                                                    value="{{ old('educations.0.result_grade') }}">
                                                @error('educations.0.result_grade')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Passing Year</label>
                                                <input type="text"
                                                    class="form-control @error('educations.0.passing_year') is-invalid @enderror"
                                                    name="educations[0][passing_year]" placeholder="e.g., 2020"
                                                    value="{{ old('educations.0.passing_year') }}">
                                                @error('educations.0.passing_year')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">GPA/CGPA</label>
                                                <input type="text"
                                                    class="form-control @error('educations.0.gpa_cgpa') is-invalid @enderror"
                                                    name="educations[0][gpa_cgpa]" placeholder="e.g., 3.75"
                                                    value="{{ old('educations.0.gpa_cgpa') }}">
                                                @error('educations.0.gpa_cgpa')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <!-- Populated Rows for Edit -->
                                    @foreach ($educations as $index => $education)
                                        <div class="education-row border rounded p-3 mb-3 bg-light position-relative"
                                            data-row="{{ $index }}">
                                            @if ($index > 0)
                                                <button type="button"
                                                    class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2"
                                                    onclick="removeEducationRow(this)">
                                                    <i class="mdi mdi-delete"></i> Remove
                                                </button>
                                                <h6 class="text-success mb-3"><i
                                                        class="mdi mdi-school-outline me-1"></i>Education
                                                    {{ $index + 1 }}</h6>
                                            @endif
                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <label class="form-label">Education Title</label>
                                                    <input type="text"
                                                        class="form-control @error('educations.' . $index . '.education_title') is-invalid @enderror"
                                                        name="educations[{{ $index }}][education_title]"
                                                        value="{{ old('educations.' . $index . '.education_title', $education['education_title'] ?? '') }}"
                                                        placeholder="e.g., Bachelor of Science">
                                                    @error('educations.' . $index . '.education_title')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Institute</label>
                                                    <input type="text"
                                                        class="form-control @error('educations.' . $index . '.institute') is-invalid @enderror"
                                                        name="educations[{{ $index }}][institute]"
                                                        value="{{ old('educations.' . $index . '.institute', $education['institute'] ?? '') }}"
                                                        placeholder="e.g., University of Dhaka">
                                                    @error('educations.' . $index . '.institute')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Group/Major</label>
                                                    <input type="text"
                                                        class="form-control @error('educations.' . $index . '.group_major') is-invalid @enderror"
                                                        name="educations[{{ $index }}][group_major]"
                                                        value="{{ old('educations.' . $index . '.group_major', $education['group_major'] ?? '') }}"
                                                        placeholder="e.g., Computer Science">
                                                    @error('educations.' . $index . '.group_major')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Board/University</label>
                                                    <input type="text"
                                                        class="form-control @error('educations.' . $index . '.board_university') is-invalid @enderror"
                                                        name="educations[{{ $index }}][board_university]"
                                                        value="{{ old('educations.' . $index . '.board_university', $education['board_university'] ?? '') }}"
                                                        placeholder="e.g., Dhaka University">
                                                    @error('educations.' . $index . '.board_university')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Result/Grade</label>
                                                    <input type="text"
                                                        class="form-control @error('educations.' . $index . '.result_grade') is-invalid @enderror"
                                                        name="educations[{{ $index }}][result_grade]"
                                                        value="{{ old('educations.' . $index . '.result_grade', $education['result_grade'] ?? '') }}"
                                                        placeholder="e.g., First Class">
                                                    @error('educations.' . $index . '.result_grade')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Passing Year</label>
                                                    <input type="text"
                                                        class="form-control @error('educations.' . $index . '.passing_year') is-invalid @enderror"
                                                        name="educations[{{ $index }}][passing_year]"
                                                        value="{{ old('educations.' . $index . '.passing_year', $education['passing_year'] ?? '') }}"
                                                        placeholder="e.g., 2020">
                                                    @error('educations.' . $index . '.passing_year')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">GPA/CGPA</label>
                                                    <input type="text"
                                                        class="form-control @error('educations.' . $index . '.gpa_cgpa') is-invalid @enderror"
                                                        name="educations[{{ $index }}][gpa_cgpa]"
                                                        value="{{ old('educations.' . $index . '.gpa_cgpa', $education['gpa_cgpa'] ?? '') }}"
                                                        placeholder="e.g., 3.75">
                                                    @error('educations.' . $index . '.gpa_cgpa')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Training Section -->
            <div class="row mt-3">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0"><i class="mdi mdi-certificate me-2"></i>Training Information</h5>
                            <button type="button" class="btn btn-sm btn-dark" id="addTrainingRow">
                                <i class="mdi mdi-plus"></i> Add Row
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="trainingContainer">
                                @php
                                    $trainings = old('trainings', $employeeData->trainings ?? []);
                                @endphp

                                @if (empty($trainings))
                                    <!-- Initial Empty Row -->
                                    <div class="training-row border rounded p-3 mb-3 bg-light" data-row="0">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label">Training Title</label>
                                                <input type="text"
                                                    class="form-control @error('trainings.0.training_title') is-invalid @enderror"
                                                    name="trainings[0][training_title]"
                                                    placeholder="e.g., Advanced Laravel Development"
                                                    value="{{ old('trainings.0.training_title') }}">
                                                @error('trainings.0.training_title')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Course Name</label>
                                                <input type="text"
                                                    class="form-control @error('trainings.0.course_name') is-invalid @enderror"
                                                    name="trainings[0][course_name]"
                                                    placeholder="e.g., Web Development Masterclass"
                                                    value="{{ old('trainings.0.course_name') }}">
                                                @error('trainings.0.course_name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Training Code</label>
                                                <input type="text"
                                                    class="form-control @error('trainings.0.training_code') is-invalid @enderror"
                                                    name="trainings[0][training_code]" placeholder="e.g., TRN-2025-001"
                                                    value="{{ old('trainings.0.training_code') }}">
                                                @error('trainings.0.training_code')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Institute</label>
                                                <input type="text"
                                                    class="form-control @error('trainings.0.institute') is-invalid @enderror"
                                                    name="trainings[0][institute]" placeholder="e.g., Training Center"
                                                    value="{{ old('trainings.0.institute') }}">
                                                @error('trainings.0.institute')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Country</label>
                                                <input type="text"
                                                    class="form-control @error('trainings.0.country') is-invalid @enderror"
                                                    name="trainings[0][country]" placeholder="e.g., Bangladesh"
                                                    value="{{ old('trainings.0.country') }}">
                                                @error('trainings.0.country')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Location</label>
                                                <input type="text"
                                                    class="form-control @error('trainings.0.location') is-invalid @enderror"
                                                    name="trainings[0][location]" placeholder="e.g., Dhaka"
                                                    value="{{ old('trainings.0.location') }}">
                                                @error('trainings.0.location')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Duration</label>
                                                <input type="text"
                                                    class="form-control @error('trainings.0.duration') is-invalid @enderror"
                                                    name="trainings[0][duration]" placeholder="e.g., 5 days"
                                                    value="{{ old('trainings.0.duration') }}">
                                                @error('trainings.0.duration')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">From Date</label>
                                                <input type="date"
                                                    class="form-control @error('trainings.0.from_date') is-invalid @enderror"
                                                    name="trainings[0][from_date]"
                                                    value="{{ old('trainings.0.from_date') }}">
                                                @error('trainings.0.from_date')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">To Date</label>
                                                <input type="date"
                                                    class="form-control @error('trainings.0.to_date') is-invalid @enderror"
                                                    name="trainings[0][to_date]"
                                                    value="{{ old('trainings.0.to_date') }}">
                                                @error('trainings.0.to_date')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <!-- Populated Rows -->
                                    @foreach ($trainings as $index => $training)
                                        <div class="training-row border rounded p-3 mb-3 bg-light position-relative"
                                            data-row="{{ $index }}">
                                            @if ($index > 0)
                                                <button type="button"
                                                    class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2"
                                                    onclick="removeTrainingRow(this)">
                                                    <i class="mdi mdi-delete"></i> Remove
                                                </button>
                                                <h6 class="text-warning mb-3"><i
                                                        class="mdi mdi-certificate-outline me-1"></i>Training
                                                    {{ $index + 1 }}</h6>
                                            @endif
                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <label class="form-label">Training Title</label>
                                                    <input type="text"
                                                        class="form-control @error('trainings.' . $index . '.training_title') is-invalid @enderror"
                                                        name="trainings[{{ $index }}][training_title]"
                                                        value="{{ old('trainings.' . $index . '.training_title', $training['training_title'] ?? '') }}"
                                                        placeholder="e.g., Advanced Laravel Development">
                                                    @error('trainings.' . $index . '.training_title')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Course Name</label>
                                                    <input type="text"
                                                        class="form-control @error('trainings.' . $index . '.course_name') is-invalid @enderror"
                                                        name="trainings[{{ $index }}][course_name]"
                                                        value="{{ old('trainings.' . $index . '.course_name', $training['course_name'] ?? '') }}"
                                                        placeholder="e.g., Web Development Masterclass">
                                                    @error('trainings.' . $index . '.course_name')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Training Code</label>
                                                    <input type="text"
                                                        class="form-control @error('trainings.' . $index . '.training_code') is-invalid @enderror"
                                                        name="trainings[{{ $index }}][training_code]"
                                                        value="{{ old('trainings.' . $index . '.training_code', $training['training_code'] ?? '') }}"
                                                        placeholder="e.g., TRN-2025-001">
                                                    @error('trainings.' . $index . '.training_code')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Institute</label>
                                                    <input type="text"
                                                        class="form-control @error('trainings.' . $index . '.institute') is-invalid @enderror"
                                                        name="trainings[{{ $index }}][institute]"
                                                        value="{{ old('trainings.' . $index . '.institute', $training['institute'] ?? '') }}"
                                                        placeholder="e.g., Training Center">
                                                    @error('trainings.' . $index . '.institute')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Country</label>
                                                    <input type="text"
                                                        class="form-control @error('trainings.' . $index . '.country') is-invalid @enderror"
                                                        name="trainings[{{ $index }}][country]"
                                                        value="{{ old('trainings.' . $index . '.country', $training['country'] ?? '') }}"
                                                        placeholder="e.g., Bangladesh">
                                                    @error('trainings.' . $index . '.country')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Location</label>
                                                    <input type="text"
                                                        class="form-control @error('trainings.' . $index . '.location') is-invalid @enderror"
                                                        name="trainings[{{ $index }}][location]"
                                                        value="{{ old('trainings.' . $index . '.location', $training['location'] ?? '') }}"
                                                        placeholder="e.g., Dhaka">
                                                    @error('trainings.' . $index . '.location')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Duration</label>
                                                    <input type="text"
                                                        class="form-control @error('trainings.' . $index . '.duration') is-invalid @enderror"
                                                        name="trainings[{{ $index }}][duration]"
                                                        value="{{ old('trainings.' . $index . '.duration', $training['duration'] ?? '') }}"
                                                        placeholder="e.g., 5 days">
                                                    @error('trainings.' . $index . '.duration')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">From Date</label>
                                                    <input type="date"
                                                        class="form-control @error('trainings.' . $index . '.from_date') is-invalid @enderror"
                                                        name="trainings[{{ $index }}][from_date]"
                                                        value="{{ old('trainings.' . $index . '.from_date', $training['from_date'] ?? '') }}">
                                                    @error('trainings.' . $index . '.from_date')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">To Date</label>
                                                    <input type="date"
                                                        class="form-control @error('trainings.' . $index . '.to_date') is-invalid @enderror"
                                                        name="trainings[{{ $index }}][to_date]"
                                                        value="{{ old('trainings.' . $index . '.to_date', $training['to_date'] ?? '') }}">
                                                    @error('trainings.' . $index . '.to_date')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="row mt-4 mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('employee.profile.education_information', $employee->id) }}" class="btn btn-secondary">
                            <i class="mdi mdi-arrow-left me-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-content-save me-1"></i>
                            {{ isset($employeeData) ? 'Update Information' : 'Save Information' }}
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get existing data counts or start from 0
            const existingEducations = document.querySelectorAll('.education-row').length;
            const existingTrainings = document.querySelectorAll('.training-row').length;

            let educationRowCount = existingEducations;
            let trainingRowCount = existingTrainings;
            const maxRows = 10;

            // Check if we need to disable add buttons based on existing rows
            if (educationRowCount >= maxRows) {
                document.getElementById('addEducationRow').disabled = true;
            }
            if (trainingRowCount >= maxRows) {
                document.getElementById('addTrainingRow').disabled = true;
            }

            // Add Education Row
            document.getElementById('addEducationRow').addEventListener('click', function() {
                if (educationRowCount >= maxRows) {
                    alert(`Maximum ${maxRows} education rows allowed`);
                    return;
                }

                const container = document.getElementById('educationContainer');
                const newRow = document.createElement('div');
                newRow.className = 'education-row border rounded p-3 mb-3 bg-light position-relative';
                newRow.setAttribute('data-row', educationRowCount);

                newRow.innerHTML = `
                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2" onclick="removeEducationRow(this)">
                        <i class="mdi mdi-delete"></i> Remove
                    </button>
                    <h6 class="text-success mb-3"><i class="mdi mdi-school-outline me-1"></i>Education ${educationRowCount + 1}</h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Education Title</label>
                            <input type="text" class="form-control" name="educations[${educationRowCount}][education_title]" placeholder="e.g., Bachelor of Science">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Institute</label>
                            <input type="text" class="form-control" name="educations[${educationRowCount}][institute]" placeholder="e.g., University of Dhaka">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Group/Major</label>
                            <input type="text" class="form-control" name="educations[${educationRowCount}][group_major]" placeholder="e.g., Computer Science">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Board/University</label>
                            <input type="text" class="form-control" name="educations[${educationRowCount}][board_university]" placeholder="e.g., Dhaka University">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Result/Grade</label>
                            <input type="text" class="form-control" name="educations[${educationRowCount}][result_grade]" placeholder="e.g., First Class">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Passing Year</label>
                            <input type="text" class="form-control" name="educations[${educationRowCount}][passing_year]" placeholder="e.g., 2020">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">GPA/CGPA</label>
                            <input type="text" class="form-control" name="educations[${educationRowCount}][gpa_cgpa]" placeholder="e.g., 3.75">
                        </div>
                    </div>
                `;

                container.appendChild(newRow);
                educationRowCount++;

                if (educationRowCount >= maxRows) {
                    this.disabled = true;
                }
            });

            // Add Training Row
            document.getElementById('addTrainingRow').addEventListener('click', function() {
                if (trainingRowCount >= maxRows) {
                    alert(`Maximum ${maxRows} training rows allowed`);
                    return;
                }

                const container = document.getElementById('trainingContainer');
                const newRow = document.createElement('div');
                newRow.className = 'training-row border rounded p-3 mb-3 bg-light position-relative';
                newRow.setAttribute('data-row', trainingRowCount);

                newRow.innerHTML = `
                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2" onclick="removeTrainingRow(this)">
                        <i class="mdi mdi-delete"></i> Remove
                    </button>
                    <h6 class="text-warning mb-3"><i class="mdi mdi-certificate-outline me-1"></i>Training ${trainingRowCount + 1}</h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Training Title</label>
                            <input type="text" class="form-control" name="trainings[${trainingRowCount}][training_title]" placeholder="e.g., Advanced Laravel Development">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Course Name</label>
                            <input type="text" class="form-control" name="trainings[${trainingRowCount}][course_name]" placeholder="e.g., Web Development Masterclass">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Training Code</label>
                            <input type="text" class="form-control" name="trainings[${trainingRowCount}][training_code]" placeholder="e.g., TRN-2025-001">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Institute</label>
                            <input type="text" class="form-control" name="trainings[${trainingRowCount}][institute]" placeholder="e.g., Training Center">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Country</label>
                            <input type="text" class="form-control" name="trainings[${trainingRowCount}][country]" placeholder="e.g., Bangladesh">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Location</label>
                            <input type="text" class="form-control" name="trainings[${trainingRowCount}][location]" placeholder="e.g., Dhaka">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Duration</label>
                            <input type="text" class="form-control" name="trainings[${trainingRowCount}][duration]" placeholder="e.g., 5 days">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">From Date</label>
                            <input type="date" class="form-control" name="trainings[${trainingRowCount}][from_date]">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">To Date</label>
                            <input type="date" class="form-control" name="trainings[${trainingRowCount}][to_date]">
                        </div>
                    </div>
                `;

                container.appendChild(newRow);
                trainingRowCount++;

                if (trainingRowCount >= maxRows) {
                    this.disabled = true;
                }
            });

            // Remove Row Functions (Global scope)
            window.removeEducationRow = function(button) {
                const row = button.closest('.education-row');
                row.remove();
                educationRowCount--;
                document.getElementById('addEducationRow').disabled = false;
            };

            window.removeTrainingRow = function(button) {
                const row = button.closest('.training-row');
                row.remove();
                trainingRowCount--;
                document.getElementById('addTrainingRow').disabled = false;
            };
        });
    </script>
@endsection
