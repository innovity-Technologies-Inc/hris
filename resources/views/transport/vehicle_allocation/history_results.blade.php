<!-- Results Table -->
<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Vehicle</th>
                <th>Allocation Name</th>
                <th>Type</th>
                <th>Reference</th>
                <th>Period</th>
                <th>Status</th>
                <th>Approved By</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($allocations as $index => $allocation)
                <tr>
                    <td>{{ $allocations->firstItem() + $index }}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            @if ($allocation->getVehicle && $allocation->getVehicle->vehicle_image)
                                <img src="{{ \App\HelperClass::get_file_url($allocation->getVehicle->vehicle_image) }}"
                                    alt="Vehicle" class="rounded-2 me-2"
                                    style="width: 40px; height: 40px; object-fit: cover;">
                            @else
                                <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                    <i class="fas fa-car text-primary"></i>
                                </div>
                            @endif
                            <div>
                                <strong>{{ $allocation->getVehicle->vehicle_category ?? 'N/A' }}</strong>
                                <small class="text-muted d-block">
                                    {{ $allocation->getVehicle->model_number ?? $allocation->getVehicle->vehicle_category }}
                                </small>
                            </div>
                        </div>
                    </td>
                    <td>{{ $allocation->name }}</td>
                    <td>
                        <span class="badge bg-info">{{ $allocation->allocation_type }}</span>
                    </td>
                    <td>
                        @if ($allocation->reference_type && $allocation->reference_id)
                            @php
                                $refLabel = match ($allocation->reference_type) {
                                    'App\\Models\\Transport\\EmployeeTransport' => 'Emp. Transport',
                                    'App\\Models\\Transport\\VehicleRequisition' => 'Requisition',
                                    default => 'Ref',
                                };
                            @endphp
                            <span class="badge bg-secondary">{{ $refLabel }}
                                #{{ $allocation->reference_id }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        <small>
                            {{ $allocation->start_date ? \Carbon\Carbon::parse($allocation->start_date)->format('d/m/Y') : '-' }}
                            <br>
                            <span class="text-muted">to</span>
                            {{ $allocation->end_date ? \Carbon\Carbon::parse($allocation->end_date)->format('d/m/Y') : '-' }}
                        </small>
                    </td>
                    <td>
                        @php
                            $statusClass = match ($allocation->status) {
                                'Allocated' => 'warning',
                                'Active' => 'success',
                                'Released' => 'secondary',
                                'Completed' => 'primary',
                                'Cancelled' => 'danger',
                                default => 'secondary',
                            };
                        @endphp
                        <span class="badge bg-{{ $statusClass }}">{{ $allocation->status }}</span>
                    </td>
                    <td>
                        @if ($allocation->approved_at)
                            <small class="text-muted d-block">
                                {{ \Carbon\Carbon::parse($allocation->approved_at)->format('d/m/Y') }}
                            </small>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('transport.vehicle_allocations.show', $allocation->id) }}"
                                class="btn btn-outline-info" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if ($allocation->status == 'Active' || $allocation->status == 'Allocated')
                                <button type="button" class="btn btn-outline-danger"
                                    onclick="releaseVehicle({{ $allocation->id }})" title="Release">
                                    <i class="fas fa-unlock"></i>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center py-5">
                        <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                        <p class="text-muted mb-0">No allocation records found</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if ($allocations->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $allocations->withQueryString()->links() }}
    </div>
@endif
