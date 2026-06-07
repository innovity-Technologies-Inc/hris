@extends('structure.master')

@section('content')
    {{-- List of Salary Grades --}}
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    @can('salary-grades.create')
                    <button type="button" class="btn btn-warning btn-sm" id="btnCreateSalaryGrade">
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
                                           name="keyword" placeholder="Search by grade code, name or act"
                                           aria-label="Keyword Search">
                                    <span class="input-group-text border-start-0 input-group-bg">
                                        <i class="mdi mdi-magnify text-muted"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive" id="salaryGradeContainer">
                        <div class="text-center py-4 text-muted">Loading Data...</div>
                    </div>
                </div>
            </div><!-- end card -->
        </div>
    </div><!-- end row -->

    {{-- Salary Grade Modal --}}
    <div class="modal fade" id="salaryGradeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header text-white border-0" style="background-color: #974063;">
                    <h5 class="modal-title" id="salaryGradeModalLabel">Add Salary Grade</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="salaryGradeForm">
                    @csrf
                    <input type="hidden" id="salaryGradeId" name="id">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Grade Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="grade_code" id="grade_code" placeholder="e.g. G1" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Grade Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="grade_name" id="grade_name" placeholder="Enter Grade Name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Act (Tofsil) <span class="text-danger">*</span></label>
                            <select class="form-select select2_list" name="tofsil_id" id="tofsil_id" required>
                                <option value="">Choose Act</option>
                                @foreach($tofsils as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
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
                        <button type="submit" class="btn rounded-pill px-4 text-white" id="btnSaveSalaryGrade" style="background-color: #974063;">Save Changes</button>
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
    const container = document.getElementById('salaryGradeContainer');
    const searchInput = document.getElementById('searchKeyword');
    const modal = new bootstrap.Modal(document.getElementById('salaryGradeModal'));
    const form = document.getElementById('salaryGradeForm');

    // Initial Load
    fetchSalaryGrades();

    // Search Logic
    let searchTimer;
    searchInput.addEventListener('input', () => {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(fetchSalaryGrades, 500);
    });

    function fetchSalaryGrades(url = "{{ route('salary_grades.index') }}") {
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
        document.querySelectorAll('.edit-salary-grade').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                axios.get(`/company-setup/salary_grades/${id}/edit`)
                    .then(response => {
                        const data = response.data.data;
                        document.getElementById('salaryGradeId').value = data.id;
                        document.getElementById('grade_code').value = data.grade_code;
                        document.getElementById('grade_name').value = data.grade_name;
                        document.getElementById('tofsil_id').value = data.tofsil_id;
                        document.getElementById('status').value = data.status;
                        
                        document.getElementById('salaryGradeModalLabel').innerText = 'Edit Salary Grade';
                        modal.show();
                    });
            });
        });

        // Delete Button
        document.querySelectorAll('.delete-salary-grade').forEach(btn => {
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
                        axios.delete(`/company-setup/salary_grades/${id}/delete`, {
                            data: { _token: "{{ csrf_token() }}" }
                        }).then(response => {
                            Swal.fire('Deleted!', response.data.message, 'success');
                            fetchSalaryGrades();
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
                fetchSalaryGrades(this.getAttribute('href'));
            });
        });
    }

    document.getElementById('btnCreateSalaryGrade').addEventListener('click', () => {
        form.reset();
        document.getElementById('salaryGradeId').value = '';
        document.getElementById('salaryGradeModalLabel').innerText = 'Add Salary Grade';
        modal.show();
    });

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('salaryGradeId').value;
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());
        
        const url = id ? `/company-setup/salary_grades/${id}/update` : "{{ route('salary_grades.store') }}";
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
            fetchSalaryGrades();
        }).catch(error => {
            console.error(error);
            const msg = error.response?.data?.message || 'Something went wrong';
            Swal.fire('Error!', msg, 'error');
        });
    });
});
</script>
@endpush
