<div class="modal fade" id="updateRequestModal" tabindex="-1" aria-labelledby="updateRequestModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content glass-modal">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold" id="updateRequestModalLabel">
                    <i class="mdi mdi-account-edit me-2"></i>Request Profile Update
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="updateRequestForm">
                    <input type="hidden" name="employee_id" value="{{ $employee->id ?? '' }}">
                    
                    <div class="mb-3 card border shadow-none bg-light">
                        <div class="card-body p-3">
                            <label class="form-label fw-bold">Select Section to Update</label>
                            <select class="form-select" name="section" id="updateSectionSelect" required>
                                <option value="">Choose a Section...</option>
                                <option value="general">General & Personal Information</option>
                                <option value="education">Education & Trainings</option>
                                <option value="employment_history">Employment History</option>
                                <option value="emergency_contact">Emergency Contact & Nominee Info</option>
                            </select>
                        </div>
                    </div>

                    <div id="dynamicFormArea" class="mt-3 d-none">
                        <div class="alert alert-info d-flex align-items-center gap-2 mb-4">
                            <i class="mdi mdi-information-outline fs-4"></i>
                            <div>
                                Please update the fields below. Once submitted, it will be sent to the approval workflow. Your live profile will not change until approved.
                            </div>
                        </div>
                        <div id="dynamicFormFields"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary d-none" id="submitUpdateRequestBtn">
                    <i class="mdi mdi-check-all me-1"></i>Submit Request
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sectionSelect = document.getElementById('updateSectionSelect');
    const dynamicFormArea = document.getElementById('dynamicFormArea');
    const dynamicFormFields = document.getElementById('dynamicFormFields');
    const submitBtn = document.getElementById('submitUpdateRequestBtn');
    
    @if(isset($employee))
    const empData = {
        general: @json($employee),
        education: @json($employee->educationInfo),
        employment_history: @json($employee->employmentHistory),
        emergency_contact: @json($employee->nomineeInfo)
    };

    sectionSelect.addEventListener('change', function() {
        const section = this.value;
        if(!section) {
            dynamicFormArea.classList.add('d-none');
            submitBtn.classList.add('d-none');
            return;
        }

        dynamicFormArea.classList.remove('d-none');
        submitBtn.classList.remove('d-none');
        
        let html = '';
        let data = empData[section] || {};
        
        if(section === 'general') {
            html += `
                <!-- Personal details -->
                <div class="card border shadow-none mb-3">
                    <div class="card-header bg-body-secondary py-2">
                        <h6 class="mb-0 fw-bold">Personal Details</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            ${generateField('first_name', 'First Name', data.first_name)}
                            ${generateField('middle_name', 'Middle Name', data.middle_name)}
                            ${generateField('last_name', 'Last Name', data.last_name)}
                            ${generateField('full_name', 'Full Name (Official)', data.full_name)}
                            ${generateSelect('gender', 'Gender', data.gender, ['Male', 'Female', 'Other'])}
                            ${generateField('date_of_birth', 'Date of Birth', data.date_of_birth, 'date')}
                            ${generateSelect('marital_status', 'Marital Status', data.marital_status, ['single', 'married', 'divorced', 'widowed'])}
                            ${generateSelect('religion', 'Religion', data.religion, ['Islam', 'Hinduism', 'Christianity', 'Buddhism', 'N/A', 'Others'])}
                            ${generateField('nationality', 'Nationality', data.nationality)}
                            ${generateSelect('blood_group', 'Blood Group', data.blood_group, ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'])}
                            ${generateField('height_feet', 'Height (Feet)', data.height_feet, 'number')}
                            ${generateField('height_inches', 'Height (Inches)', data.height_inches, 'number')}
                            ${generateField('children_count', 'Number of Children', data.children_count, 'number')}
                            ${generateField('father_name', 'Father\'s Name', data.father_name)}
                            ${generateField('mother_name', 'Mother\'s Name', data.mother_name)}
                            ${generateField('spouse_name', 'Spouse Name', data.spouse_name)}
                        </div>
                    </div>
                </div>

                <!-- Contact details -->
                <div class="card border shadow-none mb-3">
                    <div class="card-header bg-body-secondary py-2">
                        <h6 class="mb-0 fw-bold">Contact Details</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            ${generateField('personal_mobile', 'Personal Mobile', data.personal_mobile)}
                            ${generateField('home_phone', 'Home Phone', data.home_phone)}
                            ${generateField('personal_email', 'Personal Email', data.personal_email, 'email')}
                            ${generateField('work_mobile', 'Work Mobile', data.work_mobile)}
                            ${generateField('work_phone', 'Work Phone', data.work_phone)}
                            ${generateField('work_email', 'Work Email', data.work_email, 'email')}
                        </div>
                    </div>
                </div>

                <!-- Identification & Passports -->
                <div class="card border shadow-none mb-3">
                    <div class="card-header bg-body-secondary py-2">
                        <h6 class="mb-0 fw-bold">Identification & Passports</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            ${generateField('nid', 'NID Number', data.nid)}
                            ${generateField('birth_reg_no', 'Birth Registration No', data.birth_reg_no)}
                            ${generateField('tin', 'TIN Number', data.tin)}
                            ${generateField('passport_no', 'Passport Number', data.passport_no)}
                            ${generateField('passport_expiry', 'Passport Expiry Date', data.passport_expiry, 'date')}
                            ${generateField('license_no', 'License Number', data.license_no)}
                            ${generateField('license_expiry', 'License Expiry Date', data.license_expiry, 'date')}
                            ${generateField('visa_expiry', 'Visa Expiry Date', data.visa_expiry, 'date')}
                            ${generateField('work_expiry', 'Work Permit Expiry Date', data.work_expiry, 'date')}
                            ${generateField('residency_id_number', 'Residency ID Number', data.residency_id_number)}
                        </div>
                    </div>
                </div>

                <!-- Present Address -->
                <div class="card border shadow-none mb-3">
                    <div class="card-header bg-body-secondary py-2">
                        <h6 class="mb-0 fw-bold">Present Address</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            ${generateAddressField('present_address', 'line_1', 'Address Line 1', data.present_address?.line_1)}
                            ${generateAddressField('present_address', 'line_2', 'Address Line 2', data.present_address?.line_2)}
                            ${generateAddressField('present_address', 'village', 'Village/Area', data.present_address?.village)}
                            ${generateAddressField('present_address', 'post_office', 'Post Office', data.present_address?.post_office)}
                            ${generateAddressField('present_address', 'police_station', 'Police Station/Thana', data.present_address?.police_station)}
                            ${generateAddressField('present_address', 'district', 'District', data.present_address?.district)}
                            ${generateAddressField('present_address', 'division', 'Division', data.present_address?.division)}
                            ${generateAddressField('present_address', 'zip_code', 'Zip Code', data.present_address?.zip_code)}
                            ${generateAddressField('present_address', 'state', 'State/Province', data.present_address?.state)}
                            ${generateAddressField('present_address', 'country', 'Country', data.present_address?.country)}
                        </div>
                    </div>
                </div>

                <!-- Permanent Address -->
                <div class="card border shadow-none mb-3">
                    <div class="card-header bg-body-secondary py-2">
                        <h6 class="mb-0 fw-bold">Permanent Address</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            ${generateAddressField('permanent_address', 'line_1', 'Address Line 1', data.permanent_address?.line_1)}
                            ${generateAddressField('permanent_address', 'line_2', 'Address Line 2', data.permanent_address?.line_2)}
                            ${generateAddressField('permanent_address', 'village', 'Village/Area', data.permanent_address?.village)}
                            ${generateAddressField('permanent_address', 'post_office', 'Post Office', data.permanent_address?.post_office)}
                            ${generateAddressField('permanent_address', 'police_station', 'Police Station/Thana', data.permanent_address?.police_station)}
                            ${generateAddressField('permanent_address', 'district', 'District', data.permanent_address?.district)}
                            ${generateAddressField('permanent_address', 'division', 'Division', data.permanent_address?.division)}
                            ${generateAddressField('permanent_address', 'zip_code', 'Zip Code', data.permanent_address?.zip_code)}
                            ${generateAddressField('permanent_address', 'state', 'State/Province', data.permanent_address?.state)}
                            ${generateAddressField('permanent_address', 'country', 'Country', data.permanent_address?.country)}
                        </div>
                    </div>
                </div>

                <!-- Reference / Alternate Contact -->
                <div class="card border shadow-none mb-3">
                    <div class="card-header bg-body-secondary py-2">
                        <h6 class="mb-0 fw-bold">Reference / Alternate Address Info</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            ${generateAddressField('reference_address', 'emp_id', 'Reference Employee ID', data.reference_address?.emp_id)}
                            ${generateAddressField('reference_address', 'reference_name', 'Reference Name', data.reference_address?.reference_name)}
                            ${generateAddressField('reference_address', 'reference_designation', 'Reference Designation', data.reference_address?.reference_designation)}
                            ${generateAddressField('reference_address', 'line_1', 'Address Line 1', data.reference_address?.line_1)}
                            ${generateAddressField('reference_address', 'village', 'Village/Area', data.reference_address?.village)}
                            ${generateAddressField('reference_address', 'post_office', 'Post Office', data.reference_address?.post_office)}
                            ${generateAddressField('reference_address', 'district', 'District', data.reference_address?.district)}
                            ${generateAddressField('reference_address', 'division', 'Division', data.reference_address?.division)}
                            ${generateAddressField('reference_address', 'zip_code', 'Zip Code', data.reference_address?.zip_code)}
                            ${generateAddressField('reference_address', 'state', 'State/Province', data.reference_address?.state)}
                            ${generateAddressField('reference_address', 'country', 'Country', data.reference_address?.country)}
                            ${generateAddressField('reference_address', 'phone', 'Phone', data.reference_address?.phone)}
                            ${generateAddressField('reference_address', 'mobile', 'Mobile', data.reference_address?.mobile)}
                            ${generateAddressField('reference_address', 'email', 'Email', data.reference_address?.email)}
                        </div>
                    </div>
                </div>
            `;
        } else if(section === 'education') {
            // Load list manager for Educations & Trainings JSON
            html += `
                <div class="card border shadow-none mb-4">
                    <div class="card-header bg-body-secondary d-flex justify-content-between align-items-center py-2">
                        <h6 class="mb-0 fw-bold">Education Details</h6>
                        <button type="button" class="btn btn-xs btn-primary py-1" id="addEduRowBtn">
                            <i class="mdi mdi-plus"></i> Add Education
                        </button>
                    </div>
                    <div class="card-body p-3">
                        <div id="educationContainer" class="d-flex flex-column gap-3"></div>
                    </div>
                </div>

                <div class="card border shadow-none mb-3">
                    <div class="card-header bg-body-secondary d-flex justify-content-between align-items-center py-2">
                        <h6 class="mb-0 fw-bold">Training Details</h6>
                        <button type="button" class="btn btn-xs btn-primary py-1" id="addTrainingRowBtn">
                            <i class="mdi mdi-plus"></i> Add Training
                        </button>
                    </div>
                    <div class="card-body p-3">
                        <div id="trainingContainer" class="d-flex flex-column gap-3"></div>
                    </div>
                </div>
            `;
        } else if(section === 'employment_history') {
            html += `
                <div class="card border shadow-none mb-3">
                    <div class="card-header bg-body-secondary d-flex justify-content-between align-items-center py-2">
                        <h6 class="mb-0 fw-bold">Employment History List</h6>
                        <button type="button" class="btn btn-xs btn-primary py-1" id="addHistoryRowBtn">
                            <i class="mdi mdi-plus"></i> Add Job History
                        </button>
                    </div>
                    <div class="card-body p-3">
                        <div id="historyContainer" class="d-flex flex-column gap-3"></div>
                    </div>
                </div>
            `;
        } else if(section === 'emergency_contact') {
            html += `
                <div class="card border shadow-none mb-3">
                    <div class="card-header bg-body-secondary py-2">
                        <h6 class="mb-0 fw-bold">Emergency Contact / Nominee Info</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            ${generateField('nominee_name', 'Nominee Name', data.nominee_name)}
                            ${generateField('relation', 'Relation', data.relation)}
                            ${generateField('father_name', 'Father\'s Name', data.father_name)}
                            ${generateField('mother_name', 'Mother\'s Name', data.mother_name)}
                            ${generateField('spouse_name', 'Spouse Name', data.spouse_name)}
                            ${generateSelect('gender', 'Gender', data.gender, ['Male', 'Female', 'Other'])}
                            ${generateField('date_of_birth', 'Date of Birth', data.date_of_birth, 'date')}
                            ${generateSelect('religion', 'Religion', data.religion, ['Islam', 'Hinduism', 'Christianity', 'Buddhism', 'N/A', 'Others'])}
                            ${generateSelect('marital_status', 'Marital Status', data.marital_status, ['single', 'married', 'divorced', 'widowed'])}
                            ${generateField('nationality', 'Nationality', data.nationality)}
                            ${generateSelect('blood_group', 'Blood Group', data.blood_group, ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'])}
                            ${generateField('nid', 'NID Number', data.nid)}
                            ${generateField('birth_reg_no', 'Birth Registration No', data.birth_reg_no)}
                            ${generateField('phone', 'Phone Number', data.phone)}
                            ${generateField('mobile', 'Mobile Number', data.mobile)}
                            ${generateField('present_address_line', 'Present Address Line', data.present_address_line)}
                            ${generateField('village', 'Village/Area', data.village)}
                            ${generateField('post_office', 'Post Office', data.post_office)}
                            ${generateField('thana', 'Thana/Upazila', data.thana)}
                            ${generateField('district', 'District', data.district)}
                            ${generateField('state', 'State/Province', data.state)}
                            ${generateField('zip_code', 'Zip Code', data.zip_code)}
                            ${generateField('country', 'Country', data.country)}
                        </div>
                    </div>
                </div>
            `;
        }
        
        dynamicFormFields.innerHTML = html;

        // Post-inject triggers for list managers (Add More / Remove)
        if(section === 'education') {
            const eduList = Array.isArray(data.educations) ? data.educations : [];
            const trainingList = Array.isArray(data.trainings) ? data.trainings : [];

            const eduContainer = document.getElementById('educationContainer');
            const trainingContainer = document.getElementById('trainingContainer');

            // Render existing educations
            eduList.forEach((edu, idx) => addEducationRow(eduContainer, idx, edu));
            // Render existing trainings
            trainingList.forEach((train, idx) => addTrainingRow(trainingContainer, idx, train));

            // Initialize previous data hidden fields
            eduContainer.insertAdjacentHTML('afterend', `<input type="hidden" name="previous_data[educations]" value="${escapeHtml(JSON.stringify(eduList))}">`);
            trainingContainer.insertAdjacentHTML('afterend', `<input type="hidden" name="previous_data[trainings]" value="${escapeHtml(JSON.stringify(trainingList))}">`);

            document.getElementById('addEduRowBtn').addEventListener('click', () => {
                const count = eduContainer.querySelectorAll('.edu-row').length;
                addEducationRow(eduContainer, count, {});
            });

            document.getElementById('addTrainingRowBtn').addEventListener('click', () => {
                const count = trainingContainer.querySelectorAll('.training-row').length;
                addTrainingRow(container = trainingContainer, count, {});
            });
        }

        if(section === 'employment_history') {
            const historyList = Array.isArray(data.histories) ? data.histories : [];
            const historyContainer = document.getElementById('historyContainer');

            // Render existing histories
            historyList.forEach((hist, idx) => addHistoryRow(historyContainer, idx, hist));

            // Initialize previous data hidden
            historyContainer.insertAdjacentHTML('afterend', `<input type="hidden" name="previous_data[histories]" value="${escapeHtml(JSON.stringify(historyList))}">`);

            document.getElementById('addHistoryRowBtn').addEventListener('click', () => {
                const count = historyContainer.querySelectorAll('.history-row').length;
                addHistoryRow(historyContainer, count, {});
            });
        }
    });

    // Helper functions for simple fields
    function generateField(name, label, value, type='text') {
        let val = value || '';
        return `
            <div class="col-md-4">
                <label class="form-label text-muted small fw-semibold mb-1">${label}</label>
                <input type="${type}" class="form-control" name="requested_data[${name}]" value="${val}">
                <input type="hidden" name="previous_data[${name}]" value="${val}">
            </div>
        `;
    }

    function generateSelect(name, label, value, options) {
        let val = value || '';
        let optionsHtml = `<option value="">Select...</option>`;
        options.forEach(opt => {
            optionsHtml += `<option value="${opt}" ${val === opt ? 'selected' : ''}>${opt}</option>`;
        });
        return `
            <div class="col-md-4">
                <label class="form-label text-muted small fw-semibold mb-1">${label}</label>
                <select class="form-select" name="requested_data[${name}]">
                    ${optionsHtml}
                </select>
                <input type="hidden" name="previous_data[${name}]" value="${val}">
            </div>
        `;
    }

    function generateAddressField(addressSection, name, label, value) {
        let val = value || '';
        return `
            <div class="col-md-3">
                <label class="form-label text-muted small fw-semibold mb-1">${label}</label>
                <input type="text" class="form-control" name="requested_data[${addressSection}][${name}]" value="${val}">
                <input type="hidden" name="previous_data[${addressSection}][${name}]" value="${val}">
            </div>
        `;
    }

    // Dynamic Lists Managers
    function addEducationRow(container, index, data) {
        const div = document.createElement('div');
        div.className = 'card border shadow-none bg-light mb-2 edu-row position-relative';
        div.innerHTML = `
            <div class="card-body p-3">
                <div class="row g-2">
                    <div class="col-md-3">
                        <label class="form-label font-12 text-muted mb-1">Degree/Exam Title</label>
                        <input type="text" class="form-control form-control-sm" name="requested_data[educations][${index}][education_title]" value="${data.education_title || ''}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label font-12 text-muted mb-1">Institute</label>
                        <input type="text" class="form-control form-control-sm" name="requested_data[educations][${index}][institute]" value="${data.institute || ''}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label font-12 text-muted mb-1">Major/Group</label>
                        <input type="text" class="form-control form-control-sm" name="requested_data[educations][${index}][group_major]" value="${data.group_major || ''}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label font-12 text-muted mb-1">Board/University</label>
                        <input type="text" class="form-control form-control-sm" name="requested_data[educations][${index}][board_university]" value="${data.board_university || ''}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label font-12 text-muted mb-1">Result/Grade</label>
                        <input type="text" class="form-control form-control-sm" name="requested_data[educations][${index}][result_grade]" value="${data.result_grade || ''}">
                    </div>
                    <div class="col-md-2 mt-2">
                        <label class="form-label font-12 text-muted mb-1">Passing Year</label>
                        <input type="text" class="form-control form-control-sm" name="requested_data[educations][${index}][passing_year]" value="${data.passing_year || ''}">
                    </div>
                    <div class="col-md-2 mt-2">
                        <label class="form-label font-12 text-muted mb-1">GPA/CGPA</label>
                        <input type="text" class="form-control form-control-sm" name="requested_data[educations][${index}][gpa_cgpa]" value="${data.gpa_cgpa || ''}">
                    </div>
                    <div class="col-md-8 mt-2 d-flex align-items-end justify-content-end">
                        <button type="button" class="btn btn-sm btn-outline-danger remove-edu-row-btn py-1 font-11">
                            <i class="mdi mdi-delete"></i> Remove
                        </button>
                    </div>
                </div>
            </div>
        `;
        container.appendChild(div);
        
        div.querySelector('.remove-edu-row-btn').addEventListener('click', () => {
            div.remove();
            reorderRows(container, 'educations');
        });
    }

    function addTrainingRow(container, index, data) {
        const div = document.createElement('div');
        div.className = 'card border shadow-none bg-light mb-2 training-row';
        div.innerHTML = `
            <div class="card-body p-3">
                <div class="row g-2">
                    <div class="col-md-3">
                        <label class="form-label font-12 text-muted mb-1">Training Title</label>
                        <input type="text" class="form-control form-control-sm" name="requested_data[trainings][${index}][training_title]" value="${data.training_title || ''}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label font-12 text-muted mb-1">Course Name</label>
                        <input type="text" class="form-control form-control-sm" name="requested_data[trainings][${index}][course_name]" value="${data.course_name || ''}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label font-12 text-muted mb-1">Training Code</label>
                        <input type="text" class="form-control form-control-sm" name="requested_data[trainings][${index}][training_code]" value="${data.training_code || ''}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label font-12 text-muted mb-1">Institute</label>
                        <input type="text" class="form-control form-control-sm" name="requested_data[trainings][${index}][institute]" value="${data.institute || ''}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label font-12 text-muted mb-1">Country</label>
                        <input type="text" class="form-control form-control-sm" name="requested_data[trainings][${index}][country]" value="${data.country || ''}">
                    </div>
                    <div class="col-md-2 mt-2">
                        <label class="form-label font-12 text-muted mb-1">Location</label>
                        <input type="text" class="form-control form-control-sm" name="requested_data[trainings][${index}][location]" value="${data.location || ''}">
                    </div>
                    <div class="col-md-2 mt-2">
                        <label class="form-label font-12 text-muted mb-1">Duration</label>
                        <input type="text" class="form-control form-control-sm" name="requested_data[trainings][${index}][duration]" value="${data.duration || ''}">
                    </div>
                    <div class="col-md-2 mt-2">
                        <label class="form-label font-12 text-muted mb-1">From Date</label>
                        <input type="date" class="form-control form-control-sm" name="requested_data[trainings][${index}][from_date]" value="${data.from_date || ''}">
                    </div>
                    <div class="col-md-2 mt-2">
                        <label class="form-label font-12 text-muted mb-1">To Date</label>
                        <input type="date" class="form-control form-control-sm" name="requested_data[trainings][${index}][to_date]" value="${data.to_date || ''}">
                    </div>
                    <div class="col-md-4 mt-2 d-flex align-items-end justify-content-end">
                        <button type="button" class="btn btn-sm btn-outline-danger remove-training-row-btn py-1 font-11">
                            <i class="mdi mdi-delete"></i> Remove
                        </button>
                    </div>
                </div>
            </div>
        `;
        container.appendChild(div);

        div.querySelector('.remove-training-row-btn').addEventListener('click', () => {
            div.remove();
            reorderRows(container, 'trainings');
        });
    }

    function addHistoryRow(container, index, data) {
        const div = document.createElement('div');
        div.className = 'card border shadow-none bg-light mb-2 history-row';
        div.innerHTML = `
            <div class="card-body p-3">
                <div class="row g-2">
                    <div class="col-md-3">
                        <label class="form-label font-12 text-muted mb-1">Company Name</label>
                        <input type="text" class="form-control form-control-sm" name="requested_data[histories][${index}][company_name]" value="${data.company_name || ''}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label font-12 text-muted mb-1">Designation</label>
                        <input type="text" class="form-control form-control-sm" name="requested_data[histories][${index}][designation]" value="${data.designation || ''}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label font-12 text-muted mb-1">Joining Date</label>
                        <input type="date" class="form-control form-control-sm" name="requested_data[histories][${index}][joining_date]" value="${data.joining_date || ''}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label font-12 text-muted mb-1">End Date</label>
                        <input type="date" class="form-control form-control-sm" name="requested_data[histories][${index}][end_date]" value="${data.end_date || ''}">
                    </div>
                    <div class="col-md-6 mt-2">
                        <label class="form-label font-12 text-muted mb-1">Job Description</label>
                        <textarea class="form-control form-control-sm" name="requested_data[histories][${index}][job_description]" rows="2">${data.job_description || ''}</textarea>
                    </div>
                    <div class="col-md-6 mt-2">
                        <label class="form-label font-12 text-muted mb-1">Achievements</label>
                        <textarea class="form-control form-control-sm" name="requested_data[histories][${index}][achievements]" rows="2">${data.achievements || ''}</textarea>
                    </div>
                    <div class="col-md-12 mt-2 d-flex justify-content-end">
                        <button type="button" class="btn btn-sm btn-outline-danger remove-history-row-btn py-1 font-11">
                            <i class="mdi mdi-delete"></i> Remove
                        </button>
                    </div>
                </div>
            </div>
        `;
        container.appendChild(div);

        div.querySelector('.remove-history-row-btn').addEventListener('click', () => {
            div.remove();
            reorderRows(container, 'histories');
        });
    }

    function reorderRows(container, arrayName) {
        const rows = container.children;
        Array.from(rows).forEach((row, index) => {
            const inputs = row.querySelectorAll('[name]');
            inputs.forEach(input => {
                const currentName = input.getAttribute('name');
                const newName = currentName.replace(new RegExp(`requested_data\\[${arrayName}\\]\\[\\d+\\]`), `requested_data[${arrayName}][${index}]`);
                input.setAttribute('name', newName);
            });
        });
    }

    function escapeHtml(text) {
        return text
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    // Submit actions
    submitBtn.addEventListener('click', function() {
        const form = document.getElementById('updateRequestForm');
        const formData = new FormData(form);
        
        let payload = {
            employee_id: formData.get('employee_id'),
            section: formData.get('section'),
            requested_data: {},
            previous_data: {}
        };

        // Recursive object constructor helper
        function setPathValue(obj, keys, value) {
            let current = obj;
            for (let i = 0; i < keys.length; i++) {
                let key = keys[i];
                let nextKey = keys[i + 1];
                
                if (nextKey !== undefined) {
                    if (current[key] === undefined) {
                        current[key] = !isNaN(nextKey) ? [] : {};
                    }
                    current = current[key];
                } else {
                    current[key] = value;
                }
            }
        }

        // Loop form entries
        for (let [key, value] of formData.entries()) {
            if(key.startsWith('requested_data[')) {
                let path = key.substring('requested_data['.length, key.length - 1);
                let pList = path.split('][');
                setPathValue(payload.requested_data, pList, value);
            } else if(key.startsWith('previous_data[')) {
                let path = key.substring('previous_data['.length, key.length - 1);
                let pList = path.split('][');
                
                // If it is a stringified JSON (like for educations, histories lists)
                if (value.startsWith('[') || value.startsWith('{')) {
                    try {
                        value = JSON.parse(value);
                    } catch(e) {}
                }
                setPathValue(payload.previous_data, pList, value);
            }
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="mdi mdi-loading mdi-spin me-1"></i> Submitting...';

        axios.post('{{ route('profile_update_requests.store') }}', payload)
            .then(res => {
                if(res.data.success) {
                    Swal.fire('Success', res.data.message, 'success').then(() => {
                        window.location.reload();
                    });
                }
            })
            .catch(err => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="mdi mdi-check-all me-1"></i> Submit Request';
                
                let errorMsg = 'Failed to submit profile update request.';
                if(err.response && err.response.data && err.response.data.message) {
                    errorMsg = err.response.data.message;
                }
                Swal.fire('Error', errorMsg, 'error');
            });
    });
    @endif
});
</script>