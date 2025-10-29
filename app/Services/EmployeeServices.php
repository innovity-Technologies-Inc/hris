<?php

namespace App\Services;

use App\HelperClass;
use App\Models\Company;
use App\Models\CompanyLocation;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Division;
use App\Models\Employee;
use App\Models\EmployeeEducationExperienceTraining;
use App\Models\EmployeeEligiblePlan;
use App\Models\EmployeeOfficeInfo;
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

    public function employeeSearchResult(Request $request, $flexsearch){
        $query = Employee::query();

        $filters = [
            'employee_id' => $request->get('employee_id'),
            'full_name' => $request->get('employee_name'),
            'system_id' => $request->get('system_id'),
        ];

        $searchTerm = $request->get('keyword');

        $searchableFields = ['full_name'];

        $employees = $flexsearch->apply( $query,
            $filters,
            $searchTerm,
            $searchableFields)->orderBy('first_name', 'asc')->paginate(10);

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

    public function employeeAttachmentDelete($file_path){
        if($file_path != null){
            HelperClass::file_delete($file_path);
        }
    }

    public function employeeInfoSave(Request $request,$validated, $id = null)
    {

        $validated['full_name'] = trim(($validated['first_name'] ?? '') . ' ' .
            ($validated['middle_name'] ?? '') . ' ' .
            ($validated['last_name'] ?? ''));
        Log::info($validated);

        if($request->hasFile('photo_path')) {
            $photo = $request->file('photo_path');
            $validated = $this->employeeAttachmentValidation($validated, $request, $photo, 'photo_path');
            Log::info('Photo Uploaded');
        }
        if($request->hasFile('fingerprint_path')) {
            $fingerprint = $request->file('fingerprint_path');
            $validated = $this->employeeAttachmentValidation($validated, $request, $fingerprint, 'fingerprint_path');
            Log::info('Fingerprint Uploaded');
        }

        if($request->hasFile('signature_path')) {
            $signature = $request->file('signature_path');
            $validated = $this->employeeAttachmentValidation($validated, $request, $signature, 'signature_path');
            Log::info('Signature Uploaded');
        }

        if($request->hasFile('experience_attachment_path')) {
            $experience_attachment = $request->file('experience_attachment_path');
            $validated = $this->employeeAttachmentValidation($validated, $request, $experience_attachment, 'experience_attachment_path');
        }
        if(empty($id)){
            $employee_data = Employee::create($validated);
        }else{
            $employee = $this->getEmployeeById($id);

            if($request->hasFile('photo_path')) {
                $this->employeeAttachmentDelete($employee->photo_path);
            }
            if($request->hasFile('fingerprint_path')) {
                $this->employeeAttachmentDelete($employee->fingerprint_path);
            }
            if($request->hasFile('signature_path')) {
                $this->employeeAttachmentDelete($employee->signature_path);
            }
            if($request->hasFile('experience_attachment_path')) {
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

    public function getCompanies(){
        $companies = Company::all();
        return $companies;
    }

    public function getUnitByCompany($company_id){
        $units = CompanyLocation::where('company_id', $company_id)->get();
        return $units;
    }

    public function getDivisionByUnit($location_id){
        $divisions = Division::where('location_id', $location_id)->get();
        return $divisions;
    }

    public function getDepartmentByDivision($division_id){
        $departments = Department::where('division_id', $division_id)->get();
        return $departments;
    }

    public function getSectionByDepartment($department_id){
        $sections = Section::where('department_id', $department_id)->get();
        return $sections;
    }

    public function getActs(){
        $acts = Tofsil::all();
        return $acts;
    }

    public function getGradeByAct($tofsil_id){
        $grades = SalaryGrade::where('tofsil_id', $tofsil_id)->get();
        return $grades;
    }

    public function getDesignationsByDivision($division_id){
        $designations= Designation::where('division_id', $division_id)->get();
        return $designations;
    }

    public function employeeOfficeInfoValidation($request)
    {
        $validated = $request->validate([
            // Basic Identifiers
            'employee_id'              => 'required|integer',
            'emp_type'                 => 'nullable|in:permanent,contractual',
            'grade_id'                 => 'nullable|integer',
            'hr_file_no'               => 'nullable|string|max:255',
            'tofsil_id'                => 'nullable|integer',
            'file_note'                => 'nullable|string',

            // Joining Information
            'joining_company_id'       => 'nullable|integer',
            'joining_business_unit_id' => 'nullable|integer',
            'joining_division_id'      => 'nullable|integer',
            'joining_department_id'    => 'nullable|integer',
            'joining_section_id'       => 'nullable|integer',
            'joining_designation_id'   => 'nullable|integer',
            'date_of_join'             => 'nullable|date',

            // Current Posting Information
            'current_company_id'       => 'nullable|integer',
            'current_business_unit_id' => 'nullable|integer',
            'current_division_id'      => 'nullable|integer',
            'current_department_id'    => 'nullable|integer',
            'current_section_id'       => 'nullable|integer',
            'current_designation_id'   => 'nullable|integer',

            // Orientation
            'orientation_required'     => 'required|in:yes,no',
            'orientation_from'         => 'nullable|date',
            'orientation_to'           => 'nullable|date|after_or_equal:orientation_from',
            'orientation_type'         => 'nullable|string|max:100',
            'orientation_days'         => 'nullable|integer|min:1',

            // Employment & Performance
            'confirmation_date'        => 'nullable|date',
            'probation_duration'       => 'nullable|integer|min:0',
            'next_promotion_date'      => 'nullable|date',
            'promotion_cycle'          => 'nullable|string|max:100',
            'increment_cycle'          => 'nullable|string|max:100',

            // Attendance & Benefits
            'weekends'                 => 'nullable|array',
            'weekends.*'               => 'string',
            'alternate_off_day'        => 'nullable|array',
            'alternate_off_day.*'      => 'string',
            'ot_allowed'               => 'nullable|in:yes,no',
            'pf_eligible'              => 'nullable|in:yes,no',
            'salary_type'              => 'nullable|in:hourly,daily,weekly,monthly,yearly',
            'transport_eligible'       => 'nullable|in:yes,no',

            // Loan & Benefits
            'can_apply_loan'           => 'nullable|in:yes,no',
            'pf_effective_date'        => 'nullable|date',
            'can_apply_advance'        => 'nullable|in:yes,no',
            'gratuity_eligible'        => 'nullable|in:yes,no',
        ], [
            // 🧾 Custom Messages
            'emp_type.in'                     => 'Employment type must be either Permanent or Contractual.',
            'orientation_to.after_or_equal'   => 'Orientation end date must be after or equal to the start date.',
            'orientation_days.min'            => 'Orientation days must be at least 1 day.',
            'probation_duration.min'          => 'Probation duration cannot be negative.',
        ]);

        return $validated;

    }

    public function employeeOfficeInfoSave(Request $request, $validated, $employee_office_info = null)
    {
        if(empty($employee_office_info)){
            $employee_office_data = EmployeeOfficeInfo::create($validated);
        }else{
            $employee_office_data = $employee_office_info->update($validated);

        }
        return $employee_office_data;
    }

    public function employeeEligiblePlanValidation($request){
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

            // Attendance Bonus Plan
            'attendance_bonus_plan_from' => 'nullable|date',
            'attendance_bonus_plan_to' => 'nullable|date|after_or_equal:attendance_bonus_plan_from',
            'attendance_bonus_plan_status' => 'nullable|in:active,inactive',

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

            // Production Plan
            'production_plan_from' => 'nullable|date',
            'production_plan_to' => 'nullable|date|after_or_equal:production_plan_from',
            'production_plan_status' => 'nullable|in:active,inactive',

            // Early Out Deduction Plan
            'early_out_deduction_plan_from' => 'nullable|date',
            'early_out_deduction_plan_to' => 'nullable|date|after_or_equal:early_out_deduction_plan_from',
            'early_out_deduction_plan_status' => 'nullable|in:active,inactive',

            // Salary Breakdown Plan
            'salary_breakdown_plan_from' => 'nullable|date',
            'salary_breakdown_plan_to' => 'nullable|date|after_or_equal:salary_breakdown_plan_from',
            'salary_breakdown_plan_status' => 'nullable|in:active,inactive',

            // Medical Plan
            'medical_plan_from' => 'nullable|date',
            'medical_plan_to' => 'nullable|date|after_or_equal:medical_plan_from',
            'medical_plan_status' => 'nullable|in:active,inactive',

            // Night Bill Plan
            'night_bill_plan_from' => 'nullable|date',
            'night_bill_plan_to' => 'nullable|date|after_or_equal:night_bill_plan_from',
            'night_bill_plan_status' => 'nullable|in:active,inactive',

            // Tiffin Plan
            'tiffin_plan_from' => 'nullable|date',
            'tiffin_plan_to' => 'nullable|date|after_or_equal:tiffin_plan_from',
            'tiffin_plan_status' => 'nullable|in:active,inactive',

            // Dinner Plan
            'dinner_plan_from' => 'nullable|date',
            'dinner_plan_to' => 'nullable|date|after_or_equal:dinner_plan_from',
            'dinner_plan_status' => 'nullable|in:active,inactive',

            // Breakfast Plan
            'breakfast_plan_from' => 'nullable|date',
            'breakfast_plan_to' => 'nullable|date|after_or_equal:breakfast_plan_from',
            'breakfast_plan_status' => 'nullable|in:active,inactive',

            // Food Com Plan
            'food_com_plan_from' => 'nullable|date',
            'food_com_plan_to' => 'nullable|date|after_or_equal:food_com_plan_from',
            'food_com_plan_status' => 'nullable|in:active,inactive',

            // Excessive Late Plan
            'excessive_late_plan_from' => 'nullable|date',
            'excessive_late_plan_to' => 'nullable|date|after_or_equal:excessive_late_plan_from',
            'excessive_late_plan_status' => 'nullable|in:active,inactive',

            // Lunch Plan
            'lunch_plan_from' => 'nullable|date',
            'lunch_plan_to' => 'nullable|date|after_or_equal:lunch_plan_from',
            'lunch_plan_status' => 'nullable|in:active,inactive',

            // Snacks Plan
            'snacks_plan_from' => 'nullable|date',
            'snacks_plan_to' => 'nullable|date|after_or_equal:snacks_plan_from',
            'snacks_plan_status' => 'nullable|in:active,inactive',
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
                'attendance_bonus_plan_from.date' => 'The attendance bonus  plan from date is invalid.',
                'attendance_bonus_plan_to.date' => 'The attendance bonus plan to date is invalid.',
                'attendance_bonus_plan_to.after_or_equal' => 'The attendance bonus plan to date must be after or equal to the from date.',
                'attendance_bonus_plan_status.in' => 'The attendance bonus plan status must be either active or inactive.',
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
                'production_plan_from.date' => 'The production plan from date is invalid.',
                'production_plan_to.date' => 'The production plan to date is invalid.',
                'production_plan_to.after_or_equal' => 'The production plan to date must be after or equal to the from date.',
                'production_plan_status.in' => 'The production plan status must be either active or inactive.',
                'early_out_deduction_plan_from.date' => 'The early out deduction plan from date is invalid.',
                'early_out_deduction_plan_to.date' => 'The early out deduction plan to date is invalid.',
                'early_out_deduction_plan_to.after_or_equal' => 'The early out deduction plan to date must be after or equal to the from date.',
                'early_out_deduction_plan_status.in' => 'The early out deduction plan status must be either active or inactive.',
                'salary_breakdown_plan_from.date' => 'The salary breakdown plan from date is invalid.',
                'salary_breakdown_plan_to.date' => 'The salary breakdown plan to date is invalid.',
                'salary_breakdown_plan_to.after_or_equal' => 'The salary breakdown plan to date must be after or equal to the from date.',
                'salary_breakdown_plan_status.in' => 'The salary breakdown plan status must be either active or inactive.',
            ]);
        return $validated;
    }

    public function employeeEligiblePanInfoSave($validated, $employeePlan = null){

        if(isset($employeePlan)){
            $employeePlan->update($validated);
            return $employeePlan;
        }else{
            $data = EmployeeEligiblePlan::create($validated);
            return $data;
        }
    }

    public function employeeEducationInfoValidation($request){
        $validated = $request->validate([
            'employee_id' => 'required',
            'educations' => 'nullable|array',
            'educations.*.education_title' => 'required_with:educations|string',
            'educations.*.institute' => 'required_with:educations|string',
            'educations.*.group_major' => 'nullable|string',
            'educations.*.board_university' => 'nullable|string',
            'educations.*.result_grade' => 'nullable|string',
            'educations.*.passing_year' => 'required_with:educations|string',
            'educations.*.gpa_cgpa' => 'nullable|string',
            'experiences' => 'nullable|array',
            'experiences.*.company' => 'required_with:experiences|string',
            'experiences.*.designation' => 'required_with:experiences|string',
            'experiences.*.department' => 'nullable|string',
            'experiences.*.date_from' => 'required_with:experiences|date',
            'experiences.*.date_to' => 'required_with:experiences|date',
            'experiences.*.duration' => 'nullable|string',
            'experiences.*.responsibility' => 'required_with:experiences|string',
            'trainings' => 'nullable|array',
            'trainings.*.training_title' => 'required_with:trainings|string',
            'trainings.*.course_name' => 'required_with:trainings|string',
            'trainings.*.training_code' => 'nullable|string',
            'trainings.*.institute' => 'required_with:trainings|string',
            'trainings.*.country' => 'required_with:trainings|string',
            'trainings.*.location' => 'required_with:trainings|string',
            'trainings.*.duration' => 'required_with:trainings|string',
            'trainings.*.from_date' => 'required_with:trainings|date',
            'trainings.*.to_date' => 'required_with:trainings|date',
        ]);
        return $validated;
    }

    public function employeeEducationInfoSave($validated, $employeeEduData = null){
        if(isset($employeeEduData)){
            $employeeEduData->update($validated);
            return $employeeEduData;
        }else{
//            $data = new EmployeeEducationExperienceTraining($validated);
//            dd($data->getAttributes());

            $data = EmployeeEducationExperienceTraining::create($validated);
            return $data;
        }
    }

}
