<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body pt-0">
                {{-- Tab Navigation --}}
                <ul class="nav nav-underline border-bottom pt-2  mb-3" id="plan-tabs" role="tablist">

                    <li class="nav-item" role="presentation">
                        <a class="nav-link @if($type == 'meal-plans') active @endif  p-2"
                           href="{{route('employees.profile.plans', ['id' => $employee->id, 'type' => 'meal-plans'])}}">
                            <span class="d-none d-sm-block">Meal Plan</span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link @if($type == 'shift-plans') active @endif p-2"
                           href="{{route('employees.profile.plans', ['id' => $employee->id, 'type' => 'shift-plans'])}}">
                            <span class="d-none d-sm-block">Shift Plan</span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link @if($type == 'roster-plans') active @endif p-2"
                           href="{{route('employees.profile.plans', ['id' => $employee->id, 'type' => 'roster-plans'])}}">
                            <span class="d-none d-sm-block">Roster Plan</span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link @if($type == 'ot-plans') active @endif p-2"
                           href="{{route('employees.profile.plans', ['id' => $employee->id, 'type' => 'ot-plans'])}}">
                            <span class="d-none d-sm-block">OT Plan</span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link @if($type == 'offday-plans') active @endif p-2"
                           href="{{route('employees.profile.plans', ['id' => $employee->id, 'type' => 'offday-plans'])}}">
                            <span class="d-none d-sm-block">Off Day Work Plan</span>
                        </a>
                    </li>


                </ul>

                {{-- Tab Content --}}
                <div class="tab-content text-muted">
                    @if($type == 'meal-plans')
                        @include('employees.partials.profile_view.partials.meal_plan')

                    @elseif($type == 'shift-plans')
                        @include('employees.partials.profile_view.partials.shift_plan')

                    @elseif($type == 'roster-plans')
                        @include('employees.partials.profile_view.partials.roster_plan')

                    @elseif($type == 'ot-plans')
                        @include('employees.partials.profile_view.partials.ot_plan')

                    @elseif($type == 'offday-plans')
                        @include('employees.partials.profile_view.partials.offday_plan')

                    @endif


                </div>
            </div>
        </div>
    </div>
</div>
