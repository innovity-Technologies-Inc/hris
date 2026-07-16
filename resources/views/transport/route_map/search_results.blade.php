<div class="table-responsive">
    <table class="table table-hover table-striped">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Route Name</th>
                <th>Start Point</th>
                <th>End Point</th>
                <th>Via Points</th>
                <th>Status</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($routeMaps as $key => $routeMap)
                <tr>
                    <td>{{ $routeMaps->firstItem() + $key }}</td>
                    <td>
                        <strong>{{ $routeMap->route_name }}</strong>
                    </td>
                    <td>
                        {{ $routeMap->start_point }}
                    </td>
                    <td>
                        {{ $routeMap->end_point }}
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-outline-primary px-2 py-1 rounded-pill" style="font-size: 0.75rem;"
                            onclick="showRouteMapModal({{ json_encode($routeMap) }})">
                            <i class="mdi mdi-map-marker-outline"></i> View Route ({{ is_array($routeMap->via_points) ? count($routeMap->via_points) : 0 }})
                        </button>
                    </td>
                    <td>
                        <span class="badge {{ $routeMap->status_badge_class }}">
                            {{ $routeMap->status }}
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="btn-group btn-group-sm">
                            @if(auth()->user()->can('employee-transport.edit'))
                                <a href="{{ route('transport.route_maps.edit', $routeMap->id) }}"
                                    class="btn btn-primary btn-sm" title="Edit">
                                    <i data-feather="edit" style="width: 14px; height: 14px;"></i>
                                </a>
                            @endif
                            @if(auth()->user()->can('employee-transport.delete'))
                                <button type="button" class="btn btn-danger btn-sm" title="Delete"
                                    onclick="deleteRouteMap({{ $routeMap->id }})">
                                    <i data-feather="trash" style="width: 14px; height: 14px;"></i>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-4">No Route Maps found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-end mt-3">
    {{ $routeMaps->links() }}
</div>
