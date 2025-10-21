<?php

namespace App\Http\Controllers;

use App\Services\EmployeeServices;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class EmployeeProfileController extends Controller
{
    protected $empServices;
    public function __construct(EmployeeServices $empServices){
        $this->empServices = $empServices;
    }

    public function index(){
        $title = 'Employees';
        $section = 'Employees';
        $sub_section = 'Index';
        $employees = $this->empServices->getEmployees();
        return response()->json($employees);
    }

    public function generalInfoCreate(){
        $title = 'Create Employees';
        $section = 'Employees';
        $sub_section = 'Create';
        return view('employees.general_informations.form', compact('title', 'section', 'sub_section'));;
    }

    public function profileView($id){
        $employee = $this->empServices->getEmployeeById($id);
    }

    public function store(Request $request){
        try{
            $this->empServices->employeeInfoStore($request);
        }catch(\Exception $e){
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong',
                'alert-type' => 'error'
            ]);
        }
        return redirect()->back()->with([
            'message' => 'Info Updated Successfully',
            'alert-type' => 'success'
        ]);
    }

}
