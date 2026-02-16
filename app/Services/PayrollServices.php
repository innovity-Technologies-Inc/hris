<?php

namespace App\Services;

use App\Models\BonusPlan;
use App\Models\Employee;
use App\Models\EmployeeOfficeInfo;
use App\Models\EmployeeSalaryBreakdown;
use App\Models\Payroll\Bonus;
use App\Models\Payroll\Increment;
use App\Models\Payroll\PayrollProcess;
use App\Models\Payroll\Promotion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PayrollServices
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function promotionRequestData($request)
    {
        $data = [
            'employee_id' => $request->employee_id,
            'previous_designation' => $request->previous_designation,
            'new_designation' => $request->new_designation,
            'increment_base' => $request->increment_base,
            'increment_method' => $request->increment_method,
            'salary_increase_amount' => $request->salary_increase_amount,
            'effective_from' => $request->effective_from,
            'effective_to' => $request->effective_to,
        ];

        $result = $this->salaryData($data);

        return [
            'data' => $result['data'],
        ];
    }

    public function salaryData($data)
    {
        $employeeSalary = EmployeeSalaryBreakdown::where('employee_id', $data['employee_id'])->first();
        $increment_result = $this->incrementCalculation($data, $employeeSalary);
        $data['new_gross_salary'] = $increment_result['new_gross_salary'];
        $data['increment_amount_value'] = $increment_result['increment_value'];
        $data['previous_basic_salary'] = $employeeSalary->basic_salary;
        $data['previous_gross_salary'] = $employeeSalary->gross_salary;
        return [
            'data' => $data,
        ];
    }

    public function incrementCalculation($data, $employeeSalary)
    {
        $incrementBase = $data['increment_base'];
        $incrementMethod = $data['increment_method'];
        $incrementAmount = $data['salary_increase_amount'];
        $basicSalary = $employeeSalary->basic_salary;
        $grossSalary = $employeeSalary->gross_salary;
        if ($incrementBase == 'basic_salary') {
            if ($incrementMethod == 'percentage') {
                $incrementValue = $basicSalary * ($incrementAmount / 100);
            } else {
                $incrementValue = $incrementAmount;
            }
        } else {
            if ($incrementMethod == 'percentage') {
                $incrementValue = $grossSalary * ($incrementAmount / 100);
            } else {
                $incrementValue = $incrementAmount;
            }
        }

        $newGrossSalary = $grossSalary + $incrementValue;

        return [
            'new_gross_salary' => $newGrossSalary,
            'increment_value' => $incrementValue,
        ];
    }

    public function incrementRequestData($request)
    {
        $data = [
            'employee_id' => $request->employee_id,
            'increment_base' => $request->increment_base,
            'increment_method' => $request->increment_method,
            'salary_increase_amount' => $request->salary_increase_amount,
            'effective_from' => $request->effective_from,
            'effective_to' => $request->effective_to,
        ];

        $result = $this->salaryData($data);

        return [
            'data' => $result['data'],
        ];
    }

    public function promotionDataStore($data)
    {
        Promotion::create($data);
    }

    public function promotionDataUpdate($id, $data)
    {
        $promotion = Promotion::find($id);
        $promotion->update($data);
    }

    public function incrementDataStore($data)
    {
//        dd($data);
        Increment::create($data);
    }

    public function incrementDataUpdate($id, $data)
    {
        $increment = Increment::find($id);
        $increment->update($data);
    }

    public function salaryCalculation($data1, $data2)
    {
        return $data1 * ($data2 / 100);
    }

    public function updateSalaryData($data)
    {
        $employee_id = $data->employee_id;
        $salaryData = EmployeeSalaryBreakdown::where('employee_id', $employee_id)->first();
        $newGrossSalary = $data->new_gross_salary;
        $salaryData->update([
            'basic_salary' => $this->salaryCalculation($newGrossSalary, $salaryData->basic_salary_percentage),
            'house_allowance' => $this->salaryCalculation($newGrossSalary, $salaryData->house_allowance_percentage),
            'transport_allowance' => $this->salaryCalculation($newGrossSalary, $salaryData->transport_allowance_percentage),
            'food_allowance' => $this->salaryCalculation($newGrossSalary, $salaryData->food_allowance_percentage),
            'medical_allowance' => $this->salaryCalculation($newGrossSalary, $salaryData->medical_allowance_percentage),
            'other_earnings' => $this->salaryCalculation($newGrossSalary, $salaryData->other_earnings_percentage),
            'gross_salary' => $newGrossSalary
        ]);
    }

    public function designationUpdate($data)
    {
        $employee_id = $data->employee_id;
        $newDesignation = $data->new_designation;
        Log::info('Employee Designation: ' . $newDesignation);
        Log::info('Employee ID: ' . $employee_id);
        $designation = EmployeeOfficeInfo::where('employee_id', $employee_id)->first();
        $designation->update([
            'current_designation_id' => $newDesignation,
        ]);
    }

    public function bonusDataValidation()
    {
        $validated = request()->validate([
            // Validate the arrays themselves
            'employee_id' => 'nullable',
            'plan_ids' => 'required|array|min:1',

            // Validate each ID inside the arrays
            'plan_ids.*' => 'required|integer|exists:bonus_plans,id',

            'company_id' => 'nullable|exists:companies,id',
            'branch_id' => 'nullable|exists:company_locations,id',
            'division_id' => 'nullable|exists:divisions,id',
            'department_id' => 'nullable|exists:departments,id',
            'section_id' => 'nullable|exists:sections,id',

            'salary_month' => 'required',
        ], [
            'plan_ids.required' => 'Plan is required.',
            'plan_ids.*.required' => 'Plan is required.',
            'salary_month.required' => 'Salary Month is required.',
        ]);
        return $validated;
    }

    public function bonusCalculation($employee, $plan_ids)
    {
        $bonus_amount = 0;
        foreach ($plan_ids as $id) {
            $plan_data = BonusPlan::find($id);
            if ($plan_data) {
                if ($plan_data->bonus_config_type == 'Salary Based') {
                    $basic_salary = EmployeeSalaryBreakdown::where('employee_id', $employee->id)->first()->basic_salary;
                    Log::info('Basic Salary of employee_id ' . $employee->id . ': ' . $basic_salary);
                    if ($plan_data->salary_rate_type == 'Basic Rate') {
                        $bonus_amount += $basic_salary;
                    } else {
                        Log::info('Multiplier Value for this employee_id ' . $employee->id . ': ' . $plan_data->multiplier);
                        $bonus_amount += $basic_salary * $plan_data->multiplier;
                        Log::info('Bonus Amount of Plan ' . $plan_data->name . ': ' . $bonus_amount);
                    }
                } else {
                    $bonus_amount += $plan_data->custom_rate;
                }
            }
        }
        Log::info('Bonus Amount of the Employee ' . $bonus_amount);
        return $bonus_amount;
    }

    public function findEmployees($data)
    {
        $salary_month = $data['salary_month'];
        $firstDayOfSalaryMonth = Carbon::parse($salary_month)->copy()->startOfMonth();

        $query = Employee::query()
            ->select('id', 'full_name')
            ->whereHas('salary')
            ->whereHas('officeInfo', function ($q) use ($data) {
                $q->where('current_company_id', $data['company_id']);

                if (!empty($data['branch_id'])) {
                    $q->where('current_business_unit_id', $data['branch_id']);
                }
                if (!empty($data['division_id'])) {
                    $q->where('current_division_id', $data['division_id']);
                }
                if (!empty($data['department_id'])) {
                    $q->where('current_department_id', $data['department_id']);
                }
                if (!empty($data['section_id'])) {
                    $q->where('current_section_id', $data['section_id']);
                }
                if (!empty($data['employee_id'])) {
                    $q->where('id', $data['employee_id']);
                }
            })
        ->whereHas('employeeEligibility', function ($q) use ($firstDayOfSalaryMonth) {
            $q->where('bonus_plan_status', 'active')
                ->whereDate('bonus_plan_from', '<=', $firstDayOfSalaryMonth);
        });

        $employees = $query->get();

        Log::info('Employees Found:', ['count' => $employees->count()]);

        return $employees;
    }

    public function bonusProcess($data, $processId = null)
    {

        $employees = $this->findEmployees($data);
        $total_employees = count($employees);
        if ($total_employees == 0) {
            throw new \Exception('Eligible Employees not found.');
        }
        $total_bonus = 0;
        $employeeData = [];
        foreach ($employees as $employee) {
            $bonus_amount = $this->bonusCalculation($employee, $data['plan_ids']);
            $total_bonus += $bonus_amount;
            $employeeData[] = [
                'employee_id' => $employee->id,
                'bonus_amount' => $bonus_amount,
            ];
        }
        Log::info('Total Bonus Amount ' . $total_bonus);
        $data['amount'] = $total_bonus;
//        dd($data);
        DB::transaction(function () use ($data, $employeeData, $total_bonus, $total_employees, $processId) {
            if($processId == null) {
                Log::info('ProcessId ' . $processId);
                Log::info('Payroll Process Creating');
                $process = PayrollProcess::create([
                    'batch_id' => uniqid('Bonus_', true),
                    'company_id' => $data['company_id'],
                    'branch_id' => $data['branch_id'],
                    'division_id' => $data['division_id'],
                    'department_id' => $data['department_id'],
                    'section_id' => $data['section_id'],
                    'salary_month' => $data['salary_month'],
                    'type' => 'bonus',
                    'total_amount' => $total_bonus,
                    'generated_by' => Auth::id() ?? null,
                    'total_employee' => $total_employees,
                    'bonus_plan_ids' => $data['plan_ids'],
                ]);
                Log::info('Payroll Process Created: ' . $process->id);

            }
            else{
                Log::info('ProcessId ' . $processId);
                $process = PayrollProcess::find($processId);
                Log::info('Payroll Process Updating: ' . $process->id);
                $process->update([
                    'company_id' => $data['company_id'],
                    'branch_id' => $data['branch_id'],
                    'division_id' => $data['division_id'],
                    'department_id' => $data['department_id'],
                    'section_id' => $data['section_id'],
                    'salary_month' => $data['salary_month'],
                    'type' => 'bonus',
                    'total_amount' => $total_bonus,
                    'generated_by' => Auth::id() ?? null,
                    'total_employee' => $total_employees,
                    'bonus_plan_ids' => $data['plan_ids'],
                ]);
            }

            foreach ($employeeData as $employee) {
                Log::info('Bonus Process Created: ' . $employee['employee_id']);
                $bonus = Bonus::create([
                    'process_id' => $process->id,
                    'batch_id' => $process->batch_id,
                    'employee_id' => $employee['employee_id'],
                    'amount' => $employee['bonus_amount'],
                ]);
            }
            Log::info('Bonus Created: ' . $bonus->id);

        });
    }

    public function bonusDelete($id){
        Log::info('Deleting Old Bonus Data');
        $bonuses = Bonus::where('process_id', $id)->get();
        foreach ($bonuses as $bonus) {
            $bonus->delete();
        }
    }
    public function processDelete($id){
        Log::info('Deleting Old Process Data');
        $process = PayrollProcess::find($id);
        $process->delete();
    }

    public function searchResult(Request $request, $modelName, $flexsearch)
    {
        $query = $modelName::with('getEmployee');

        $filters = [];

        if ($request->filled('effective_from_start')) {
            $filters['effective_from>='] = ($request->input('effective_from_start'));
        }

        if ($request->filled('effective_from_end')) {
            $filters['effective_from<='] = ($request->input('effective_from_end'));
        }

        if ($request->filled('status')) {
            $filters['status'] = ($request->input('status'));
        }

        $searchTerm = $request->get('keyword');

        $searchableFields = ['getEmployee.applicant_id', 'getEmployee.full_name', 'getEmployee.system_id'];

        $data = $flexsearch->apply($query,
            $filters,
            $searchTerm,
            $searchableFields)->orderBy('id', 'desc')->paginate(20);

        return $data;
    }

    public function payrollProcessSearchResult(Request $request, $modelName, $flexsearch)
    {
        $query = $modelName::with('generatedBy');

        $filters = [];

        if ($request->filled('from_start')) {
            $filters['created_at>='] = ($request->input('from_start'));
        }

        if ($request->filled('from_end')) {
            $filters['created_at<='] = ($request->input('from_end'));
        }

        if ($request->filled('status')) {
            $filters['approval_status'] = ($request->input('status'));
        }

        if ($request->filled('salary_month')) {
            $filters['salary_month'] = ($request->input('salary_month'));
        }

        $searchTerm = $request->get('keyword');

        $searchableFields = ['generatedBy.name', 'batch_id'];

        $data = $flexsearch->apply($query,
            $filters,
            $searchTerm,
            $searchableFields);
        return $data;
    }

}
