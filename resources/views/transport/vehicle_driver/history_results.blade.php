@foreach ($inactiveAssignments as $date => $assignments)
    <div class="mb-4">
        <div class="d-flex align-items-center mb-3">
            <div class="bg-primary text-white px-3 py-2 rounded">
                <strong>{{ \Carbon\Carbon::parse($date)->format('d M, Y') }}</strong>
            </div>
            <div class="flex-grow-1 ms-2">
                <hr class="my-0">
            </div>
            <span class="badge bg-secondary ms-2">{{ $assignments->count() }} Assignment(s)</span>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col" class="text-center">#</th>
                        <th scope="col">Vehicle</th>
                        <th scope="col">Driver</th>
                        <th scope="col" class="text-center">Start Date</th>
                        <th scope="col" class="text-center">End Date</th>
                        <th scope="col" class="text-center">Deactivated At</th>
                        <th scope="col" class="text-center" style="width: 100px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $sl = 1;
                    @endphp
                    @foreach ($assignments as $item)
                        <tr>
                            <th scope="row" class="text-center">{{ $sl++ }}</th>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if ($item->getVehicle && $item->getVehicle->vehicle_image)
                                        <img src="{{ asset('storage/' . $item->getVehicle->vehicle_image) }}"
                                            alt="{{ $item->getVehicle->model_number ?? 'Vehicle' }}"
                                            class="rounded me-2" style="width: 45px; height: 45px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center me-2"
                                            style="width: 45px; height: 45px;">
                                            <i data-feather="truck" class="text-muted"
                                                style="width: 20px; height: 20px;"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <strong>{{ $item->getVehicle->model_number ?? 'N/A' }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            <span
                                                class="badge bg-info text-dark">{{ $item->getVehicle->vehicle_category ?? 'N/A' }}</span>
                                            {{ $item->getVehicle->license_number ?? '' }}
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if ($item->getDriver && $item->getDriver->photo_path)
                                        <img src="{{ asset('storage/' . $item->getDriver->photo_path) }}"
                                            alt="{{ $item->getDriver->full_name ?? 'Driver' }}"
                                            class="rounded-circle me-2"
                                            style="width: 45px; height: 45px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2"
                                            style="width: 45px; height: 45px;">
                                            <i data-feather="user" class="text-muted"
                                                style="width: 20px; height: 20px;"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <strong>{{ $item->getDriver->full_name ?? 'N/A' }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            ID: {{ $item->getDriver->system_id ?? 'N/A' }}
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                {{ $item->start_date ? \Carbon\Carbon::parse($item->start_date)->format('d M, Y') : 'N/A' }}
                            </td>
                            <td class="text-center">
                                {{ $item->end_date ? \Carbon\Carbon::parse($item->end_date)->format('d M, Y') : '-' }}
                            </td>
                            <td class="text-center">
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($item->updated_at)->format('d M, Y h:i A') }}
                                </small>
                            </td>
                            <td class="text-center">
                                <a type="button" class="btn btn-info btn-sm"
                                    href="{{ route('transport.vehicle_drivers.show', $item->id) }}" title="View">
                                    <i style="height: 12px; width: 12px" data-feather="eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endforeach

<div class="mt-3">
    {{ $vehicleDrivers->links() }}
</div>
