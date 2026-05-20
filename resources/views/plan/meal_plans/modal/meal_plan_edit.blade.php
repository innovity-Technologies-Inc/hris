{{-- Edit Modal 1 --}}
<div class="modal fade" id="meal_plan_edit-{{$item->id}}" tabindex="-1" aria-labelledby="editModalLabel1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel1">Edit </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('plan.meal_plans.update', $item->id)}}" method="POST">
                @method('put')
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-4 mb-3">
                            <label class="form-label">Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" value="{{$item->name}}">
                        </div>

                        <div class="col-lg-4 mb-3">
                            <label class="form-label">Type<span class="text-danger">*</span></label>
                            <select class="form-select" name="type">
                                <option value="breakfast" {{$item->type == 'breakfast' ? 'selected' : ''}}>Breakfast</option>
                                <option value="lunch" {{$item->type == 'lunch' ? 'selected' : ''}}>Lunch</option>
                                <option value="snacks" {{$item->type == 'snacks' ? 'selected' : ''}}>Snacks</option>
                                <option value="dinner" {{$item->type == 'dinner' ? 'selected' : ''}}>Dinner</option>
                            </select>
                        </div>

                        <div class="col-lg-4 mb-3">
                            <label class="form-label">Status<span class="text-danger">*</span></label>
                            <select class="form-select" name="status">
                                <option value="active" selected>Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>

                        <div class="col-lg-12 mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3">{{$item->description}}</textarea>
                        </div>

                        <div class="col-lg-4 mb-3">
                            <label class="form-label">Start Time<span class="text-danger">*</span></label>
                            <input type="time" class="form-control" name="start_time" value="07:00">
                        </div>

                        <div class="col-lg-4 mb-3">
                            <label class="form-label">End Time<span class="text-danger">*</span></label>
                            <input type="time" class="form-control" name="end_time" value="09:00">
                        </div>

                        <div class="col-lg-4 mb-3">
                            <label class="form-label">Cost (Tk)<span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="cost" value="50.00" step="0.01">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>


