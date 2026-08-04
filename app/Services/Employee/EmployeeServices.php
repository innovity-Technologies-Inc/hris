<?php

namespace App\Services\Employee;

use App\Enums\UserType;
use App\HelperClass;
use App\Models\Company\Branch;
use App\Models\Company\Company;
use App\Models\Company\CompanyLocation;
use App\Models\Company\Department;
use App\Models\Company\Designation;
use App\Models\Company\Division;
use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeBankAccount;
use App\Models\Employee\EmployeeEducationExperienceTraining;
use App\Models\Employee\EmployeeEligiblePlan;
use App\Models\Employee\EmployeeLifecycle;
use App\Models\Employee\EmployeeNominee;
use App\Models\Employee\EmployeeOfficeInfo;
use App\Models\Employee\EmployeeSalaryBreakdown;
use App\Models\Company\SalaryGrade;
use App\Models\Company\Section;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\Setting\NotificationServices;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

use App\Mail\Employee\EmployeeAccountCreated;
use Illuminate\Support\Facades\Mail;

class EmployeeServices
{
    protected $notificationService;

    public function __construct(NotificationServices $notificationService)
    {
        $this->notificationService = $notificationService;
    }

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

    public function getRoles()
    {
        return Role::all();
    }


    public function employeeInfoValidation($request)
    {
        $id = $request->route('id');
        $employee = $id ? Employee::find($id) : null;
        $userId = $employee ? $employee->user_id : null;
        $isEmployee = auth()->user()->user_type === UserType::Employee;

        $rules = [
            'applicant_id' => 'nullable|string|unique:employees,applicant_id,' . $id,
            'system_id' => 'nullable|string|unique:employees,system_id,' . $id,
            'punch_card_no' => $isEmployee ? 'nullable|string' : 'required|string|unique:employees,punch_card_no,' . $id,

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
            'blood_group' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
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
            'nid' => 'nullable|string|max:255',

            // Birth Information
            'date_of_birth' => 'required|date|before:today',
            'birth_country' => 'nullable|string|max:255',
            'birth_reg_no' => 'nullable|string|max:255',

            // Contact Information
            'personal_mobile' => 'required|string|max:20',
            'home_phone' => 'nullable|string|max:20',
            'work_mobile' => 'nullable|string|max:20',
            'work_phone' => 'nullable|string|max:20',
            'work_email' => 'nullable|email|max:255|unique:users,email,' . $userId,
            'personal_email' => 'nullable|email|max:255',

            // Login Information
            'user_type' => $id ? 'nullable|string|in:' . implode(',', UserType::values()) : 'required|string|in:' . implode(',', UserType::values()),
            'roles' => 'nullable|array',
            'password' => $id ? 'nullable|min:8|confirmed' : 'required|min:8|confirmed',

            // File uploads
            'photo_path' => 'nullable|file|image|mimes:jpeg,png,jpg,webp|max:2048',
            'fingerprint_path' => 'nullable|file|mimes:jpeg,png,jpg,webp|max:2048',
            'signature_path' => 'nullable|file|mimes:jpeg,png,jpg,webp|max:2048',
            'experience_attachment_path' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ];
        
        $rules = $this->applyProfileFieldConfigRules($rules, 'general');

        $validated = $request->validate($rules,
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
                'user_type.required' => 'User type is required for login information.',
                'password.required' => 'Password is required for new accounts.',
                'password.min' => 'Password must be at least 8 characters.',
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

        // Handle User Logic
        $userData = [
            'name' => $validated['full_name'],
            'email' => $request->work_email,
        ];

        if ($request->filled('user_type')) {
            $userData['user_type'] = $request->user_type;
        }

        if (!$id) {
            $userData['status'] = 'active';
        }

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        if ($id) {
            $employee = $this->getEmployeeById($id);
            $user = User::find($employee->user_id);
            if ($user) {
                $user->update($userData);
            } else {
                $user = User::create($userData);
                $employee->update(['user_id' => $user->id]);
            }
        } else {
            $user = User::create($userData);
            $validated['user_id'] = $user->id;
        }

        // Sync Roles
        if ($request->has('roles')) {
            $user->syncRoles($request->roles);
        }

        if ($request->hasFile('photo_path')) {
            $photo = $request->file('photo_path');
            $validated = $this->employeeAttachmentValidation($validated, $request, $photo, 'photo_path');
        }
        if ($request->hasFile('fingerprint_path')) {
            $fingerprint = $request->file('fingerprint_path');
            $validated = $this->employeeAttachmentValidation($validated, $request, $fingerprint, 'fingerprint_path');
        }

        if ($request->hasFile('signature_path')) {
            $signature = $request->file('signature_path');
            $validated = $this->employeeAttachmentValidation($validated, $request, $signature, 'signature_path');
        }
        if ($request->hasFile('experience_attachment_path')) {
            $experience_attachment = $request->file('experience_attachment_path');
            $validated = $this->employeeAttachmentValidation($validated, $request, $experience_attachment, 'experience_attachment_path');
        }
        if (empty($validated['applicant_id'])) {
            $latest = Employee::where('applicant_id', 'LIKE', 'APP%')
                ->select('applicant_id')
                ->orderByRaw('CAST(SUBSTRING(applicant_id, 4) AS UNSIGNED) DESC')
                ->first();
            $num = $latest ? intval(substr($latest->applicant_id, 3)) + 1 : 1;
            do {
                $applicantId = 'APP' . str_pad($num, 6, '0', STR_PAD_LEFT);
                $num++;
            } while (Employee::where('applicant_id', $applicantId)->exists());
            $validated['applicant_id'] = $applicantId;
        }

        if (empty($validated['system_id'])) {
            $latest = Employee::where('system_id', 'LIKE', 'SYS%')
                ->select('system_id')
                ->orderByRaw('CAST(SUBSTRING(system_id, 4) AS UNSIGNED) DESC')
                ->first();
            $num = $latest ? intval(substr($latest->system_id, 3)) + 1 : 1;
            do {
                $systemId = 'SYS' . str_pad($num, 6, '0', STR_PAD_LEFT);
                $num++;
            } while (Employee::where('system_id', $systemId)->exists());
            $validated['system_id'] = $systemId;
        }

        if (empty($id)) {

            $employee_data = Employee::create($validated);
            $employee_data->general_info_status = (auth()->user()->user_type === UserType::Employee) ? 'pending' : 'active';
            $employee_data->save();
            $this->revertProfileToPending($employee_data->id);
            // Update user with employee_id for bi-directional link
            $user->update(['employee_id' => $employee_data->id]);

            EmployeeLifecycle::create([
                'employee_id' => $employee_data->id,
                'type' => 'profile_created',
                'status_date' => now(),
                'description' => 'Employee profile has been created.'
            ]);
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
            $employee->general_info_status = (auth()->user()->user_type === UserType::Employee) ? 'pending' : 'active';
            $employee->save();
            $this->revertProfileToPending($id);
            $employee_data = $employee;
        }
        return $employee_data;
    }

    public function getEmployeeById($id)
    {
        try {
            $employee = Employee::with('user')->find($id);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error in EmployeeServices@getEmployeeById: ' . $e->getMessage(), ['exception' => $e]);
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

    public function getDesignations()
    {
        $designations = Designation::all();
        return $designations;
    }


    public function employeeOfficeInfoValidation($request)
    {
        $rules = [
            // Basic Identifiers
            'employee_id' => 'required|integer',
            'emp_type' => 'nullable|in:permanent,contractual',
            'grade_id' => 'nullable|integer',
            'hr_file_no' => 'nullable|string|max:255',
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
            'transport_eligible' => 'nullable|in:yes,no',

            // Loan & Benefits
            'can_apply_loan' => 'nullable|in:yes,no',
            'pf_effective_date' => 'nullable|date',
            'can_apply_advance' => 'nullable|in:yes,no',
            'gratuity_eligible' => 'nullable|in:yes,no',
        ];

        $rules = $this->applyProfileFieldConfigRules($rules, 'office-information');

        $validated = $request->validate($rules, [
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
            $employee_office_info->update($validated);
            $employee_office_data = $employee_office_info->fresh();
        }

        // Handle Lifecycle for Joined / Probation / Confirmed
        if (!empty($validated['date_of_join'])) {
            $exists = EmployeeLifecycle::where('employee_id', $validated['employee_id'])
                ->where('type', 'joined')->exists();
            if (!$exists) {
                EmployeeLifecycle::create([
                    'employee_id' => $validated['employee_id'],
                    'type' => 'joined',
                    'status_date' => $validated['date_of_join'],
                    'description' => 'Employee joined the company.'
                ]);
            }
        }

        if (!empty($validated['probation_duration']) && empty($validated['confirmation_date'])) {
            // Check if probation lifecycle already exists
            $exists = EmployeeLifecycle::where('employee_id', $validated['employee_id'])
                ->where('type', 'probation')->exists();
            if (!$exists) {
                EmployeeLifecycle::create([
                    'employee_id' => $validated['employee_id'],
                    'type' => 'probation',
                    'status_date' => $validated['date_of_join'] ?? now(),
                    'description' => 'Employee is on probation for ' . $validated['probation_duration'] . ' months.'
                ]);
            }
        }

        if (!empty($validated['confirmation_date'])) {
            $exists = EmployeeLifecycle::where('employee_id', $validated['employee_id'])
                ->where('type', 'confirmed')->exists();
            if (!$exists) {
                EmployeeLifecycle::create([
                    'employee_id' => $validated['employee_id'],
                    'type' => 'confirmed',
                    'status_date' => $validated['confirmation_date'],
                    'description' => 'Employee has been confirmed.'
                ]);
            }
        }

        return $employee_office_data;
    }

    public function employeeEligiblePlanValidation($request)
    {
        $rules = [
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
        ];

        $rules = $this->applyProfileFieldConfigRules($rules, 'employee-policy');

        $validated = $request->validate($rules,
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
        $rules = [
            'employee_id' => 'required',
            'educations' => 'nullable|array',
            'educations.*.education_title' => 'nullable|string|max:255',
            'educations.*.institute' => 'nullable|string|max:255',
            'educations.*.group_major' => 'nullable|string|max:255',
            'educations.*.board_university' => 'nullable|string|max:255',
            'educations.*.result_grade' => 'nullable|string|max:100',
            'educations.*.passing_year' => 'nullable|string|max:10',
            'educations.*.gpa_cgpa' => 'nullable|string|max:20',
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
        ];

        $rules = $this->applyProfileFieldConfigRules($rules, 'education');

        $validated = $request->validate($rules, [
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
        $validated['status'] = (auth()->user()->user_type === UserType::Employee) ? 'pending' : 'active';
        if (isset($employeeEduData)) {
            $employeeEduData->update($validated);
            $this->revertProfileToPending($employeeEduData->employee_id);
            return $employeeEduData;
        } else {
            $data = EmployeeEducationExperienceTraining::create($validated);
            $this->revertProfileToPending($data->employee_id);
            return $data;
        }
    }

    public function employeeNomineeInfoValidation($request)
    {
        $rules = [
            'employee_id' => 'required|integer|exists:employees,id',
            'nominee_name' => 'required|string|max:255',
            'relation' => 'required|string|max:100',
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
        ];

        $rules = $this->applyProfileFieldConfigRules($rules, 'emergency_contact');

        $validated = $request->validate($rules, [
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
        $validated['status'] = (auth()->user()->user_type === UserType::Employee) ? 'pending' : 'active';
        if (isset($employeeNomineeData)) {
            if ($request->hasFile('photo_path')) {
                $this->employeeAttachmentDelete($employeeNomineeData->photo_path);
                $photo = $request->file('photo_path');
                $validated = $this->employeeAttachmentValidation($validated, $request, $photo, 'photo_path');
                Log::info('Photo Uploaded');
            }
            $employeeNomineeData->update($validated);
            $this->revertProfileToPending($employeeNomineeData->employee_id);
            return $employeeNomineeData;
        } else {
            if ($request->hasFile('photo_path')) {
                $photo = $request->file('photo_path');
                $validated = $this->employeeAttachmentValidation($validated, $request, $photo, 'photo_path');
                Log::info('Photo Uploaded');
            }
            $data = EmployeeNominee::create($validated);
            $this->revertProfileToPending($data->employee_id);
            return $data;
        }
    }

    public function employeeSalaryBreakdownValidation($request)
    {
        $rules = [
            'employee_id' => 'required|exists:employees,id',
            'pay_scale_id' => 'required|exists:pay_scales,id',

            'basic_salary' => 'required|numeric|min:0',
            'house_allowance' => 'nullable|numeric|min:0',
            'transport_allowance' => 'nullable|numeric|min:0',
            'food_allowance' => 'nullable|numeric|min:0',
            'medical_allowance' => 'nullable|numeric|min:0',
            'other_earnings' => 'nullable|numeric|min:0',

            'basic_salary_percentage' => 'required|numeric|min:0',
            'house_allowance_percentage' => 'nullable|numeric|min:0',
            'transport_allowance_percentage' => 'nullable|numeric|min:0',
            'food_allowance_percentage' => 'nullable|numeric|min:0',
            'medical_allowance_percentage' => 'nullable|numeric|min:0',
            'other_earnings_percentage' => 'nullable|numeric|min:0',

            'gross_salary' => 'required|numeric|min:0',
        ];

        $rules = $this->applyProfileFieldConfigRules($rules, 'salary-breakdown');

        // Custom validation for Pay Scale range
        if ($request->has('pay_scale_id') && $request->has('gross_salary')) {
            $payScale = \App\Models\Company\PayScale::find($request->pay_scale_id);
            if ($payScale) {
                // Keep it required if the config says so, otherwise nullable
                $rules['gross_salary'] .= "|numeric|min:{$payScale->min_salary}|max:{$payScale->max_salary}";
            }
        }

        $validated = $request->validate($rules, [
            'employee_id.required' => 'The employee field is required.',
            'employee_id.exists' => 'The selected employee does not exist.',
            'pay_scale_id.required' => 'Please select a Pay Scale.',
            'gross_salary.min' => 'Gross salary is lower than the minimum allowed for this pay scale.',
            'gross_salary.max' => 'Gross salary is higher than the maximum allowed for this pay scale.',

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

                'basic_salary_percentage.required' => 'The basic salary percentage is required.',
                'basic_salary_percentage.numeric' => 'The basic salary percentage must be a valid number.',
                'basic_salary_percentage.min' => 'The basic salary percentage must be a positive value.',

                'house_allowance_percentage.numeric' => 'The house allowance percentage must be a valid number.',
                'house_allowance_percentage.min' => 'The house allowance percentage must be a positive value.',

                'transport_allowance_percentage.numeric' => 'The transport allowance percentage must be a valid number.',
                'transport_allowance_percentage.min' => 'The transport allowance percentage must be a positive value.',

                'food_allowance_percentage.numeric' => 'The food allowance percentage must be a valid number.',
                'food_allowance_percentage.min' => 'The food allowance percentage must be a positive value.',

                'medical_allowance_percentage.numeric' => 'The medical allowance percentage must be a valid number.',
                'medical_allowance_percentage.min' => 'The medical allowance percentage must be a positive value.',

                'other_earnings_percentage.numeric' => 'The other earnings percentage must be a valid number.',
                'other_earnings_percentage.min' => 'The other earnings percentage must be a positive value.',

                'gross_salary.required' => 'The gross salary is required.',
                'gross_salary.numeric' => 'The gross salary must be a valid number.',
        ]);

        // Total Percentage Validation (Final Defense)
        $totalPercentage = (float)$request->basic_salary_percentage +
            (float)$request->house_allowance_percentage +
            (float)$request->transport_allowance_percentage +
            (float)$request->food_allowance_percentage +
            (float)$request->medical_allowance_percentage +
            (float)$request->other_earnings_percentage;

        if (round($totalPercentage, 2) != 100.00) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'basic_salary_percentage' => ["The total salary breakdown percentage must be exactly 100%. Currently it is {$totalPercentage}%."],
            ]);
        }

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
        $rules = [
            'employee_id' => 'required',
            'bank_id' => 'required',
            'branch_id' => 'nullable',
            'account_holder_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'remarks' => 'nullable|string',
        ];

        $rules = $this->applyProfileFieldConfigRules($rules, 'employee-bank-account');

        $validated = $request->validate($rules, [
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

    /**
     * Toggle employee status between active and inactive
     */
    public function toggleEmployeeStatus($employeeId, $status)
    {
        $employee = Employee::findOrFail($employeeId);
        $oldStatus = $employee->status;
        $employee->status = $status;
        $employee->save();

        if ($oldStatus !== $status) {
            EmployeeLifecycle::create([
                'employee_id' => $employee->id,
                'type' => $status,
                'status_date' => now(),
                'description' => 'Employee status changed from ' . $oldStatus . ' to ' . $status . '.'
            ]);
        }

        // Also update associated user if exists
        if ($employee->user_id) {
            \App\Models\User::where('id', $employee->user_id)->update(['status' => $status]);
        }

        // Send Email Notification
        if (!empty($employee->work_email)) {
            try {
                \Illuminate\Support\Facades\Mail::to($employee->work_email)->send(new \App\Mail\Employee\ProfileStatusMail($employee, $status));
            } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error in EmployeeServices@toggleEmployeeStatus: ' . $e->getMessage(), ['exception' => $e]);

            }
        }

        return $employee;
    }

    public function createEmployeeAccount(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'applicant_id' => 'nullable|string|unique:employees,applicant_id',
            'system_id' => 'nullable|string|unique:employees,system_id',
            'punch_card_no' => 'required|string|unique:employees,punch_card_no',
            'work_email' => 'required|email|unique:users,email|unique:employees,work_email',
            'user_type' => 'required|string|in:' . implode(',', UserType::values()),
            'role' => 'nullable|string|exists:roles,name',
            'password' => 'required|min:8|confirmed',
        ]);

        $applicantId = $request->input('applicant_id');
        if (empty($applicantId)) {
            $latest = Employee::where('applicant_id', 'LIKE', 'APP%')
                ->select('applicant_id')
                ->orderByRaw('CAST(SUBSTRING(applicant_id, 4) AS UNSIGNED) DESC')
                ->first();
            $num = $latest ? intval(substr($latest->applicant_id, 3)) + 1 : 1;
            do {
                $applicantId = 'APP' . str_pad($num, 6, '0', STR_PAD_LEFT);
                $num++;
            } while (Employee::where('applicant_id', $applicantId)->exists());
        }

        $systemId = $request->input('system_id');
        if (empty($systemId)) {
            $latest = Employee::where('system_id', 'LIKE', 'SYS%')
                ->select('system_id')
                ->orderByRaw('CAST(SUBSTRING(system_id, 4) AS UNSIGNED) DESC')
                ->first();
            $num = $latest ? intval(substr($latest->system_id, 3)) + 1 : 1;
            do {
                $systemId = 'SYS' . str_pad($num, 6, '0', STR_PAD_LEFT);
                $num++;
            } while (Employee::where('system_id', $systemId)->exists());
        }

        $nameParts = explode(' ', trim($request->full_name), 2);
        $firstName = $nameParts[0];
        $lastName = $nameParts[1] ?? '';

        try {
            return DB::transaction(function () use ($request, $applicantId, $systemId, $firstName, $lastName) {
                // 1. Create Employee
                $employee = Employee::create([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'full_name' => $request->full_name,
                    'work_email' => $request->work_email,
                    'applicant_id' => $applicantId,
                    'system_id' => $systemId,
                    'punch_card_no' => $request->punch_card_no,
                    'status' => 'active', // Default to active for manually created accounts
                ]);

                // 2. Create User
                $user = User::create([
                    'name' => $request->full_name,
                    'email' => $request->work_email,
                    'password' => Hash::make($request->password),
                    'user_type' => $request->user_type,
                    'employee_id' => $employee->id,
                    'status' => 'active',
                ]);

                // 3. Link User to Employee
                $employee->update(['user_id' => $user->id]);

                // 4. Assign Role
                if ($request->has('role') && !empty($request->role)) {
                    $user->assignRole($request->role);
                }

                // 5. System Notification (Always save to DB)
                try {
                    $this->notificationService->createNotification(
                        $user->user_type === UserType::Group ? 'hr' : $user->user_type->value,
                        $user->id,
                        'Account Created',
                        'Welcome! Your employee account has been successfully created.',
                        ['employee_id' => $employee->id]
                    );
                } catch (\Exception $ne) {
            \Illuminate\Support\Facades\Log::error('Error in EmployeeServices@createEmployeeAccount: ' . $ne->getMessage(), ['exception' => $ne]);
                    Log::warning('System notification failed during account creation: ' . $ne->getMessage());
                }

                // 6. Send Email (Decoupled try-catch)
                try {
                    Mail::to($request->work_email)->send(new EmployeeAccountCreated(
                        $request->full_name,
                        $request->work_email,
                        $request->password
                    ));
                } catch (\Exception $me) {
            \Illuminate\Support\Facades\Log::error('Error in EmployeeServices@createEmployeeAccount: ' . $me->getMessage(), ['exception' => $me]);
                    Log::warning('Email delivery failed during account creation: ' . $me->getMessage());
                    // We don't re-throw here so the transaction can still commit and the account is created
                }

                return $user;
            });

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error in EmployeeServices@createEmployeeAccount: ' . $e->getMessage(), ['exception' => $e]);
            throw $e;
        }
    }
    public function updateLoginInfo(Request $request, $employeeId)
    {
        $employee = Employee::findOrFail($employeeId);
        $user = User::find($employee->user_id);
        $canManageRoles = auth()->user()->can('role-management.edit');

        // Validation logic
        $rules = [
            'password' => ($user ? 'nullable' : 'required') . '|min:8|confirmed',
        ];

        if ($canManageRoles) {
            $rules['work_email'] = 'required|email|unique:users,email' . ($user ? ',' . $user->id : '');
            $rules['user_type'] = 'required|string|in:' . implode(',', UserType::values());
            $rules['role'] = 'nullable|string|exists:roles,name';
        }

        $request->validate($rules);

        // Update or Create logic
        $userData = [];
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        if ($canManageRoles) {
            $userData['email'] = $request->work_email;
            $userData['user_type'] = $request->user_type;
            $userData['name'] = $employee->full_name;
            $userData['status'] = 'active';
            $userData['employee_id'] = $employee->id;
            
            // Update employee work email as well
            $employee->update(['work_email' => $request->work_email]);
        }

        if ($user) {
            if (!empty($userData)) {
                $user->update($userData);
            }
        } else {
            $user = User::create($userData);
            $employee->update(['user_id' => $user->id]);
        }

        if ($canManageRoles && $request->filled('role')) {
            $user->syncRoles([$request->role]);
        }

        return $user;
    }

    /**
     * Validate employee employment history information
     */
    public function employeeEmploymentHistoryValidation($request)
    {
        $rules = [
            'employee_id' => 'required|exists:employees,id',
            'histories' => 'required|array|min:1',
            'histories.*.company_name' => 'required|string|max:255',
            'histories.*.designation' => 'required|string|max:255',
            'histories.*.joining_date' => 'required|date',
            'histories.*.end_date' => 'nullable|date|after_or_equal:histories.*.joining_date',
            'histories.*.job_description' => 'nullable|string',
            'histories.*.achievements' => 'nullable|string',
        ];

        $rules = $this->applyProfileFieldConfigRules($rules, 'employment_history');

        return $request->validate($rules);
    }

    /**
     * Save employee employment history information
     */
    public function employeeEmploymentHistorySave($validated, $history = null)
    {
        if (!$history) {
            $history = new \App\Models\Employee\EmployeeEmploymentHistory();
        }

        $history->fill([
            'employee_id' => $validated['employee_id'],
            'histories' => $validated['histories'],
            'status' => (auth()->user()->user_type === UserType::Employee) ? 'pending' : 'active',
        ]);

        $history->save();
        $this->revertProfileToPending($history->employee_id);
        return $history;
    }

    /**
     * Review employee profile status.
     */
    public function reviewProfile($employee, string $status, ?string $cause = null, ?array $sections = [])
    {
        Log::info('--- Profile Review Started ---', [
            'employee_id' => $employee->id,
            'review_status' => $status,
            'selected_sections' => $sections
        ]);

        // 1. Update Employee Main Status
        $employee->status = $status;
        if ($status === 'incomplete') {
            $employee->review_cause = $cause;
        } else {
            $employee->review_cause = null;
        }
        $employee->save();

        // 2. Helper to Update Section Status ONLY if record exists
        $updateSectionStatus = function($table, $empId, $newStatus) {
            return \Illuminate\Support\Facades\DB::table($table)
                ->where('employee_id', $empId)
                ->update(['status' => $newStatus]);
        };

        // 3. Update Section Statuses
        if ($status === 'active') {
            // If overall status is active, all sections become active
            $employee->general_info_status = 'active';
            $employee->save();

            $eduUpdated = $updateSectionStatus('employee_education_experience_training', $employee->id, 'active');
            $nomUpdated = $updateSectionStatus('employee_nominees', $employee->id, 'active');
            $histUpdated = $updateSectionStatus('employee_employment_histories', $employee->id, 'active');

            Log::info('Set All to Active', ['edu' => $eduUpdated, 'nom' => $nomUpdated, 'hist' => $histUpdated]);
        } else {
            // If overall status is incomplete, handle specific sections
            $targetSections = !empty($sections) ? $sections : ['general', 'education', 'history', 'nominee'];
            Log::info('Processing Incomplete Status', ['targetSections' => $targetSections]);

            $employee->general_info_status = in_array('general', $targetSections) ? 'incomplete' : 'active';
            $employee->save();

            $eduStatus = in_array('education', $targetSections) ? 'incomplete' : 'active';
            $eduUpdated = $updateSectionStatus('employee_education_experience_training', $employee->id, $eduStatus);

            $histStatus = in_array('history', $targetSections) ? 'incomplete' : 'active';
            $histUpdated = $updateSectionStatus('employee_employment_histories', $employee->id, $histStatus);

            $nomStatus = in_array('nominee', $targetSections) ? 'incomplete' : 'active';
            $nomUpdated = $updateSectionStatus('employee_nominees', $employee->id, $nomStatus);

            Log::info('Section Updates Executed', [
                'edu' => ['status' => $eduStatus, 'result' => $eduUpdated],
                'hist' => ['status' => $histStatus, 'result' => $histUpdated],
                'nom' => ['status' => $nomStatus, 'result' => $nomUpdated],
            ]);
        }

        $user = $employee->user;
        if (!$user) {
            return;
        }

        $notificationService = app(\App\Services\Setting\NotificationServices::class);

        if ($status === 'incomplete') {
            // Send Email
            \Illuminate\Support\Facades\Mail::to($employee->work_email)->send(new \App\Mail\Employee\ProfileIncompleteMail($employee, $cause));
            
            // Create Notification for Employee
            $notificationService->createNotification(
                UserType::Employee->value,
                $user->id,
                'Profile Incomplete',
                'Your profile has been marked as incomplete. Cause: ' . $cause,
                ['employee_id' => $employee->id]
            );
        } elseif ($status === 'active') {
            // Send Email
            \Illuminate\Support\Facades\Mail::to($employee->work_email)->send(new \App\Mail\Employee\ProfileActiveMail($employee));
            
            // Create Notification for Employee
            $notificationService->createNotification(
                UserType::Employee->value,
                $user->id,
                'Profile Activated',
                'Your profile has been successfully reviewed and activated.',
                ['employee_id' => $employee->id]
            );
        }

        return $employee;
    }

    /**
     * Revert employee profile and user status to pending when an employee updates their profile.
     */
    public function revertProfileToPending($employeeId)
    {
        if (auth()->user()->user_type !== UserType::Employee) {
            return;
        }

        $employee = Employee::find($employeeId);
        if ($employee) {
            $employee->update(['status' => 'pending']);

            if ($employee->user_id) {
                \App\Models\User::where('id', $employee->user_id)->update(['status' => 'pending']);
            }

            // Notify HR
            app(\App\Services\Setting\NotificationServices::class)->createNotification(
                'hr',
                null,
                'Profile Updated for Review',
                'Employee ' . $employee->full_name . ' has updated their profile details.',
                ['employee_id' => $employee->id]
            );
        }
    }

    /**
     * Public wrapper to adjust rules dynamically based on profile field configurations.
     */
    public function getProfileFieldConfigRules(array $rules, string $section): array
    {
        return $this->applyProfileFieldConfigRules($rules, $section);
    }

    /**
     * Adjust rules dynamically based on profile field configurations.
     */
    private function applyProfileFieldConfigRules(array $rules, string $section): array
    {
        $configs = \App\Models\Setting\ProfileFieldConfig::where('section', $section)->get()->keyBy('field_name');

        foreach ($rules as $field => $ruleStr) {
            $configKey = $field;

            // Handle nested dot notation (e.g. present_address.line_1 -> present_address)
            if (str_contains($field, '.')) {
                $parts = explode('.', $field);
                $configKey = $parts[0];
            }

            // Handle suffix mapping (e.g. shift_plan_from -> shift_plan)
            if (!isset($configs[$configKey])) {
                foreach (['shift_plan', 'leave_plan', 'ot_plan', 'day_off_work_plan', 'roster_plans', 'bonus_plan', 'allowance_plan', 'late_deduction_plan', 'early_out_deduction_plan', 'medical_plan', 'excessive_late_plan', 'meal_plan'] as $planPrefix) {
                    if (str_starts_with($field, $planPrefix . '_')) {
                        $configKey = $planPrefix;
                        break;
                    }
                }
            }

            if (isset($configs[$configKey])) {
                $isRequired = $configs[$configKey]->is_required;
                $ruleArray = is_string($ruleStr) ? explode('|', $ruleStr) : $ruleStr;

                if ($isRequired) {
                    // Ensure rule has 'required', remove 'nullable' and 'sometimes'
                    if (!in_array('required', $ruleArray)) {
                        $ruleArray[] = 'required';
                    }
                    $ruleArray = array_filter($ruleArray, fn($r) => $r !== 'nullable' && $r !== 'sometimes');
                } else {
                    // Ensure rule has 'nullable', remove 'required'
                    if (!in_array('nullable', $ruleArray)) {
                        $ruleArray[] = 'nullable';
                    }
                    $ruleArray = array_filter($ruleArray, fn($r) => $r !== 'required');
                }

                $rules[$field] = implode('|', array_unique($ruleArray));
            }
        }

        return $rules;
    }
}

