<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body pt-0">
                {{-- Tab Navigation --}}
                <ul class="nav nav-underline border-bottom pt-2" id="plan-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active p-2" id="meal-plan-tab" data-bs-toggle="tab" href="#meal-plan"
                           role="tab" aria-controls="meal-plan" aria-selected="true">
                            <span class="d-none d-sm-block">Meal Plan</span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link p-2" id="shift-plan-tab" data-bs-toggle="tab" href="#shift-plan"
                           role="tab" aria-controls="shift-plan" aria-selected="false">
                            <span class="d-none d-sm-block">Shift Plan</span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link p-2" id="roster-plan-tab" data-bs-toggle="tab" href="#roster-plan"
                           role="tab" aria-controls="roster-plan" aria-selected="false">
                            <span class="d-none d-sm-block">Roster Plan</span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link p-2" id="ot-plan-tab" data-bs-toggle="tab" href="#ot-plan" role="tab"
                           aria-controls="ot-plan" aria-selected="false">
                            <span class="d-none d-sm-block">OT Plan</span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link p-2" id="offday-plan-tab" data-bs-toggle="tab" href="#offday-plan"
                           role="tab" aria-controls="offday-plan" aria-selected="false">
                            <span class="d-none d-sm-block">Off Day Plan</span>
                        </a>
                    </li>
                </ul>

                {{-- Tab Content --}}
                <div class="tab-content text-muted">
                    {{-- Meal Plan Tab --}}
                    <div class="tab-pane active show pt-4" id="meal-plan" role="tabpanel"
                         aria-labelledby="meal-plan-tab">
                        @include('employees.partials.profile_view.partials.meal_plan')
                    </div>

                    {{-- Shift Plan Tab --}}
                    <div class="tab-pane pt-4" id="shift-plan" role="tabpanel" aria-labelledby="shift-plan-tab">
                        @include('employees.partials.profile_view.partials.shift_plan')
                    </div>

                    {{-- Roster Plan Tab --}}
                    <div class="tab-pane pt-4" id="roster-plan" role="tabpanel" aria-labelledby="roster-plan-tab">
                        @include('employees.partials.profile_view.partials.roster_plan')
                    </div>

                    {{-- OT Plan Tab --}}
                    <div class="tab-pane pt-4" id="ot-plan" role="tabpanel" aria-labelledby="ot-plan-tab">
                        @include('employees.partials.profile_view.partials.ot_plan')
                    </div>

                    {{-- Off Day Plan Tab --}}
                    <div class="tab-pane pt-4" id="offday-plan" role="tabpanel" aria-labelledby="offday-plan-tab">
                        @include('employees.partials.profile_view.partials.offday_plan')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
