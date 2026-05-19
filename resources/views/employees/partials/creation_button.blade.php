@php
    $isOwner = isset($employee) && (auth()->user()->employee_id == $employee->id || auth()->user()->id == $employee->user_id);
    $canCreate = auth()->user()->can('employee-management.create');
@endphp

@if($isOwner || $canCreate)
<div class="card shadow-sm border-0">

    <div class="card-body p-4">
        <div class="row g-3 row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-5">
            <div class="col">
                <a href="{{ Route::is('employees.profile.*') ? route('employees.profile.general_informations', $employee->id) : 'javascript:void(0)' }}"
                    class="btn btn-outline-secondary w-100 py-3 text-decoration-none
            @if (request()->routeIs('employees.general_informations.create') ||
                    request()->routeIs('employees.profile.general_informations')) active @endif">
                    <span class="badge bg-secondary rounded-circle me-2">1</span>
                    General Information
                </a>
            </div>

            @if (isset($employee->id))
                <div class="col">
                    <a href="{{ route('employees.profile.education_information', $employee->id) }}"
                        class="btn btn-outline-secondary w-100 py-3 text-decoration-none @if (request()->routeIs('employees.education_information.create') ||
                                request()->routeIs('employees.profile.education_information')) active @endif">
                        <span class="badge bg-secondary rounded-circle me-2">2</span>
                        Education Information
                    </a>
                </div>
            @else
                <div class="col">
                    <button type="submit"
                        class="btn btn-outline-secondary w-100 py-3 text-decoration-none information">
                        <span class="badge bg-secondary rounded-circle me-2">2</span>
                        Education Information
                    </button>
                </div>
            @endif

            @if (isset($employee->id))
                <div class="col">
                    <a href="{{ route('employees.profile.office_informations', $employee->id) }}"
                        class="btn btn-outline-secondary w-100 py-3 text-decoration-none @if (request()->routeIs('employees.office_informations.create') ||
                                request()->routeIs('employees.profile.office_informations')) active @endif">
                        <span class="badge bg-secondary rounded-circle me-2">3</span>
                        Office Information
                    </a>
                </div>
            @else
                <div class="col">
                    <button type="submit"
                        class="btn btn-outline-secondary w-100 py-3 text-decoration-none information">
                        <span class="badge bg-secondary rounded-circle me-2">3</span>
                        Office Information
                    </button>
                </div>
            @endif

            @if (isset($employee->id))
                <div class="col">
                    <a href="{{ route('employees.profile.eligible_plans', $employee->id) }}"
                        class="btn btn-outline-secondary w-100 py-3 text-decoration-none @if (request()->routeIs('employees.eligible_plans.create') || request()->routeIs('employees.profile.eligible_plans')) active @endif">
                        <span class="badge bg-secondary rounded-circle me-2">4</span>
                        Policy Tag
                    </a>
                </div>
            @else
                <div class="col">
                    <button type="submit"
                        class="btn btn-outline-secondary w-100 py-3 text-decoration-none information">
                        <span class="badge bg-secondary rounded-circle me-2">4</span>
                        Policy Tag
                    </button>
                </div>
            @endif

            @if (isset($employee->id))
                <div class="col">
                    <a href="{{ route('employees.profile.nominee_information', $employee->id) }}"
                        class="btn btn-outline-secondary w-100 py-3 text-decoration-none @if (request()->routeIs('employees.nominee_information.create') ||
                                request()->routeIs('employees.profile.nominee_information')) active @endif">
                        <span class="badge bg-secondary rounded-circle me-2">5</span>
                        Nominee Information
                    </a>
                </div>
            @else
                <div class="col">
                    <button type="submit"
                        class="btn btn-outline-secondary w-100 py-3 text-decoration-none information">
                        <span class="badge bg-secondary rounded-circle me-2">5</span>
                        Nominee Information
                    </button>
                </div>
            @endif

            @if (isset($employee->id))
                <div class="col">
                    <a href="{{ route('employees.profile.salary_breakdown', $employee->id) }}"
                        class="btn btn-outline-secondary w-100 py-3 text-decoration-none @if (request()->routeIs('employees.salary_breakdown.create') || request()->routeIs('employees.profile.salary_breakdown')) active @endif">
                        <span class="badge bg-secondary rounded-circle me-2">6</span>
                        Salary Breakdown
                    </a>
                </div>
            @else
                <div class="col">
                    <button type="submit"
                        class="btn btn-outline-secondary w-100 py-3 text-decoration-none information">
                        <span class="badge bg-secondary rounded-circle me-2">6</span>
                        Salary Breakdown
                    </button>
                </div>
            @endif

            @if (isset($employee->id))
                <div class="col">
                    <a href="{{ route('employees.profile.bank_accounts', $employee->id) }}"
                        class="btn btn-outline-secondary w-100 py-3 text-decoration-none @if (request()->routeIs('employees.bank_accounts.create') || request()->routeIs('employees.profile.bank_accounts')) active @endif">
                        <span class="badge bg-secondary rounded-circle me-2">7</span>
                        Accounts Information
                    </a>
                </div>
            @else
                <div class="col">
                    <button type="submit"
                        class="btn btn-outline-secondary w-100 py-3 text-decoration-none information">
                        <span class="badge bg-secondary rounded-circle me-2">7</span>
                        Accounts Information
                    </button>
                </div>
            @endif


            @if (isset($employee->id))
                <div class="col">
                    <a href="{{ route('employees.profile.plans', ['id' => $employee->id, 'type' => 'meal-plans']) }}"
                        class="btn btn-outline-secondary w-100 py-3 text-decoration-none @if (request()->routeIs('employees.profile.plans')) active @endif">
                        <span class="badge bg-secondary rounded-circle me-2">8</span>
                        Plans
                    </a>
                </div>
            @else
                <div class="col">
                    <button type="submit"
                        class="btn btn-outline-secondary w-100 py-3 text-decoration-none information">
                        <span class="badge bg-secondary rounded-circle me-2">8</span>
                        Plans
                    </button>
                </div>
            @endif

            @if (isset($employee->id))
                <div class="col">
                    <a href="{{ route('employees.profile.leave_info', $employee->id) }}"
                        class="btn btn-outline-secondary w-100 py-3 text-decoration-none @if (request()->routeIs('employees.profile.leave_info')) active @endif">
                        <span class="badge bg-secondary rounded-circle me-2">9</span>
                        Leave Info
                    </a>
                </div>
            @else
                <div class="col">
                    <button type="submit"
                        class="btn btn-outline-secondary w-100 py-3 text-decoration-none information">
                        <span class="badge bg-secondary rounded-circle me-2">9</span>
                        Leave Info
                    </button>
                </div>
            @endif


        </div>
    </div>

</div>
<script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>

<script>
    $(document).ready(function() {
        $('.information').on('click', function() {
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
@endif
