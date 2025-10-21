<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmployeeProfileController extends Controller
{
    public function showGeneralInfoFormShow()
    {
        $title = 'Employee General Information';
        $section = 'Employee Management';
        $sub_section = 'Employee Profile';

        return view('employees.general_informations.form', compact('title', 'section', 'sub_section'));

    }
}
