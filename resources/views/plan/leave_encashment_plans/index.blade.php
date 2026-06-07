@extends('structure.master')

@section('content')
    {{-- Leave Encashment Plans List --}}
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    @can('leave-encashment-plans.create')
                    <button type="button" class="btn btn-warning btn-sm" id="btnCreatePlan">
                        <i style="height: 12px; width: 12px" data-feather="plus"></i> Create
                    </button>
                    @endcan
                </div><!-- end card header -->

                {{-- Search Filter Form --}}
                <form id="filterForm">
                    <div class="row mb-1 mt-2 mx-4">
                        <div class="col-12">
                            <div class="input-group input-group-md">
                                <input type="text" class="form-control border-end-0" id="searchKeyword"
                                       name="keyword" placeholder="Search plans by title or description..."
                                       aria-label="Keyword Search">
                                <span class="input-group-text border-start-0 input-group-bg">
                                    <i class="mdi mdi-magnify text-muted"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="card-body" id="planContainer">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div><!-- end card -->
        </div>
    </div><!-- end row -->

    {{-- Leave Encashment Plan Modal --}}
    <div class="modal fade" id="planModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header text-white border-0" style="background-color: #974063;">
                    <h5 class="modal-title" id="planModalLabel">Add Leave Encashment Plan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="planForm">
                    @csrf
                    <input type="hidden" id="planId" name="id">
                    <div class="modal-body p-4">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="title" id="title" placeholder="e.g. Annual Leave Encashment Policy" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Encashment Basis <span class="text-danger">*</span></label>
                                <select class="form-select" name="encashment_basis" id="encashment_basis" required>
                                    <option value="basic">Basic Salary</option>
                                    <option value="gross">Gross Salary</option>
                                </select>
                                <small class="text-muted">Salary component used for calculation</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Encashment Rate <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" step="0.01" class="form-control" name="encashment_rate" id="encashment_rate" value="1.00" required>
                                    <span class="input-group-text">x Day Salary</span>
                                </div>
                                <small class="text-muted">1.00 = Full Day, 0.50 = Half Day</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Min Balance to Maintain <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="min_balance_to_maintain" id="min_balance_to_maintain" value="0" required>
                                <small class="text-muted">Minimum leave balance that must remain</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Max Encashable Days (Yearly)</label>
                                <input type="number" class="form-control" name="max_encashable_days_per_year" id="max_encashable_days_per_year" placeholder="Optional">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                                <select class="form-select" name="status" id="status" required>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Description</label>
                                <textarea class="form-control" name="description" id="description" rows="3" placeholder="Policy details..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn rounded-pill px-4 text-white" id="btnSavePlan" style="background-color: #974063;">Save Changes</button>
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
    const container = document.getElementById('planContainer');
    const searchInput = document.getElementById('searchKeyword');
    const modalEl = document.getElementById('planModal');
    const modal = new bootstrap.Modal(modalEl);
    const form = document.getElementById('planForm');

    // Initial Load
    fetchPlans();

    // Search Logic
    let searchTimer;
    searchInput.addEventListener('input', () => {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => fetchPlans(), 500);
    });

    function fetchPlans(url = "{{ route('plan.leave_encashment_plans.index') }}") {
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
        document.querySelectorAll('.edit-plan').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                axios.get(`/plans/leave-encashment-plans/edit/${id}`)
                    .then(response => {
                        const data = response.data.data;
                        document.getElementById('planId').value = data.id;
                        document.getElementById('title').value = data.title;
                        document.getElementById('description').value = data.description;
                        document.getElementById('encashment_basis').value = data.encashment_basis;
                        document.getElementById('min_balance_to_maintain').value = data.min_balance_to_maintain;
                        document.getElementById('max_encashable_days_per_year').value = data.max_encashable_days_per_year;
                        document.getElementById('encashment_rate').value = data.encashment_rate;
                        document.getElementById('status').value = data.status;
                        
                        document.getElementById('planModalLabel').innerText = 'Edit Leave Encashment Plan';
                        modal.show();
                    });
            });
        });

        // Delete Button
        document.querySelectorAll('.delete-plan').forEach(btn => {
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
                        axios.delete(`/plans/leave-encashment-plans/delete/${id}`, {
                            headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" }
                        }).then(response => {
                            Swal.fire('Deleted!', response.data.message, 'success');
                            fetchPlans();
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
                fetchPlans(this.getAttribute('href'));
            });
        });
    }

    const btnCreate = document.getElementById('btnCreatePlan');
    if (btnCreate) {
        btnCreate.addEventListener('click', () => {
            form.reset();
            document.getElementById('planId').value = '';
            document.getElementById('planModalLabel').innerText = 'Add Leave Encashment Plan';
            modal.show();
        });
    }

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('planId').value;
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());
        
        const url = id ? `/plans/leave-encashment-plans/update/${id}` : "{{ route('plan.leave_encashment_plans.store') }}";
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
            fetchPlans();
        }).catch(error => {
            console.error(error);
            const msg = error.response?.data?.message || 'Something went wrong';
            Swal.fire('Error!', msg, 'error');
        });
    });
});
</script>
@endpush
