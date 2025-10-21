<?php

namespace App\Services;

use App\Models\Employee;

class EmployeeServices
{
    public function getEmployeeById($id){
        $employee = Employee::findorFail($id);
        return $employee;
    }
    public function employeeGeneralInfoStore(){

    }
}
