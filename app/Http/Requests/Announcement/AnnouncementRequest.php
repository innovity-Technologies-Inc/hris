<?php

namespace App\Http\Requests\Announcement;

use Illuminate\Foundation\Http\FormRequest;

class AnnouncementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'attachment' => 'nullable|file|mimes:pdf,jpeg,png,jpg,doc,docx,xls,xlsx|max:10240',
            'company_id' => 'nullable|exists:companies,id',
            'branch_id' => 'nullable|exists:company_locations,id',
            'division_id' => 'nullable|exists:divisions,id',
            'department_id' => 'nullable|exists:departments,id',
            'section_id' => 'nullable|exists:sections,id',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The announcement title is required.',
            'content.required' => 'The announcement content is required.',
            'attachment.file' => 'The attachment must be a valid file.',
            'attachment.mimes' => 'The attachment must be a file of type: pdf, doc, docx, xls, xlsx, jpeg, png, jpg.',
            'attachment.max' => 'The attachment size may not be greater than 10MB.',
            'company_id.exists' => 'The selected company is invalid.',
            'branch_id.exists' => 'The selected branch/location is invalid.',
            'division_id.exists' => 'The selected division is invalid.',
            'department_id.exists' => 'The selected department is invalid.',
            'section_id.exists' => 'The selected section is invalid.',
        ];
    }
}
