<?php

namespace App\Services;

use App\HelperClass;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeServices
{
    public function getEmployees()
    {
        $employees = Employee::latest()->paginate(10);
        return $employees;
    }

    public function employeeInfoValidation($request)
    {
        $validated = $request->validate(
            [
                // System Identifiers
                'applicant_id' => 'required|string|unique:employees,applicant_id',
                'system_id' => 'required|string|unique:employees,system_id',
                'punch_card_no' => 'required|string|unique:employees,punch_card_no',

                // Personal Information
                'first_name' => 'required|string|max:255',
                'last_name' => 'nullable|string|max:255',
                'middle_name' => 'nullable|string|max:255',
                'father_name' => 'required|string|max:255',
                'mother_name' => 'required|string|max:255',
                'spouse_name' => 'nullable|string|max:255',
                'marital_status' => 'nullable|in:Single,Married,Divorced,Widowed',
                'gender' => 'required|in:Male,Female,Other',
                'religion' => 'required|string|max:255',
                'nationality' => 'required|string|max:255',
                'height_feet' => 'nullable|integer|min:0',
                'height_inches' => 'nullable|integer|min:0|max:11',
                'children_count' => 'nullable|integer|min:0',

                'present_address.line_1' => 'required|string',
                'present_address.village' => 'nullable|string',
                'present_address.post_office' => 'required|string',
                'present_address.district' => 'required|string',
                'present_address.division' => 'required|string',
                'present_address.zip_code' => 'required|string',
                'present_address.state' => 'required|string',
                'present_address.country' => 'required|string',

                'permanent_address.line_1' => 'nullable|string',
                'permanent_address.village' => 'nullable|string',
                'permanent_address.post_office' => 'nullable|string',
                'permanent_address.district' => 'nullable|string',
                'permanent_address.division' => 'nullable|string',
                'permanent_address.zip_code' => 'nullable|string',
                'permanent_address.state' => 'nullable|string',
                'permanent_address.country' => 'nullable|string',

                'reference_address.emp_id' => 'nullable|string',
                'reference_address.reference_name' => 'nullable|string',
                'reference_address.reference_designation' => 'nullable|string',
                'reference_address.phone' => 'nullable|string',
                'reference_address.mobile' => 'nullable|string',
                'reference_address.email' => 'nullable|string',
                'reference_address.line_1' => 'nullable|string',
                'reference_address.village' => 'nullable|string',
                'reference_address.post_office' => 'nullable|string',
                'reference_address.district' => 'nullable|string',
                'reference_address.division' => 'nullable|string',
                'reference_address.zip_code' => 'nullable|string',
                'reference_address.state' => 'nullable|string',
                'reference_address.country' => 'nullable|string',


                // Document Information
                'tin' => 'nullable|string|max:255',
                'passport_no' => 'nullable|string|max:255',
                'passport_expiry' => 'nullable|date|after:today',
                'license_no' => 'nullable|string|max:255',
                'license_expiry' => 'nullable|date|after:today',
                'bgmea_id' => 'nullable|string|max:255',
                'visa_expiry' => 'nullable|date|after:today',
                'work_expiry' => 'nullable|date|after:today',
                'residency_id_number' => 'nullable|string|max:255',

                // Birth Information
                'date_of_birth' => 'required|date|before:today',
                'birth_country' => 'nullable|string|max:255',
                'birth_reg_no' => 'nullable|string|max:255',

                // Contact Information
                'personal_mobile' => 'required|string|max:20',
                'home_phone' => 'nullable|string|max:20',
                'work_mobile' => 'nullable|string|max:20',
                'work_phone' => 'nullable|string|max:20',
                'work_email' => 'nullable|email|max:255',
                'personal_email' => 'nullable|email|max:255',

                // File uploads
                'photo_path' => 'nullable|file|image|mimes:jpeg,png,jpg|max:2048',
                'fingerprint_path' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
                'signature_path' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
                'experience_attachment_path' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            ],
            // Custom error messages
            [
                'applicant_id.required' => 'Applicant ID is required.',
                'applicant_id.unique' => 'Applicant ID must be unique.',
                'system_id.required' => 'System ID is required.',
                'system_id.unique' => 'System ID must be unique.',
                'punch_card_no.required' => 'Punch card number is required.',
                'punch_card_no.unique' => 'Punch card number must be unique.',
                'first_name.required' => 'First name is required.',
                'father_name.required' => 'Father\'s name is required.',
                'mother_name.required' => 'Mother\'s name is required.',
                'gender.required' => 'Gender is required.',
                'gender.in' => 'Gender must be Male, Female, or Other.',
                'marital_status.in' => 'Marital status must be Single, Married, Divorced, or Widowed.',
                'date_of_birth.required' => 'Date of birth is required.',
                'date_of_birth.before' => 'Date of birth must be before today.',
                'passport_expiry.after' => 'Passport expiry date must be a future date.',
                'license_expiry.after' => 'License expiry date must be a future date.',
                'visa_expiry.after' => 'Visa expiry date must be a future date.',
                'work_expiry.after' => 'Work expiry date must be a future date.',
                'personal_mobile.required' => 'Personal mobile is required.',
                'work_email.email' => 'Work email must be a valid email address.',
                'personal_email.email' => 'Personal email must be a valid email address.',
                'photo_path.image' => 'Photo must be an image file.',
                'experience_attachment_path.mimes' => 'Experience attachment must be a PDF or Word document.',
            ]
        );

        return $validated;
    }

    public function employeeAttachmentValidation($validated, $request, $file, $key_name)
    {
        if ($request->hasFile($key_name)) {
            $file_path = HelperClass::file_upload($file, 'employee_attachments');
            $validated[$key_name] = $file_path;
            return $validated;
        }
    }


    public function employeeInfoStore(Request $request)
    {
        $validated = $this->employeeInfoValidation($request);

        $photo = $request->file('photo_path');
        $validated = $this->employeeAttachmentValidation($validated, $request, $photo, 'photo_path');

        $fingerprint = $request->file('fingerprint_path');
        $validated = $this->employeeAttachmentValidation($validated, $request, $fingerprint, 'fingerprint_path');

        $signature = $request->file('signature_path');
        $validated = $this->employeeAttachmentValidation($validated, $request, $signature, 'signature_path');

        $experience_attachment = $request->file('experience_attachment_path');
        $validated = $this->employeeAttachmentValidation($validated, $request, $experience_attachment, 'experience_attachment_path');

        $validated['full_name'] = $validated['first_name'] . ' ' . $validated['middle_name'] . ' ' . $validated['last_name'];
        $employee_data = Employee::create($validated);
        return $employee_data;
    }

    public function getEmployeeById($id)
    {
        try {
            $employee = Employee::find($id);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'message' => 'Data Not Found',
                'alert-type' => 'error'
            ]);
        }
        return $employee;
    }

    public function employeeSearchResult(Request $request)
    {

        $employees = Employee::query();

        $searchTerm = $request->get('keyword');
        $employee_id = $request->get('employee_id');
        $employee_name = $request->get('employee_name');
        $system_id = $request->get('system_id');

        if (!empty($employee_name)) {
            $employees->where('full_name', $employee_name);
        }

        if (!empty($employee_id)) {
            $employees->where('applicant_id', $employee_id);
        }

        if (!empty($employee_id)) {
            $employees->where('system_id', $system_id);
        }

//        Term based Serch
        if (!empty($searchTerm)) {
            $searchTerms = explode(' ', $searchTerm);
            $employees = $employees->where(function ($query) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $query->where(function ($q) use ($term) {
                        $q->where('full_name', 'like', "%{$term}%")
                            ->orWhere('system_id', 'like', "%{$term}%")
                            ->orWhere('applicant_id', 'like', "%{$term}%");
                    });
                }
            });
        }


        $employees = $employees->orderBy('first_name', 'asc')->paginate(10);

        return $employees;

    }

}
