<div class="modal fade" id="updateRequestModal" tabindex="-1" aria-labelledby="updateRequestModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateRequestModalLabel">Request Profile Update</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateRequestForm">
                    <input type="hidden" name="employee_id" value="{{ $employee->id ?? '' }}">
                    
                    <div class="mb-3">
                        <label class="form-label">Which section do you want to update?</label>
                        <select class="form-select" name="section" id="updateSectionSelect" required>
                            <option value="">Select Section</option>
                            <option value="general">General</option>
                            <option value="education">Education</option>
                            <option value="employment_history">Employment History</option>
                            <option value="emergency_contact">Emergency Contact</option>
                        </select>
                    </div>

                    <div id="dynamicFormArea" class="mt-4 border-top pt-3 d-none">
                        <div class="alert alert-info">
                            Please update the fields below. Once submitted, it will be sent to HR for approval. Your live profile will not change until approved.
                        </div>
                        <div id="dynamicFormFields"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary d-none" id="submitUpdateRequestBtn">Submit Request</button>
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
    
    // Only proceed if employee data is available on the page
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
        
        let html = '<div class="row g-3">';
        let data = empData[section] || {};
        
        if(section === 'general') {
            html += generateField('first_name', 'First Name', data.first_name);
            html += generateField('last_name', 'Last Name', data.last_name);
            html += generateField('personal_mobile', 'Personal Mobile', data.personal_mobile);
            html += generateField('personal_email', 'Personal Email', data.personal_email);
            html += generateField('date_of_birth', 'Date of Birth', data.date_of_birth, 'date');
        } else if(section === 'education') {
            let eduStr = typeof data.educations === 'string' ? data.educations : JSON.stringify(data.educations || []);
            html += generateTextarea('educations', 'Educations JSON', eduStr);
        } else if(section === 'employment_history') {
            let histStr = typeof data.histories === 'string' ? data.histories : JSON.stringify(data.histories || []);
            html += generateTextarea('histories', 'Histories JSON', histStr);
        } else if(section === 'emergency_contact') {
            html += generateField('nominee_name', 'Name', data.nominee_name);
            html += generateField('relation', 'Relation', data.relation);
            html += generateField('mobile', 'Mobile', data.mobile);
            html += generateField('nid', 'NID', data.nid);
        }
        
        html += '</div>';
        dynamicFormFields.innerHTML = html;
    });

    function generateField(name, label, value, type='text') {
        let val = value || '';
        return `
            <div class="col-md-6">
                <label class="form-label">${label}</label>
                <input type="${type}" class="form-control" name="requested_data[${name}]" value="${val}">
                <input type="hidden" name="previous_data[${name}]" value="${val}">
            </div>
        `;
    }

    function generateTextarea(name, label, value) {
        let val = value || '';
        return `
            <div class="col-12">
                <label class="form-label">${label}</label>
                <textarea class="form-control" name="requested_data[${name}]" rows="4">${val}</textarea>
                <input type="hidden" name="previous_data[${name}]" value="${val}">
            </div>
        `;
    }

    submitBtn.addEventListener('click', function() {
        const form = document.getElementById('updateRequestForm');
        const formData = new FormData(form);
        
        let payload = {
            employee_id: formData.get('employee_id'),
            section: formData.get('section'),
            requested_data: {},
            previous_data: {}
        };
        
        for (let [key, value] of formData.entries()) {
            if(key.startsWith('requested_data[')) {
                let actualKey = key.match(/\[(.*?)\]/)[1];
                payload.requested_data[actualKey] = value;
            } else if(key.startsWith('previous_data[')) {
                let actualKey = key.match(/\[(.*?)\]/)[1];
                payload.previous_data[actualKey] = value;
            }
        }

        axios.post('{{ route('profile_update_requests.store') }}', payload)
            .then(res => {
                if(res.data.success) {
                    Swal.fire('Success', res.data.message, 'success').then(() => {
                        window.location.reload();
                    });
                }
            })
            .catch(err => {
                Swal.fire('Error', 'Failed to submit request.', 'error');
            });
    });
    @endif
});
</script>