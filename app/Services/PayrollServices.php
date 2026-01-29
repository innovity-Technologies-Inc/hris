<?php

namespace App\Services;

use App\Models\EmployeeOfficeInfo;
use App\Models\EmployeeSalaryBreakdown;
use App\Models\Payroll\Increment;
use App\Models\Payroll\Promotion;
use Illuminate\Support\Facades\Log;

class PayrollServices
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function promotionRequestData($request){
        $data = [
            'employee_id'              => $request->employee_id,
            'previous_designation'     => $request->previous_designation,
            'new_designation'          => $request->new_designation,
            'increment_base'           => $request->increment_base,
            'increment_method'         => $request->increment_method,
            'salary_increase_amount'   => $request->salary_increase_amount,
            'effective_from'           => $request->effective_from,
            'effective_to'             => $request->effective_to,
        ];

        $result = $this->salaryData($data);

        return [
            'data' => $result['data'],
        ];
    }

    public function salaryData($data){
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

    public function incrementCalculation($data, $employeeSalary){
        $incrementBase  = $data['increment_base'];
        $incrementMethod = $data['increment_method'];
        $incrementAmount = $data['salary_increase_amount'];
        $basicSalary = $employeeSalary->basic_salary;
        $grossSalary = $employeeSalary->gross_salary;
        if ($incrementBase == 'basic_salary'){
            if ($incrementMethod == 'percentage'){
                $incrementValue    =  $basicSalary * ($incrementAmount / 100);
            }else{
                $incrementValue = $incrementAmount;
            }
        }else{
            if ($incrementMethod == 'percentage'){
                $incrementValue    =  $grossSalary * ($incrementAmount / 100);
            }else{
                $incrementValue = $incrementAmount;
            }
        }

        $newGrossSalary = $grossSalary + $incrementValue;

        return [
            'new_gross_salary' => $newGrossSalary,
            'increment_value' => $incrementValue,
        ];
    }
    public function incrementRequestData($request){
        $data = [
            'employee_id'              => $request->employee_id,
            'increment_base'           => $request->increment_base,
            'increment_method'         => $request->increment_method,
            'salary_increase_amount'   => $request->salary_increase_amount,
            'effective_from'           => $request->effective_from,
            'effective_to'             => $request->effective_to,
        ];

        $result = $this->salaryData($data);

        return [
            'data' => $result['data'],
        ];
    }

    public function promotionDataStore($data){
        Promotion::create($data);
    }

    public function promotionDataUpdate($id,$data){
        $promotion = Promotion::find($id);
        $promotion->update($data);
    }

    public function incrementDataStore($data){
//        dd($data);
        Increment::create($data);
    }

    public function incrementDataUpdate($id,$data){
        $increment = Increment::find($id);
        $increment->update($data);
    }

    public function salaryCalculation($data1, $data2){
        return $data1 * ($data2 / 100);
    }

    public function updateSalaryData($data){
        $employee_id = $data->employee_id;
        $salaryData = EmployeeSalaryBreakdown::where('employee_id', $employee_id)->first();
        $newGrossSalary = $data->new_gross_salary;
        $salaryData->update([
            'basic_salary' => $this->salaryCalculation( $newGrossSalary, $salaryData->basic_salary_percentage),
            'house_allowance' => $this->salaryCalculation( $newGrossSalary, $salaryData->house_allowance_percentage),
            'transport_allowance' => $this->salaryCalculation( $newGrossSalary, $salaryData->transport_allowance_percentage),
            'food_allowance' => $this->salaryCalculation($newGrossSalary, $salaryData->food_allowance_percentage),
            'medical_allowance' => $this->salaryCalculation($newGrossSalary, $salaryData->medical_allowance_percentage),
            'other_earnings' => $this->salaryCalculation($newGrossSalary, $salaryData->other_earnings_percentage),
            'gross_salary' => $newGrossSalary
        ]);
    }

    public function designationUpdate($data){
        $employee_id = $data->employee_id;
        $newDesignation  = $data->new_designation;
        Log::info('Employee Designation: ' . $newDesignation);
        Log::info('Employee ID: ' . $employee_id);
        $designation = EmployeeOfficeInfo::where('employee_id', $employee_id)->first();
        $designation->update([
            'current_designation_id' => $newDesignation,
        ]);
    }

}
