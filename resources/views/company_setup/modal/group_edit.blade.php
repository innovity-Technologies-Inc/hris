{{--    Edit Model --}}
<div class="modal fade" id="group-edit{{$group->id}}" tabindex="-1" aria-labelledby="exampleModalPopoversLabel" aria-modal="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalPopoversLabel">Edit Group</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('groups.save')}}" method="post">
                @csrf

                    <input type="hidden" name="id" value="{{$group->id}}">
            <div class="modal-body">
                <div class="mb-3 row">
                    <div class="col-lg-8">
                        <label for="simpleinput" class="form-label">Group Name</label>
                        <input type="text" id="simpleinput" class="form-control" name="name"
                               placeholder="Enter Group Name" value="{{$group->name}}">
                        @error('name')
                        <small class="text-danger">{{$message}}</small>
                        @enderror
                    </div>

                    <div class="col-lg-4">
                        <label for="example-select" class="form-label">Status</label>
                        <select class="form-select" id="example-select" name="status">
                            <option @if($group->status == 'active') selected @endif value="active">Active</option>
                            <option @if($group->status == 'inactive') selected @endif value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
            </form>
        </div>
    </div>
</div>
