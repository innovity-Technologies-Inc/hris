<div class="card border mb-3 route-card" style="border-color: var(--primary-color) !important;" data-index="{{ $index }}">
    <div class="card-header d-flex justify-content-between align-items-center bg-light">
        <h6 class="mb-0 text-dark fw-bold">Route #<span class="leg-number">{{ is_numeric($index) ? $index + 1 : '1' }}</span></h6>
        <button type="button" class="btn btn-outline-danger btn-sm remove-leg-btn {{ ($showRemove ?? false) ? '' : 'd-none' }}">
            <i class="bi bi-trash"></i> Remove Route
        </button>
    </div>
    <div class="card-body">
        <input type="hidden" name="items[{{ $index }}][id]" value="{{ $detail->id ?? '' }}">
        <div class="row">
            <!-- Source Address -->
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Source Address <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-map"></i></span>
                    <input type="text" name="items[{{ $index }}][source_address]"
                           value="{{ old("items.{$index}.source_address", $detail->source_address ?? '') }}"
                           class="form-control border-start-0 source-address" placeholder="Search starting point..." required>
                </div>
                <input type="hidden" name="items[{{ $index }}][source_lat]" class="source-lat"
                       value="{{ old("items.{$index}.source_lat", $detail->source_lat ?? '') }}">
                <input type="hidden" name="items[{{ $index }}][source_lng]" class="source-lng"
                       value="{{ old("items.{$index}.source_lng", $detail->source_lng ?? '') }}">
            </div>

            <!-- Destination Address -->
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Destination Address <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-pin-map-fill"></i></span>
                    <input type="text" name="items[{{ $index }}][destination_address]"
                           value="{{ old("items.{$index}.destination_address", $detail->destination_address ?? '') }}"
                           class="form-control border-start-0 destination-address" placeholder="Search destination..." required>
                </div>
                <input type="hidden" name="items[{{ $index }}][dest_lat]" class="dest-lat"
                       value="{{ old("items.{$index}.dest_lat", $detail->dest_lat ?? '') }}">
                <input type="hidden" name="items[{{ $index }}][dest_lng]" class="dest-lng"
                       value="{{ old("items.{$index}.dest_lng", $detail->dest_lng ?? '') }}">
            </div>

            <!-- Distance -->
            <div class="col-md-3 mb-3">
                <label class="form-label fw-semibold">Distance (KM)</label>
                <input type="text" name="items[{{ $index }}][distance]"
                       value="{{ old("items.{$index}.distance", $detail->distance ?? '0.00') }}"
                       class="form-control bg-light leg-distance" readonly>
            </div>

            <!-- Reason -->
            <div class="col-md-5 mb-3">
                <label class="form-label fw-semibold">Reason <span class="text-danger">*</span></label>
                <input type="text" name="items[{{ $index }}][reason]"
                       value="{{ old("items.{$index}.reason", $detail->reason ?? '') }}"
                       class="form-control" placeholder="Reason for this route..." required>
            </div>

            <!-- Attachment -->
            <div class="col-md-4 mb-3">
                <label class="form-label fw-semibold">Attachment</label>
                <input type="file" name="items[{{ $index }}][attachment]" class="form-control attachment-input">
                @if(isset($detail) && $detail->attachment_path)
                    <div class="mt-2">
                        <a href="{{ \Illuminate\Support\Facades\Storage::url($detail->attachment_path) }}" target="_blank" class="btn btn-outline-info btn-xs py-1 px-2 small">
                            <i class="bi bi-file-earmark-check"></i> View Current Attachment
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
