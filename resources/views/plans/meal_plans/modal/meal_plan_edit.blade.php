{{-- Edit Modal 1 --}}
<div class="modal fade" id="meal_plan_edit1" tabindex="-1" aria-labelledby="editModalLabel1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel1">Edit Meal Plan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="#" method="POST">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-4 mb-3">
                            <label class="form-label">Meal Plan Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="meal_plan_name" value="Morning Special">
                        </div>

                        <div class="col-lg-4 mb-3">
                            <label class="form-label">Meal Type<span class="text-danger">*</span></label>
                            <select class="form-select" name="meal_type">
                                <option value="breakfast" selected>Breakfast</option>
                                <option value="lunch">Lunch</option>
                                <option value="snacks">Snacks</option>
                                <option value="dinner">Dinner</option>
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
                            <textarea class="form-control" name="description" rows="3">Paratha, Egg & Tea - A traditional morning breakfast with freshly made paratha, boiled eggs, and hot tea.</textarea>
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
                            <label class="form-label">Per Meal Cost (Tk)<span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="per_meal_cost" value="50.00" step="0.01">
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

{{-- Edit Modal 2 --}}
<div class="modal fade" id="meal_plan_edit2" tabindex="-1" aria-labelledby="editModalLabel2" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel2">Edit Meal Plan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="#" method="POST">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-4 mb-3">
                            <label class="form-label">Meal Plan Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="meal_plan_name" value="Light Breakfast">
                        </div>

                        <div class="col-lg-4 mb-3">
                            <label class="form-label">Meal Type<span class="text-danger">*</span></label>
                            <select class="form-select" name="meal_type">
                                <option value="breakfast" selected>Breakfast</option>
                                <option value="lunch">Lunch</option>
                                <option value="snacks">Snacks</option>
                                <option value="dinner">Dinner</option>
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
                            <textarea class="form-control" name="description" rows="3">Bread, Butter & Milk - A light and healthy breakfast option with fresh bread, butter, and warm milk.</textarea>
                        </div>

                        <div class="col-lg-4 mb-3">
                            <label class="form-label">Start Time<span class="text-danger">*</span></label>
                            <input type="time" class="form-control" name="start_time" value="08:00">
                        </div>

                        <div class="col-lg-4 mb-3">
                            <label class="form-label">End Time<span class="text-danger">*</span></label>
                            <input type="time" class="form-control" name="end_time" value="10:00">
                        </div>

                        <div class="col-lg-4 mb-3">
                            <label class="form-label">Per Meal Cost (Tk)<span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="per_meal_cost" value="40.00" step="0.01">
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
