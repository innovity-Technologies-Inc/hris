<?php

namespace App\Services\Setting;

use App\Enums\UserType;
use App\Models\User;
use App\Models\Employee\ProfileUpdateRequest;
use App\Notifications\Approval\ApprovalActionRequiredNotification;
use App\Services\Setting\NotificationServices;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Innovity\ApprovalEngine\Contracts\ApproverResolverInterface;
use Innovity\ApprovalEngine\Models\ApprovalStepRequest;
use Innovity\ApprovalEngine\Services\ApprovalResolver;

class WorkflowStepRequestService
{
    /**
     * Handle the workflow step request creation logic.
     *
     * @param ApprovalStepRequest $stepRequest
     * @return void
     */
    public function handleCreated(ApprovalStepRequest $stepRequest): void
    {
        // 1. Run Auto-Approval Check
        $this->handleAutoApproval($stepRequest);

        // 2. Send Notifications if the step is still pending
        $this->notifyApprovers($stepRequest);
    }

    /**
     * Handle the auto-approval check.
     *
     * @param ApprovalStepRequest $stepRequest
     * @return void
     */
    protected function handleAutoApproval(ApprovalStepRequest $stepRequest): void
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

        // Find target user
        $targetUser = $this->resolveTargetUser($approvable);
        $targetWeight = $targetUser ? $targetUser->user_type->weight() : 99;
        
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
        
        // Case 2: Target user has strictly higher authority level weight than the required level (Lower Level Approval)
        if (!$shouldAutoApprove && ($step->type === 'user-type' || $step->type === 'role-user')) {
            if ($targetUser && !empty($step->required_user_type)) {
                $stepWeight = UserType::getWeight($step->required_user_type);
                if ($targetWeight < $stepWeight) {
                    $shouldAutoApprove = true;
                    $reason = "Auto-approved: Target user level ({$targetUser->user_type->value}) has higher authority than required level ({$step->required_user_type}).";
                }
            }
        }

        if ($shouldAutoApprove) {
            app(ApprovalResolver::class)->approve($stepRequest, $requestingUser->id, $reason);
            
            // Reload the step request so that its status becomes approved
            $stepRequest->refresh();
        }
    }

    /**
     * Notify resolved approvers if the step is pending.
     *
     * @param ApprovalStepRequest $stepRequest
     * @return void
     */
    protected function notifyApprovers(ApprovalStepRequest $stepRequest): void
    {
        if ($stepRequest->status->value !== 'pending') {
            return;
        }

        $approvable = $stepRequest->approvalRequest->approvable ?? null;
        if (!$approvable) {
            return;
        }

        $resolver = app(ApproverResolverInterface::class);
        $approverIds = $resolver->resolve((string) $stepRequest->workflowStep->id, $approvable);
        
        if (empty($approverIds)) {
            return;
        }

        $users = User::whereIn('id', $approverIds)->get();
        foreach ($users as $user) {
            // Send Mail Notification
            try {
                $user->notify(new ApprovalActionRequiredNotification($stepRequest));
            } catch (\Exception $e) {
                Log::error('Mail Notification error: ' . $e->getMessage());
            }
            
            // Send Custom App Notification
            try {
                $moduleName = $stepRequest->approvalRequest->workflow->module ?? '';
                $module = ucfirst($moduleName ?: 'Item');
                
                if ($approvable instanceof ProfileUpdateRequest) {
                    $url = route('profile_update_requests.show', $approvable->id, false);
                } else {
                    $normalizedRoute = str_replace('-', '_', $moduleName);
                    if ($normalizedRoute === 'claim_expense') {
                        $normalizedRoute = 'claim_expenses';
                    }
                    $url = Route::has($normalizedRoute . '.show') 
                            ? route($normalizedRoute . '.show', $approvable->id, false) 
                            : '/' . $moduleName;
                }

                app(NotificationServices::class)->createNotification(
                    $user->user_type->value ?? $user->user_type,
                    $user->id,
                    'Approval Action Required',
                    "You have a new $module approval request pending your action.",
                    ['url' => $url, 'type' => 'approval_request']
                );
            } catch (\Exception $e) {
                Log::error('Custom Notification error: ' . $e->getMessage());
            }
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
        if (method_exists($approvable, 'creator')) {
            $creator = $approvable->creator;
            if ($creator) {
                return $creator;
            }
        }

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

        return null;
    }

    /**
     * Resolve the target user of the approvable model.
     *
     * @param mixed $approvable
     * @return User|null
     */
    protected function resolveTargetUser($approvable): ?User
    {
        if (method_exists($approvable, 'user')) {
            $user = $approvable->user;
            if ($user) {
                return $user;
            }
        }

        if (method_exists($approvable, 'employee')) {
            $emp = $approvable->employee()->withoutGlobalScopes()->first();
            if ($emp && $emp->user) {
                return $emp->user;
            }
        }

        if (method_exists($approvable, 'getEmployee')) {
            $emp = $approvable->getEmployee()->withoutGlobalScopes()->first();
            if ($emp && $emp->user) {
                return $emp->user;
            }
        }

        if (method_exists($approvable, 'creator')) {
            return $approvable->creator;
        }

        return null;
    }
}
