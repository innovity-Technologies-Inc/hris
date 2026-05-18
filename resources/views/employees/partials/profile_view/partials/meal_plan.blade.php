{{-- Meal Plan Assignment Interface --}}

<div class="row">

    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="d-flex align-items-center">
                <i class="mdi mdi-food-apple text-primary fs-4 me-2"></i>
                <h5 class="fs-16 text-dark fw-semibold mb-0">Meal Plan Management</h5>
            </div>

        </div>
        @can('employee-management.edit')
            <div>
                {{-- Create Button to Open Modal --}}
                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#createMealPlanModal">
                    <i class="mdi mdi-plus-circle me-1"></i> Create New
                </button>
            </div>
        @endcan
    </div>
</div>

{{-- Active Meal Plans Section --}}
<div class="row mb-4 mt-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-success-subtle">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <i class="mdi mdi-check-circle text-success fs-5 me-2"></i>
                        <h6 class="mb-0 fw-semibold text-success">Active Meal Plan Assignments</h6>
                    </div>
                    <span class="badge bg-success">{{ $totalActiveMealPlan }} Active</span>
                </div>
            </div>
            <div class="card-body p-0">
                @if ($totalActiveMealPlan > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>SL</th>
                                    <th>Meal Type</th>
                                    <th>Plan Name</th>
                                    <th>Daily Cost</th>
                                    <th>Effective From</th>
                                    <th>Effective To</th>
                                    <th>Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $sl = 1;
                                @endphp
                                @foreach ($activeMealPlans as $plan)
                                    <tr>
                                        <td><span
                                                class="badge bg-secondary-subtle text-secondary">#{{ $sl++ }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-secondary">
                                                <i
                                                    class="mdi mdi-silverware-fork-knife me-1"></i>{{ $plan->getPlan->type }}
                                            </span>
                                        </td>
                                        <td><strong>{{ $plan->getPlan->name }}</strong></td>
                                        <td><span class="text-success fw-semibold">{{ \App\HelperClass::getCurrency() }}
                                                {{ number_format($plan->getPlan->cost ?? 0) }}</span>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($plan->from)->format('jS F Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($plan->to)->format('jS F Y') }}</td>
                                        <td>
                                            <span class="badge bg-success">
                                                <i class="mdi mdi-check-circle me-1"></i>{{ $plan->status }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @can('employee-management.edit')
                                                <form
                                                    action="{{ route('employees.profile.plans.remove', ['id' => $plan->id, 'type' => 'meal-plans']) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('put')
                                                    <button type="submit"
                                                        class="btn btn-sm btn-outline-danger shadow-sm removeBtn">
                                                        <i class="mdi mdi-close-circle"></i> Remove
                                                    </button>
                                                </form>
                                            @else
                                                <span class="badge bg-light text-muted">No Actions</span>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info m-3">
                        <i class="mdi mdi-information-outline me-2"></i>
                        No active meal plan assignments found.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Previous/Expired Meal Plans Section --}}
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-secondary-subtle">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <i class="mdi mdi-history text-secondary fs-5 me-2"></i>
                        <h6 class="mb-0 fw-semibold text-secondary">Previous Meal Plan Assignments</h6>
                    </div>
                    <span class="badge bg-secondary">{{ $totalPreviousMealPlan }} Records</span>
                </div>
            </div>
            <div class="card-body p-0">
                @if ($totalPreviousMealPlan > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>SL</th>
                                    <th>Meal Type</th>
                                    <th>Plan Name</th>
                                    <th>Daily Cost</th>
                                    <th>Effective From</th>
                                    <th>Effective To</th>
                                    <th>Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $sl = 1;
                                @endphp
                                @foreach ($previousMealPlans as $plan)
                                    <tr class="text-muted">
                                        <td><span
                                                class="badge bg-secondary-subtle text-secondary">#{{ $sl++ }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-secondary">
                                                <i
                                                    class="mdi mdi-silverware-fork-knife me-1"></i>{{ $plan->getPlan->type }}
                                            </span>
                                        </td>
                                        <td>{{ $plan->getPlan->name }}</td>
                                        <td><span
                                                class="text-success fw-semibold">{{ \App\HelperClass::getCurrency() }}
                                                {{ number_format($plan->getPlan->cost ?? 0) }}</span>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($plan->from)->format('jS F Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($plan->to)->format('jS F Y') }}</td>
                                        <td>
                                            @if ($plan->status == 'inactive')
                                                <span class="badge bg-warning-subtle text-warning">
                                                    <i class="mdi mdi-clock-alert-outline me-1"></i>{{ $plan->status }}
                                                </span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger">
                                                    <i class="mdi mdi-close-circle me-1"></i>{{ $plan->status }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @can('employee-management.edit')
                                                <form
                                                    action="{{ route('employees.profile.plans.delete', ['type' => 'meal-plans', 'id' => $plan->id]) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="btn btn-sm btn-danger confirmDelete"
                                                        title="Delete Record">
                                                        <i class="mdi mdi-delete"></i> Delete
                                                    </button>
                                                </form>
                                            @else
                                                <span class="badge bg-light text-muted">No Actions</span>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-secondary m-3">
                        <i class="mdi mdi-information-outline me-2"></i>
                        No previous meal plan assignments found.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Include the Create Modal --}}
@include('employees.partials.modal.create_meal_modal')
