<div class="card shadow-sm border-0">

   <div class="card-body p-4">
    <div class="row g-3 row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-5">
        <div class="col">
            <a href="{{Route::is('employees.profile.*') ? route('employees.profile.general_informations', $employee->id) : route('employees.general_informations.create') }}" class="btn btn-outline-secondary w-100 py-3 text-decoration-none
            @if(request()->routeIs('employees.general_informations.create') || request()->routeIs('employees.profile.general_informations')) active @endif">
                <span class="badge bg-secondary rounded-circle me-2">1</span>
                General Information
            </a>
        </div>

        @if(isset($employee->id))
        <div class="col">
            <a href="{{Route::is('employees.profile.*') ? route('employees.profile.office_informations', $employee->id) : route('employees.office_informations.edit', $employee->id)}}"
               class="btn btn-outline-secondary w-100 py-3 text-decoration-none @if(request()->routeIs('employees.office_informations.create') || request()->routeIs('employees.office_informations.edit')) active @endif">
                <span class="badge bg-secondary rounded-circle me-2">2</span>
                Office Information
            </a>
        </div>
         @else
        <div class="col">
            <button type="submit"
               class="btn btn-outline-secondary w-100 py-3 text-decoration-none" id="office-information">
                <span class="badge bg-secondary rounded-circle me-2">2</span>
                Office Information
            </button>
        </div>
        @endif
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
<script src="{{asset('assets/libs/jquery/jquery.min.js')}}"></script>

<script>
    $(document).ready(function() {
        $('#office-information').on('click', function() {
        Swal.fire({
            title: 'Access Denied',
            text: 'Please complete general sections first',
            icon: 'warning',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'OK'
        })
    })
    });
</script>

