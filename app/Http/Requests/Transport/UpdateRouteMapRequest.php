<?php

namespace App\Http\Requests\Transport;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRouteMapRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'route_name' => 'required|string|max:255',
            'start_point' => 'required|string|max:255',
            'end_point' => 'required|string|max:255',
            'via_points' => 'nullable|array',
            'via_points.*' => 'nullable|string|max:255',
            'route_details' => 'nullable|string|max:1000',
            'status' => 'required|in:Active,Inactive',
        ];
    }
}
