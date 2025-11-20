@php
     // ========================================
    // SHIFT PLAN DATA
    // ========================================

    $availableShiftPlans = [
        [
            'id' => 1,
            'name' => 'Day Shift',
            'clock_in_time' => '08:00',
            'clock_out_time' => '16:00',
            'grace_time' => 15,
            'work_hours' => 8,
        ],
        [
            'id' => 2,
            'name' => 'Evening Shift',
            'clock_in_time' => '16:00',
            'clock_out_time' => '00:00',
            'grace_time' => 15,
            'work_hours' => 8,
        ],
        [
            'id' => 3,
            'name' => 'Night Shift',
            'clock_in_time' => '00:00',
            'clock_out_time' => '08:00',
            'grace_time' => 10,
            'work_hours' => 8,
        ],
        [
            'id' => 4,
            'name' => 'Flexible Shift',
            'clock_in_time' => '09:00',
            'clock_out_time' => '18:00',
            'grace_time' => 30,
            'work_hours' => 9,
        ],
    ];

    $activeShiftPlans = [
        [
            'id' => 301,
            'shift_name' => 'Day Shift',
            'clock_in' => '08:00',
            'clock_out' => '16:00',
            'effective_from' => '2025-01-01',
            'effective_to' => '2025-12-31',
            'status' => 'Active',
        ],
        [
            'id' => 302,
            'shift_name' => 'Evening Shift',
            'clock_in' => '16:00',
            'clock_out' => '00:00',
            'effective_from' => '2025-02-01',
            'effective_to' => '2025-07-31',
            'status' => 'Active',
        ],
    ];

    $previousShiftPlans = [
        [
            'id' => 401,
            'shift_name' => 'Night Shift',
            'clock_in' => '00:00',
            'clock_out' => '08:00',
            'effective_from' => '2024-01-01',
            'effective_to' => '2024-12-31',
            'status' => 'Expired',
        ],
        [
            'id' => 402,
            'shift_name' => 'Day Shift',
            'clock_in' => '08:00',
            'clock_out' => '16:00',
            'effective_from' => '2023-06-01',
            'effective_to' => '2024-05-31',
            'status' => 'Expired',
        ],
    ];

    // ========================================
    // ROSTER PLAN DATA
    // ========================================

    $availableRosterPlans = [
        (object) [
            'id' => 1,
            'plan_name' => 'Standard Weekly Roster',
            'short_name' => 'SWR',
            'repetition_days' => 7,
            'description' => 'Mon-Fri work, Sat-Sun off',
            'shift_1_name' => 'Day Shift',
            'shift_1_clock_in' => '08:00:00',
            'shift_1_clock_out' => '16:00:00',
            'shift_2_name' => null,
            'shift_2_clock_in' => null,
            'shift_2_clock_out' => null,
        ],
        (object) [
            'id' => 2,
            'plan_name' => 'Bi-Weekly Rotating',
            'short_name' => 'BWR',
            'repetition_days' => 14,
            'description' => 'Alternating week schedules',
            'shift_1_name' => 'Morning Shift',
            'shift_1_clock_in' => '06:00:00',
            'shift_1_clock_out' => '14:00:00',
            'shift_2_name' => 'Night Shift',
            'shift_2_clock_in' => '22:00:00',
            'shift_2_clock_out' => '06:00:00',
        ],
        (object) [
            'id' => 3,
            'plan_name' => '6 Days Work Pattern',
            'short_name' => '6DWP',
            'repetition_days' => 7,
            'description' => 'Work 6 days, 1 day off',
            'shift_1_name' => 'Day Shift',
            'shift_1_clock_in' => '08:00:00',
            'shift_1_clock_out' => '16:00:00',
            'shift_2_name' => null,
            'shift_2_clock_in' => null,
            'shift_2_clock_out' => null,
        ],
        (object) [
            'id' => 4,
            'plan_name' => 'Triple Shift Rotation',
            'short_name' => 'TSR',
            'repetition_days' => 21,
            'description' => '3-week rotation cycle',
            'shift_1_name' => 'Morning Shift',
            'shift_1_clock_in' => '06:00:00',
            'shift_1_clock_out' => '14:00:00',
            'shift_2_name' => 'Evening Shift',
            'shift_2_clock_in' => '14:00:00',
            'shift_2_clock_out' => '22:00:00',
        ],
    ];
    $activeRosterPlans = [
        [
            'id' => 501,
            'plan_name' => 'Standard Weekly Roster',
            'short_name' => 'SWR',
            'repetition_days' => 7,
            'effective_from' => '2025-01-01',
            'effective_to' => '2025-06-30',
            'status' => 'Active',
        ],
        [
            'id' => 502,
            'plan_name' => 'Bi-Weekly Rotating',
            'short_name' => 'BWR',
            'repetition_days' => 14,
            'effective_from' => '2025-01-15',
            'effective_to' => '2025-12-31',
            'status' => 'Active',
        ],
    ];

    $previousRosterPlans = [
        [
            'id' => 601,
            'plan_name' => '6 Days Work Pattern',
            'short_name' => '6DWP',
            'repetition_days' => 7,
            'effective_from' => '2024-01-01',
            'effective_to' => '2024-12-31',
            'status' => 'Expired',
        ],
        [
            'id' => 602,
            'plan_name' => 'Standard Weekly Roster',
            'short_name' => 'SWR',
            'repetition_days' => 7,
            'effective_from' => '2023-01-01',
            'effective_to' => '2023-12-31',
            'status' => 'Completed',
        ],
    ];

    // ========================================
    // OVERTIME PLAN DATA
    // ========================================

    $availableOTPlans = [
        (object) [
            'id' => 1,
            'name' => 'Standard OT (1.5x)',
            'ot_type' => 'regular_ot',
            'ot_config_type' => 'salary_based',
            'salary_rate_type' => 'multiplier',
            'overtime_multiplier' => 1.5,
            'custom_overtime_rate' => null,
        ],
        (object) [
            'id' => 2,
            'name' => 'Weekend OT (2.0x)',
            'ot_type' => 'weekend_ot',
            'ot_config_type' => 'salary_based',
            'salary_rate_type' => 'multiplier',
            'overtime_multiplier' => 2.0,
            'custom_overtime_rate' => null,
        ],
        (object) [
            'id' => 3,
            'name' => 'Holiday OT (2.5x)',
            'ot_type' => 'holiday_ot',
            'ot_config_type' => 'salary_based',
            'salary_rate_type' => 'multiplier',
            'overtime_multiplier' => 2.5,
            'custom_overtime_rate' => null,
        ],
        (object) [
            'id' => 4,
            'name' => 'Custom Rate OT',
            'ot_type' => 'regular_ot',
            'ot_config_type' => 'custom',
            'salary_rate_type' => null,
            'overtime_multiplier' => null,
            'custom_overtime_rate' => 500.0,
        ],
    ];

    $activeOTPlans = [
        [
            'id' => 701,
            'name' => 'Standard OT (1.5x)',
            'ot_type' => 'regular_ot',
            'ot_config_type' => 'salary_based',
            'salary_rate_type' => 'multiplier',
            'overtime_multiplier' => 1.5,
            'custom_overtime_rate' => null,
            'effective_from' => '2025-01-01',
            'effective_to' => '2025-12-31',
            'status' => 'Active',
        ],
        [
            'id' => 702,
            'name' => 'Weekend OT (2.0x)',
            'ot_type' => 'weekend_ot',
            'ot_config_type' => 'salary_based',
            'salary_rate_type' => 'multiplier',
            'overtime_multiplier' => 2.0,
            'custom_overtime_rate' => null,
            'effective_from' => '2025-01-01',
            'effective_to' => '2025-12-31',
            'status' => 'Active',
        ],
    ];

    $previousOTPlans = [
        [
            'id' => 801,
            'name' => 'Holiday OT (2.5x)',
            'ot_type' => 'holiday_ot',
            'ot_config_type' => 'salary_based',
            'salary_rate_type' => 'multiplier',
            'overtime_multiplier' => 2.5,
            'custom_overtime_rate' => null,
            'effective_from' => '2024-01-01',
            'effective_to' => '2024-12-31',
            'status' => 'Expired',
        ],
        [
            'id' => 802,
            'name' => 'Custom Rate OT',
            'ot_type' => 'regular_ot',
            'ot_config_type' => 'custom',
            'salary_rate_type' => null,
            'overtime_multiplier' => null,
            'custom_overtime_rate' => 450.0,
            'effective_from' => '2023-06-01',
            'effective_to' => '2024-05-31',
            'status' => 'Expired',
        ],
    ];

    // ========================================
    // OFF DAY PLAN DATA
    // ========================================

    $availableOffDayPlans = [
        (object) [
            'id' => 1,
            'name' => 'Weekend (Sat-Sun)',
            'short_name' => 'WKD',
            'remuneration_amount' => 1500.0,
            'start_time' => '00:00:00',
            'end_time' => '23:59:59',
        ],
        (object) [
            'id' => 2,
            'name' => 'Friday Only',
            'short_name' => 'FRI',
            'remuneration_amount' => 1200.0,
            'start_time' => '00:00:00',
            'end_time' => '23:59:59',
        ],
        (object) [
            'id' => 3,
            'name' => 'Emergency Coverage',
            'short_name' => 'EMG',
            'remuneration_amount' => 2000.0,
            'start_time' => '08:00:00',
            'end_time' => '20:00:00',
        ],
        (object) [
            'id' => 4,
            'name' => 'Holiday Coverage',
            'short_name' => null,
            'remuneration_amount' => 2500.0,
            'start_time' => '06:00:00',
            'end_time' => '18:00:00',
        ],
    ];

    $activeOffDayPlans = [
        [
            'id' => 901,
            'name' => 'Weekend (Sat-Sun)',
            'short_name' => 'WKD',
            'remuneration_amount' => 1500.0,
            'effective_from' => '2025-01-01',
            'effective_to' => '2025-12-31',
            'status' => 'Active',
        ],
        [
            'id' => 902,
            'name' => 'Friday Only',
            'short_name' => 'FRI',
            'remuneration_amount' => 1200.0,
            'effective_from' => '2025-01-01',
            'effective_to' => '2025-12-31',
            'status' => 'Active',
        ],
    ];

    $previousOffDayPlans = [
        [
            'id' => 1001,
            'name' => 'Emergency Coverage',
            'short_name' => 'EMG',
            'remuneration_amount' => 2000.0,
            'effective_from' => '2024-01-01',
            'effective_to' => '2024-12-31',
            'status' => 'Expired',
        ],
        [
            'id' => 1002,
            'name' => 'Holiday Coverage',
            'short_name' => 'HOL',
            'remuneration_amount' => 2500.0,
            'effective_from' => '2023-01-01',
            'effective_to' => '2023-12-31',
            'status' => 'Expired',
        ],
    ]; // ========================================
    // EMPLOYEE DATA
    // ========================================

    // Dummy employee list for all forms
    $employees = [
        ['id' => 1, 'name' => 'John Doe', 'emp_id' => 'EMP001'],
        ['id' => 2, 'name' => 'Jane Smith', 'emp_id' => 'EMP002'],
        ['id' => 3, 'name' => 'Mike Johnson', 'emp_id' => 'EMP003'],
        ['id' => 4, 'name' => 'Sarah Williams', 'emp_id' => 'EMP004'],
        ['id' => 5, 'name' => 'David Brown', 'emp_id' => 'EMP005'],
    ];
@endphp
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
