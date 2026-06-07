@extends('structure.master')

@section('content')
    {{-- Penalty Plans List --}}
    <div class="row">
        <div class="col-xl-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold" style="color: #974063;">Penalty Plans</h5>
                    @can('penalty-plans.create')
                    <button type="button" class="btn btn-warning btn-sm shadow-sm" id="btnCreatePenaltyPlan">
                        <i style="height: 12px; width: 12px" data-feather="plus"></i> Create
                    </button>
                    @endcan
                </div><!-- end card header -->

                <div class="card-body">
                    <form id="filterForm" class="mb-4">
                        <div class="row mb-1 mt-2 mx-4">
                            <div class="col-12">
                                <div class="input-group input-group-md">
                                    <input type="text" class="form-control border-end-0" id="searchKeyword"
                                           name="keyword" placeholder="Search penalty plans by title or description..."
                                           aria-label="Keyword Search">
                                    <span class="input-group-text border-start-0 input-group-bg">
                                        <i class="mdi mdi-magnify text-muted"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div id="penaltyPlanContainer">
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- end card -->
        </div>
    </div><!-- end row -->

    {{-- Penalty Plan Modal --}}
    <div class="modal fade" id="penaltyPlanModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header text-white border-0" style="background-color: #974063;">
                    <h5 class="modal-title" id="penaltyPlanModalLabel">Add Penalty Plan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="penaltyPlanForm">
                    @csrf
                    <input type="hidden" id="penaltyPlanId" name="id">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="title" id="title" placeholder="e.g. Late Penalty" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea class="form-control" name="description" id="description" rows="3" placeholder="Enter description"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Penalty Amount <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">{{ \App\HelperClass::getCurrency() }}</span>
                                <input type="number" step="0.01" class="form-control" name="penalty_amount" id="penalty_amount" placeholder="0.00" required>
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
                        <button type="submit" class="btn rounded-pill px-4 text-white" id="btnSavePenaltyPlan" style="background-color: #974063;">Save Changes</button>
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
    const container = document.getElementById('penaltyPlanContainer');
    const searchInput = document.getElementById('searchKeyword');
    const modalEl = document.getElementById('penaltyPlanModal');
    const modal = new bootstrap.Modal(modalEl);
    const form = document.getElementById('penaltyPlanForm');

    // Initial Load
    fetchPenaltyPlans();

    // Search Logic
    let searchTimer;
    searchInput.addEventListener('input', () => {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => fetchPenaltyPlans(), 500);
    });

    function fetchPenaltyPlans(url = "{{ route('plan.penalty_plans.index') }}") {
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
        document.querySelectorAll('.edit-penalty-plan').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                // FIXED URL PATH
                axios.get(`/plans/penalty-plans/edit/${id}`)
                    .then(response => {
                        const data = response.data.data;
                        document.getElementById('penaltyPlanId').value = data.id;
                        document.getElementById('title').value = data.title;
                        document.getElementById('description').value = data.description;
                        document.getElementById('penalty_amount').value = data.penalty_amount;
                        document.getElementById('status').value = data.status;
                        
                        document.getElementById('penaltyPlanModalLabel').innerText = 'Edit Penalty Plan';
                        modal.show();
                    });
            });
        });

        // Delete Button
        document.querySelectorAll('.delete-penalty-plan').forEach(btn => {
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
                        // FIXED URL PATH
                        axios.delete(`/plans/penalty-plans/delete/${id}`, {
                            headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" }
                        }).then(response => {
                            Swal.fire('Deleted!', response.data.message, 'success');
                            fetchPenaltyPlans();
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
                fetchPenaltyPlans(this.getAttribute('href'));
            });
        });
    }

    const btnCreate = document.getElementById('btnCreatePenaltyPlan');
    if (btnCreate) {
        btnCreate.addEventListener('click', () => {
            form.reset();
            document.getElementById('penaltyPlanId').value = '';
            document.getElementById('penaltyPlanModalLabel').innerText = 'Add Penalty Plan';
            modal.show();
        });
    }

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('penaltyPlanId').value;
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());
        
        // FIXED URL PATH
        const url = id ? `/plans/penalty-plans/update/${id}` : "{{ route('plan.penalty_plans.store') }}";
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
            fetchPenaltyPlans();
        }).catch(error => {
            console.error(error);
            const msg = error.response?.data?.message || 'Something went wrong';
            Swal.fire('Error!', msg, 'error');
        });
    });
});
</script>
@endpush
