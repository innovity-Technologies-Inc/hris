<?php

namespace App\Services;

use App\Models\EmployeeSalaryBreakdown;
use App\Models\Payroll\Increment;
use App\Models\Payroll\Promotion;

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
        $data['new_basic_salary'] = $increment_result['new_basic'];
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
            $newBasicSalary = $basicSalary + $incrementValue;
            $newGrossSalary = $grossSalary + $incrementValue;
        }else{
            if ($incrementMethod == 'percentage'){
                $incrementValue    =  $grossSalary * ($incrementAmount / 100);
                $newSalary = $grossSalary + $incrementValue;
            }else{
                $incrementValue = $incrementAmount;
                $newSalary = $grossSalary + $incrementValue;
            }
        }

        return [
            'new_basic' => $newSalary,
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
            'employee_salary' => $result['model'],
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

}
