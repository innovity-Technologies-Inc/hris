@extends('structure.master')

@section('content')
    <!-- Analytics Header Row -->
    <div class="row mb-4">
        <!-- Metric Cards -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-primary shadow-sm h-100 bg-primary bg-opacity-10 text-primary-emphasis">
                <div class="card-body d-flex flex-column justify-content-center align-items-center py-4">
                    <i class="bi bi-file-earmark-person fs-1 mb-2 text-primary"></i>
                    <h3 class="mb-1 fw-bold" id="metricTotalCvs">0</h3>
                    <p class="mb-0 text-muted small fw-semibold text-uppercase">Total CVs Saved</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-success shadow-sm h-100 bg-success bg-opacity-10 text-success-emphasis">
                <div class="card-body d-flex flex-column justify-content-center align-items-center py-4">
                    <i class="bi bi-star-fill fs-1 mb-2 text-success"></i>
                    <h3 class="mb-1 fw-bold" id="metricAvgScore">0.0</h3>
                    <p class="mb-0 text-muted small fw-semibold text-uppercase">Average CV Score</p>
                </div>
            </div>
        </div>
        <!-- Charts -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card shadow-sm h-100 border">
                <div class="card-header bg-transparent py-2 border-0">
                    <span class="small fw-bold text-muted text-uppercase">Career Level</span>
                </div>
                <div class="card-body py-2 d-flex justify-content-center align-items-center" style="position: relative; height: 120px;">
                    <canvas id="careerChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card shadow-sm h-100 border">
                <div class="card-header bg-transparent py-2 border-0">
                    <span class="small fw-bold text-muted text-uppercase">CV Score Range</span>
                </div>
                <div class="card-body py-2 d-flex justify-content-center align-items-center" style="position: relative; height: 120px;">
                    <canvas id="scoreChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- CV Bank Grid -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-semibold text-dark">
                        <i class="bi bi-folder2-open me-2 text-warning"></i>CV Bank Registry
                    </h5>
                    @can('cv-bank.create')
                        <a href="{{ route('cv_bank.create') }}" class="btn btn-warning btn-sm">
                            <i style="height: 12px; width: 12px" data-feather="plus"></i> Add CVs
                        </a>
                    @endcan
                </div>

                <div class="card-body">
                    <!-- Filters Form -->
                    <!-- Filters Form -->
                    <form id="filterForm" class="mb-4">
                        <div class="row g-3">
                            <div class="col-lg-3 col-md-6 col-12">
                                <label class="form-label small fw-semibold text-muted mb-1">Search Keyword</label>
                                <div class="input-group">
                                    <input type="text" class="form-control border-end-0" id="searchKeyword"
                                           name="keyword" placeholder="Search by name, company, designation...">
                                    <span class="input-group-text border-start-0 bg-white">
                                        <i class="bi bi-search text-muted"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-3 col-sm-6 col-12">
                                <label class="form-label small fw-semibold text-muted mb-1">Company</label>
                                <select name="company_name" id="filterCompany" class="form-select">
                                    <option value="">All Companies</option>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->name }}">{{ $company->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-3 col-sm-6 col-12">
                                <label class="form-label small fw-semibold text-muted mb-1">Designation</label>
                                <select name="designation" id="filterDesignation" class="form-select">
                                    <option value="">All Designations</option>
                                    @foreach($designations as $designation)
                                        <option value="{{ $designation->company_designation }}">{{ $designation->company_designation }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                                <label class="form-label small fw-semibold text-muted mb-1">Career Level</label>
                                <select name="career_level" id="filterCareerLevel" class="form-select">
                                    <option value="">All Levels</option>
                                    <option value="Entry">Entry</option>
                                    <option value="Mid">Mid</option>
                                    <option value="Senior">Senior</option>
                                    <option value="Executive">Executive</option>
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-8 col-sm-6 col-12">
                                <label class="form-label small fw-semibold text-muted mb-1">Score Range</label>
                                <div class="input-group">
                                    <input type="number" min="0" max="100" class="form-control" id="filterMinScore" name="min_score" placeholder="Min">
                                    <span class="input-group-text bg-light text-muted small">-</span>
                                    <input type="number" min="0" max="100" class="form-control" id="filterMaxScore" name="max_score" placeholder="Max">
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Table Container -->
                    <div class="table-responsive" id="logContainer">
                        <div class="text-center py-4 text-muted">Loading CV records...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('logContainer');
    const searchInput = document.getElementById('searchKeyword');
    const companySelect = document.getElementById('filterCompany');
    const designationSelect = document.getElementById('filterDesignation');
    const careerLevelSelect = document.getElementById('filterCareerLevel');
    const minScoreInput = document.getElementById('filterMinScore');
    const maxScoreInput = document.getElementById('filterMaxScore');

    let careerChartInstance = null;
    let scoreChartInstance = null;

    // Load table data and analytics pings
    fetchLogs();
    fetchAnalytics();

    // Event Listeners for Filters
    let filterTimer;
    const triggerSearch = () => {
        clearTimeout(filterTimer);
        filterTimer = setTimeout(fetchLogs, 400);
    };

    searchInput.addEventListener('input', triggerSearch);
    companySelect.addEventListener('change', triggerSearch);
    designationSelect.addEventListener('change', triggerSearch);
    careerLevelSelect.addEventListener('change', triggerSearch);
    minScoreInput.addEventListener('input', triggerSearch);
    maxScoreInput.addEventListener('input', triggerSearch);

    function fetchLogs(url = "{{ route('cv_bank.index') }}") {
        const keyword = searchInput.value;
        const company = companySelect.value;
        const designation = designationSelect.value;
        const level = careerLevelSelect.value;
        const min = minScoreInput.value;
        const max = maxScoreInput.value;

        const fullUrl = `${url}${url.includes('?') ? '&' : '?'}keyword=${encodeURIComponent(keyword)}&company_name=${encodeURIComponent(company)}&designation=${encodeURIComponent(designation)}&career_level=${encodeURIComponent(level)}&min_score=${encodeURIComponent(min)}&max_score=${encodeURIComponent(max)}`;

        axios.get(fullUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(response => {
                container.innerHTML = response.data;
                if (window.feather) {
                    feather.replace();
                }
            })
            .catch(error => {
                console.error(error);
                container.innerHTML = '<div class="text-danger text-center py-4">Failed to load CV records.</div>';
            });
    }

    function fetchAnalytics() {
        axios.get("{{ route('cv_bank.analytics') }}")
            .then(response => {
                if (response.data.success) {
                    const data = response.data.data;
                    document.getElementById('metricTotalCvs').textContent = data.total_cvs;
                    document.getElementById('metricAvgScore').textContent = data.average_score;
                    renderCharts(data);
                }
            })
            .catch(error => console.error('Failed to load CV Bank analytics:', error));
    }

    function renderCharts(data) {
        // Destroy old instances
        if (careerChartInstance) careerChartInstance.destroy();
        if (scoreChartInstance) scoreChartInstance.destroy();

        // 1. Career Level Bar Chart
        const ctxCareer = document.getElementById('careerChart').getContext('2d');
        careerChartInstance = new Chart(ctxCareer, {
            type: 'bar',
            data: {
                labels: data.career_level.labels,
                datasets: [{
                    label: 'Count',
                    data: data.career_level.values,
                    backgroundColor: '#3b82f6',
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false } },
                    y: { ticks: { stepSize: 1 }, grid: { display: false } }
                }
            }
        });

        // 2. Score Range Pie Chart
        const ctxScore = document.getElementById('scoreChart').getContext('2d');
        scoreChartInstance = new Chart(ctxScore, {
            type: 'pie',
            data: {
                labels: data.score_ranges.labels,
                datasets: [{
                    data: data.score_ranges.values,
                    backgroundColor: ['#f87171', '#fbbf24', '#34d399', '#60a5fa']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: { boxWidth: 10, font: { size: 9 } }
                    }
                }
            }
        });
    }

    // Delete CV via Axios
    $(document).on('click', '.delete-cv', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this CV record!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                axios.delete(`/cv-bank/${id}`, {
                    data: { _token: "{{ csrf_token() }}" }
                }).then(response => {
                    Swal.fire('Deleted!', response.data.message, 'success');
                    fetchLogs();
                    fetchAnalytics();
                }).catch(error => {
                    const errMsg = error.response && error.response.data && error.response.data.message
                        ? error.response.data.message
                        : 'Deletion failed';
                    Swal.fire('Error!', errMsg, 'error');
                });
            }
        });
    });

    // Pagination Click handler
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        fetchLogs($(this).attr('href'));
    });
});
</script>
@endpush
