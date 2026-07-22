@extends('structure.master')

@section('content')
    {{-- Penalty Management List --}}
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    @can('penalty-management.create')
                    <button type="button" class="btn btn-warning btn-sm" id="btnAssignPenalty">
                        <i style="height: 12px; width: 12px" data-feather="plus"></i> Assign Penalty
                    </button>
                    @else
                    <div></div>
                    @endcan
                    <div class="d-flex gap-2">
                        <button type="button" id="exportExcelBtn" class="btn btn-success btn-sm no-loader">
                            <i class="bi bi-file-earmark-excel me-1"></i> Export
                        </button>
                        <button type="button" id="printBtn" class="btn btn-secondary btn-sm no-loader">
                            <i class="bi bi-printer me-1"></i> Print
                        </button>
                    </div>
                </div><!-- end card header -->

                {{-- Search Filter Form --}}
                <form id="filterForm">
                    <div class="row mb-1 mt-2 mx-4">
                        <div class="col-12">
                            <div class="input-group input-group-md">
                                <input type="text" class="form-control border-end-0" id="searchKeyword"
                                       name="keyword" placeholder="Search by employee name, ID, or cause..."
                                       aria-label="Keyword Search">
                                <span class="input-group-text border-start-0 input-group-bg">
                                    <i class="mdi mdi-magnify text-muted"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="card-body" id="penaltyContainer">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div><!-- end card -->
        </div>
    </div><!-- end row -->

    {{-- Penalty Assignment Modal --}}
    <div class="modal fade" id="penaltyModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header text-white border-0" style="background-color: #974063;">
                    <h5 class="modal-title" id="penaltyModalLabel">Assign Penalty</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="penaltyForm">
                    @csrf
                    <input type="hidden" id="penaltyId" name="id">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Employee <span class="text-danger">*</span></label>
                            <select class="form-select penalty-employee-select" name="employee_id" id="employee_id" required>
                                <option value="">Select Employee</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}">{{ $emp->full_name }} ({{ $emp->applicant_id ?? $emp->system_id }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Penalty Plan <span class="text-danger">*</span></label>
                            <select class="form-select" name="penalty_plan_id" id="penalty_plan_id" required>
                                <option value="">Select Plan</option>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}" data-amount="{{ $plan->penalty_amount }}">{{ $plan->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Occurrence Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="occurrence_date" id="occurrence_date" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Penalty Amount <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">{{ \App\HelperClass::getCurrency() }}</span>
                                <input type="number" step="0.01" class="form-control" name="penalty_amount" id="penalty_amount" placeholder="0.00" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Cause</label>
                            <textarea class="form-control" name="cause" id="cause" rows="2" placeholder="Describe the reason for penalty"></textarea>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select class="form-select" name="status" id="status" required>
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="deducted">Deducted</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn rounded-pill px-4 text-white" id="btnSavePenalty" style="background-color: #974063;">Save Changes</button>
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
    const container = document.getElementById('penaltyContainer');
    const searchInput = document.getElementById('searchKeyword');
    const modalEl = document.getElementById('penaltyModal');
    const modal = new bootstrap.Modal(modalEl);
    const form = document.getElementById('penaltyForm');
    const planSelect = document.getElementById('penalty_plan_id');
    const amountInput = document.getElementById('penalty_amount');

    // Initialize Select2 for Employee Dropdown
    if ($.fn.select2) {
        $('#employee_id').select2({
            dropdownParent: $('#penaltyModal'),
            width: '100%',
            placeholder: 'Select Employee'
        });
    }

    // Auto-fill amount based on plan
    planSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const amount = selectedOption.getAttribute('data-amount');
        if (amount) {
            amountInput.value = parseFloat(amount).toFixed(2);
        }
    });

    // Initial Load
    fetchPenalties();

    // Search Logic
    let searchTimer;
    searchInput.addEventListener('input', () => {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => fetchPenalties(), 500);
    });

    function fetchPenalties(url = "{{ route('payroll.penalty.index') }}") {
        const keyword = searchInput.value;
        const fullUrl = `${url}${url.includes('?') ? '&' : '?'}keyword=${keyword}`;

        axios.get(fullUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(response => {
                container.innerHTML = response.data;
                bindActionButtons();
                if (window.feather) feather.replace();
            })
            .catch(error => {
                console.error(error);
                container.innerHTML = '<div class="text-danger text-center py-4">Failed to load data.</div>';
            });
    }

    function bindActionButtons() {
        // Edit Button
        document.querySelectorAll('.edit-penalty').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                axios.get(`/payroll/penalty/edit/${id}`)
                    .then(response => {
                        const data = response.data.data;
                        document.getElementById('penaltyId').value = data.id;
                        // Select2 updating
                        $('#employee_id').val(data.employee_id).trigger('change');
                        document.getElementById('penalty_plan_id').value = data.penalty_plan_id;
                        document.getElementById('occurrence_date').value = data.occurrence_date;
                        document.getElementById('cause').value = data.cause || '';
                        document.getElementById('penalty_amount').value = data.penalty_amount;
                        document.getElementById('status').value = data.status;
                        
                        document.getElementById('penaltyModalLabel').innerText = 'Edit Assigned Penalty';
                        modal.show();
                    });
            });
        });

        // Delete Button
        document.querySelectorAll('.delete-penalty').forEach(btn => {
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
                        axios.delete(`/payroll/penalty/delete/${id}`, {
                            headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" }
                        }).then(response => {
                            Swal.fire('Deleted!', response.data.message, 'success');
                            fetchPenalties();
                        }).catch(error => {
                            console.error(error);
                            Swal.fire('Error!', 'Failed to delete.', 'error');
                        });
                    }
                });
            });
        });

        // Pagination
        document.querySelectorAll('.pagination a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                fetchPenalties(this.getAttribute('href'));
            });
        });
    }

    // Excel export
    const exportBtn = document.getElementById('exportExcelBtn');
    if (exportBtn) {
        exportBtn.addEventListener('click', function(e) {
            e.preventDefault();
            window.ignoreBeforeUnload = true;
            setTimeout(() => { window.ignoreBeforeUnload = false; }, 2000);
            
            const keyword = searchInput.value;
            window.location.href = "{{ route('payroll.penalty.export.excel') }}?keyword=" + encodeURIComponent(keyword);
        });
    }

    // Print
    const printBtn = document.getElementById('printBtn');
    if (printBtn) {
        printBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const keyword = searchInput.value;
            window.open("{{ route('payroll.penalty.print') }}?keyword=" + encodeURIComponent(keyword), '_blank');
        });
    }

    const btnAssign = document.getElementById('btnAssignPenalty');
    if (btnAssign) {
        btnAssign.addEventListener('click', () => {
            form.reset();
            document.getElementById('penaltyId').value = '';
            $('#employee_id').val('').trigger('change');
            document.getElementById('penaltyModalLabel').innerText = 'Assign Penalty';
            modal.show();
        });
    }

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('penaltyId').value;
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());
        
        const url = id ? `/payroll/penalty/update/${id}` : "{{ route('payroll.penalty.store') }}";
        const method = id ? 'put' : 'post';

        axios({
            method: method,
            url: url,
            data: data,
            headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" }
        }).then(response => {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: response.data.message,
                timer: 1500,
                showConfirmButton: false
            });
            modal.hide();
            fetchPenalties();
        }).catch(error => {
            console.error(error);
            const msg = error.response?.data?.message || 'Something went wrong';
            Swal.fire('Error!', msg, 'error');
        });
    });
});
</script>
@endpush
