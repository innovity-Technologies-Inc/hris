<?php

namespace App\Services\Setting;

use Innovity\ApprovalEngine\Models\Workflow as ApprovalWorkflow;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Models\User;

class ApprovalWorkflowServices
{
    /**
     * Create a new approval workflow.
     */
    public function createWorkflow(array $data): ApprovalWorkflow
    {
        return DB::transaction(function () use ($data) {
            $sanitized = $this->sanitizeInclusionsAndExclusions($data);

            $workflow = ApprovalWorkflow::create([
                'name' => ucfirst($data['module_name']) . ' Workflow',
                'module' => $data['module_name'],
                'type' => $data['type'],
                'total_steps' => count($data['steps']),
                'required_approvals' => $data['type'] === 'random' ? ($data['required_approvals'] ?? null) : null,
                'is_active' => ($data['is_active'] ?? '0') == '1',
                
                // Save Inclusions & Exclusions
                'includer_user_types' => $sanitized['includer_user_types'],
                'includer_role_ids' => $sanitized['includer_role_ids'],
                'includer_user_ids' => $sanitized['includer_user_ids'],
                'exclude_user_types' => $sanitized['exclude_user_types'],
                'exclude_role_ids' => $sanitized['exclude_role_ids'],
                'exclude_user_ids' => $sanitized['exclude_user_ids'],
            ]);

            $this->createWorkflowSteps($workflow, $data['steps']);

            return $workflow;
        });
    }

    /**
     * Update an existing approval workflow.
     */
    public function updateWorkflow(int $id, array $data): ApprovalWorkflow
    {
        return DB::transaction(function () use ($id, $data) {
            $workflow = ApprovalWorkflow::findOrFail($id);
            $sanitized = $this->sanitizeInclusionsAndExclusions($data);

            $workflow->update([
                'name' => ucfirst($data['module_name']) . ' Workflow',
                'module' => $data['module_name'],
                'type' => $data['type'],
                'total_steps' => count($data['steps']),
                'required_approvals' => $data['type'] === 'random' ? ($data['required_approvals'] ?? null) : null,
                'is_active' => ($data['is_active'] ?? '0') == '1',
                
                // Save Inclusions & Exclusions
                'includer_user_types' => $sanitized['includer_user_types'],
                'includer_role_ids' => $sanitized['includer_role_ids'],
                'includer_user_ids' => $sanitized['includer_user_ids'],
                'exclude_user_types' => $sanitized['exclude_user_types'],
                'exclude_role_ids' => $sanitized['exclude_role_ids'],
                'exclude_user_ids' => $sanitized['exclude_user_ids'],
            ]);

            $workflow->steps()->delete();
            $this->createWorkflowSteps($workflow, $data['steps']);

            return $workflow;
        });
    }

    /**
     * Delete an approval workflow.
     */
    public function deleteWorkflow(int $id): bool
    {
        $workflow = ApprovalWorkflow::findOrFail($id);
        return $workflow->delete();
    }

    /**
     * Create steps associated with a workflow.
     */
    private function createWorkflowSteps(ApprovalWorkflow $workflow, array $steps): void
    {
        foreach ($steps as $index => $step) {
            $stepName = 'Step ' . ($index + 1) . ' - ';
            if ($step['type'] === 'user-type') {
                $stepName .= ucfirst(str_replace('-', ' ', $step['required_user_type']));
            } elseif ($step['type'] === 'role') {
                $roleName = Role::find($step['role_id'])->name ?? 'Role';
                $stepName .= $roleName;
            } elseif ($step['type'] === 'role-user') {
                $roleName = Role::find($step['role_id'])->name ?? 'Role';
                $stepName .= ucfirst(str_replace('-', ' ', $step['required_user_type'])) . ' (' . $roleName . ')';
            } elseif ($step['type'] === 'specific-user') {
                $userName = User::find($step['user_id'])->name ?? 'User';
                $stepName .= $userName;
            }

            $workflow->steps()->create([
                'name' => $stepName,
                'step_order' => $index + 1,
                'type' => $step['type'],
                'required_user_type' => $step['required_user_type'] ?? null,
                'role_id' => $step['role_id'] ?? null,
                'user_id' => $step['user_id'] ?? null,
            ]);
        }
    }

    /**
     * Sanitize scope types for inclusions/exclusions.
     */
    private function sanitizeInclusionsAndExclusions(array $data): array
    {
        $scopeType = $data['scope_type'] ?? 'all';
        $excludeScopeType = $data['exclude_scope_type'] ?? 'none';

        // Inclusions sanitization
        $includerUserTypes = $scopeType !== 'all' && isset($data['includer_user_types']) ? $data['includer_user_types'] : null;
        $includerRoleIds = $scopeType !== 'all' && isset($data['includer_role_ids']) ? $data['includer_role_ids'] : null;
        $includerUserIds = $scopeType !== 'all' && isset($data['includer_user_ids']) ? $data['includer_user_ids'] : null;

        if ($scopeType === 'user_type') {
            $includerRoleIds = null;
            $includerUserIds = null;
        } elseif ($scopeType === 'role') {
            $includerUserTypes = null;
            $includerUserIds = null;
        } elseif ($scopeType === 'user_type_role') {
            $includerUserIds = null;
        } elseif ($scopeType === 'specific_user') {
            $includerUserTypes = null;
            $includerRoleIds = null;
        } else {
            $includerUserTypes = null;
            $includerRoleIds = null;
            $includerUserIds = null;
        }

        // Exclusions sanitization
        $excludeUserTypes = $excludeScopeType !== 'none' && isset($data['exclude_user_types']) ? $data['exclude_user_types'] : null;
        $excludeRoleIds = $excludeScopeType !== 'none' && isset($data['exclude_role_ids']) ? $data['exclude_role_ids'] : null;
        $excludeUserIds = $excludeScopeType !== 'none' && isset($data['exclude_user_ids']) ? $data['exclude_user_ids'] : null;

        if ($excludeScopeType === 'user_type') {
            $excludeRoleIds = null;
            $excludeUserIds = null;
        } elseif ($excludeScopeType === 'role') {
            $excludeUserTypes = null;
            $excludeUserIds = null;
        } elseif ($excludeScopeType === 'user_type_role') {
            $excludeUserIds = null;
        } elseif ($excludeScopeType === 'specific_user') {
            $excludeUserTypes = null;
            $excludeRoleIds = null;
        } else {
            $excludeUserTypes = null;
            $excludeRoleIds = null;
            $excludeUserIds = null;
        }

        return [
            'includer_user_types' => $includerUserTypes,
            'includer_role_ids' => $includerRoleIds,
            'includer_user_ids' => $includerUserIds,
            'exclude_user_types' => $excludeUserTypes,
            'exclude_role_ids' => $excludeRoleIds,
            'exclude_user_ids' => $excludeUserIds,
        ];
    }
}
