@extends('structure.master')

@section('content')
    <div class="row">
        {{-- Service Analysis Summary Cards --}}
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm border-0 rounded-3 h-100 bg-glass">
                <div class="card-body p-4 text-center">
                    <div class="avatar-md mx-auto mb-3">
                        <div class="avatar-title bg-soft-primary text-primary rounded-circle fs-24">
                            <i class="mdi mdi-account-group"></i>
                        </div>
                    </div>
                    <h4 class="fw-bold mb-1">{{ $serviceSummary['total'] }}</h4>
                    <p class="text-muted mb-0">Total Employees</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm border-0 rounded-3 h-100 bg-glass">
                <div class="card-body p-4 text-center">
                    <div class="avatar-md mx-auto mb-3">
                        <div class="avatar-title bg-soft-success text-success rounded-circle fs-24">
                            <i class="mdi mdi-account-check"></i>
                        </div>
                    </div>
                    <h4 class="fw-bold mb-1">{{ $serviceSummary['active'] }}</h4>
                    <p class="text-muted mb-0">Active Employees</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm border-0 rounded-3 h-100 bg-glass">
                <div class="card-body p-4 text-center">
                    <div class="avatar-md mx-auto mb-3">
                        <div class="avatar-title bg-soft-info text-info rounded-circle fs-24">
                            <i class="mdi mdi-account-plus"></i>
                        </div>
                    </div>
                    <h4 class="fw-bold mb-1">{{ $serviceSummary['new_joinees'] }}</h4>
                    <p class="text-muted mb-0">New Joiners (Month)</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm border-0 rounded-3 h-100 bg-glass">
                <div class="card-body p-4 text-center">
                    <div class="avatar-md mx-auto mb-3">
                        <div class="avatar-title bg-soft-warning text-warning rounded-circle fs-24">
                            <i class="mdi mdi-clock-fast"></i>
                        </div>
                    </div>
                    <h4 class="fw-bold mb-1">{{ $serviceSummary['avg_tenure'] }}y</h4>
                    <p class="text-muted mb-0">Avg. Tenure</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Age Analysis Section --}}
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0 rounded-3 h-100">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0">
                        <i class="mdi mdi-chart-donut text-primary me-2"></i> Age Distribution
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div style="height: 220px;">
                        <canvas id="ageDistChart"></canvas>
                    </div>
                    <div class="row text-center mt-4 pt-2 border-top">
                        <div class="col-4 border-end">
                            <p class="text-muted mb-1 small">Average</p>
                            <h5 class="mb-0 fw-bold text-primary">{{ $ageStats['avg'] }}y</h5>
                        </div>
                        <div class="col-4 border-end">
                            <p class="text-muted mb-1 small">Min</p>
                            <h5 class="mb-0 fw-bold text-success">{{ $ageStats['min'] }}y</h5>
                        </div>
                        <div class="col-4">
                            <p class="text-muted mb-1 small">Max</p>
                            <h5 class="mb-0 fw-bold text-danger">{{ $ageStats['max'] }}y</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Service Loyalty Chart --}}
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0 rounded-3 h-100">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0">
                        <i class="mdi mdi-chart-bar text-success me-2"></i> Years of Service
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div style="height: 220px;">
                        <canvas id="loyaltyChart"></canvas>
                    </div>
                    <p class="text-muted small text-center mt-4 mb-0">Distribution of employee tenure across the organization.</p>
                </div>
            </div>
        </div>

        {{-- Company Distribution Chart --}}
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0 rounded-3 h-100">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0">
                        <i class="mdi mdi-office-building text-info me-2"></i> Company Distribution
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div style="height: 220px;">
                        <canvas id="companyDistChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Dynamic Hierarchy Breakdown --}}
        @foreach($dynamicHierarchies as $key => $hierarchy)
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm border-0 rounded-3 h-100">
                    <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">
                            <i class="mdi {{ $hierarchy['icon'] }} text-{{ $hierarchy['color'] }} me-2"></i> {{ $hierarchy['title'] }}
                        </h5>
                    </div>
                    <div class="card-body p-4 pt-0">
                        {{-- Dynamic Filters --}}
                        <div class="row g-2 mb-4 mt-2 filter-container" data-chart-key="{{ $key }}">
                            {{-- Company Filter --}}
                            <div class="col">
                                <select class="form-select form-select-sm filter-company">
                                    @if(count($filterOptions['companies']) > 1 || auth()->user()->user_type === 'Group')
                                        <option value="">All Companies</option>
                                    @endif
                                    @foreach($filterOptions['companies'] as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            @if(in_array($key, ['division', 'department', 'section']))
                                <div class="col">
                                    <select class="form-select form-select-sm filter-bu">
                                        @if(count($filterOptions['businessUnits']) > 1 || auth()->user()->user_type === 'Group')
                                            <option value="">All Business Units</option>
                                        @endif
                                        @foreach($filterOptions['businessUnits'] as $id => $name)
                                            <option value="{{ $id }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            @if(in_array($key, ['department', 'section']))
                                <div class="col">
                                    <select class="form-select form-select-sm filter-division">
                                        @if(count($filterOptions['divisions']) > 1 || auth()->user()->user_type === 'Group')
                                            <option value="">All Divisions</option>
                                        @endif
                                        @foreach($filterOptions['divisions'] as $id => $name)
                                            <option value="{{ $id }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            @if($key === 'section')
                                <div class="col">
                                    <select class="form-select form-select-sm filter-department">
                                        @if(count($filterOptions['departments']) > 1 || auth()->user()->user_type === 'Group')
                                            <option value="">All Departments</option>
                                        @endif
                                        @foreach($filterOptions['departments'] as $id => $name)
                                            <option value="{{ $id }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                        </div>

                        <div style="height: 250px; position: relative;">
                            <canvas id="{{ $key }}Chart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row">
        {{-- Upcoming Birthdays List --}}
        <div class="col-lg-12 mb-4">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">
                        <i class="mdi mdi-cake-variant text-danger me-2"></i> Upcoming Birthdays (Current Month)
                    </h5>
                    <span class="badge bg-soft-danger text-danger px-3 rounded-pill">{{ count($birthdays) }} This Month</span>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Employee</th>
                                    <th>Birthday Date</th>
                                    <th>Upcoming Age</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($birthdays as $birthday)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($birthday['photo'])
                                                    <img src="{{ $birthday['photo'] }}" alt="" class="rounded-circle me-3" width="35" height="35">
                                                @else
                                                    <div class="avatar-xs me-3">
                                                        <span class="avatar-title bg-soft-primary text-primary rounded-circle">
                                                            {{ substr($birthday['full_name'], 0, 1) }}
                                                        </span>
                                                    </div>
                                                @endif
                                                <span class="fw-semibold">{{ $birthday['full_name'] }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-muted"><i class="mdi mdi-calendar-star me-1 text-danger"></i> {{ $birthday['date'] }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-soft-info text-info">{{ $birthday['age_upcoming'] }} years</span>
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('employee.profile.general_informations', $birthday['id']) }}" class="btn btn-sm btn-light rounded-pill px-3">
                                                View Profile
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">No birthdays this month.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        [data-bs-theme=dark] .bg-glass {
            background: rgba(45, 55, 72, 0.7);
        }
        .bg-soft-purple { background-color: rgba(111, 66, 193, 0.15); }
        .text-purple { color: #6f42c1; }
    </style>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const commonOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            usePointStyle: true,
                            font: { size: 11 }
                        }
                    }
                }
            };

            const chartColors = [
                'rgba(52, 152, 219, 0.7)',
                'rgba(46, 204, 113, 0.7)',
                'rgba(155, 89, 182, 0.7)',
                'rgba(241, 196, 15, 0.7)',
                'rgba(231, 76, 60, 0.7)',
                'rgba(52, 73, 94, 0.7)',
                'rgba(26, 188, 156, 0.7)',
                'rgba(230, 126, 34, 0.7)'
            ];

            // Age Distribution Chart
            const ageCtx = document.getElementById('ageDistChart').getContext('2d');
            new Chart(ageCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($ageDist['labels']) !!},
                    datasets: [{
                        data: {!! json_encode($ageDist['data']) !!},
                        backgroundColor: chartColors,
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    ...commonOptions,
                    cutout: '70%'
                }
            });

            // Loyalty Chart
            const loyaltyCtx = document.getElementById('loyaltyChart').getContext('2d');
            new Chart(loyaltyCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($loyaltyDist['labels']) !!},
                    datasets: [{
                        label: 'Employees',
                        data: {!! json_encode($loyaltyDist['data']) !!},
                        backgroundColor: 'rgba(46, 204, 113, 0.6)',
                        borderColor: 'rgb(46, 204, 113)',
                        borderWidth: 1,
                        borderRadius: 6
                    }]
                },
                options: {
                    ...commonOptions,
                    scales: {
                        y: { beginAtZero: true, ticks: { precision: 0 } }
                    }
                }
            });

            // Company Distribution Chart
            const companyCtx = document.getElementById('companyDistChart').getContext('2d');
            new Chart(companyCtx, {
                type: 'pie',
                data: {
                    labels: {!! json_encode($companyDist['labels']) !!},
                    datasets: [{
                        data: {!! json_encode($companyDist['data']) !!},
                        backgroundColor: chartColors,
                        borderWidth: 0
                    }]
                },
                options: commonOptions
            });

            // Dynamic Hierarchy Charts
            const dynamicHierarchies = {!! json_encode($dynamicHierarchies) !!};
            const hierarchyCharts = {};
            
            Object.keys(dynamicHierarchies).forEach(key => {
                const hierarchy = dynamicHierarchies[key];
                const ctx = document.getElementById(key + 'Chart');
                
                if (ctx) {
                    let chartConfig = {
                        type: hierarchy.chartType,
                        data: {
                            labels: hierarchy.data.labels,
                            datasets: [{
                                label: 'Employees',
                                data: hierarchy.data.data,
                                backgroundColor: hierarchy.chartType === 'bar' ? 'rgba(52, 152, 219, 0.6)' : chartColors,
                                borderColor: hierarchy.chartType === 'bar' ? 'rgb(52, 152, 219)' : 'transparent',
                                borderWidth: hierarchy.chartType === 'bar' ? 1 : 0,
                                borderRadius: hierarchy.chartType === 'bar' ? 6 : 0
                            }]
                        },
                        options: { ...commonOptions }
                    };

                    if (hierarchy.chartType === 'bar') {
                        chartConfig.options.scales = {
                            y: { beginAtZero: true, ticks: { precision: 0 } }
                        };
                    }

                    hierarchyCharts[key] = new Chart(ctx.getContext('2d'), chartConfig);
                }
            });

            // Handle Filter Changes
            document.querySelectorAll('.filter-container select').forEach(select => {
                select.addEventListener('change', function() {
                    const container = this.closest('.filter-container');
                    const chartKey = container.getAttribute('data-chart-key');
                    
                    const company_id = container.querySelector('.filter-company')?.value || '';
                    const business_unit_id = container.querySelector('.filter-bu')?.value || '';
                    const division_id = container.querySelector('.filter-division')?.value || '';
                    const department_id = container.querySelector('.filter-department')?.value || '';

                    // Show loading state on canvas wrapper if needed
                    const baseUrl = "{{ route('employee.reports.filtered_data', ['type' => ':type']) }}";
                    const url = baseUrl.replace(':type', chartKey);

                    axios.get(url, {
                        params: { company_id, business_unit_id, division_id, department_id }
                    })
                    .then(response => {
                        const data = response.data;
                        if (hierarchyCharts[chartKey]) {
                            hierarchyCharts[chartKey].data.labels = data.labels;
                            hierarchyCharts[chartKey].data.datasets[0].data = data.data;
                            hierarchyCharts[chartKey].update();
                        }
                    })
                    .catch(error => console.error('Error fetching filtered data:', error));
                });
            });
        });
    </script>
@endpush
