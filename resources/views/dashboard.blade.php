@extends('structure.master')
@section('content')
    <style>
        .dashboard-bg {
            background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);
            min-height: calc(100vh - 100px);
        }

        [data-bs-theme=dark] .dashboard-bg {
            background: linear-gradient(135deg, #1a1d20 0%, #2c3034 100%);
        }

        .header-card {
            background: white;
            border: 2px solid var(--primary-color);
        }

        [data-bs-theme=dark] .header-card {
            background: var(--bs-body-bg);
            border-color: var(--bs-dashboard-accent);
        }

        .header-title {
            color: var(--primary-color);
            letter-spacing: 1px;
        }

        [data-bs-theme=dark] .header-title {
            color: var(--bs-dashboard-accent);
        }

        .header-subtitle {
            color: var(--primary-color);
            opacity: 0.8;
        }

        [data-bs-theme=dark] .header-subtitle {
            color: var(--bs-dashboard-accent);
            opacity: 0.8;
        }

        .stat-card {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border-left: 5px solid var(--primary-color);
            cursor: pointer;
            animation: fadeInUp 0.6s ease forwards;
            background: white;
        }

        [data-bs-theme=dark] .stat-card {
            background: var(--bs-body-bg);
            border-left-color: var(--bs-dashboard-accent);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 150px;
            height: 150px;
            opacity: 0.06;
            background: var(--primary-color);
            transform: translate(30px, -30px) rotate(15deg);
            transition: all 0.4s ease;
        }

        [data-bs-theme=dark] .stat-card::before {
            background: var(--bs-dashboard-accent);
        }

        .stat-card::after {
            content: '→';
            position: absolute;
            bottom: 1rem;
            right: 1rem;
            font-size: 1.5rem;
            opacity: 0;
            transition: all 0.3s ease;
            font-weight: bold;
            color: var(--primary-color);
        }

        [data-bs-theme=dark] .stat-card::after {
            color: var(--bs-dashboard-accent);
        }

        .stat-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15) !important;
            background: var(--primary-color) !important;
            border-left-color: #000000;
        }

        [data-bs-theme=dark] .stat-card:hover {
            background: var(--bs-dashboard-accent) !important;
            border-left-color: #000000;
        }

        .stat-card:hover::before {
            transform: translate(20px, -20px) rotate(25deg);
            opacity: 0.1;
            background: white;
        }

        .stat-card:hover::after {
            opacity: 0.8;
            right: 0.5rem;
            color: white !important;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            transition: all 0.3s ease;
            background: rgba(var(--primary-color-rgb), 0.12);
            color: var(--primary-color);
        }

        [data-bs-theme=dark] .stat-icon {
            background: rgba(0, 0, 0, 0.3);
            color: var(--bs-dashboard-accent);
        }

        .stat-card:hover .stat-icon {
            transform: scale(1.1) rotate(5deg);
            background: rgba(255, 255, 255, 0.2) !important;
            color: white !important;
        }

        .stat-label {
            letter-spacing: 0.8px;
            transition: all 0.3s ease;
            color: var(--primary-color);
        }

        [data-bs-theme=dark] .stat-label {
            color: var(--bs-dashboard-accent);
        }

        .stat-card:hover .stat-label {
            color: white !important;
        }

        .stat-value {
            transition: all 0.3s ease;
            color: #2c3e50;
        }

        [data-bs-theme=dark] .stat-value {
            color: var(--bs-body-color);
        }

        .stat-card:hover .stat-value {
            transform: scale(1.05);
            color: white !important;
        }

        .stat-footer {
            color: var(--primary-color) !important;
            transition: all 0.3s ease;
        }

        [data-bs-theme=dark] .stat-footer {
            color: var(--bs-dashboard-accent) !important;
        }

        .stat-card:hover .stat-footer,
        .stat-card:hover .stat-footer i,
        .stat-card:hover .stat-footer span {
            color: white !important;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stat-card:nth-child(1) { animation-delay: 0.1s; }
        .stat-card:nth-child(2) { animation-delay: 0.2s; }
        .stat-card:nth-child(3) { animation-delay: 0.3s; }
        .stat-card:nth-child(4) { animation-delay: 0.4s; }
        .stat-card:nth-child(5) { animation-delay: 0.5s; }
        .stat-card:nth-child(6) { animation-delay: 0.6s; }
    </style>

    <div class="dashboard-bg p-4">
        <div class="container-fluid">
            <div class="header-card text-center rounded-3 shadow mb-4 py-3 px-4">
                <h1 class="header-title h3 fw-bold mb-1">
                    {{ App\HelperClass::getGeneralSetting()?->name ?? 'HRMS' }}
                </h1>
                <p class="header-subtitle small mb-0">
                    Human Resource Information System Dashboard
                </p>
            </div>

            <div class="row g-4 mt-2">
                {{-- Companies --}}
                <div class="col-12 col-md-6 col-lg-3">
                    <a href="{{ route('companies.index') }}" class="text-decoration-none d-block">        
                        <div class="stat-card companies rounded-3 shadow p-3 position-relative overflow-hidden">
                            <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center mb-2 fs-4">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="stat-label text-uppercase small fw-semibold mb-2">Total Companies</div>
                            <div class="stat-value h2 fw-bold text-dark mb-0">{{ $stats['companies'] ?? 0 }}</div>
                            <div class="mt-2 pt-2 border-top d-flex align-items-center gap-2 small stat-footer">
                                <i class="fas fa-chart-line"></i><span>View all companies</span>
                            </div>
                        </div>
                    </a>
                </div>

                {{-- Business Units --}}
                <div class="col-12 col-md-6 col-lg-3">
                    <a href="{{ route('company_locations.index') }}" class="text-decoration-none d-block">
                        <div class="stat-card business-units rounded-3 shadow p-3 position-relative overflow-hidden">
                            <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center mb-2 fs-4">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="stat-label text-uppercase small fw-semibold mb-2">Business Units</div>
                            <div class="stat-value h2 fw-bold text-dark mb-0">{{ $stats['business_units'] ?? 0 }}</div>
                            <div class="mt-2 pt-2 border-top d-flex align-items-center gap-2 small stat-footer">
                                <i class="fas fa-chart-line"></i><span>View all locations</span>
                            </div>
                        </div>
                    </a>
                </div>

                {{-- Departments --}}
                <div class="col-12 col-md-6 col-lg-3">
                    <a href="{{ route('departments.index') }}" class="text-decoration-none d-block">      
                        <div class="stat-card departments rounded-3 shadow p-3 position-relative overflow-hidden">
                            <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center mb-2 fs-4">
                                <i class="fas fa-sitemap"></i>
                            </div>
                            <div class="stat-label text-uppercase small fw-semibold mb-2">Departments</div>
                            <div class="stat-value h2 fw-bold text-dark mb-0">{{ $stats['departments'] ?? 0 }}</div>
                            <div class="mt-2 pt-2 border-top d-flex align-items-center gap-2 small stat-footer">
                                <i class="fas fa-chart-line"></i><span>View all departments</span>
                            </div>
                        </div>
                    </a>
                </div>

                {{-- Sections --}}
                <div class="col-12 col-md-6 col-lg-3">
                    <a href="{{ route('sections.index') }}" class="text-decoration-none d-block">
                        <div class="stat-card sections rounded-3 shadow p-3 position-relative overflow-hidden">
                            <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center mb-2 fs-4">
                                <i class="fas fa-layer-group"></i>
                            </div>
                            <div class="stat-label text-uppercase small fw-semibold mb-2">Sections</div>
                            <div class="stat-value h2 fw-bold text-dark mb-0">{{ $stats['sections'] ?? 0 }}</div>
                            <div class="mt-2 pt-2 border-top d-flex align-items-center gap-2 small stat-footer">
                                <i class="fas fa-chart-line"></i><span>View all sections</span>
                            </div>
                        </div>
                    </a>
                </div>

                {{-- Divisions --}}
                <div class="col-12 col-md-6 col-lg-3">
                    <a href="{{ route('divisions.index') }}" class="text-decoration-none d-block">        
                        <div class="stat-card divisions rounded-3 shadow p-3 position-relative overflow-hidden">
                            <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center mb-2 fs-4">
                                <i class="fas fa-project-diagram"></i>
                            </div>
                            <div class="stat-label text-uppercase small fw-semibold mb-2">Divisions</div>
                            <div class="stat-value h2 fw-bold text-dark mb-0">{{ $stats['divisions'] ?? 0 }}</div>
                            <div class="mt-2 pt-2 border-top d-flex align-items-center gap-2 small stat-footer">
                                <i class="fas fa-chart-line"></i><span>View all divisions</span>
                            </div>
                        </div>
                    </a>
                </div>

                {{-- Employees --}}
                <div class="col-12 col-md-6 col-lg-3">
                    <a href="{{ route('employees.index') }}" class="text-decoration-none d-block">        
                        <div class="stat-card employees rounded-3 shadow p-3 position-relative overflow-hidden">
                            <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center mb-2 fs-4">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="stat-label text-uppercase small fw-semibold mb-2">Total Employees</div>
                            <div class="stat-value h2 fw-bold text-dark mb-0">{{ $stats['employees'] ?? 0 }}</div>
                            <div class="mt-2 pt-2 border-top d-flex align-items-center gap-2 small stat-footer">
                                <i class="fas fa-chart-line"></i><span>View all employees</span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
