<div class="p-0">
    @php
        $currency = \App\HelperClass::getCurrency() ?? '৳';
    @endphp

    @if ($challans->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="bi bi-info-circle fs-3 d-block mb-2 text-secondary"></i>
            No tax challan records found.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered mb-0 align-middle">
                <thead>
                    <tr class="table-light text-muted small text-uppercase">
                        <th scope="col" style="width: 60px;">#</th>
                        <th scope="col">Company</th>
                        <th scope="col">Employee</th>
                        <th scope="col" class="text-center">Paid From</th>
                        <th scope="col" class="text-center">Paid To</th>
                        <th scope="col">Attachments</th>
                        <th scope="col" class="text-center" style="width: 120px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php $sl = ($challans->currentPage() - 1) * $challans->perPage() + 1; @endphp
                    @foreach ($challans as $challan)
                        <tr>
                            <th scope="row" class="fw-semibold">{{ $sl++ }}</th>
                            <td class="fw-semibold text-dark">{{ $challan->company->name ?? 'N/A' }}</td>
                            <td>
                                @if($challan->employee)
                                    <div class="d-flex align-items-center gap-2">
                                        {!! \App\HelperClass::generateAvatar(
                                            $challan->employee->photo_path ?? null,
                                            $challan->employee->full_name ?? 'N/A',
                                            36,
                                            '#974063',
                                            '',
                                            $challan->employee_id,
                                        ) !!}
                                        <div>
                                            <a href="{{ route('employee.profile.general_informations', $challan->employee_id) }}"
                                                class="text-decoration-none fw-semibold text-dark">
                                                {{ $challan->employee->full_name }}
                                            </a>
                                            <div class="text-muted small">ID: {{ $challan->employee->applicant_id ?? $challan->employee->system_id ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2.5 py-1.5 rounded-pill fw-semibold small">Company-wide</span>
                                @endif
                            </td>
                            <td class="text-center fw-semibold text-dark">
                                {{ Carbon\Carbon::parse($challan->tax_paid_from . '-01')->format('M Y') }}
                            </td>
                            <td class="text-center fw-semibold text-dark">
                                {{ Carbon\Carbon::parse($challan->tax_paid_to . '-01')->format('M Y') }}
                            </td>
                            <td>
                                @if(!empty($challan->attachments))
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach($challan->attachments as $index => $path)
                                            <a href="{{ asset('storage/' . $path) }}" target="_blank" class="badge bg-light text-primary border p-1.5 text-decoration-none">
                                                <i class="bi bi-file-earmark-arrow-down me-1"></i> File {{ $index + 1 }}
                                            </a>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-muted small">No attachments</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary edit-challan" data-id="{{ $challan->id }}" title="Edit Challan">
                                        <i class="mdi mdi-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger delete-challan" data-id="{{ $challan->id }}" title="Delete Challan">
                                        <i class="mdi mdi-trash-can"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($challans->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-3 px-3">
                <div class="text-muted small">
                    Showing {{ $challans->firstItem() }} to {{ $challans->lastItem() }} of {{ $challans->total() }} entries
                </div>
                <div>
                    {!! $challans->links('vendor.pagination.bootstrap-5') !!}
                </div>
            </div>
        @endif
    @endif
</div>
