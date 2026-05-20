@extends('structure.master')
@section('content')
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
                    <a href="{{ route('employee.index') }}" class="text-decoration-none d-block">        
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

