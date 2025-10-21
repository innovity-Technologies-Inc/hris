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

}
