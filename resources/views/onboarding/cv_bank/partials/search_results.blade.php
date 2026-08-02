<table class="table table-hover mb-0">
    <thead>
        <tr>
            <th>#</th>
            <th>Applicant Name</th>
            <th>Company Name</th>
            <th>Designation</th>
            <th>Career Level</th>
            <th>CV Score</th>
            <th>CV Document</th>
            <th class="text-end">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($cvs as $cv)
            <tr>
                <td>{{ $loop->iteration + ($cvs->currentPage() - 1) * $cvs->perPage() }}</td>
                <td class="fw-semibold text-dark">{{ $cv->applicant_name }}</td>
                <td>{{ $cv->company_name }}</td>
                <td>{{ $cv->designation }}</td>
                <td>
                    <span class="badge rounded-pill bg-light text-dark border px-2 py-1">{{ $cv->career_level }}</span>
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <span class="fw-bold me-2">{{ $cv->cv_score }}</span>
                        <div class="progress" style="width: 60px; height: 6px;">
                            <div class="progress-bar 
                                @if($cv->cv_score >= 80) bg-success 
                                @elseif($cv->cv_score >= 50) bg-warning 
                                @else bg-danger 
                                @endif" 
                                role="progressbar" 
                                style="width: {{ $cv->cv_score }}%;" 
                                aria-valuenow="{{ $cv->cv_score }}" 
                                aria-valuemin="0" 
                                aria-valuemax="100">
                            </div>
                        </div>
                    </div>
                </td>
                <td>
                    @if($cv->attachment_path)
                        <a href="{{ asset('storage/' . $cv->attachment_path) }}" target="_blank" class="btn btn-xs btn-outline-primary py-0 px-2 small">
                            <i class="bi bi-file-earmark-pdf-fill me-1"></i>View CV
                        </a>
                    @else
                        <span class="text-muted small">No Document</span>
                    @endif
                </td>
                <td class="text-end">
                    <div class="d-flex justify-content-end gap-1">
                        @can('cv-bank.edit')
                            <a href="{{ route('cv_bank.edit', $cv->id) }}" class="btn btn-primary btn-sm" title="Edit CV">
                                <i class="bi bi-pencil"></i>
                            </a>
                        @endcan

                        @can('cv-bank.delete')
                            <button type="button" class="btn btn-danger btn-sm delete-cv" data-id="{{ $cv->id }}" title="Delete CV">
                                <i class="bi bi-trash"></i>
                            </button>
                        @endcan
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center text-muted py-4">No CV records found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="mt-4 d-flex justify-content-start">
    {{ $cvs->links() }}
</div>
