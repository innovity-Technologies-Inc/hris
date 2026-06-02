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
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0 rounded-3 h-100">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0">
                        <i class="mdi mdi-chart-donut text-primary me-2"></i> Age Distribution
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div style="height: 350px;">
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
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0 rounded-3 h-100">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0">
                        <i class="mdi mdi-chart-bar text-success me-2"></i> Years of Service
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div style="height: 350px;">
                        <canvas id="loyaltyChart"></canvas>
                    </div>
                    <p class="text-muted small text-center mt-4 mb-0">Distribution of employee tenure across the organization.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Drill-Down Analytics Section --}}
        <div class="col-lg-12 mb-4">
            <div class="card shadow-sm border-0 rounded-3 h-100">
                <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0" id="drillDownTitle">
                        <i class="mdi mdi-chart-bar text-primary me-2"></i> Company-wise Distribution
                    </h5>
                    <button class="btn btn-sm btn-outline-secondary d-none" id="btnBackDrillDown">
                        <i class="mdi mdi-arrow-left me-1"></i> Back
                    </button>
                </div>
                <div class="card-body p-4">
                    <div style="height: 450px; position: relative;">
                        <canvas id="drillDownChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
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
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
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

            // Drill-Down Chart Implementation
            const drillDownCtx = document.getElementById('drillDownChart');
            let drillDownChartInstance = null;
            
            // State tracking
            let drillDownHistory = []; // Array of objects: { level, parentId, title }
            const defaultLevel = 'company';

            const btnBack = document.getElementById('btnBackDrillDown');
            const titleEl = document.getElementById('drillDownTitle');

            // Dynamic colors based on hierarchy level (using different opacities for gender stacks)
            const levelColors = {
                'company': { 
                    male: 'rgba(52, 152, 219, 0.8)', female: 'rgba(52, 152, 219, 0.5)', other: 'rgba(52, 152, 219, 0.2)',
                    border: 'rgb(52, 152, 219)'
                }, // Blue
                'business_unit': { 
                    male: 'rgba(46, 204, 113, 0.8)', female: 'rgba(46, 204, 113, 0.5)', other: 'rgba(46, 204, 113, 0.2)',
                    border: 'rgb(46, 204, 113)' 
                }, // Green
                'division': { 
                    male: 'rgba(155, 89, 182, 0.8)', female: 'rgba(155, 89, 182, 0.5)', other: 'rgba(155, 89, 182, 0.2)',
                    border: 'rgb(155, 89, 182)' 
                }, // Purple
                'department': { 
                    male: 'rgba(241, 196, 15, 0.8)', female: 'rgba(241, 196, 15, 0.5)', other: 'rgba(241, 196, 15, 0.2)',
                    border: 'rgb(241, 196, 15)' 
                }, // Yellow
                'section': { 
                    male: 'rgba(230, 126, 34, 0.8)', female: 'rgba(230, 126, 34, 0.5)', other: 'rgba(230, 126, 34, 0.2)',
                    border: 'rgb(230, 126, 34)' 
                } // Orange
            };

            function renderDrillDownChart(level, parentId = null) {
                axios.get('{{ route("employee.reports.drill_down") }}', {
                    params: { level: level, parent_id: parentId }
                }).then(response => {
                    const data = response.data;

                    titleEl.innerHTML = `<i class="mdi mdi-chart-bar text-primary me-2"></i> ${data.title}`;

                    if (drillDownHistory.length > 0) {
                        btnBack.classList.remove('d-none');
                    } else {
                        btnBack.classList.add('d-none');
                    }

                    if (drillDownChartInstance) {
                        drillDownChartInstance.destroy();
                    }

                    const colors = levelColors[level] || levelColors['company'];

                    drillDownChartInstance = new Chart(drillDownCtx.getContext('2d'), {
                        type: 'bar',
                        data: {
                            labels: data.labels,
                            datasets: data.datasets.map(ds => {
                                let bg;
                                if(ds.label === 'Male') bg = colors.male;
                                else if(ds.label === 'Female') bg = colors.female;
                                else bg = colors.other;
                                
                                return {
                                    label: ds.label,
                                    data: ds.data,
                                    backgroundColor: bg,
                                    borderColor: colors.border,
                                    borderWidth: 1,
                                    stack: ds.stack,
                                    borderRadius: 4
                                };
                            })
                        },
                        options: {
                            ...commonOptions,
                            scales: {
                                x: { stacked: true },
                                y: { stacked: true, beginAtZero: true, ticks: { precision: 0 } }
                            },
                            onClick: (event, elements) => {
                                if (elements.length > 0 && data.next_level) {
                                    const index = elements[0].index;
                                    const clickedId = data.ids[index];

                                    drillDownHistory.push({
                                        level: level,
                                        parentId: parentId,
                                        title: data.title
                                    });

                                    renderDrillDownChart(data.next_level, clickedId);
                                }
                            },
                            onHover: (event, chartElement) => {
                                event.native.target.style.cursor = chartElement[0] && data.next_level ? 'pointer' : 'default';
                            }
                        }
                    });
                }).catch(error => console.error('Error fetching drill-down data:', error));
            }

            // Initial Render
            renderDrillDownChart(defaultLevel);

            // Handle Back Button
            btnBack.addEventListener('click', function() {
                if (drillDownHistory.length > 0) {
                    const previousState = drillDownHistory.pop();
                    renderDrillDownChart(previousState.level, previousState.parentId);
                }
            });
        });
    </script>
@endpush
