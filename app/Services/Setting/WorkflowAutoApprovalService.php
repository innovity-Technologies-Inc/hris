<?php

namespace App\Services\Setting;

use App\Enums\UserType;
use App\Models\User;
use Innovity\ApprovalEngine\Contracts\ApproverResolverInterface;
use Innovity\ApprovalEngine\Models\ApprovalStepRequest;
use Innovity\ApprovalEngine\Services\ApprovalResolver;

class WorkflowAutoApprovalService
{
    /**
     * Handle the auto-approval check when a new step request is created.
     *
     * @param ApprovalStepRequest $stepRequest
     * @return void
     */
    public function handle(ApprovalStepRequest $stepRequest): void
    {
        $approvable = $stepRequest->approvalRequest->approvable ?? null;
        if (!$approvable) {
            return;
        }

        // Find requesting user
        $requestingUser = $this->resolveRequestingUser($approvable);
        if (!$requestingUser) {
            return;
        }

        $requesterWeight = $requestingUser->user_type->weight();
        
        // Get step details
        $step = $stepRequest->workflowStep;
        $shouldAutoApprove = false;
        $reason = '';

        // Case 1: Requester is themselves resolved as an approver (Self Approval)
        $resolver = app(ApproverResolverInterface::class);
        $approverIds = $resolver->resolve((string) $step->id, $approvable);
        
        if (in_array($requestingUser->id, $approverIds)) {
            $shouldAutoApprove = true;
            $reason = 'Auto-approved: Requester is the resolved approver.';
        }
        
        // Case 2: Requester has strictly higher authority level weight than the required level (Lower Level Approval)
        if (!$shouldAutoApprove && ($step->type === 'user-type' || $step->type === 'role-user')) {
            if (!empty($step->required_user_type)) {
                $stepWeight = UserType::getWeight($step->required_user_type);
                if ($requesterWeight < $stepWeight) {
                    $shouldAutoApprove = true;
                    $reason = "Auto-approved: Requester level ({$requestingUser->user_type->value}) has higher authority than required level ({$step->required_user_type}).";
                }
            }
        }

        if ($shouldAutoApprove) {
            app(ApprovalResolver::class)->approve($stepRequest, $requestingUser->id, $reason);
            
            // Reload the step request so that any subsequent code or hooks see the approved status
            $stepRequest->refresh();
        }
    }

    /**
     * Resolve the requesting user from the approvable model.
     *
     * @param mixed $approvable
     * @return User|null
     */
    protected function resolveRequestingUser($approvable): ?User
    {
        if (method_exists($approvable, 'user')) {
            return $approvable->user;
        } 
        
        if (method_exists($approvable, 'getEmployee')) {
            $emp = $approvable->getEmployee()->withoutGlobalScopes()->first();
            return $emp ? $emp->user : null;
        } 
        
        if (method_exists($approvable, 'employee')) {
            $emp = $approvable->employee()->withoutGlobalScopes()->first();
            return $emp ? $emp->user : null;
        } 
        
        if (method_exists($approvable, 'creator')) {
            return $approvable->creator;
        }

        return null;
    }
}
