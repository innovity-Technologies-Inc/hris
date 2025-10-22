<?php

namespace App\Http\Controllers;

use App\Services\EmployeeServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmployeeProfileController extends Controller
{
    protected $empServices;
    public function __construct(EmployeeServices $empServices){
        $this->empServices = $empServices;
    }

    public function index(Request $request){
        $title = 'Employees';
        $section = 'Employees';
        $sub_section = 'Index';

        $employees = $this->empServices->employeeSearchResult($request);

        if ($request->ajax()) {
            return view('employees.partials.search_results', compact('employees'))->render();
        }
        return view('employees.index', compact('employees', 'title', 'section', 'sub_section'));

    }

    public function generalInfoCreate(){
        $title = 'Create Employees';
        $section = 'Employees';
        $sub_section = 'Create';
        return view('employees.general_informations.form', compact('title', 'section', 'sub_section'));;
    }

    public function profileView($id){
        $title = 'Employee Profile';
        $section = 'Employees';
        $sub_section = 'Profile';
        $employee = $this->empServices->getEmployeeById($id);
        return view('employees.profile', compact('title', 'section', 'sub_section', 'employee'));
    }

    public function generalInfoStore(Request $request){
        try{
            $this->empServices->employeeInfoStore($request);
        }catch(\Exception $e){
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            ]);
        }
        return redirect()->back()->with([
            'message' => 'Info Updated Successfully',
            'alert-type' => 'success'
        ]);
    }

}
