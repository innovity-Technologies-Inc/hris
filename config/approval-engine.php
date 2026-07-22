<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Approval Engine Table Names
    |--------------------------------------------------------------------------
    |
    | Define the table names used by the approval engine.
    |
    */
    'table_names' => [
        'workflows' => 'approval_workflows',
        'workflow_steps' => 'approval_workflow_steps',
        'requests' => 'approval_requests',
        'step_requests' => 'approval_step_requests',
    ],

    /*
    |--------------------------------------------------------------------------
    | Workflow Modules
    |--------------------------------------------------------------------------
    |
    | Define the modules that support approval workflows.
    | This can be used to populate dropdowns in your UI.
    |
    */
    'modules' => [
        'bonus' => 'Bonus and Reward',
        'career-movement' => 'Career Movement',
        'decrement' => 'Decrement',
        'employee-bank-account' => 'Employee Bank Account',
        'employee-policy' => 'Employee Policy',
        'increment' => 'Increment',
        'leave' => 'Leave',
        'office-information' => 'Office Information',
        'profile-update' => 'Profile Update',
        'promotion' => 'Promotion',
        'demotion' => 'Demotion',
        'salary' => 'Salary',
        'salary-breakdown' => 'Salary Breakdown',
        'travel-movement' => 'Travel Movement',
        'claim-expense' => 'Claim Expense',
        'resign' => 'Resignation',
        'offboarding-resignation' => 'Offboarding Resignation',
        'offboarding-termination' => 'Offboarding Termination',
        'penalty' => 'Penalty Management',
        'advance-salary' => 'Advance Salary',
        'arrear' => 'Arrear Management',
    ],

];
