<div class="table-responsive">
    <table class="table table-bordered mb-0">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">OT Plan Name</th>
                <th scope="col">Config Type</th>
                <th scope="col">Rate</th>
                <th scope="col">Status</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @php
                $sl = \App\HelperClass::indexNumberSerialization($plans);
            @endphp
            @foreach ($plans as $item)
                <tr>
                    <th scope="row">{{ $sl++ }}</th>
                    <td>{{ $item->name }}</td>
                    <td>
                        @if ($item->ot_config_type == 'Salary Based')
                            <span class="badge text-bg-primary">Salary Based</span>
                        @else
                            <span class="badge text-bg-success">Custom</span>
                        @endif
                    </td>
                    <td>
                        @if ($item->ot_config_type == 'Salary Based')
                            @if ($item->salary_rate_type == 'Multiplier' && $item->overtime_multiplier)
                                <span
                                    class="badge text-bg-secondary">{{ number_format($item->overtime_multiplier, 2) }}x
                                    Base Rate</span>
                            @elseif ($item->salary_rate_type == 'Basic Rate')
                                <span class="badge text-bg-secondary">Basic Rate</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        @else
                            @if ($item->custom_overtime_rate)
                                <span class="badge text-bg-secondary">{{ \App\HelperClass::getCurrency() }}
                                    {{ number_format($item->custom_overtime_rate, 2) }}/hr</span>
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
                            href="{{ route('plans.ot_plans.show', $item->id) }}" title="View">
                            <i style="height: 12px; width: 12px" data-feather="eye"></i>
                        </a>

                        <a type="button" class="btn btn-warning btn-sm"
                            href="{{ route('plans.ot_plans.edit', $item->id) }}" title="Edit">
                            <i style="height: 12px; width: 12px" data-feather="edit"></i>
                        </a>

                        <form action="{{ route('plans.ot_plans.delete', $item->id) }}" method="POST"
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
