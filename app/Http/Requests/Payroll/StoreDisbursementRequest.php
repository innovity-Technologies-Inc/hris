<?php

namespace App\Http\Requests\Payroll;

use Illuminate\Foundation\Http\FormRequest;

class StoreDisbursementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'process_id' => 'required|exists:payroll_process,id',
            'record_ids' => 'required|array|min:1',
            'payment_method' => 'required|string|max:255',
            'note' => 'nullable|string',
            'attachments.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,zip|max:5120', // 5MB max
        ];
    }
}
