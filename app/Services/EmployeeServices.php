<?php

namespace App\Services;

use App\HelperClass;
use App\Models\Branch;
use App\Models\Company;
use App\Models\CompanyLocation;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Division;
use App\Models\Employee;
use App\Models\EmployeeBankAccount;
use App\Models\EmployeeEducationExperienceTraining;
use App\Models\EmployeeEligiblePlan;
use App\Models\EmployeeNominee;
use App\Models\EmployeeOfficeInfo;
use App\Models\EmployeeSalaryBreakdown;
use App\Models\SalaryGrade;
use App\Models\Section;
use App\Models\Tofsil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class EmployeeServices
{
    public function getEmployees()
    {
        $employees = Employee::latest()->paginate(10);
        return $employees;
    }

    public function employeeSearchResult(Request $request, $flexsearch)
    {
        $query = Employee::query();

        $filters = [
            'applicant_id' => $request->get('employee_id'),
            'full_name' => $request->get('employee_name'),
            'system_id' => $request->get('system_id'),
        ];


        $searchTerm = $request->get('keyword');

        $searchableFields = ['applicant_id', 'full_name', 'system_id'];

        $employees = $flexsearch->apply($query,
            $filters,
            $searchTerm,
            $searchableFields)->orderBy('id', 'desc')->paginate(50);

        return $employees;
    }


    public function employeeInfoValidation($request)
    {
        $validated = $request->validate(
            [
                // System Identifiers
                'applicant_id' => 'required|string',
                'system_id' => 'required|string',
                'punch_card_no' => 'required|string',

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
        $file_path = HelperClass::file_upload($file, 'employee_attachments');
        $validated[$key_name] = $file_path;
        return $validated;
    }

    public function employeeAttachmentDelete($file_path)
    {
        if ($file_path != null) {
            HelperClass::file_delete($file_path);
        }
    }

    public function employeeInfoSave(Request $request, $validated, $id = null)
    {

        $validated['full_name'] = trim(($validated['first_name'] ?? '') . ' ' .
            ($validated['middle_name'] ?? '') . ' ' .
            ($validated['last_name'] ?? ''));
        Log::info($validated);

        if ($request->hasFile('photo_path')) {
            $photo = $request->file('photo_path');
            $validated = $this->employeeAttachmentValidation($validated, $request, $photo, 'photo_path');
            Log::info('Photo Uploaded');
        }
        if ($request->hasFile('fingerprint_path')) {
            $fingerprint = $request->file('fingerprint_path');
            $validated = $this->employeeAttachmentValidation($validated, $request, $fingerprint, 'fingerprint_path');
            Log::info('Fingerprint Uploaded');
        }

        if ($request->hasFile('signature_path')) {
            $signature = $request->file('signature_path');
            $validated = $this->employeeAttachmentValidation($validated, $request, $signature, 'signature_path');
            Log::info('Signature Uploaded');
        }

        if ($request->hasFile('experience_attachment_path')) {
            $experience_attachment = $request->file('experience_attachment_path');
            $validated = $this->employeeAttachmentValidation($validated, $request, $experience_attachment, 'experience_attachment_path');
        }
        if (empty($id)) {
            $employee_data = Employee::create($validated);
        } else {
            $employee = $this->getEmployeeById($id);

            if ($request->hasFile('photo_path')) {
                $this->employeeAttachmentDelete($employee->photo_path);
            }
            if ($request->hasFile('fingerprint_path')) {
                $this->employeeAttachmentDelete($employee->fingerprint_path);
            }
            if ($request->hasFile('signature_path')) {
                $this->employeeAttachmentDelete($employee->signature_path);
            }
            if ($request->hasFile('experience_attachment_path')) {
                $this->employeeAttachmentDelete($employee->experience_attachment_path);
            }
            $employee->update($validated);
            $employee_data = $employee;
        }
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

    public function getCompanies()
    {
        $companies = Company::all();
        return $companies;
    }

    public function getUnit($company_id)
    {
        $units = CompanyLocation::where('company_id', $company_id)->get();
        return $units;
    }

    public function getDivision($company_id, $location_id)
    {
        $divisions = Division::where('company_id', $company_id)
            ->where('location_id', $location_id)->get();
        return $divisions;
    }

    public function getDepartment($company_id, $location_id, $division_id)
    {
        $departments = Department::where('company_id', $company_id)
            ->where('location_id', $location_id)
        ->where('division_id', $division_id)

                ->get();
        return $departments;
    }

    public function getSection($company_id, $location_id, $division_id, $department_id)
    {
        $sections = Section::where('company_id', $company_id)
            ->where('location_id', $location_id)
            ->where('division_id', $division_id)->where('department_id', $department_id)->get();
        return $sections;
    }

    public function getActs()
    {
        $acts = Tofsil::all();
        return $acts;
    }

    public function getGradeByAct($tofsil_id)
    {
        $grades = SalaryGrade::where('tofsil_id', $tofsil_id)->get();
        return $grades;
    }

    public function getDesignations()
    {
        $designations = Designation::all();
        return $designations;
    }

    public function getBranchesByBank($bank_id)
    {
        $branches = Branch::where('bank_id', $bank_id)->get();
        return $branches;
    }

    public function employeeOfficeInfoValidation($request)
    {
        $validated = $request->validate([
            // Basic Identifiers
            'employee_id' => 'required|integer',
            'emp_type' => 'nullable|in:permanent,contractual',
            'grade_id' => 'nullable|integer',
            'hr_file_no' => 'nullable|string|max:255',
            'tofsil_id' => 'nullable|integer',
            'file_note' => 'nullable|string',

            // Joining Information
            'joining_company_id' => 'nullable|integer',
            'joining_business_unit_id' => 'nullable|integer',
            'joining_division_id' => 'nullable|integer',
            'joining_department_id' => 'nullable|integer',
            'joining_section_id' => 'nullable|integer',
            'joining_designation_id' => 'nullable|integer',
            'date_of_join' => 'nullable|date',

            // Current Posting Information
            'current_company_id' => 'nullable|integer',
            'current_business_unit_id' => 'nullable|integer',
            'current_division_id' => 'nullable|integer',
            'current_department_id' => 'nullable|integer',
            'current_section_id' => 'nullable|integer',
            'current_designation_id' => 'nullable|integer',

            // Orientation
            'orientation_required' => 'required|in:yes,no',
            'orientation_from' => 'nullable|date',
            'orientation_to' => 'nullable|date|after_or_equal:orientation_from',
            'orientation_type' => 'nullable|string|max:100',
            'orientation_days' => 'nullable|integer|min:1',

            // Employment & Performance
            'confirmation_date' => 'nullable|date',
            'probation_duration' => 'nullable|integer|min:0',
            'next_promotion_date' => 'nullable|date',
            'promotion_cycle' => 'nullable|string|max:100',
            'increment_cycle' => 'nullable|string|max:100',

            // Attendance & Benefits
            'weekends' => 'nullable|array',
            'weekends.*' => 'string',
            'alternate_off_day' => 'nullable|array',
            'alternate_off_day.*' => 'string',
            'ot_allowed' => 'nullable|in:yes,no',
            'pf_eligible' => 'nullable|in:yes,no',
            'salary_type' => 'nullable|in:hourly,daily,weekly,monthly,yearly',
            'transport_eligible' => 'nullable|in:yes,no',

            // Loan & Benefits
            'can_apply_loan' => 'nullable|in:yes,no',
            'pf_effective_date' => 'nullable|date',
            'can_apply_advance' => 'nullable|in:yes,no',
            'gratuity_eligible' => 'nullable|in:yes,no',
        ], [
            // 🧾 Custom Messages
            'emp_type.in' => 'Employment type must be either Permanent or Contractual.',
            'orientation_to.after_or_equal' => 'Orientation end date must be after or equal to the start date.',
            'orientation_days.min' => 'Orientation days must be at least 1 day.',
            'probation_duration.min' => 'Probation duration cannot be negative.',
        ]);

        return $validated;

    }

    public function employeeOfficeInfoSave(Request $request, $validated, $employee_office_info = null)
    {
        if (empty($employee_office_info)) {
            $employee_office_data = EmployeeOfficeInfo::create($validated);
        } else {
            $employee_office_data = $employee_office_info->update($validated);

        }
        return $employee_office_data;
    }

    public function employeeEligiblePlanValidation($request)
    {
        $validated = $request->validate([
            'employee_id' => 'required',

            // Shift Plan
            'shift_plan_from' => 'nullable|date',
            'shift_plan_to' => 'nullable|date|after_or_equal:shift_plan_from',
            'shift_plan_status' => 'nullable|in:active,inactive',

            // Leave Plan
            'leave_plan_from' => 'nullable|date',
            'leave_plan_to' => 'nullable|date|after_or_equal:leave_plan_from',
            'leave_plan_status' => 'nullable|in:active,inactive',

            // OT Plan
            'ot_plan_from' => 'nullable|date',
            'ot_plan_to' => 'nullable|date|after_or_equal:ot_plan_from',
            'ot_plan_status' => 'nullable|in:active,inactive',

            // Day Off Work Plan
            'day_off_work_plan_from' => 'nullable|date',
            'day_off_work_plan_to' => 'nullable|date|after_or_equal:day_off_work_plan_from',
            'day_off_work_plan_status' => 'nullable|in:active,inactive',

            // Roster Plans
            'roster_plans_from' => 'nullable|date',
            'roster_plans_to' => 'nullable|date|after_or_equal:roster_plans_from',
            'roster_plans_status' => 'nullable|in:active,inactive',

            // Bonus Plan
            'bonus_plan_from' => 'nullable|date',
            'bonus_plan_to' => 'nullable|date|after_or_equal:bonus_plan_from',
            'bonus_plan_status' => 'nullable|in:active,inactive',

            // Allowance Plan
            'allowance_plan_from' => 'nullable|date',
            'allowance_plan_to' => 'nullable|date|after_or_equal:allowance_plan_from',
            'allowance_plan_status' => 'nullable|in:active,inactive',

            // Late Deduction Plan
            'late_deduction_plan_from' => 'nullable|date',
            'late_deduction_plan_to' => 'nullable|date|after_or_equal:late_deduction_plan_from',
            'late_deduction_plan_status' => 'nullable|in:active,inactive',

            // Early Out Deduction Plan
            'early_out_deduction_plan_from' => 'nullable|date',
            'early_out_deduction_plan_to' => 'nullable|date|after_or_equal:early_out_deduction_plan_from',
            'early_out_deduction_plan_status' => 'nullable|in:active,inactive',

            // Medical Plan
            'medical_plan_from' => 'nullable|date',
            'medical_plan_to' => 'nullable|date|after_or_equal:medical_plan_from',
            'medical_plan_status' => 'nullable|in:active,inactive',

            // Excessive Late Plan
            'excessive_late_plan_from' => 'nullable|date',
            'excessive_late_plan_to' => 'nullable|date|after_or_equal:excessive_late_plan_from',
            'excessive_late_plan_status' => 'nullable|in:active,inactive',

            // Meal Plan
            'meal_plan_from' => 'nullable|date',
            'meal_plan_to' => 'nullable|date|after_or_equal:meal_plan_from',
            'meal_plan_status' => 'nullable|in:active,inactive',
        ],
            [
                'employee_id.required' => 'The employee field is required.',
                'employee_id.exists' => 'The selected employee is invalid.',
                'employee_id.unique' => 'The employee has already been assigned a plan.',
                'shift_plan_from.date' => 'The shift plan from date is invalid.',
                'shift_plan_to.date' => 'The shift plan to date is invalid.',
                'shift_plan_to.after_or_equal' => 'The shift plan to date must be after or equal to the from date.',
                'shift_plan_status.in' => 'The shift plan status must be either active or inactive.',
                'leave_plan_from.date' => 'The leave plan from date is invalid.',
                'leave_plan_to.date' => 'The leave plan to date is invalid.',
                'leave_plan_to.after_or_equal' => 'The leave plan to date must be after or equal to the from date.',
                'leave_plan_status.in' => 'The leave plan status must be either active or inactive.',
                'ot_plan_from.date' => 'The OT plan from date is invalid.',
                'ot_plan_to.date' => 'The OT plan to date is invalid.',
                'ot_plan_to.after_or_equal' => 'The OT plan to date must be after or equal to the from date.',
                'ot_plan_status.in' => 'The OT plan status must be either active or inactive.',
                'day_off_work_plan_from.date' => 'The day off work plan from date is invalid.',
                'day_off_work_plan_to.date' => 'The day off work plan to date is invalid.',
                'day_off_work_plan_to.after_or_equal' => 'The day off work plan to date must be after or equal to the from date.',
                'day_off_work_plan_status.in' => 'The day off work plan status must be either active or inactive.',
                'roster_plans_from.date' => 'The roster plans from date is invalid.',
                'roster_plans_to.date' => 'The roster plans to date is invalid.',
                'roster_plans_to.after_or_equal' => 'The roster plans to date must be after or equal to the from date.',
                'roster_plans_status.in' => 'The roster plans status must be either active or inactive.',
                'bonus_plan_from.date' => 'The bonus plan from date is invalid.',
                'bonus_plan_to.date' => 'The bonus plan to date is invalid.',
                'bonus_plan_to.after_or_equal' => 'The bonus plan to date must be after or equal to the from date.',
                'bonus_plan_status.in' => 'The bonus plan status must be either active or inactive.',
                'allowance_plan_from.date' => 'The allowance plan from date is invalid.',
                'allowance_plan_to.date' => 'The allowance plan to date is invalid.',
                'allowance_plan_to.after_or_equal' => 'The allowance plan to date must be after or equal to the from date.',
                'allowance_plan_status.in' => 'The allowance plan status must be either active or inactive.',
                'late_deduction_plan_from.date' => 'The late deduction plan from date is invalid.',
                'late_deduction_plan_to.date' => 'The late deduction plan to date is invalid.',
                'late_deduction_plan_to.after_or_equal' => 'The late deduction plan to date must be after or equal to the from date.',
                'late_deduction_plan_status.in' => 'The late deduction plan status must be either active or inactive.',
                'early_out_deduction_plan_from.date' => 'The early out deduction plan from date is invalid.',
                'early_out_deduction_plan_to.date' => 'The early out deduction plan to date is invalid.',
                'early_out_deduction_plan_to.after_or_equal' => 'The early out deduction plan to date must be after or equal to the from date.',
                'early_out_deduction_plan_status.in' => 'The early out deduction plan status must be either active or inactive.',
                'meal_plan_from.date' => 'The meal plan from date is invalid.',
                'meal_plan_to.date' => 'The meal plan to date is invalid.',
                'meal_plan_to.after_or_equal' => 'The meal plan to date must be after or equal to the from date.',
                'meal_plan_status.in' => 'The meal plan status must be either active or inactive.',
            ]);
        return $validated;
    }

    public function employeeEligiblePanInfoSave($validated, $employeePlan = null)
    {

        if (isset($employeePlan)) {
            $employeePlan->update($validated);
            return $employeePlan;
        } else {
            $data = EmployeeEligiblePlan::create($validated);
            return $data;
        }
    }

    public function employeeEducationInfoValidation($request)
    {
        $validated = $request->validate([
            'employee_id' => 'required',
            'educations' => 'nullable|array',
            'educations.*.education_title' => 'nullable|string|max:255',
            'educations.*.institute' => 'nullable|string|max:255',
            'educations.*.group_major' => 'nullable|string|max:255',
            'educations.*.board_university' => 'nullable|string|max:255',
            'educations.*.result_grade' => 'nullable|string|max:100',
            'educations.*.passing_year' => 'nullable|string|max:10',
            'educations.*.gpa_cgpa' => 'nullable|string|max:20',
            'experiences' => 'nullable|array',
            'experiences.*.company' => 'nullable|string|max:255',
            'experiences.*.designation' => 'nullable|string|max:255',
            'experiences.*.department' => 'nullable|string|max:255',
            'experiences.*.date_from' => 'nullable|date',
            'experiences.*.date_to' => 'nullable|date|after_or_equal:experiences.*.date_from',
            'experiences.*.duration' => 'nullable|string|max:100',
            'experiences.*.responsibility' => 'nullable|string|max:1000',
            'trainings' => 'nullable|array',
            'trainings.*.training_title' => 'nullable|string|max:255',
            'trainings.*.course_name' => 'nullable|string|max:255',
            'trainings.*.training_code' => 'nullable|string|max:100',
            'trainings.*.institute' => 'nullable|string|max:255',
            'trainings.*.country' => 'nullable|string|max:100',
            'trainings.*.location' => 'nullable|string|max:255',
            'trainings.*.duration' => 'nullable|string|max:100',
            'trainings.*.from_date' => 'nullable|date',
            'trainings.*.to_date' => 'nullable|date|after_or_equal:trainings.*.from_date',
        ], [
            // Employee ID
            'employee_id.required' => 'Employee is required.',

            // Education fields
            'educations.*.education_title.string' => 'Education title must be a valid text.',
            'educations.*.education_title.max' => 'Education title cannot exceed 255 characters.',
            'educations.*.institute.string' => 'Institute name must be a valid text.',
            'educations.*.institute.max' => 'Institute name cannot exceed 255 characters.',
            'educations.*.group_major.string' => 'Group/Major must be a valid text.',
            'educations.*.group_major.max' => 'Group/Major cannot exceed 255 characters.',
            'educations.*.board_university.string' => 'Board/University must be a valid text.',
            'educations.*.board_university.max' => 'Board/University cannot exceed 255 characters.',
            'educations.*.result_grade.string' => 'Result/Grade must be a valid text.',
            'educations.*.result_grade.max' => 'Result/Grade cannot exceed 100 characters.',
            'educations.*.passing_year.string' => 'Passing year must be a valid text.',
            'educations.*.passing_year.max' => 'Passing year cannot exceed 10 characters.',
            'educations.*.gpa_cgpa.string' => 'GPA/CGPA must be a valid text.',
            'educations.*.gpa_cgpa.max' => 'GPA/CGPA cannot exceed 20 characters.',

            // Experience fields
            'experiences.*.company.string' => 'Company name must be a valid text.',
            'experiences.*.company.max' => 'Company name cannot exceed 255 characters.',
            'experiences.*.designation.string' => 'Designation must be a valid text.',
            'experiences.*.designation.max' => 'Designation cannot exceed 255 characters.',
            'experiences.*.department.string' => 'Department must be a valid text.',
            'experiences.*.department.max' => 'Department cannot exceed 255 characters.',
            'experiences.*.date_from.date' => 'Start date must be a valid date.',
            'experiences.*.date_to.date' => 'End date must be a valid date.',
            'experiences.*.date_to.after_or_equal' => 'End date must be after or equal to start date.',
            'experiences.*.duration.string' => 'Duration must be a valid text.',
            'experiences.*.duration.max' => 'Duration cannot exceed 100 characters.',
            'experiences.*.responsibility.string' => 'Responsibility must be a valid text.',
            'experiences.*.responsibility.max' => 'Responsibility cannot exceed 1000 characters.',

            // Training fields
            'trainings.*.training_title.string' => 'Training title must be a valid text.',
            'trainings.*.training_title.max' => 'Training title cannot exceed 255 characters.',
            'trainings.*.course_name.string' => 'Course name must be a valid text.',
            'trainings.*.course_name.max' => 'Course name cannot exceed 255 characters.',
            'trainings.*.training_code.string' => 'Training code must be a valid text.',
            'trainings.*.training_code.max' => 'Training code cannot exceed 100 characters.',
            'trainings.*.institute.string' => 'Institute must be a valid text.',
            'trainings.*.institute.max' => 'Institute cannot exceed 255 characters.',
            'trainings.*.country.string' => 'Country must be a valid text.',
            'trainings.*.country.max' => 'Country cannot exceed 100 characters.',
            'trainings.*.location.string' => 'Location must be a valid text.',
            'trainings.*.location.max' => 'Location cannot exceed 255 characters.',
            'trainings.*.duration.string' => 'Duration must be a valid text.',
            'trainings.*.duration.max' => 'Duration cannot exceed 100 characters.',
            'trainings.*.from_date.date' => 'From date must be a valid date.',
            'trainings.*.to_date.date' => 'To date must be a valid date.',
            'trainings.*.to_date.after_or_equal' => 'To date must be after or equal to from date.',
        ]);
        return $validated;
    }

    public function employeeEducationInfoSave($validated, $employeeEduData = null)
    {
        if (isset($employeeEduData)) {
            $employeeEduData->update($validated);
            return $employeeEduData;
        } else {
//            $data = new EmployeeEducationExperienceTraining($validated);
//            dd($data->getAttributes());

            $data = EmployeeEducationExperienceTraining::create($validated);
            return $data;
        }
    }

    public function employeeNomineeInfoValidation($request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|integer|exists:employees,id',
            'nominee_name' => 'required|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'spouse_name' => 'nullable|string|max:255',
            'gender' => 'required|in:Male,Female,Other',
            'date_of_birth' => 'required|date|before:today',
            'religion' => 'nullable|string|max:100',
            'marital_status' => 'required|in:single,married,divorced,widowed',
            'nationality' => 'required|string|max:100',
            'blood_group' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'photo_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'nid' => 'nullable|string|max:50',
            'birth_reg_no' => 'nullable|string|max:50',
            'bank_account_no' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:20',
            'mobile' => 'required|string|max:20',
            'present_address_line' => 'nullable|string|max:255',
            'village' => 'nullable|string|max:255',
            'post_office' => 'nullable|string|max:255',
            'thana' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:20',
            'country' => 'required|string|max:100',
        ], [
            'employee_id.required' => 'Employee ID is required.',
            'employee_id.exists' => 'The selected employee does not exist.',
            'nominee_name.required' => 'Nominee name is required.',
            'gender.required' => 'Please select gender.',
            'gender.in' => 'Gender must be male, female, or other.',
            'date_of_birth.required' => 'Date of birth is required.',
            'date_of_birth.before' => 'Date of birth must be a valid past date.',
            'marital_status.required' => 'Please select marital status.',
            'marital_status.in' => 'Invalid marital status value.',
            'nationality.required' => 'Nationality is required.',
            'blood_group.in' => 'Invalid blood group format.',
            'photo_path.image' => 'Photo must be an image file.',
            'photo_path.mimes' => 'Photo must be jpeg, jpg, or png format.',
            'photo_path.max' => 'Photo size must not exceed 2MB.',
            'ratio.required' => 'Nominee ratio is required.',
            'ratio.numeric' => 'Ratio must be a valid number.',
            'ratio.max' => 'Ratio cannot exceed 100%.',
            'mobile.required' => 'Mobile number is required.',
            'country.required' => 'Country field is required.',
        ]);

        return $validated;
    }

    public function employeeNomineeInfoSave($request, $validated, $employeeNomineeData = null)
    {
        if (isset($employeeNomineeData)) {
            if ($request->hasFile('photo_path')) {
                $this->employeeAttachmentDelete($employeeNomineeData->photo_path);
                $photo = $request->file('photo_path');
                $validated = $this->employeeAttachmentValidation($validated, $request, $photo, 'photo_path');
                Log::info('Photo Uploaded');
            }
            $employeeNomineeData->update($validated);
            return $employeeNomineeData;
        } else {
            if ($request->hasFile('photo_path')) {
                $photo = $request->file('photo_path');
                $validated = $this->employeeAttachmentValidation($validated, $request, $photo, 'photo_path');
                Log::info('Photo Uploaded');
            }
            $data = EmployeeNominee::create($validated);
            return $data;
        }
    }

    public function employeeSalaryBreakdownValidation($request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',

            'basic_salary' => 'required|numeric|min:0',
            'house_allowance' => 'nullable|numeric|min:0',
            'transport_allowance' => 'nullable|numeric|min:0',
            'food_allowance' => 'nullable|numeric|min:0',
            'medical_allowance' => 'nullable|numeric|min:0',
            'other_earnings' => 'nullable|numeric|min:0',
            'gross_salary' => 'required|numeric|min:0',

            'currency' => 'nullable|string|in:BDT,USD,EUR,INR',
        ],
            [
                'employee_id.required' => 'The employee field is required.',
                'employee_id.exists' => 'The selected employee does not exist.',

                'basic_salary.required' => 'The basic salary is required.',
                'basic_salary.numeric' => 'The basic salary must be a valid number.',
                'basic_salary.min' => 'The basic salary must be a positive value.',

                'house_allowance.numeric' => 'The house allowance must be a valid number.',
                'house_allowance.min' => 'The house allowance must be a positive value.',

                'transport_allowance.numeric' => 'The transport allowance must be a valid number.',
                'transport_allowance.min' => 'The transport allowance must be a positive value.',

                'food_allowance.numeric' => 'The food allowance must be a valid number.',
                'food_allowance.min' => 'The food allowance must be a positive value.',

                'medical_allowance.numeric' => 'The medical allowance must be a valid number.',
                'medical_allowance.min' => 'The medical allowance must be a positive value.',


                'other_earnings.numeric' => 'The other earnings must be a valid number.',
                'other_earnings.min' => 'The other earnings must be a positive value.',

                'gross_salary.required' => 'The gross salary is required.',
                'gross_salary.numeric' => 'The gross salary must be a valid number.',
                'gross_salary.min' => 'The gross salary must be a positive value.',

                'currency.string' => 'The currency must be a valid string.',
                'currency.in' => 'The currency must be one of the following: BDT, USD, EUR, INR.',

            ]);

        return $validated;
    }

    public function employeeSalaryBreakdownInfoSave($validated, $employeeSalaryBreakdown = null)
    {

        if (isset($employeeSalaryBreakdown)) {
            $employeeSalaryBreakdown->update($validated);
            return $employeeSalaryBreakdown;
        } else {
            $data = EmployeeSalaryBreakdown::create($validated);
            return $data;
        }
    }

    public function employeeBankAccountsInfoValidation($request)
    {
        $validated = $request->validate([
            'employee_id' => 'required',
            'bank_id' => 'required',
            'branch_id' => 'nullable',
            'account_holder_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'remarks' => 'nullable|string',
        ], [
            'employee_id.required' => 'Please select an employee.',
            'employee_id.exists' => 'The selected employee does not exist.',
            'bank_id.required' => 'Please select a bank.',
            'bank_id.exists' => 'The selected bank does not exist.',
            'branch_id.exists' => 'The selected branch does not exist.',
            'account_holder_name.required' => 'Account holder name is required.',
            'account_holder_name.max' => 'Account holder name cannot exceed 255 characters.',
            'account_number.required' => 'Account number is required.',
            'account_number.max' => 'Account number cannot exceed 255 characters.',
            'status.required' => 'Please select a status.',
            'status.in' => 'Status must be either active or inactive.',
        ]);
        return $validated;
    }

    public function employeeBankAccountsInfoSave($validated, $employeeData = null)
    {
        if (isset($employeeData)) {
            $employeeData->update($validated);
            return $employeeData;
        } else {
            $data = EmployeeBankAccount::create($validated);
            return $data;
        }
    }

}
