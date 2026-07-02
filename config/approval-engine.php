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
        'bonus' => 'Bonus',
        'career-movement' => 'Career Movement',
        'increment' => 'Increment',
        'leave' => 'Leave',
        'profile-update' => 'Profile Update',
        'promotion' => 'Promotion',
        'salary' => 'Salary',
        'travel-movement' => 'Travel Movement',

    ],

];
