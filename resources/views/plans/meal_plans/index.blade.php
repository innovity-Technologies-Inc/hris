@extends('structure.master')
@section('content')

    {{-- Form for Creating Meal Plan --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Add Meal Plan</h5>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form action="#" method="post">
                                {{-- First Row: Meal Plan Name, Meal Type and Status --}}
                                <div class="mb-3 row">
                                    <div class="col-lg-4">
                                        <label for="meal_plan_name" class="form-label">Meal Plan Name<span class="text-danger">*</span></label>
                                        <input type="text" id="meal_plan_name" class="form-control" name="meal_plan_name"
                                               placeholder="Enter Meal Plan Name">
                                    </div>

                                    <div class="col-lg-4">
                                        <label for="meal_type" class="form-label">Meal Type<span class="text-danger">*</span></label>
                                        <select class="form-select" id="meal_type" name="meal_type">
                                            <option value="">Select Meal Type</option>
                                            <option value="breakfast">Breakfast</option>
                                            <option value="lunch">Lunch</option>
                                            <option value="snacks">Snacks</option>
                                            <option value="dinner">Dinner</option>
                                        </select>
                                    </div>

                                    <div class="col-lg-4">
                                        <label for="status" class="form-label">Status<span class="text-danger">*</span></label>
                                        <select class="form-select" id="status" name="status">
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Second Row: Description (Full Width) --}}
                                <div class="mb-3 row">
                                    <div class="col-lg-12">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea id="description" class="form-control" name="description"
                                                  placeholder="Enter Description" rows="3"></textarea>
                                    </div>
                                </div>

                                {{-- Third Row: Start Time, End Time, Per Meal Cost --}}
                                <div class="mb-3 row">
                                    <div class="col-lg-4">
                                        <label for="start_time" class="form-label">Start Time<span class="text-danger">*</span></label>
                                        <input type="time" id="start_time" class="form-control" name="start_time">
                                    </div>

                                    <div class="col-lg-4">
                                        <label for="end_time" class="form-label">End Time<span class="text-danger">*</span></label>
                                        <input type="time" id="end_time" class="form-control" name="end_time">
                                    </div>

                                    <div class="col-lg-4">
                                        <label for="per_meal_cost" class="form-label">Per Meal Cost (Tk)<span class="text-danger">*</span></label>
                                        <input type="number" id="per_meal_cost" class="form-control" name="per_meal_cost"
                                               placeholder="Enter Cost" step="0.01">
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Meal Plans Tabs with Dummy Data --}}
    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Meal Plans</h5>
                </div>
                <div class="card-body pt-0">
                    <ul class="nav nav-underline border-bottom pt-2" id="meal_plans_tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active p-2" id="breakfast_tab" data-bs-toggle="tab" href="#breakfast"
                               role="tab">
                                <span class="d-none d-sm-block"><i class="mdi mdi-coffee text-warning me-1"></i>Breakfast</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link p-2" id="lunch_tab" data-bs-toggle="tab" href="#lunch"
                               role="tab">
                                <span class="d-none d-sm-block"><i class="mdi mdi-food text-success me-1"></i>Lunch</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link p-2" id="snacks_tab" data-bs-toggle="tab" href="#snacks"
                               role="tab">
                                <span class="d-none d-sm-block"><i class="mdi mdi-cookie text-info me-1"></i>Snacks</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link p-2" id="dinner_tab" data-bs-toggle="tab" href="#dinner"
                               role="tab">
                                <span class="d-none d-sm-block"><i class="mdi mdi-silverware-fork-knife text-primary me-1"></i>Dinner</span>
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content text-muted">
                        {{-- Breakfast Tab --}}
                        <div class="tab-pane active show pt-4" id="breakfast" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-bordered mb-0">
                                    <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Start Time</th>
                                        <th scope="col">End Time</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Per Meal Cost (Tk)</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th scope="row">1</th>
                                            <td>Morning Special</td>
                                            <td>07:00 AM</td>
                                            <td>09:00 AM</td>
                                            <td>
                                                <span class="badge text-bg-success">Active</span>
                                            </td>
                                            <td>50.00</td>
                                            <td>
                                                <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#meal_plan_view1">
                                                    <i style="height: 12px; width: 12px" data-feather="eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#meal_plan_edit1">
                                                    <i style="height: 12px; width: 12px" data-feather="edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger">
                                                    <i style="height: 12px; width: 12px" data-feather="trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">2</th>
                                            <td>Light Breakfast</td>
                                            <td>08:00 AM</td>
                                            <td>10:00 AM</td>
                                            <td>
                                                <span class="badge text-bg-success">Active</span>
                                            </td>
                                            <td>40.00</td>
                                            <td>
                                                <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#meal_plan_view2">
                                                    <i style="height: 12px; width: 12px" data-feather="eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#meal_plan_edit2">
                                                    <i style="height: 12px; width: 12px" data-feather="edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger">
                                                    <i style="height: 12px; width: 12px" data-feather="trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Lunch Tab --}}
                        <div class="tab-pane pt-4" id="lunch" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-bordered mb-0">
                                    <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Start Time</th>
                                        <th scope="col">End Time</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Per Meal Cost (Tk)</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th scope="row">1</th>
                                            <td>Traditional Lunch</td>
                                            <td>12:00 PM</td>
                                            <td>02:00 PM</td>
                                            <td>
                                                <span class="badge text-bg-success">Active</span>
                                            </td>
                                            <td>120.00</td>
                                            <td>
                                                <button type="button" class="btn btn-info btn-sm">
                                                    <i style="height: 12px; width: 12px" data-feather="eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-primary btn-sm">
                                                    <i style="height: 12px; width: 12px" data-feather="edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger">
                                                    <i style="height: 12px; width: 12px" data-feather="trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">2</th>
                                            <td>Premium Lunch</td>
                                            <td>01:00 PM</td>
                                            <td>03:00 PM</td>
                                            <td>
                                                <span class="badge text-bg-danger">Inactive</span>
                                            </td>
                                            <td>150.00</td>
                                            <td>
                                                <button type="button" class="btn btn-info btn-sm">
                                                    <i style="height: 12px; width: 12px" data-feather="eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-primary btn-sm">
                                                    <i style="height: 12px; width: 12px" data-feather="edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger">
                                                    <i style="height: 12px; width: 12px" data-feather="trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">3</th>
                                            <td>Quick Lunch</td>
                                            <td>12:30 PM</td>
                                            <td>01:30 PM</td>
                                            <td>
                                                <span class="badge text-bg-success">Active</span>
                                            </td>
                                            <td>80.00</td>
                                            <td>
                                                <button type="button" class="btn btn-info btn-sm">
                                                    <i style="height: 12px; width: 12px" data-feather="eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-primary btn-sm">
                                                    <i style="height: 12px; width: 12px" data-feather="edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger">
                                                    <i style="height: 12px; width: 12px" data-feather="trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Snacks Tab --}}
                        <div class="tab-pane pt-4" id="snacks" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-bordered mb-0">
                                    <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Start Time</th>
                                        <th scope="col">End Time</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Per Meal Cost (Tk)</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th scope="row">1</th>
                                            <td>Evening Snacks</td>
                                            <td>04:00 PM</td>
                                            <td>05:30 PM</td>
                                            <td>
                                                <span class="badge text-bg-success">Active</span>
                                            </td>
                                            <td>30.00</td>
                                            <td>
                                                <button type="button" class="btn btn-info btn-sm">
                                                    <i style="height: 12px; width: 12px" data-feather="eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-primary btn-sm">
                                                    <i style="height: 12px; width: 12px" data-feather="edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger">
                                                    <i style="height: 12px; width: 12px" data-feather="trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Dinner Tab --}}
                        <div class="tab-pane pt-4" id="dinner" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-bordered mb-0">
                                    <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Start Time</th>
                                        <th scope="col">End Time</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Per Meal Cost (Tk)</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th scope="row">1</th>
                                            <td>Healthy Dinner</td>
                                            <td>07:00 PM</td>
                                            <td>09:00 PM</td>
                                            <td>
                                                <span class="badge text-bg-success">Active</span>
                                            </td>
                                            <td>100.00</td>
                                            <td>
                                                <button type="button" class="btn btn-info btn-sm">
                                                    <i style="height: 12px; width: 12px" data-feather="eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-primary btn-sm">
                                                    <i style="height: 12px; width: 12px" data-feather="edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger">
                                                    <i style="height: 12px; width: 12px" data-feather="trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">2</th>
                                            <td>Premium Dinner</td>
                                            <td>08:00 PM</td>
                                            <td>10:00 PM</td>
                                            <td>
                                                <span class="badge text-bg-danger">Inactive</span>
                                            </td>
                                            <td>180.00</td>
                                            <td>
                                                <button type="button" class="btn btn-info btn-sm">
                                                    <i style="height: 12px; width: 12px" data-feather="eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-primary btn-sm">
                                                    <i style="height: 12px; width: 12px" data-feather="edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger">
                                                    <i style="height: 12px; width: 12px" data-feather="trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Include Modals --}}
    @include('plans.meal_plans.modal.meal_plan_view')
    @include('plans.meal_plans.modal.meal_plan_edit')

@endsection
