@extends('structure.master')

@section('content')
    {{-- List of Pay Scales --}}
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    @can('pay-scales.create')
                    <button type="button" class="btn btn-warning btn-sm" id="btnCreatePayScale">
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
                                           name="keyword" placeholder="Search by grade code, name or pay group"
                                           aria-label="Keyword Search">
                                    <span class="input-group-text border-start-0 input-group-bg">
                                        <i class="mdi mdi-magnify text-muted"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive" id="payScaleContainer">
                        <div class="text-center py-4 text-muted">Loading Data...</div>
                    </div>
                </div>
            </div><!-- end card -->
        </div>
    </div><!-- end row -->

    {{-- Pay Scale Modal --}}
    <div class="modal fade" id="payScaleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header text-white border-0" style="background-color: #974063;">
                    <h5 class="modal-title" id="payScaleModalLabel">Add Pay Scale</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="payScaleForm">
                    @csrf
                    <input type="hidden" id="payScaleId" name="id">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Salary Grade <span class="text-danger">*</span></label>
                            <select class="form-select" name="grade_id" id="grade_id" required>
                                <option value="">Select Grade</option>
                                @foreach($grades as $grade)
                                    <option value="{{ $grade->id }}">{{ $grade->grade_code }} - {{ $grade->grade_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Pay Group <span class="text-danger">*</span></label>
                            <select class="form-select" name="pay_group_id" id="pay_group_id" required>
                                <option value="">Select Pay Group</option>
                                @foreach($payGroups as $group)
                                    <option value="{{ $group->id }}">{{ $group->title }} ({{ $group->payroll_frequency }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Min Salary <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control" name="min_salary" id="min_salary" placeholder="0.00" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Max Salary <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control" name="max_salary" id="max_salary" placeholder="0.00" required>
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
                        <button type="submit" class="btn rounded-pill px-4 text-white" id="btnSavePayScale" style="background-color: #974063;">Save Changes</button>
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
    const container = document.getElementById('payScaleContainer');
    const searchInput = document.getElementById('searchKeyword');
    const modal = new bootstrap.Modal(document.getElementById('payScaleModal'));
    const form = document.getElementById('payScaleForm');

    // Initial Load
    fetchPayScales();

    // Search Logic
    let searchTimer;
    searchInput.addEventListener('input', () => {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(fetchPayScales, 500);
    });

    function fetchPayScales(url = "{{ route('pay_scales.index') }}") {
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
        document.querySelectorAll('.edit-pay-scale').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                axios.get(`/company-setup/pay-scales/${id}/edit`)
                    .then(response => {
                        const data = response.data.data;
                        document.getElementById('payScaleId').value = data.id;
                        document.getElementById('grade_id').value = data.grade_id;
                        document.getElementById('pay_group_id').value = data.pay_group_id;
                        document.getElementById('min_salary').value = data.min_salary;
                        document.getElementById('max_salary').value = data.max_salary;
                        document.getElementById('status').value = data.status;
                        
                        document.getElementById('payScaleModalLabel').innerText = 'Edit Pay Scale';
                        modal.show();
                    });
            });
        });

        // Delete Button
        document.querySelectorAll('.delete-pay-scale').forEach(btn => {
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
                        axios.delete(`/company-setup/pay-scales/${id}/delete`, {
                            data: { _token: "{{ csrf_token() }}" }
                        }).then(response => {
                            Swal.fire('Deleted!', response.data.message, 'success');
                            fetchPayScales();
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
                fetchPayScales(this.getAttribute('href'));
            });
        });
    }

    document.getElementById('btnCreatePayScale').addEventListener('click', () => {
        form.reset();
        document.getElementById('payScaleId').value = '';
        document.getElementById('payScaleModalLabel').innerText = 'Add Pay Scale';
        modal.show();
    });

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('payScaleId').value;
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());
        
        const url = id ? `/company-setup/pay-scales/${id}/update` : "{{ route('pay_scales.store') }}";
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
            fetchPayScales();
        }).catch(error => {
            console.error(error);
            const msg = error.response?.data?.message || 'Something went wrong';
            Swal.fire('Error!', msg, 'error');
        });
    });
});
</script>
@endpush
