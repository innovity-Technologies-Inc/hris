<?php

namespace App\Services\Setting;

use Innovity\ApprovalEngine\Events\ApprovalCompleted;
use Innovity\ApprovalEngine\Events\ApprovalRejected;

class WorkflowEventDispatcherService
{
    /**
     * Map of modules to their dedicated listener classes.
     *
     * @var array
     */
    protected array $workflowListeners = [
        'promotion'                => \App\Listeners\Workflow\PromotionWorkflowListener::class,
        'demotion'                 => \App\Listeners\Workflow\DemotionWorkflowListener::class,
        'increment'                => \App\Listeners\Workflow\IncrementWorkflowListener::class,
        'decrement'                => \App\Listeners\Workflow\DecrementWorkflowListener::class,
        'leave'                    => \App\Listeners\Workflow\LeaveWorkflowListener::class,
        'salary'                   => \App\Listeners\Workflow\PayrollWorkflowListener::class,
        'bonus'                    => \App\Listeners\Workflow\PayrollWorkflowListener::class,
        'profile-update'           => \App\Listeners\Workflow\ProfileUpdateWorkflowListener::class,
        'office-information'       => \App\Listeners\Workflow\ProfileUpdateWorkflowListener::class,
        'employee-policy'          => \App\Listeners\Workflow\ProfileUpdateWorkflowListener::class,
        'salary-breakdown'         => \App\Listeners\Workflow\ProfileUpdateWorkflowListener::class,
        'employee-bank-account'    => \App\Listeners\Workflow\ProfileUpdateWorkflowListener::class,
        'career-movement'          => \App\Listeners\Workflow\TransferWorkflowListener::class,
    ];

    /**
     * Dispatch dynamic workflow approval completed events.
     *
     * @param ApprovalCompleted $event
     * @return void
     */
    public function handleCompleted(ApprovalCompleted $event): void
    {
        $module = $event->approvalRequest->workflow->module;
        
        if (isset($this->workflowListeners[$module])) {
            $listener = app($this->workflowListeners[$module]);
            if (method_exists($listener, 'handleCompleted')) {
                $listener->handleCompleted($event);
            }
        }
    }

    /**
     * Dispatch dynamic workflow approval rejected events.
     *
     * @param ApprovalRejected $event
     * @return void
     */
    public function handleRejected(ApprovalRejected $event): void
    {
        $module = $event->approvalRequest->workflow->module;
        
        if (isset($this->workflowListeners[$module])) {
            $listener = app($this->workflowListeners[$module]);
            if (method_exists($listener, 'handleRejected')) {
                $listener->handleRejected($event);
            }
        }
    }
}
