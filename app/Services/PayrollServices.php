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
            'status'                   => $request->status,
        ];

        $employeeSalary = $this->salaryData($data);

        return [
            'data' => $data,
            'employee_salary' => $employeeSalary,
        ];
    }

    public function salaryData($data){
        $employeeSalary = EmployeeSalaryBreakdown::where($data['employee_id'])->first();
        $data['new_basic_salary'] = $this->incrementCalculation($data, $employeeSalary);
        $data['previous_basic_salary'] = $employeeSalary->basic_salary;
        $data['previous_gross_salary'] = $employeeSalary->gross_salary;
        return $employeeSalary;
    }

    public function incrementCalculation($data, $employeeSalary){
        $incrementBase  = $data['increment_base'];
        $incrementMethod = $data['increment_method'];
        $incrementAmount = $data['salary_increase_amount'];
        if ($incrementBase == 'basic_salary'){
            $basicSalary = $employeeSalary->basic_salary;
            if ($incrementMethod == 'percentage'){
                $incrementValue    =  $basicSalary * ($incrementAmount / 100);
                $newSalary = $basicSalary + $incrementValue;
            }else{
                $newSalary = $basicSalary + $incrementAmount;
            }
        }else{
            $salary = $employeeSalary->gross_salary;
            if ($incrementMethod == 'percentage'){
                $incrementValue    =  $salary * ($incrementAmount / 100);
                $newSalary = $salary + $incrementValue;
            }else{
                $newSalary = $salary + $incrementAmount;
            }
        }
        return $newSalary;
    }
    public function incrementRequestData($request){
        $data = [
            'employee_id'              => $request->employee_id,
            'increment_base'           => $request->increment_base,
            'increment_method'         => $request->increment_method,
            'salary_increase_amount'   => $request->salary_increase_amount,
            'effective_from'           => $request->effective_from,
            'effective_to'             => $request->effective_to,
            'status'                   => $request->status,
        ];

        $this->salaryData($data);

        return $data;
    }

    public function promotionDataStore($data){
        Promotion::create($data);
    }

    public function promotionDataUpdate($id,$data){
        $promotion = Promotion::find($id);
        $promotion->update($data);
    }

    public function incrementDataStore($data){
        Increment::create($data);
    }

    public function incrementDataUpdate($id,$data){
        $increment = Increment::find($id);
        $increment->update($data);
    }

}
