<div class="table-responsive">
    <table class="table table-bordered mb-0">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Plan Name</th>
                <th scope="col">Short Name</th>
                <th scope="col">Plan Type</th>
                <th scope="col">Config Type</th>
                <th scope="col">Rate</th>
                <th scope="col">Status</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @php
                $sl = 1; // In production: $sl = \App\HelperClass::indexNumberSerialization($plans);
            @endphp
            @foreach ($plans as $item)
                <tr>
                    <th scope="row">{{ $sl++ }}</th>
                    <td>{{ $item->name }}</td>
                    <td>
                        @if ($item->short_name)
                            <span class="badge text-bg-secondary">{{ $item->short_name }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if ($item->type === 'comp-off')
                            <span class="badge text-bg-info">Comp-off</span>
                        @else
                            <span class="badge text-bg-success">Paid</span>
                        @endif
                    </td>
                    <td>
                        @if ($item->type === 'comp-off')
                            <span class="text-muted">N/A</span>
                        @elseif (isset($item->offday_config_type) && $item->offday_config_type == 'Salary Based')
                            <span class="badge text-bg-primary">Salary Based</span>
                        @else
                            <span class="badge text-bg-success">Custom</span>
                        @endif
                    </td>
                    <td>
                        @if ($item->type === 'comp-off')
                            <span class="badge text-bg-info">Comp-off</span>
                        @elseif (isset($item->offday_config_type) && $item->offday_config_type == 'Salary Based')
                            @if ($item->salary_rate_type == 'Multiplier' && $item->offday_multiplier)
                                <span class="badge text-bg-secondary">{{ number_format($item->offday_multiplier, 2) }}x
                                    Base Rate</span>
                            @elseif ($item->salary_rate_type == 'Basic Rate')
                                <span class="badge text-bg-secondary">Basic Rate</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        @else
                            @if ($item->custom_offday_rate)
                                <span class="badge text-bg-secondary">
                                    {{ \App\HelperClass::getGeneralSetting()->currency ?? '৳' }}
                                    {{ number_format($item->custom_offday_rate, 2) }}/hr
                                </span>
                            @elseif (isset($item->remuneration))
                                {{-- Fallback for old data before migration --}}
                                <span class="badge text-bg-secondary">
                                    {{ \App\HelperClass::getGeneralSetting()->currency ?? '৳' }}
                                    {{ number_format($item->remuneration, 2) }}
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        @endif
                    </td>
                    <td>
                        @if ($item->status == 'active')
                            <span class="badge text-bg-success">Active</span>
                        @else
                            <span class="badge text-bg-danger">Inactive</span>
                        @endif
                    </td>


                    <td>
                        <a type="button" class="btn btn-primary btn-sm"
                            href="{{ route('plan.off_day_plans.show', $item->id) }}" title="View">
                            <i style="height: 12px; width: 12px" data-feather="eye"></i>
                        </a>

                        <a type="button" class="btn btn-warning btn-sm"
                            href="{{ route('plan.off_day_plans.edit', $item->id) }}" title="Edit">
                            <i style="height: 12px; width: 12px" data-feather="edit"></i>
                        </a>

                        <form action="{{ route('plan.off_day_plans.delete', $item->id) }}" method="POST"
                            style="display: inline-block">
                            @csrf
                            @method('DELETE')

                            <button class="btn btn-sm btn-danger confirmDelete" title="Delete" type="submit">
                                <i style="height: 12px; width: 12px" data-feather="trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach

        </tbody>
    </table>
    <div class="mt-3">
        {{ $plans->links() }}
    </div>
</div>

