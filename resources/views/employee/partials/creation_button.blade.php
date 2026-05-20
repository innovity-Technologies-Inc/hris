<div class="card shadow-sm border-0">

    <div class="card-body p-4">
        <div class="row g-3 row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-5">
            {{-- 1. General Information --}}
            <div class="col">
                <a href="{{ Route::is('employee.profile.*') ? route('employee.profile.general_informations', $employee->id) : 'javascript:void(0)' }}"
                    class="btn btn-outline-secondary w-100 py-3 text-decoration-none
            @if (request()->routeIs('employee.general_informations.create') ||
                    request()->routeIs('employee.profile.general_informations')) active @endif">
                    <span class="badge bg-secondary rounded-circle me-2 px-2 py-1">1</span>
                    General Information
                </a>
            </div>

            {{-- 2. Education Information --}}
            @if (isset($employee->id))
                <div class="col">
                    <a href="{{ route('employee.profile.education_information', $employee->id) }}"
                        class="btn btn-outline-secondary w-100 py-3 text-decoration-none @if (request()->routeIs('employee.education_information.create') ||
                                request()->routeIs('employee.profile.education_information')) active @endif">
                        <span class="badge bg-secondary rounded-circle me-2 px-2 py-1">2</span>
                        Education Information
                    </a>
                </div>
            @else
                <div class="col">
                    <button type="submit"
                        class="btn btn-outline-secondary w-100 py-3 text-decoration-none information">
                        <span class="badge bg-secondary rounded-circle me-2 px-2 py-1">2</span>
                        Education Information
                    </button>
                </div>
            @endif

            {{-- 3. Employment History --}}
            @if (isset($employee->id))
                <div class="col">
                    <a href="{{ route('employee.profile.employment_history', $employee->id) }}"
                        class="btn btn-outline-secondary w-100 py-3 text-decoration-none @if (request()->routeIs('employee.employment_history.create') ||
                                request()->routeIs('employee.profile.employment_history')) active @endif">
                        <span class="badge bg-secondary rounded-circle me-2 px-2 py-1">3</span>
                        Employment History
                    </a>
                </div>
            @else
                <div class="col">
                    <button type="submit"
                        class="btn btn-outline-secondary w-100 py-3 text-decoration-none information">
                        <span class="badge bg-secondary rounded-circle me-2 px-2 py-1">3</span>
                        Employment History
                    </button>
                </div>
            @endif

            {{-- 4. Nominee Information --}}
            @if (isset($employee->id))
                <div class="col">
                    <a href="{{ route('employee.profile.nominee_information', $employee->id) }}"
                        class="btn btn-outline-secondary w-100 py-3 text-decoration-none @if (request()->routeIs('employee.nominee_information.create') ||
                                request()->routeIs('employee.profile.nominee_information')) active @endif">
                        <span class="badge bg-secondary rounded-circle me-2 px-2 py-1">4</span>
                        Nominee Information
                    </a>
                </div>
            @else
                <div class="col">
                    <button type="submit"
                        class="btn btn-outline-secondary w-100 py-3 text-decoration-none information">
                        <span class="badge bg-secondary rounded-circle me-2 px-2 py-1">4</span>
                        Nominee Information
                    </button>
                </div>
            @endif

            {{-- 5. Office Information --}}
            @if (isset($employee->id) && (auth()->user()->user_type !== 'Employee' || $employee->status === 'active'))
                <div class="col">
                    <a href="{{ route('employee.profile.office_informations', $employee->id) }}"
                        class="btn btn-outline-secondary w-100 py-3 text-decoration-none @if (request()->routeIs('employee.office_informations.create') ||
                                request()->routeIs('employee.profile.office_informations')) active @endif">
                        <span class="badge bg-secondary rounded-circle me-2 px-2 py-1">5</span>
                        Office Information
                    </a>
                </div>
            @elseif(!isset($employee->id))
                <div class="col">
                    <button type="submit"
                        class="btn btn-outline-secondary w-100 py-3 text-decoration-none information">
                        <span class="badge bg-secondary rounded-circle me-2 px-2 py-1">5</span>
                        Office Information
                    </button>
                </div>
            @endif

            {{-- 6. Policy Tag --}}
            @if (isset($employee->id) && (auth()->user()->user_type !== 'Employee' || $employee->status === 'active'))
                <div class="col">
                    <a href="{{ route('employee.profile.eligible_plans', $employee->id) }}"
                        class="btn btn-outline-secondary w-100 py-3 text-decoration-none @if (request()->routeIs('employee.eligible_plans.create') || request()->routeIs('employee.profile.eligible_plans')) active @endif">
                        <span class="badge bg-secondary rounded-circle me-2 px-2 py-1">6</span>
                        Policy Tag
                    </a>
                </div>
            @elseif(!isset($employee->id))
                <div class="col">
                    <button type="submit"
                        class="btn btn-outline-secondary w-100 py-3 text-decoration-none information">
                        <span class="badge bg-secondary rounded-circle me-2 px-2 py-1">6</span>
                        Policy Tag
                    </button>
                </div>
            @endif

            {{-- 7. Salary Breakdown --}}
            @if (isset($employee->id) && (auth()->user()->user_type !== 'Employee' || $employee->status === 'active'))
                <div class="col">
                    <a href="{{ route('employee.profile.salary_breakdown', $employee->id) }}"
                        class="btn btn-outline-secondary w-100 py-3 text-decoration-none @if (request()->routeIs('employee.salary_breakdown.create') || request()->routeIs('employee.profile.salary_breakdown')) active @endif">
                        <span class="badge bg-secondary rounded-circle me-2 px-2 py-1">7</span>
                        Salary Breakdown
                    </a>
                </div>
            @elseif(!isset($employee->id))
                <div class="col">
                    <button type="submit"
                        class="btn btn-outline-secondary w-100 py-3 text-decoration-none information">
                        <span class="badge bg-secondary rounded-circle me-2 px-2 py-1">7</span>
                        Salary Breakdown
                    </button>
                </div>
            @endif

            {{-- 8. Accounts Information --}}
            @if (isset($employee->id) && (auth()->user()->user_type !== 'Employee' || $employee->status === 'active'))
                <div class="col">
                    <a href="{{ route('employee.profile.bank_accounts', $employee->id) }}"
                        class="btn btn-outline-secondary w-100 py-3 text-decoration-none @if (request()->routeIs('employee.bank_accounts.create') || request()->routeIs('employee.profile.bank_accounts')) active @endif">
                        <span class="badge bg-secondary rounded-circle me-2 px-2 py-1">8</span>
                        Accounts Information
                    </a>
                </div>
            @elseif(!isset($employee->id))
                <div class="col">
                    <button type="submit"
                        class="btn btn-outline-secondary w-100 py-3 text-decoration-none information">
                        <span class="badge bg-secondary rounded-circle me-2 px-2 py-1">8</span>
                        Accounts Information
                    </button>
                </div>
            @endif


            {{-- 9. Plans --}}
            @if (isset($employee->id) && (auth()->user()->user_type !== 'Employee' || $employee->status === 'active'))
                <div class="col">
                    <a href="{{ route('employee.profile.plans', ['id' => $employee->id, 'type' => 'meal-plans']) }}"
                        class="btn btn-outline-secondary w-100 py-3 text-decoration-none @if (request()->routeIs('employee.profile.plans')) active @endif">
                        <span class="badge bg-secondary rounded-circle me-2 px-2 py-1">9</span>
                        Plans
                    </a>
                </div>
            @elseif(!isset($employee->id))
                <div class="col">
                    <button type="submit"
                        class="btn btn-outline-secondary w-100 py-3 text-decoration-none information">
                        <span class="badge bg-secondary rounded-circle me-2 px-2 py-1">9</span>
                        Plans
                    </button>
                </div>
            @endif

            {{-- 10. Leave Info --}}
            @if (isset($employee->id) && (auth()->user()->user_type !== 'Employee' || $employee->status === 'active'))
                <div class="col">
                    <a href="{{ route('employee.profile.leave_info', $employee->id) }}"
                        class="btn btn-outline-secondary w-100 py-3 text-decoration-none @if (request()->routeIs('employee.profile.leave_info')) active @endif">
                        <span class="badge bg-secondary rounded-circle me-2 px-2 py-1">10</span>
                        Leave Info
                    </a>
                </div>
            @elseif(!isset($employee->id))
                <div class="col">
                    <button type="submit"
                        class="btn btn-outline-secondary w-100 py-3 text-decoration-none information">
                        <span class="badge bg-secondary rounded-circle me-2 px-2 py-1">10</span>
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

