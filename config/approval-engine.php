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
        'leave' => 'Leave',
        'promotion' => 'Promotion',
        'increment' => 'Increment',
        'career-movement' => 'Career Movement',
        'travel-movement' => 'Travel Movement',
        'bonus' => 'Bonus',
        'salary' => 'Salary',

    ],

];
