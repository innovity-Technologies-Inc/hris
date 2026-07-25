@extends('structure.master')

@section('content')
    {{-- List of Pay Groups --}}
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    @can('pay-groups.create')
                    <button type="button" class="btn btn-warning btn-sm" id="btnCreatePayGroup">
                        <i style="height: 12px; width: 12px" data-feather="plus"></i> Create
                    </button>
                    @endcan
                </div><!-- end card header -->

                <div class="card-body">
                    <form id="filterForm">
                        {{-- First Row: Keyword Search --}}
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="input-group input-group-md">
                                    <input type="text" class="form-control border-end-0" id="searchKeyword"
                                           name="keyword" placeholder="Search pay groups by title or frequency"
                                           aria-label="Keyword Search">
                                    <span class="input-group-text border-start-0 input-group-bg">
                                        <i class="mdi mdi-magnify text-muted"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive" id="payGroupContainer">
                        <div class="text-center py-4 text-muted">Loading Data...</div>
                    </div>
                </div>
            </div><!-- end card -->
        </div>
    </div><!-- end row -->

    {{-- Pay Group Modal --}}
    <div class="modal fade" id="payGroupModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header text-white border-0" style="background-color: #974063;">
                    <h5 class="modal-title" id="payGroupModalLabel">Add Pay Group</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="payGroupForm">
                    @csrf
                    <input type="hidden" id="payGroupId" name="id">
                    <div class="modal-body p-4">
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
                                <option value="Daily">Daily</option>
                            </select>
                        </div>
                        <div id="dynamicWorkingMetrics" style="display: block;">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Working Hours / Day <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control" name="working_hours_per_day" id="working_hours_per_day" placeholder="e.g. 8" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Working Days / Cycle <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control" name="working_days_per_cycle" id="working_days_per_cycle" placeholder="e.g. 30" required>
                                </div>
                            </div>
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
                        <button type="submit" class="btn rounded-pill px-4 text-white" id="btnSavePayGroup" style="background-color: #974063;">Save Changes</button>
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
        
        const dynamicWorkingMetrics = document.getElementById('dynamicWorkingMetrics');
        const workingHoursInput = document.getElementById('working_hours_per_day');
        const workingDaysInput = document.getElementById('working_days_per_cycle');

        // Always show the working metrics and make them required
        dynamicWorkingMetrics.style.display = 'block';
        workingHoursInput.required = true;
        workingDaysInput.required = true;

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
                if (window.feather) {
                    feather.replace();
                }
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
                        document.getElementById('title').value = data.title;
                        document.getElementById('payroll_frequency').value = data.payroll_frequency;
                        document.getElementById('status').value = data.status;
                        
                        document.getElementById('working_hours_per_day').value = data.working_hours_per_day || '';
                        document.getElementById('working_days_per_cycle').value = data.working_days_per_cycle || '';
                        
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
