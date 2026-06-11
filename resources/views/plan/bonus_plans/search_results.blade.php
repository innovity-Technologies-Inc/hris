<div class="table-responsive">
    <table class="table table-bordered mb-0">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Pay Group</th>
                <th scope="col">Bonus & Reward Plan Name</th>
                <th scope="col">Bonus & Reward Type</th>
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
                    {{-- Serial Number --}}
                    <th scope="row">{{ $sl++ }}</th>

                    {{-- Pay Group --}}
                    <td>{{ $item->payGroup->title ?? '-' }}</td>

                    {{-- Plan Name --}}
                    <td>{{ $item->name }}</td>

                    {{-- Bonus Type --}}
                    <td>
                        <span class="badge text-bg-info">
                            {{ ucwords(str_replace('_', ' ', $item->bonus_type)) }}
                        </span>
                    </td>

                    {{-- Configuration Type (Salary Based vs Custom) --}}
                    <td>
                        @if ($item->bonus_config_type == 'Salary Based')
                            <span class="badge text-bg-primary">Salary Based</span>
                        @else
                            <span class="badge text-bg-success">Custom</span>
                        @endif
                    </td>

                    {{-- Rate Display (conditional based on config type) --}}
                    <td>
                        @if ($item->bonus_config_type == 'Salary Based')
                            {{-- Salary-based calculation display --}}
                            @if ($item->salary_rate_type == 'Multiplier' && $item->multiplier)
                                <span class="badge text-bg-secondary">
                                    {{ number_format($item->multiplier, 2) }}× Base Salary
                                </span>
                            @elseif ($item->salary_rate_type == 'Basic Rate')
                                <span class="badge text-bg-secondary">Basic Salary (100%)</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        @else
                            {{-- Custom fixed amount display --}}
                            @if ($item->custom_rate)
                                <span class="badge text-bg-secondary">
                                    {{\App\HelperClass::getCurrency()}} {{ number_format($item->custom_rate, 2) }}
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        @endif
                    </td>

                    {{-- Status --}}
                    <td>
                        @if ($item->status == 'active')
                            <span class="badge text-bg-success">Active</span>
                        @else
                            <span class="badge text-bg-danger">Inactive</span>
                        @endif
                    </td>

                    {{-- Action Buttons --}}
                    <td>
                        {{-- View Button --}}
                        <a type="button" class="btn btn-primary btn-sm"
                            href="{{ route('plan.bonus_plans.show', $item->id) }}" title="View">
                            <i style="height: 12px; width: 12px" data-feather="eye"></i>
                        </a>

                        {{-- Edit Button --}}
                        <a type="button" class="btn btn-warning btn-sm"
                            href="{{ route('plan.bonus_plans.edit', $item->id) }}" title="Edit">
                            <i style="height: 12px; width: 12px" data-feather="edit"></i>
                        </a>

                        {{-- Delete Button with Form --}}
                        <form action="{{ route('plan.bonus_plans.delete', $item->id) }}" method="POST"
                            style="display: inline-block">
                            @csrf
                            @method('DELETE')

                            <button class="btn btn-sm btn-danger confirmDelete" title="Delete"
                                type="submit">
                                <i style="height: 12px; width: 12px" data-feather="trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Pagination Links --}}
    <div class="mt-3">
        {{ $plans->links() }}
    </div>
</div>

