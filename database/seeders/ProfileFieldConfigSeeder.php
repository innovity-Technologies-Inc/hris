<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting\ProfileFieldConfig;

class ProfileFieldConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $configs = [
            // 1. General Section
            ['section' => 'general', 'field_name' => 'applicant_id', 'label' => 'Applicant ID', 'is_required' => false],
            ['section' => 'general', 'field_name' => 'system_id', 'label' => 'System ID', 'is_required' => false],
            ['section' => 'general', 'field_name' => 'punch_card_no', 'label' => 'Punch Card No', 'is_required' => false],
            ['section' => 'general', 'field_name' => 'first_name', 'label' => 'First Name', 'is_required' => false],
            ['section' => 'general', 'field_name' => 'last_name', 'label' => 'Last Name', 'is_required' => false],
            ['section' => 'general', 'field_name' => 'middle_name', 'label' => 'Middle Name', 'is_required' => false],
            ['section' => 'general', 'field_name' => 'father_name', 'label' => 'Father Name', 'is_required' => false],
            ['section' => 'general', 'field_name' => 'mother_name', 'label' => 'Mother Name', 'is_required' => false],
            ['section' => 'general', 'field_name' => 'spouse_name', 'label' => 'Spouse Name', 'is_required' => false],
            ['section' => 'general', 'field_name' => 'marital_status', 'label' => 'Marital Status', 'is_required' => false],
            ['section' => 'general', 'field_name' => 'gender', 'label' => 'Gender', 'is_required' => false],
            ['section' => 'general', 'field_name' => 'religion', 'label' => 'Religion', 'is_required' => false],
            ['section' => 'general', 'field_name' => 'nationality', 'label' => 'Nationality', 'is_required' => false],
            ['section' => 'general', 'field_name' => 'blood_group', 'label' => 'Blood Group', 'is_required' => false],
            ['section' => 'general', 'field_name' => 'height_feet', 'label' => 'Height (Feet)', 'is_required' => false],
            ['section' => 'general', 'field_name' => 'height_inches', 'label' => 'Height (Inches)', 'is_required' => false],
            ['section' => 'general', 'field_name' => 'children_count', 'label' => 'Children Count', 'is_required' => false],
            ['section' => 'general', 'field_name' => 'present_address', 'label' => 'Present Address', 'is_required' => false],
            ['section' => 'general', 'field_name' => 'permanent_address', 'label' => 'Permanent Address', 'is_required' => false],
            ['section' => 'general', 'field_name' => 'reference_address', 'label' => 'Reference Address', 'is_required' => false],
            ['section' => 'general', 'field_name' => 'tin', 'label' => 'TIN', 'is_required' => false],
            ['section' => 'general', 'field_name' => 'passport_no', 'label' => 'Passport No', 'is_required' => false],
            ['section' => 'general', 'field_name' => 'passport_expiry', 'label' => 'Passport Expiry', 'is_required' => false],
            ['section' => 'general', 'field_name' => 'license_no', 'label' => 'License No', 'is_required' => false],
            ['section' => 'general', 'field_name' => 'license_expiry', 'label' => 'License Expiry', 'is_required' => false],
            ['section' => 'general', 'field_name' => 'visa_expiry', 'label' => 'Visa Expiry', 'is_required' => false],
            ['section' => 'general', 'field_name' => 'work_expiry', 'label' => 'Work Expiry', 'is_required' => false],
            ['section' => 'general', 'field_name' => 'residency_id_number', 'label' => 'Residency ID Number', 'is_required' => false],
            ['section' => 'general', 'field_name' => 'nid', 'label' => 'NID', 'is_required' => false],
            ['section' => 'general', 'field_name' => 'date_of_birth', 'label' => 'Date of Birth', 'is_required' => false],
            ['section' => 'general', 'field_name' => 'birth_country', 'label' => 'Birth Country', 'is_required' => false],
            ['section' => 'general', 'field_name' => 'birth_reg_no', 'label' => 'Birth Reg No', 'is_required' => false],
            ['section' => 'general', 'field_name' => 'personal_mobile', 'label' => 'Personal Mobile', 'is_required' => false],
            ['section' => 'general', 'field_name' => 'home_phone', 'label' => 'Home Phone', 'is_required' => false],
            ['section' => 'general', 'field_name' => 'work_mobile', 'label' => 'Work Mobile', 'is_required' => false],
            ['section' => 'general', 'field_name' => 'work_phone', 'label' => 'Work Phone', 'is_required' => false],
            ['section' => 'general', 'field_name' => 'work_email', 'label' => 'Work Email', 'is_required' => false],
            ['section' => 'general', 'field_name' => 'personal_email', 'label' => 'Personal Email', 'is_required' => false],
            ['section' => 'general', 'field_name' => 'photo_path', 'label' => 'Photo', 'is_required' => false],
            ['section' => 'general', 'field_name' => 'fingerprint_path', 'label' => 'Fingerprint', 'is_required' => false],
            ['section' => 'general', 'field_name' => 'signature_path', 'label' => 'Signature', 'is_required' => false],
            ['section' => 'general', 'field_name' => 'experience_attachment_path', 'label' => 'Experience Attachment', 'is_required' => false],

            // 2. Education Section
            ['section' => 'education', 'field_name' => 'educations', 'label' => 'Educations', 'is_required' => false],
            ['section' => 'education', 'field_name' => 'trainings', 'label' => 'Trainings', 'is_required' => false],

            // 3. Employment History
            ['section' => 'employment_history', 'field_name' => 'histories', 'label' => 'Employment Histories', 'is_required' => false],

            // 4. Emergency Contact Nominee
            ['section' => 'emergency_contact', 'field_name' => 'nominee_name', 'label' => 'Nominee Name', 'is_required' => false],
            ['section' => 'emergency_contact', 'field_name' => 'relation', 'label' => 'Relation', 'is_required' => false],
            ['section' => 'emergency_contact', 'field_name' => 'father_name', 'label' => 'Father Name', 'is_required' => false],
            ['section' => 'emergency_contact', 'field_name' => 'mother_name', 'label' => 'Mother Name', 'is_required' => false],
            ['section' => 'emergency_contact', 'field_name' => 'spouse_name', 'label' => 'Spouse Name', 'is_required' => false],
            ['section' => 'emergency_contact', 'field_name' => 'gender', 'label' => 'Gender', 'is_required' => false],
            ['section' => 'emergency_contact', 'field_name' => 'date_of_birth', 'label' => 'Date of Birth', 'is_required' => false],
            ['section' => 'emergency_contact', 'field_name' => 'religion', 'label' => 'Religion', 'is_required' => false],
            ['section' => 'emergency_contact', 'field_name' => 'marital_status', 'label' => 'Marital Status', 'is_required' => false],
            ['section' => 'emergency_contact', 'field_name' => 'nationality', 'label' => 'Nationality', 'is_required' => false],
            ['section' => 'emergency_contact', 'field_name' => 'blood_group', 'label' => 'Blood Group', 'is_required' => false],
            ['section' => 'emergency_contact', 'field_name' => 'photo_path', 'label' => 'Photo', 'is_required' => false],
            ['section' => 'emergency_contact', 'field_name' => 'nid', 'label' => 'NID', 'is_required' => false],
            ['section' => 'emergency_contact', 'field_name' => 'birth_reg_no', 'label' => 'Birth Reg No', 'is_required' => false],
            ['section' => 'emergency_contact', 'field_name' => 'bank_account_no', 'label' => 'Bank Account No', 'is_required' => false],
            ['section' => 'emergency_contact', 'field_name' => 'phone', 'label' => 'Phone', 'is_required' => false],
            ['section' => 'emergency_contact', 'field_name' => 'mobile', 'label' => 'Mobile', 'is_required' => false],
            ['section' => 'emergency_contact', 'field_name' => 'present_address_line', 'label' => 'Present Address Line', 'is_required' => false],
            ['section' => 'emergency_contact', 'field_name' => 'village', 'label' => 'Village', 'is_required' => false],
            ['section' => 'emergency_contact', 'field_name' => 'post_office', 'label' => 'Post Office', 'is_required' => false],
            ['section' => 'emergency_contact', 'field_name' => 'thana', 'label' => 'Thana', 'is_required' => false],
            ['section' => 'emergency_contact', 'field_name' => 'district', 'label' => 'District', 'is_required' => false],
            ['section' => 'emergency_contact', 'field_name' => 'state', 'label' => 'State', 'is_required' => false],
            ['section' => 'emergency_contact', 'field_name' => 'zip_code', 'label' => 'Zip Code', 'is_required' => false],
            ['section' => 'emergency_contact', 'field_name' => 'country', 'label' => 'Country', 'is_required' => false],

            // 5. Office Information
            ['section' => 'office-information', 'field_name' => 'emp_type', 'label' => 'Employment Type', 'is_required' => false],
            ['section' => 'office-information', 'field_name' => 'grade_id', 'label' => 'Salary Grade', 'is_required' => false],
            ['section' => 'office-information', 'field_name' => 'hr_file_no', 'label' => 'HR File No', 'is_required' => false],
            ['section' => 'office-information', 'field_name' => 'file_note', 'label' => 'File Note', 'is_required' => false],
            ['section' => 'office-information', 'field_name' => 'joining_company_id', 'label' => 'Joining Company', 'is_required' => false],
            ['section' => 'office-information', 'field_name' => 'joining_business_unit_id', 'label' => 'Joining Business Unit', 'is_required' => false],
            ['section' => 'office-information', 'field_name' => 'joining_division_id', 'label' => 'Joining Division', 'is_required' => false],
            ['section' => 'office-information', 'field_name' => 'joining_department_id', 'label' => 'Joining Department', 'is_required' => false],
            ['section' => 'office-information', 'field_name' => 'joining_section_id', 'label' => 'Joining Section', 'is_required' => false],
            ['section' => 'office-information', 'field_name' => 'joining_designation_id', 'label' => 'Joining Designation', 'is_required' => false],
            ['section' => 'office-information', 'field_name' => 'date_of_join', 'label' => 'Date of Join', 'is_required' => false],
            ['section' => 'office-information', 'field_name' => 'current_company_id', 'label' => 'Current Company', 'is_required' => false],
            ['section' => 'office-information', 'field_name' => 'current_business_unit_id', 'label' => 'Current Business Unit', 'is_required' => false],
            ['section' => 'office-information', 'field_name' => 'current_division_id', 'label' => 'Current Division', 'is_required' => false],
            ['section' => 'office-information', 'field_name' => 'current_department_id', 'label' => 'Current Department', 'is_required' => false],
            ['section' => 'office-information', 'field_name' => 'current_section_id', 'label' => 'Current Section', 'is_required' => false],
            ['section' => 'office-information', 'field_name' => 'current_designation_id', 'label' => 'Current Designation', 'is_required' => false],
            ['section' => 'office-information', 'field_name' => 'orientation_required', 'label' => 'Orientation Required', 'is_required' => false],
            ['section' => 'office-information', 'field_name' => 'orientation_from', 'label' => 'Orientation From', 'is_required' => false],
            ['section' => 'office-information', 'field_name' => 'orientation_to', 'label' => 'Orientation To', 'is_required' => false],
            ['section' => 'office-information', 'field_name' => 'orientation_type', 'label' => 'Orientation Type', 'is_required' => false],
            ['section' => 'office-information', 'field_name' => 'orientation_days', 'label' => 'Orientation Days', 'is_required' => false],
            ['section' => 'office-information', 'field_name' => 'confirmation_date', 'label' => 'Confirmation Date', 'is_required' => false],
            ['section' => 'office-information', 'field_name' => 'probation_duration', 'label' => 'Probation Duration', 'is_required' => false],
            ['section' => 'office-information', 'field_name' => 'next_promotion_date', 'label' => 'Next Promotion Date', 'is_required' => false],
            ['section' => 'office-information', 'field_name' => 'promotion_cycle', 'label' => 'Promotion Cycle', 'is_required' => false],
            ['section' => 'office-information', 'field_name' => 'increment_cycle', 'label' => 'Increment Cycle', 'is_required' => false],
            ['section' => 'office-information', 'field_name' => 'weekends', 'label' => 'Weekends', 'is_required' => false],
            ['section' => 'office-information', 'field_name' => 'alternate_off_day', 'label' => 'Alternate Off Day', 'is_required' => false],
            ['section' => 'office-information', 'field_name' => 'ot_allowed', 'label' => 'OT Allowed', 'is_required' => false],
            ['section' => 'office-information', 'field_name' => 'pf_eligible', 'label' => 'PF Eligible', 'is_required' => false],
            ['section' => 'office-information', 'field_name' => 'salary_type', 'label' => 'Salary Type', 'is_required' => false],
            ['section' => 'office-information', 'field_name' => 'transport_eligible', 'label' => 'Transport Eligible', 'is_required' => false],
            ['section' => 'office-information', 'field_name' => 'can_apply_loan', 'label' => 'Can Apply Loan', 'is_required' => false],
            ['section' => 'office-information', 'field_name' => 'pf_effective_date', 'label' => 'PF Effective Date', 'is_required' => false],
            ['section' => 'office-information', 'field_name' => 'can_apply_advance', 'label' => 'Can Apply Advance', 'is_required' => false],
            ['section' => 'office-information', 'field_name' => 'gratuity_eligible', 'label' => 'Gratuity Eligible', 'is_required' => false],

            // 6. Employee Policy (Eligible Plan)
            ['section' => 'employee-policy', 'field_name' => 'shift_plan', 'label' => 'Shift Plan', 'is_required' => false],
            ['section' => 'employee-policy', 'field_name' => 'leave_plan', 'label' => 'Leave Plan', 'is_required' => false],
            ['section' => 'employee-policy', 'field_name' => 'ot_plan', 'label' => 'OT Plan', 'is_required' => false],
            ['section' => 'employee-policy', 'field_name' => 'day_off_work_plan', 'label' => 'Day Off Work Plan', 'is_required' => false],
            ['section' => 'employee-policy', 'field_name' => 'roster_plans', 'label' => 'Roster Plans', 'is_required' => false],
            ['section' => 'employee-policy', 'field_name' => 'bonus_plan', 'label' => 'Bonus Plan', 'is_required' => false],
            ['section' => 'employee-policy', 'field_name' => 'allowance_plan', 'label' => 'Allowance Plan', 'is_required' => false],
            ['section' => 'employee-policy', 'field_name' => 'late_deduction_plan', 'label' => 'Late Deduction Plan', 'is_required' => false],
            ['section' => 'employee-policy', 'field_name' => 'early_out_deduction_plan', 'label' => 'Early Out Deduction Plan', 'is_required' => false],
            ['section' => 'employee-policy', 'field_name' => 'medical_plan', 'label' => 'Medical Plan', 'is_required' => false],
            ['section' => 'employee-policy', 'field_name' => 'excessive_late_plan', 'label' => 'Excessive Late Plan', 'is_required' => false],
            ['section' => 'employee-policy', 'field_name' => 'meal_plan', 'label' => 'Meal Plan', 'is_required' => false],

            // 7. Salary Breakdown
            ['section' => 'salary-breakdown', 'field_name' => 'pay_scale_id', 'label' => 'Pay Scale', 'is_required' => false],
            ['section' => 'salary-breakdown', 'field_name' => 'basic_salary', 'label' => 'Basic Salary', 'is_required' => false],
            ['section' => 'salary-breakdown', 'field_name' => 'house_allowance', 'label' => 'House Allowance', 'is_required' => false],
            ['section' => 'salary-breakdown', 'field_name' => 'transport_allowance', 'label' => 'Transport Allowance', 'is_required' => false],
            ['section' => 'salary-breakdown', 'field_name' => 'food_allowance', 'label' => 'Food Allowance', 'is_required' => false],
            ['section' => 'salary-breakdown', 'field_name' => 'medical_allowance', 'label' => 'Medical Allowance', 'is_required' => false],
            ['section' => 'salary-breakdown', 'field_name' => 'other_earnings', 'label' => 'Other Earnings', 'is_required' => false],
            ['section' => 'salary-breakdown', 'field_name' => 'basic_salary_percentage', 'label' => 'Basic Salary %', 'is_required' => false],
            ['section' => 'salary-breakdown', 'field_name' => 'house_allowance_percentage', 'label' => 'House Allowance %', 'is_required' => false],
            ['section' => 'salary-breakdown', 'field_name' => 'transport_allowance_percentage', 'label' => 'Transport Allowance %', 'is_required' => false],
            ['section' => 'salary-breakdown', 'field_name' => 'food_allowance_percentage', 'label' => 'Food Allowance %', 'is_required' => false],
            ['section' => 'salary-breakdown', 'field_name' => 'medical_allowance_percentage', 'label' => 'Medical Allowance %', 'is_required' => false],
            ['section' => 'salary-breakdown', 'field_name' => 'other_earnings_percentage', 'label' => 'Other Earnings %', 'is_required' => false],
            ['section' => 'salary-breakdown', 'field_name' => 'gross_salary', 'label' => 'Gross Salary', 'is_required' => false],

            // 8. Bank Account
            ['section' => 'employee-bank-account', 'field_name' => 'bank_id', 'label' => 'Bank', 'is_required' => false],
            ['section' => 'employee-bank-account', 'field_name' => 'branch_id', 'label' => 'Branch', 'is_required' => false],
            ['section' => 'employee-bank-account', 'field_name' => 'account_holder_name', 'label' => 'Account Holder Name', 'is_required' => false],
            ['section' => 'employee-bank-account', 'field_name' => 'account_number', 'label' => 'Account Number', 'is_required' => false],
            ['section' => 'employee-bank-account', 'field_name' => 'status', 'label' => 'Status', 'is_required' => false],
            ['section' => 'employee-bank-account', 'field_name' => 'remarks', 'label' => 'Remarks', 'is_required' => false],
        ];

        foreach ($configs as $config) {
            ProfileFieldConfig::updateOrCreate(
                ['section' => $config['section'], 'field_name' => $config['field_name']],
                ['label' => $config['label'], 'is_required' => $config['is_required']]
            );
        }
    }
}
