{{-- View Modal 1 --}}
<div class="modal fade" id="meal_plan_view-{{$item->id}}" tabindex="-1" aria-labelledby="viewModalLabel1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewModalLabel1">Meal Plan Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6 mb-3">
                        <label class="form-label fw-bold">Name:</label>
                        <p class="form-control-plaintext">{{$item->name}}</p>
                    </div>

                    <div class="col-lg-6 mb-3">
                        <label class="form-label fw-bold">Type:</label>
                        <p class="form-control-plaintext">{{ucwords($item->type)}}</p>
                    </div>

                    <div class="col-lg-6 mb-3">
                        <label class="form-label fw-bold">Start Time:</label>
                        <p class="form-control-plaintext">{{$item->start_time}}</p>
                    </div>

                    <div class="col-lg-6 mb-3">
                        <label class="form-label fw-bold">End Time:</label>
                        <p class="form-control-plaintext">{{$item->end_time}}</p>
                    </div>

                    <div class="col-lg-6 mb-3">
                        <label class="form-label fw-bold">Cost:</label>
                        <p class="form-control-plaintext">{{$item->cost}}</p>
                    </div>

                    <div class="col-lg-6 mb-3">
                        <label class="form-label fw-bold">Status:</label>
                        @if($item->status == 'active')
                            <span class="badge text-bg-success">Active</span>
                        @else
                            <span class="badge text-bg-danger">Inactive</span>

                        @endif
                    </div>

                    <div class="col-lg-12 mb-3">
                        <label class="form-label fw-bold">Description:</label>
                        <p class="form-control-plaintext">{{$item->description}}
                        </p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

