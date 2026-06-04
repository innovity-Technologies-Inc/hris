@extends('structure.master')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card glass-card border-0 shadow-lg mb-4">
                <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold mb-1"><i class="mdi mdi-credit-card-outline me-2 text-primary"></i>Pay Group Management</h4>
                        <p class="text-muted mb-0">Define and manage payroll processing groups and frequencies.</p>
                    </div>
                    @can('general-settings.create')
                    <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm" id="btnCreatePayGroup">
                        <i class="mdi mdi-plus me-1"></i> Add New Pay Group
                    </button>
                    @endcan
                </div>
                <div class="card-body p-4">
                    {{-- Search Section --}}
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="input-group search-group shadow-sm rounded-pill overflow-hidden border">
                                <span class="input-group-text bg-white border-0 ps-3">
                                    <i class="mdi mdi-magnify text-muted fs-18"></i>
                                </span>
                                <input type="text" class="form-control border-0 py-2" id="searchKeyword" placeholder="Search by title, frequency...">
                            </div>
                        </div>
                    </div>

                    {{-- Data Table --}}
                    <div id="payGroupContainer">
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Pay Group Modal --}}
    <div class="modal fade" id="payGroupModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white border-0">
                    <h5 class="modal-title" id="payGroupModalLabel">Add Pay Group</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="payGroupForm">
                    @csrf
                    <input type="hidden" id="payGroupId" name="id">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Company <span class="text-danger">*</span></label>
                            <select class="form-select" name="current_company_id" id="current_company_id" required>
                                <option value="">Select Company</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="title" id="title" placeholder="e.g. Monthly Staff" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Payroll Frequency <span class="text-danger">*</span></label>
                            <select class="form-select" name="payroll_frequency" id="payroll_frequency" required>
                                <option value="Monthly">Monthly</option>
                                <option value="Weekly">Weekly</option>
                                <option value="Hourly">Hourly</option>
                            </select>
                        </div>
                        <div class="mb-3" id="processingDayGroup">
                            <label class="form-label fw-semibold" id="processingDayLabel">Generation Date of Each Month <span class="text-danger">*</span></label>
                            <div id="processingDayInputContainer">
                                {{-- Will be populated dynamically --}}
                            </div>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select class="form-select" name="status" id="status" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4" id="btnSavePayGroup">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('payGroupContainer');
    const searchInput = document.getElementById('searchKeyword');
    const modal = new bootstrap.Modal(document.getElementById('payGroupModal'));
    const form = document.getElementById('payGroupForm');
    const frequencySelect = document.getElementById('payroll_frequency');
    const dayInputContainer = document.getElementById('processingDayInputContainer');
    const dayLabel = document.getElementById('processingDayLabel');

    // Initial Load
    fetchPayGroups();

    // Search Logic
    let searchTimer;
    searchInput.addEventListener('input', () => {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(fetchPayGroups, 500);
    });

    // Frequency Change Logic
    frequencySelect.addEventListener('change', updateProcessingDayInput);

    function updateProcessingDayInput(selectedValue = null) {
        const frequency = typeof selectedValue === 'string' ? selectedValue : frequencySelect.value;
        let html = '';
        
        if (frequency === 'Monthly') {
            dayLabel.innerText = 'Generation Date of Each Month';
            html = `<select class="form-select" name="salary_processing_day" required>`;
            for (let i = 1; i <= 31; i++) {
                html += `<option value="${i}">${i}</option>`;
            }
            html += `</select>`;
        } else if (frequency === 'Weekly') {
            dayLabel.innerText = 'Generation Day of the Week';
            const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            html = `<select class="form-select" name="salary_processing_day" required>`;
            days.forEach(day => {
                html += `<option value="${day}">${day}</option>`;
            });
            html += `</select>`;
        } else {
            dayLabel.innerText = 'Salary Processing Date';
            html = `<input type="text" class="form-control bg-light" name="salary_processing_day" value="Daily" readonly>`;
        }
        
        dayInputContainer.innerHTML = html;
    }

    // Default init
    updateProcessingDayInput();

    function fetchPayGroups(url = "{{ route('pay_groups.index') }}") {
        const keyword = searchInput.value;
        const fullUrl = `${url}${url.includes('?') ? '&' : '?'}keyword=${keyword}`;

        axios.get(fullUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(response => {
                container.innerHTML = response.data;
                bindActionButtons();
            })
            .catch(error => {
                console.error(error);
                container.innerHTML = '<div class="text-danger text-center py-4">Failed to load data.</div>';
            });
    }

    function bindActionButtons() {
        // Edit Button
        document.querySelectorAll('.edit-pay-group').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                axios.get(`/company-setup/pay-groups/${id}/edit`)
                    .then(response => {
                        const data = response.data.data;
                        document.getElementById('payGroupId').value = data.id;
                        document.getElementById('current_company_id').value = data.current_company_id;
                        document.getElementById('title').value = data.title;
                        document.getElementById('payroll_frequency').value = data.payroll_frequency;
                        document.getElementById('status').value = data.status;
                        
                        updateProcessingDayInput(data.payroll_frequency);
                        const daySelect = dayInputContainer.querySelector('[name="salary_processing_day"]');
                        if (daySelect) daySelect.value = data.salary_processing_day;

                        document.getElementById('payGroupModalLabel').innerText = 'Edit Pay Group';
                        modal.show();
                    });
            });
        });

        // Delete Button
        document.querySelectorAll('.delete-pay-group').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.delete(`/company-setup/pay-groups/${id}/delete`, {
                            data: { _token: "{{ csrf_token() }}" }
                        }).then(response => {
                            Swal.fire('Deleted!', response.data.message, 'success');
                            fetchPayGroups();
                        }).catch(error => {
                            Swal.fire('Error!', 'Failed to delete.', 'error');
                        });
                    }
                });
            });
        });

        // Pagination links
        document.querySelectorAll('.pagination a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                fetchPayGroups(this.getAttribute('href'));
            });
        });
    }

    document.getElementById('btnCreatePayGroup').addEventListener('click', () => {
        form.reset();
        document.getElementById('payGroupId').value = '';
        document.getElementById('payGroupModalLabel').innerText = 'Add Pay Group';
        updateProcessingDayInput();
        modal.show();
    });

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('payGroupId').value;
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());
        
        const url = id ? `/company-setup/pay-groups/${id}/update` : "{{ route('pay_groups.store') }}";
        const method = id ? 'put' : 'post';

        axios({
            method: method,
            url: url,
            data: data
        }).then(response => {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: response.data.message,
                timer: 1500,
                showConfirmButton: false
            });
            modal.hide();
            fetchPayGroups();
        }).catch(error => {
            console.error(error);
            const msg = error.response?.data?.message || 'Something went wrong';
            Swal.fire('Error!', msg, 'error');
        });
    });
});
</script>
@endpush
