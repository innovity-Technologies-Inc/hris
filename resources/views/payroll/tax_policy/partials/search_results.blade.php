<div class="card-body p-0">
    @if ($policies->isEmpty())
        <div class="text-center py-4 text-muted">No tax policy records found.</div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered mb-0">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Company & Branch</th>
                        <th scope="col">Zero Tax Return Limit (Male)</th>
                        <th scope="col">Zero Tax Return Limit (Female)</th>
                        <th scope="col">Minimum Tax</th>
                        <th scope="col">Exemption Policy</th>
                        <th scope="col">Slabs Count</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php $sl = ($policies->currentPage() - 1) * $policies->perPage() + 1; @endphp
                    @foreach ($policies as $policy)
                        <tr>
                            <th scope="row">{{ $sl++ }}</th>
                            <td>
                                <div class="fw-semibold text-dark">
                                    {{ $policy->getCompany->name ?? 'Global (All Companies)' }}
                                </div>
                                <small class="text-muted">
                                    {{ $policy->getBranch->name ?? 'All Branches' }}
                                </small>
                            </td>
                            <td>৳{{ number_format($policy->zero_tax_male, 2) }}</td>
                            <td>৳{{ number_format($policy->zero_tax_female, 2) }}</td>
                            <td>৳{{ number_format($policy->min_tax_amount, 2) }}</td>
                            <td>
                                @if($policy->exemption_type === 'fixed')
                                    <span class="badge bg-soft-primary text-primary px-2 py-1">Fixed Exemption</span>
                                    <div class="small mt-1 text-muted">
                                        @if($policy->salary_ratio) Ratio: {{ $policy->salary_ratio }} @endif
                                        @if($policy->fixed_amount) | Fixed Amount: ৳{{ number_format($policy->fixed_amount, 2) }} @endif
                                    </div>
                                @else
                                    <span class="badge bg-soft-info text-info px-2 py-1">Exempt Allowances</span>
                                    <div class="small mt-1 text-muted">
                                        @if(!empty($policy->exempt_allowances))
                                            {{ implode(', ', array_map(function($allowance) {
                                                return ucwords(str_replace('_', ' ', $allowance));
                                            }, $policy->exempt_allowances)) }}
                                        @else
                                            No allowances added
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $policy->slabs->count() }} Slabs</span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    @can('tax-policy.edit')
                                        <a href="{{ route('tax-policy.edit', $policy->id) }}"
                                            class="btn btn-primary btn-sm" title="Edit">
                                            <i style="height: 12px; width: 12px" data-feather="edit"></i>
                                        </a>
                                    @endcan
                                    @can('tax-policy.delete')
                                        <button class="btn btn-danger btn-sm delete-policy" 
                                                data-url="{{ route('tax-policy.destroy', $policy->id) }}" 
                                                title="Delete">
                                            <i style="height: 12px; width: 12px" data-feather="trash"></i>
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($policies->hasPages())
            <div class="d-flex justify-content-center mt-3 p-3">
                {{ $policies->links() }}
            </div>
        @endif
    @endif
</div>
