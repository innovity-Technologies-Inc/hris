@extends('structure.master')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h3 class="mb-2 fw-bold">
                            <i data-feather="users" style="width: 32px; height: 32px;"></i>
                            Driver Assignment Details
                        </h3>
                        <p class="text-muted mb-0">View complete assignment information</p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <a href="{{ route('transport.vehicle_drivers.index') }}" class="btn btn-secondary">
                            <i data-feather="arrow-left" style="width: 16px; height: 16px;"></i> Back
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0 text-white">
                            <i data-feather="calendar" style="width: 18px; height: 18px;"></i>
                            Assignment Details
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <small class="text-muted d-block mb-1">Start Date</small>
                                    <h6 class="fw-bold">
                                        {{ \Carbon\Carbon::parse($vehicleDriver->start_date)->format('d M, Y') }}</h6>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <small class="text-muted d-block mb-1">End Date</small>
                                    <h6 class="fw-bold">
                                        {{ $vehicleDriver->end_date ? \Carbon\Carbon::parse($vehicleDriver->end_date)->format('d M, Y') : 'Ongoing' }}
                                    </h6>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <small class="text-muted d-block mb-1">Status</small>
                                    @if ($vehicleDriver->status == 'active')
                                        <span class="badge bg-success fs-6">Active</span>
                                    @else
                                        <span class="badge bg-danger fs-6">Inactive</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <small class="text-muted d-block mb-1">Created</small>
                                    <h6 class="fw-bold">{{ $vehicleDriver->created_at->format('d M, Y') }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0">
                            <i data-feather="truck" style="width: 18px; height: 18px;"></i>
                            Vehicle Information
                        </h6>
                    </div>
                    <div class="card-body">
                        @if ($vehicleDriver->getVehicle)
                            <div class="text-center mb-3">
                                @if ($vehicleDriver->getVehicle->vehicle_image)
                                    <img src="{{ asset('storage/' . $vehicleDriver->getVehicle->vehicle_image) }}"
                                        alt="{{ $vehicleDriver->getVehicle->model_number }}" class="rounded"
                                        style="max-height: 200px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                        style="height: 200px;">
                                        <i data-feather="truck" class="text-muted" style="width: 80px; height: 80px;"></i>
                                    </div>
                                @endif
                            </div>
                            <table class="table table-sm">
                                <tbody>
                                    <tr>
                                        <td class="text-muted border-0 py-2">Model</td>
                                        <td class="fw-semibold border-0 py-2">{{ $vehicleDriver->getVehicle->model_number }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted border-0 py-2">Category</td>
                                        <td class="border-0 py-2">
                                            <span
                                                class="badge bg-info text-dark">{{ $vehicleDriver->getVehicle->vehicle_category }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted border-0 py-2">Year</td>
                                        <td class="fw-semibold border-0 py-2">
                                            {{ $vehicleDriver->getVehicle->manufacture_year }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted border-0 py-2">Fuel Type</td>
                                        <td class="fw-semibold border-0 py-2">{{ $vehicleDriver->getVehicle->fuel_type }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted border-0 py-2">License Number</td>
                                        <td class="fw-semibold border-0 py-2">
                                            {{ $vehicleDriver->getVehicle->license_number ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted border-0 py-2">Color</td>
                                        <td class="fw-semibold border-0 py-2">
                                            {{ $vehicleDriver->getVehicle->color ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted border-0 py-2">Seating Capacity</td>
                                        <td class="fw-semibold border-0 py-2">
                                            {{ $vehicleDriver->getVehicle->seating_capacity ?? 'N/A' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        @else
                            <p class="text-muted">No vehicle information available</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0">
                            <i data-feather="user" style="width: 18px; height: 18px;"></i>
                            Driver Information
                        </h6>
                    </div>
                    <div class="card-body">
                        @if ($vehicleDriver->getDriver)
                            <div class="text-center mb-3">
                                {!! \App\HelperClass::generateAvatar(
                                    $vehicleDriver->getDriver->photo_path ?? null,
                                    $vehicleDriver->getDriver->full_name ?? 'N/A',
                                    150,
                                    '#974063',
                                    '',
                                    $vehicleDriver->driver_id,
                                ) !!}
                            </div>
                            <table class="table table-sm">
                                <tbody>
                                    <tr>
                                        <td class="text-muted border-0 py-2">Name</td>
                                        <td class="fw-semibold border-0 py-2">
                                            <a href="{{ route('employee.profile.general_informations', $vehicleDriver->driver_id) }}"
                                                class="text-decoration-none text-dark">
                                                {{ $vehicleDriver->getDriver->full_name }}
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted border-0 py-2">System ID</td>
                                        <td class="fw-semibold border-0 py-2">{{ $vehicleDriver->getDriver->system_id }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted border-0 py-2">Phone</td>
                                        <td class="fw-semibold border-0 py-2">
                                            {{ $vehicleDriver->getDriver->personal_mobile ?? ($vehicleDriver->getDriver->work_mobile ?? 'N/A') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted border-0 py-2">Email</td>
                                        <td class="fw-semibold border-0 py-2">
                                            {{ $vehicleDriver->getDriver->work_email ?? ($vehicleDriver->getDriver->personal_email ?? 'N/A') }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        @else
                            <p class="text-muted">No driver information available</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

