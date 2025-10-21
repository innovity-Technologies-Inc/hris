<?php

namespace App\Http\Controllers;

use App\Services\EmployeeServices;
use Illuminate\Http\Request;

class EmployeeProfileController extends Controller
{
    protected $empServices;
    public function __construct(EmployeeServices $empServices){
        $this->empServices = $empServices;
    }

    public function index(){
        return view('employee_profile.index');
    }

    public function profileView($id){
        $employee = $this->empServices->getEmployeeById($id);
    }

    public function store(Request $request){
        $data = $request->validate([

        ]);
    }
    public function showGeneralInfoFormShow()
    {
        $title = 'Employee General Information';
        $section = 'Employee Management';
        $sub_section = 'Employee Profile';

        return view('employees.general_informations.form', compact('title', 'section', 'sub_section'));

    }
}
