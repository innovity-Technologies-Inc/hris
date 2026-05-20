<table class="table table-bordered table-hover mb-0">
    <thead class="table-light">
        <tr>
            <th scope="col" class="text-center">#</th>
            <th scope="col">Vehicle</th>
            <th scope="col">Driver</th>
            <th scope="col" class="text-center">Start Date</th>
            <th scope="col" class="text-center">End Date</th>
            <th scope="col" class="text-center" style="width: 150px;">Action</th>
        </tr>
    </thead>
    <tbody>
        @php
            $sl = ($vehicleDrivers->currentPage() - 1) * $vehicleDrivers->perPage() + 1;
        @endphp
        @forelse($vehicleDrivers as $item)
            <tr>
                <th scope="row" class="text-center">{{ $sl++ }}</th>
                <td>
                    <div class="d-flex align-items-center">
                        @if ($item->getVehicle && $item->getVehicle->vehicle_image)
                            <img src="{{ asset('storage/' . $item->getVehicle->vehicle_image) }}"
                                alt="{{ $item->getVehicle->model_number ?? 'Vehicle' }}" class="rounded me-2"
                                style="width: 45px; height: 45px; object-fit: cover;">
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center me-2"
                                style="width: 45px; height: 45px;">
                                <i data-feather="truck" class="text-muted" style="width: 20px; height: 20px;"></i>
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
                        {!! \App\HelperClass::generateAvatar(
                            $item->getDriver->photo_path ?? null,
                            $item->getDriver->full_name ?? 'N/A',
                            45,
                            '#974063',
                            'me-2',
                            $item->driver_id,
                        ) !!}
                        <div>
                            <a href="{{ route('employee.profile.general_informations', $item->driver_id) }}"
                                class="text-decoration-none">
                                <strong class="text-dark">{{ $item->getDriver->full_name ?? 'N/A' }}</strong>
                            </a>
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
                    <a type="button" class="btn btn-info btn-sm"
                        href="{{ route('transport.vehicle_drivers.show', $item->id) }}" title="View">
                        <i style="height: 12px; width: 12px" data-feather="eye"></i>
                    </a>

                    <form action="{{ route('transport.vehicle_drivers.destroy', $item->id) }}" method="POST"
                        style="display: inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger confirmDelete" title="Delete">
                            <i style="height: 12px; width: 12px" data-feather="trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center py-4">
                    <div class="text-muted">
                        <i data-feather="inbox" style="width: 48px; height: 48px;"></i>
                        <p class="mt-2 mb-0">No driver assignments found</p>
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="mt-3">
    {{ $vehicleDrivers->links() }}
</div>

