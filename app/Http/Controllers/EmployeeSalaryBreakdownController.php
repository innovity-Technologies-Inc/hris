<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\EmployeeSalaryBreakdown;

class EmployeeSalaryBreakdownController extends Controller
{
    public function show($id)
    {
        $title = 'Employees';
        $section = 'Employees';
        $sub_section = 'Employees salary breakdown';
        $section_url = route('employees.index');
        $employee = Employee::findOrFail($id);
        $employeeSalaryBreakdown = EmployeeSalaryBreakdown::where('employee_id', $id)->first();
        return view('employees.profile', compact('employeeSalaryBreakdown', 'employee', 'title', 'section', 'sub_section', 'section_url'));
    }

    public function create($id)
    {
        $title = 'Add Employees Information';
        $section = 'Employees Salary Breakdown';
        $sub_section = 'Add';
        $section_url = route('employees.index');
        $employee = Employee::findOrFail($id);
        return view('employees.salary_breakdown.form', compact('employee', 'title', 'section', 'sub_section', 'section_url'));

    }
}
