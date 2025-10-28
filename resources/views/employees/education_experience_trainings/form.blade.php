@extends('structure.master')
@section('content')
    <div class="mt-4">
        <form id="employeeForm" method="POST" action="{{ isset($employeeData) ? route('employees.education_information.update', $employeeData->employee_id) : route('employees.education_information.store') }}">
            @if(isset($employeeData))
                @method('PUT')
            @endif
            @csrf

            <!-- Employee Selection Section -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0"><i class="mdi mdi-account me-2"></i>Employee Name</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6 mb-3">
                                    <label for="employee_id" class="form-label">Employee Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" readonly
                                           value="{{ $employee->full_name }}">

                                    <input type="hidden" name="employee_id" value="{{ $employee->id }}">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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
                                    $educations = old('educations', $employeeData->employee_educations ?? []);
                                @endphp

                                @if(empty($educations))
                                    <!-- Initial Empty Row for Create -->
                                    <div class="education-row border rounded p-3 mb-3 bg-light" data-row="0">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label">Education Title <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="educations[0][education_title]" placeholder="e.g., Bachelor of Science">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Institute <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="educations[0][institute]" placeholder="e.g., University of Dhaka">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Group/Major</label>
                                                <input type="text" class="form-control" name="educations[0][group_major]" placeholder="e.g., Computer Science">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Board/University</label>
                                                <input type="text" class="form-control" name="educations[0][board_university]" placeholder="e.g., Dhaka University">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Result/Grade</label>
                                                <input type="text" class="form-control" name="educations[0][result_grade]" placeholder="e.g., First Class">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Passing Year <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="educations[0][passing_year]" placeholder="e.g., 2020">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">GPA/CGPA</label>
                                                <input type="text" class="form-control" name="educations[0][gpa_cgpa]" placeholder="e.g., 3.75">
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <!-- Populated Rows for Edit -->
                                    @foreach($educations as $index => $education)
                                        <div class="education-row border rounded p-3 mb-3 bg-light position-relative" data-row="{{ $index }}">
                                            @if($index > 0)
                                                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2" onclick="removeEducationRow(this)">
                                                    <i class="mdi mdi-delete"></i> Remove
                                                </button>
                                                <h6 class="text-success mb-3"><i class="mdi mdi-school-outline me-1"></i>Education {{ $index + 1 }}</h6>
                                            @endif
                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <label class="form-label">Education Title <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="educations[{{ $index }}][education_title]"
                                                           value="{{ $education['education_title'] ?? '' }}" placeholder="e.g., Bachelor of Science">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Institute <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="educations[{{ $index }}][institute]"
                                                           value="{{ $education['institute'] ?? '' }}" placeholder="e.g., University of Dhaka">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Group/Major</label>
                                                    <input type="text" class="form-control" name="educations[{{ $index }}][group_major]"
                                                           value="{{ $education['group_major'] ?? '' }}" placeholder="e.g., Computer Science">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Board/University</label>
                                                    <input type="text" class="form-control" name="educations[{{ $index }}][board_university]"
                                                           value="{{ $education['board_university'] ?? '' }}" placeholder="e.g., Dhaka University">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Result/Grade</label>
                                                    <input type="text" class="form-control" name="educations[{{ $index }}][result_grade]"
                                                           value="{{ $education['result_grade'] ?? '' }}" placeholder="e.g., First Class">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Passing Year <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="educations[{{ $index }}][passing_year]"
                                                           value="{{ $education['passing_year'] ?? '' }}" placeholder="e.g., 2020">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">GPA/CGPA</label>
                                                    <input type="text" class="form-control" name="educations[{{ $index }}][gpa_cgpa]"
                                                           value="{{ $education['gpa_cgpa'] ?? '' }}" placeholder="e.g., 3.75">
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

            <!-- Experience Section -->
            <div class="row mt-3">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0"><i class="mdi mdi-briefcase me-2"></i>Experience Information</h5>
                            <button type="button" class="btn btn-sm btn-light" id="addExperienceRow">
                                <i class="mdi mdi-plus"></i> Add Row
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="experienceContainer">
                                @php
                                    $experiences = old('experiences', $employeeData->employee_experiences ?? []);
                                @endphp

                                @if(empty($experiences))
                                    <!-- Initial Empty Row -->
                                    <div class="experience-row border rounded p-3 mb-3 bg-light" data-row="0">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label">Company <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="experiences[0][company]" placeholder="e.g., ABC Corporation Ltd">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Designation <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="experiences[0][designation]" placeholder="e.g., Senior Software Engineer">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Department</label>
                                                <input type="text" class="form-control" name="experiences[0][department]" placeholder="e.g., IT Department">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Date From <span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" name="experiences[0][date_from]">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Date To <span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" name="experiences[0][date_to]">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Duration</label>
                                                <input type="text" class="form-control" name="experiences[0][duration]" placeholder="e.g., 2 years 6 months">
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label">Responsibility</label>
                                                <textarea class="form-control" name="experiences[0][responsibility]" rows="2" placeholder="Describe your key responsibilities and achievements"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <!-- Populated Rows -->
                                    @foreach($experiences as $index => $experience)
                                        <div class="experience-row border rounded p-3 mb-3 bg-light position-relative" data-row="{{ $index }}">
                                            @if($index > 0)
                                                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2" onclick="removeExperienceRow(this)">
                                                    <i class="mdi mdi-delete"></i> Remove
                                                </button>
                                                <h6 class="text-info mb-3"><i class="mdi mdi-briefcase-outline me-1"></i>Experience {{ $index + 1 }}</h6>
                                            @endif
                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <label class="form-label">Company <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="experiences[{{ $index }}][company]"
                                                           value="{{ $experience['company'] ?? '' }}" placeholder="e.g., ABC Corporation Ltd">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Designation <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="experiences[{{ $index }}][designation]"
                                                           value="{{ $experience['designation'] ?? '' }}" placeholder="e.g., Senior Software Engineer">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Department</label>
                                                    <input type="text" class="form-control" name="experiences[{{ $index }}][department]"
                                                           value="{{ $experience['department'] ?? '' }}" placeholder="e.g., IT Department">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Date From <span class="text-danger">*</span></label>
                                                    <input type="date" class="form-control" name="experiences[{{ $index }}][date_from]"
                                                           value="{{ $experience['date_from'] ?? '' }}">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Date To <span class="text-danger">*</span></label>
                                                    <input type="date" class="form-control" name="experiences[{{ $index }}][date_to]"
                                                           value="{{ $experience['date_to'] ?? '' }}">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Duration</label>
                                                    <input type="text" class="form-control" name="experiences[{{ $index }}][duration]"
                                                           value="{{ $experience['duration'] ?? '' }}" placeholder="e.g., 2 years 6 months">
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="form-label">Responsibility</label>
                                                    <textarea class="form-control" name="experiences[{{ $index }}][responsibility]" rows="2" placeholder="Describe your key responsibilities and achievements">{{ $experience['responsibility'] ?? '' }}</textarea>
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
                                    $trainings = old('trainings', $employeeData->employee_trainings ?? []);
                                @endphp

                                @if(empty($trainings))
                                    <!-- Initial Empty Row -->
                                    <div class="training-row border rounded p-3 mb-3 bg-light" data-row="0">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label">Training Title <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="trainings[0][training_title]" placeholder="e.g., Advanced Laravel Development">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Course Name</label>
                                                <input type="text" class="form-control" name="trainings[0][course_name]" placeholder="e.g., Web Development Masterclass">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Training Code</label>
                                                <input type="text" class="form-control" name="trainings[0][training_code]" placeholder="e.g., TRN-2025-001">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Institute</label>
                                                <input type="text" class="form-control" name="trainings[0][institute]" placeholder="e.g., Training Center">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Country</label>
                                                <input type="text" class="form-control" name="trainings[0][country]" placeholder="e.g., Bangladesh">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Location</label>
                                                <input type="text" class="form-control" name="trainings[0][location]" placeholder="e.g., Dhaka">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Duration</label>
                                                <input type="text" class="form-control" name="trainings[0][duration]" placeholder="e.g., 5 days">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">From Date <span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" name="trainings[0][from_date]">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">To Date <span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" name="trainings[0][to_date]">
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <!-- Populated Rows -->
                                    @foreach($trainings as $index => $training)
                                        <div class="training-row border rounded p-3 mb-3 bg-light position-relative" data-row="{{ $index }}">
                                            @if($index > 0)
                                                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2" onclick="removeTrainingRow(this)">
                                                    <i class="mdi mdi-delete"></i> Remove
                                                </button>
                                                <h6 class="text-warning mb-3"><i class="mdi mdi-certificate-outline me-1"></i>Training {{ $index + 1 }}</h6>
                                            @endif
                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <label class="form-label">Training Title <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="trainings[{{ $index }}][training_title]"
                                                           value="{{ $training['training_title'] ?? '' }}" placeholder="e.g., Advanced Laravel Development">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Course Name</label>
                                                    <input type="text" class="form-control" name="trainings[{{ $index }}][course_name]"
                                                           value="{{ $training['course_name'] ?? '' }}" placeholder="e.g., Web Development Masterclass">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Training Code</label>
                                                    <input type="text" class="form-control" name="trainings[{{ $index }}][training_code]"
                                                           value="{{ $training['training_code'] ?? '' }}" placeholder="e.g., TRN-2025-001">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Institute</label>
                                                    <input type="text" class="form-control" name="trainings[{{ $index }}][institute]"
                                                           value="{{ $training['institute'] ?? '' }}" placeholder="e.g., Training Center">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Country</label>
                                                    <input type="text" class="form-control" name="trainings[{{ $index }}][country]"
                                                           value="{{ $training['country'] ?? '' }}" placeholder="e.g., Bangladesh">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Location</label>
                                                    <input type="text" class="form-control" name="trainings[{{ $index }}][location]"
                                                           value="{{ $training['location'] ?? '' }}" placeholder="e.g., Dhaka">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Duration</label>
                                                    <input type="text" class="form-control" name="trainings[{{ $index }}][duration]"
                                                           value="{{ $training['duration'] ?? '' }}" placeholder="e.g., 5 days">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">From Date <span class="text-danger">*</span></label>
                                                    <input type="date" class="form-control" name="trainings[{{ $index }}][from_date]"
                                                           value="{{ $training['from_date'] ?? '' }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">To Date <span class="text-danger">*</span></label>
                                                    <input type="date" class="form-control" name="trainings[{{ $index }}][to_date]"
                                                           value="{{ $training['to_date'] ?? '' }}">
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
                        <a href="#" class="btn btn-secondary">
                            <i class="mdi mdi-arrow-left me-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-content-save me-1"></i> {{ isset($employeeData) ? 'Update Information' : 'Save Information' }}
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
            const existingExperiences = document.querySelectorAll('.experience-row').length;
            const existingTrainings = document.querySelectorAll('.training-row').length;

            let educationRowCount = existingEducations;
            let experienceRowCount = existingExperiences;
            let trainingRowCount = existingTrainings;
            const maxRows = 10;

            // Check if we need to disable add buttons based on existing rows
            if (educationRowCount >= maxRows) {
                document.getElementById('addEducationRow').disabled = true;
            }
            if (experienceRowCount >= maxRows) {
                document.getElementById('addExperienceRow').disabled = true;
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
                            <label class="form-label">Education Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="educations[${educationRowCount}][education_title]" placeholder="e.g., Bachelor of Science">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Institute <span class="text-danger">*</span></label>
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
                            <label class="form-label">Passing Year <span class="text-danger">*</span></label>
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

            // Add Experience Row
            document.getElementById('addExperienceRow').addEventListener('click', function() {
                if (experienceRowCount >= maxRows) {
                    alert(`Maximum ${maxRows} experience rows allowed`);
                    return;
                }

                const container = document.getElementById('experienceContainer');
                const newRow = document.createElement('div');
                newRow.className = 'experience-row border rounded p-3 mb-3 bg-light position-relative';
                newRow.setAttribute('data-row', experienceRowCount);

                newRow.innerHTML = `
                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2" onclick="removeExperienceRow(this)">
                        <i class="mdi mdi-delete"></i> Remove
                    </button>
                    <h6 class="text-info mb-3"><i class="mdi mdi-briefcase-outline me-1"></i>Experience ${experienceRowCount + 1}</h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Company <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="experiences[${experienceRowCount}][company]" placeholder="e.g., ABC Corporation Ltd">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Designation <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="experiences[${experienceRowCount}][designation]" placeholder="e.g., Senior Software Engineer">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Department</label>
                            <input type="text" class="form-control" name="experiences[${experienceRowCount}][department]" placeholder="e.g., IT Department">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Date From <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="experiences[${experienceRowCount}][date_from]">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Date To <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="experiences[${experienceRowCount}][date_to]">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Duration</label>
                            <input type="text" class="form-control" name="experiences[${experienceRowCount}][duration]" placeholder="e.g., 2 years 6 months">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Responsibility</label>
                            <textarea class="form-control" name="experiences[${experienceRowCount}][responsibility]" rows="2" placeholder="Describe your key responsibilities and achievements"></textarea>
                        </div>
                    </div>
                `;

                container.appendChild(newRow);
                experienceRowCount++;

                if (experienceRowCount >= maxRows) {
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
                            <label class="form-label">Training Title <span class="text-danger">*</span></label>
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
                            <label class="form-label">From Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="trainings[${trainingRowCount}][from_date]">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">To Date <span class="text-danger">*</span></label>
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

            window.removeExperienceRow = function(button) {
                const row = button.closest('.experience-row');
                row.remove();
                experienceRowCount--;
                document.getElementById('addExperienceRow').disabled = false;
            };

            window.removeTrainingRow = function(button) {
                const row = button.closest('.training-row');
                row.remove();
                trainingRowCount--;
                document.getElementById('addTrainingRow').disabled = false;
            };

            // Initialize Select2
            if (typeof $.fn.select2 !== 'undefined') {
                $('.select2').select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    placeholder: 'Select Employee'
                });
            }
        });
    </script>
@endsection
