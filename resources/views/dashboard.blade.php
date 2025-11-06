@extends('structure.master')
@section('content')
    <style>
        .dashboard-bg {
            background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);
            min-height: calc(100vh - 100px);
        }

        .header-gradient {
            background: var(--primary-color);
        }

        .header-title {
            letter-spacing: 1.5px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        .stat-card {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border-left: 5px solid;
            cursor: pointer;
            animation: fadeInUp 0.6s ease forwards;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 150px;
            height: 150px;
            opacity: 0.06;
            transform: translate(30px, -30px) rotate(15deg);
            transition: all 0.4s ease;
        }

        .stat-card::after {
            content: '→';
            position: absolute;
            bottom: 1.5rem;
            right: 1.5rem;
            font-size: 1.5rem;
            opacity: 0;
            transition: all 0.3s ease;
            font-weight: bold;
        }

        .stat-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15) !important;
        }

        .stat-card:hover::before {
            transform: translate(20px, -20px) rotate(25deg);
            opacity: 0.1;
        }

        .stat-card:hover::after {
            opacity: 0.6;
            right: 1rem;
        }

        .stat-card.companies {
            border-left-color: #2c3e50;
        }

        .stat-card.companies::before,
        .stat-card.companies::after {
            background: #2c3e50;
            color: #2c3e50;
        }

        .stat-card.business-units {
            border-left-color: #34495e;
        }

        .stat-card.business-units::before,
        .stat-card.business-units::after {
            background: #34495e;
            color: #34495e;
        }

        .stat-card.departments {
            border-left-color: #5d6d7e;
        }

        .stat-card.departments::before,
        .stat-card.departments::after {
            background: #5d6d7e;
            color: #5d6d7e;
        }

        .stat-card.sections {
            border-left-color: #7f8c8d;
        }

        .stat-card.sections::before,
        .stat-card.sections::after {
            background: #7f8c8d;
            color: #7f8c8d;
        }

        .stat-card.divisions {
            border-left-color: #95a5a6;
        }

        .stat-card.divisions::before,
        .stat-card.divisions::after {
            background: #95a5a6;
            color: #95a5a6;
        }

        .stat-card.employees {
            border-left-color: #17a2b8;
        }

        .stat-card.employees::before,
        .stat-card.employees::after {
            background: #17a2b8;
            color: #17a2b8;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            transition: all 0.3s ease;
        }

        .stat-card:hover .stat-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .stat-card.companies .stat-icon {
            background: rgba(44, 62, 80, 0.12);
            color: #2c3e50;
        }

        .stat-card.business-units .stat-icon {
            background: rgba(52, 73, 94, 0.12);
            color: #34495e;
        }

        .stat-card.departments .stat-icon {
            background: rgba(93, 109, 126, 0.12);
            color: #5d6d7e;
        }

        .stat-card.sections .stat-icon {
            background: rgba(127, 140, 141, 0.12);
            color: #7f8c8d;
        }

        .stat-card.divisions .stat-icon {
            background: rgba(149, 165, 166, 0.12);
            color: #95a5a6;
        }

        .stat-card.employees .stat-icon {
            background: rgba(23, 162, 184, 0.12);
            color: #17a2b8;
        }

        .stat-label {
            letter-spacing: 0.8px;
            transition: all 0.3s ease;
        }

        .stat-card:hover .stat-label {
            color: #495057 !important;
        }

        .stat-value {
            transition: all 0.3s ease;
        }

        .stat-card:hover .stat-value {
            transform: scale(1.05);
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

        .stat-card:nth-child(1) {
            animation-delay: 0.1s;
        }

        .stat-card:nth-child(2) {
            animation-delay: 0.2s;
        }

        .stat-card:nth-child(3) {
            animation-delay: 0.3s;
        }

        .stat-card:nth-child(4) {
            animation-delay: 0.4s;
        }

        .stat-card:nth-child(5) {
            animation-delay: 0.5s;
        }

        .stat-card:nth-child(6) {
            animation-delay: 0.6s;
        }
    </style>

    <div class="dashboard-bg p-4">
        <div class="container-fluid">
            <div
                class="header-gradient text-white text-center rounded-4 shadow-lg mb-4 py-5 px-4 position-relative overflow-hidden">
                <h1 class="header-title display-4 fw-bold mb-2 position-relative" style="z-index: 1;">
                    GenItech HRIS System
                </h1>
                <p class="lead fw-light mb-0 position-relative" style="z-index: 1; opacity: 0.95;">
                    Human Resource Information System Dashboard
                </p>
            </div>

            <div class="row g-4 mt-2">
                <div class="col-12 col-md-6 col-lg-4">
                    <a href="{{ route('companies.index') }}" class="text-decoration-none d-block">
                        <div class="stat-card companies bg-white rounded-3 shadow p-4 position-relative overflow-hidden">
                            <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center mb-3 fs-3">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="stat-label text-uppercase text-secondary small fw-semibold mb-2">
                                Total Companies
                            </div>
                            <div class="stat-value display-4 fw-bold text-dark" style="line-height: 1;">
                                {{ $stats['companies'] ?? 0 }}
                            </div>
                            <div class="mt-3 pt-3 border-top d-flex align-items-center gap-2 small text-muted">
                                <i class="fas fa-chart-line"></i>
                                <span>View all companies</span>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-12 col-md-6 col-lg-4">
                    <a href="{{ route('company_locations.index') }}" class="text-decoration-none d-block">
                        <div
                            class="stat-card business-units bg-white rounded-3 shadow p-4 position-relative overflow-hidden">
                            <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center mb-3 fs-3">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="stat-label text-uppercase text-secondary small fw-semibold mb-2">
                                Business Units
                            </div>
                            <div class="stat-value display-4 fw-bold text-dark" style="line-height: 1;">
                                {{ $stats['business_units'] ?? 0 }}
                            </div>
                            <div class="mt-3 pt-3 border-top d-flex align-items-center gap-2 small text-muted">
                                <i class="fas fa-chart-line"></i>
                                <span>View all locations</span>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-12 col-md-6 col-lg-4">
                    <a href="{{ route('departments.index') }}" class="text-decoration-none d-block">
                        <div class="stat-card departments bg-white rounded-3 shadow p-4 position-relative overflow-hidden">
                            <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center mb-3 fs-3">
                                <i class="fas fa-sitemap"></i>
                            </div>
                            <div class="stat-label text-uppercase text-secondary small fw-semibold mb-2">
                                Departments
                            </div>
                            <div class="stat-value display-4 fw-bold text-dark" style="line-height: 1;">
                                {{ $stats['departments'] ?? 0 }}
                            </div>
                            <div class="mt-3 pt-3 border-top d-flex align-items-center gap-2 small text-muted">
                                <i class="fas fa-chart-line"></i>
                                <span>View all departments</span>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-12 col-md-6 col-lg-4">
                    <a href="{{ route('sections.index') }}" class="text-decoration-none d-block">
                        <div class="stat-card sections bg-white rounded-3 shadow p-4 position-relative overflow-hidden">
                            <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center mb-3 fs-3">
                                <i class="fas fa-layer-group"></i>
                            </div>
                            <div class="stat-label text-uppercase text-secondary small fw-semibold mb-2">
                                Sections
                            </div>
                            <div class="stat-value display-4 fw-bold text-dark" style="line-height: 1;">
                                {{ $stats['sections'] ?? 0 }}
                            </div>
                            <div class="mt-3 pt-3 border-top d-flex align-items-center gap-2 small text-muted">
                                <i class="fas fa-chart-line"></i>
                                <span>View all sections</span>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-12 col-md-6 col-lg-4">
                    <a href="{{ route('divisions.index') }}" class="text-decoration-none d-block">
                        <div class="stat-card divisions bg-white rounded-3 shadow p-4 position-relative overflow-hidden">
                            <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center mb-3 fs-3">
                                <i class="fas fa-project-diagram"></i>
                            </div>
                            <div class="stat-label text-uppercase text-secondary small fw-semibold mb-2">
                                Divisions
                            </div>
                            <div class="stat-value display-4 fw-bold text-dark" style="line-height: 1;">
                                {{ $stats['divisions'] ?? 0 }}
                            </div>
                            <div class="mt-3 pt-3 border-top d-flex align-items-center gap-2 small text-muted">
                                <i class="fas fa-chart-line"></i>
                                <span>View all divisions</span>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-12 col-md-6 col-lg-4">
                    <a href="{{ route('employees.index') }}" class="text-decoration-none d-block">
                        <div class="stat-card employees bg-white rounded-3 shadow p-4 position-relative overflow-hidden">
                            <div class="stat-icon rounded-3 d-flex align-items-center justify-content-center mb-3 fs-3">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="stat-label text-uppercase text-secondary small fw-semibold mb-2">
                                Total Employees
                            </div>
                            <div class="stat-value display-4 fw-bold text-dark" style="line-height: 1;">
                                {{ $stats['employees'] ?? 0 }}
                            </div>
                            <div class="mt-3 pt-3 border-top d-flex align-items-center gap-2 small text-muted">
                                <i class="fas fa-chart-line"></i>
                                <span>View all employees</span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
