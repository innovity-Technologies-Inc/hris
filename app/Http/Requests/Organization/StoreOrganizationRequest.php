<?php

namespace App\Http\Requests\Organization;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrganizationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only Super Admins (organization_id is null) are authorized
        return is_null(auth()->user()->organization_id);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name'           => 'required|string|max:255',
            'slug'           => 'required|string|max:255|unique:organizations,slug',
            'logo'           => 'nullable|image|mimes:webp,jpeg,png,jpg,gif,svg|max:2048',
            'email'          => 'nullable|email|max:255',
            'phone'          => 'nullable|string|max:50',
            'address'        => 'nullable|string|max:500',
            'status'         => 'required|in:active,inactive',
            // Optional admin provisioning fields
            'admin_name'     => 'nullable|string|max:255',
            'admin_email'    => 'nullable|email|max:255|unique:users,email',
            'admin_password' => 'nullable|string|min:8',
        ];
    }
}
