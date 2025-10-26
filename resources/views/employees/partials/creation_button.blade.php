<div class="card shadow-sm border-0">
    <div class="card-header bg-white border-bottom py-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h5 class="card-title mb-1 fw-semibold">Employee Information Creation</h5>
                <p class="text-muted mb-0 small">Complete all sections to create employee profile</p>
            </div>
        </div>
    </div>

   <div class="card-body p-4">
    <div class="row g-3 row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-5">
        <div class="col">
            <a href="{{isset($employee_id) ? route('employees.general_informations.edit', $employee_id) : route('employees.general_informations.create')}}" class="btn btn-outline-secondary w-100 py-3 text-decoration-none
            @if(request()->routeIs('employees.general_informations.create') || request()->routeIs('employees.general_informations.edit')) active @endif">
                <span class="badge bg-secondary rounded-circle me-2">1</span>
                General Information
            </a>
        </div>

        <div class="col">
            <a href="{{isset($employee_id) ? route('employees.office_informations.edit', $employee_id) : route('employees.office_informations.create')}}"
               class="btn btn-outline-secondary w-100 py-3 text-decoration-none @if(request()->routeIs('employees.office_informations.create') || request()->routeIs('employees.office_informations.edit')) active @endif">
                <span class="badge bg-secondary rounded-circle me-2">2</span>
                Office Information
            </a>
        </div>

        <div class="col">
            <a href="#eligible-plans-information" class="btn btn-outline-secondary w-100 py-3 text-decoration-none">
                <span class="badge bg-secondary rounded-circle me-2">3</span>
                Eligible Plans
            </a>
        </div>

        <div class="col">
            <a href="#network-information" class="btn btn-outline-secondary w-100 py-3 text-decoration-none">
                <span class="badge bg-secondary rounded-circle me-2">4</span>
                Network Information
            </a>
        </div>

        <div class="col">
            <a href="#nominee-information" class="btn btn-outline-secondary w-100 py-3 text-decoration-none">
                <span class="badge bg-secondary rounded-circle me-2">5</span>
                Nominee Information
            </a>
        </div>
    </div>
</div>

</div>
