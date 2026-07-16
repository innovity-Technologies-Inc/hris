<?php

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class StoreApprovalWorkflowRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'module_name' => 'required|string|unique:approval_workflows,module',
            'type' => 'required|in:sequential,random',
            'required_approvals' => 'nullable|integer|min:1|max:' . max(1, count($this->input('steps') ?? [])),
            'is_active' => 'nullable|in:0,1',
            'scope_type' => 'nullable|string',
            'exclude_scope_type' => 'nullable|string',
            'steps' => 'required|array|min:1',
            'steps.*.type' => 'required|in:user-type,role,role-user,specific-user',
            'steps.*.required_user_type' => 'nullable|required_if:steps.*.type,user-type,role-user|string',
            'steps.*.role_id' => 'nullable|required_if:steps.*.type,role,role-user|exists:roles,id',
            'steps.*.user_id' => 'nullable|required_if:steps.*.type,specific-user|exists:users,id',
            
            // Inclusions & Exclusions validation
            'includer_user_types' => 'nullable|array',
            'includer_role_ids' => 'nullable|array',
            'includer_user_ids' => 'nullable|array',
            'exclude_user_types' => 'nullable|array',
            'exclude_role_ids' => 'nullable|array',
            'exclude_user_ids' => 'nullable|array',
        ];
    }

    public function messages(): array
    {
        return [
            'module_name.unique' => 'An approval workflow already exists for this module.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $steps = $this->input('steps');
            $type = $this->input('type');
            if ($type === 'sequential' && is_array($steps)) {
                $error = $this->validateSequentialSteps($steps);
                if ($error) {
                    $validator->errors()->add('steps', $error);
                }
            }
        });
    }

    private function validateSequentialSteps(array $steps): ?string
    {
        $previousWeight = null;
        $previousName = '';

        foreach ($steps as $index => $step) {
            $currentWeight = null;
            $currentName = '';

            if (($step['type'] ?? '') === 'user-type' || ($step['type'] ?? '') === 'role-user') {
                if (!empty($step['required_user_type'])) {
                    $currentWeight = \App\Enums\UserType::getWeight($step['required_user_type']);
                    $currentName = $step['required_user_type'];
                }
            } elseif (($step['type'] ?? '') === 'specific-user') {
                if (!empty($step['user_id'])) {
                    $user = \App\Models\User::find($step['user_id']);
                    if ($user) {
                        $currentWeight = $user->user_type->weight();
                        $currentName = $user->user_type->value;
                    }
                }
            }

            if ($currentWeight !== null) {
                if ($previousWeight !== null && $previousWeight < $currentWeight) {
                    return "Step " . ($index + 1) . " (level: {$currentName}) cannot have a lower authority level than Step " . $index . " (level: {$previousName}) in a sequential workflow.";
                }
                $previousWeight = $currentWeight;
                $previousName = $currentName;
            }
        }

        return null;
    }
}
