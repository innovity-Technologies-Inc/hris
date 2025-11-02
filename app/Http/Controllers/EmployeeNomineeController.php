<?php

namespace App\Http\Controllers;

use App\Imports\EmployeeNomineeImport;
use App\Imports\EmployeeNomineeInformationImport;
use App\Models\Employee;
use App\Models\EmployeeEligiblePlan;
use App\Models\EmployeeNominee;
use App\Models\EmployeeNomineeInfo;
use App\Services\EmployeeServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeNomineeController extends Controller
{
    protected $empServices;
    public function __construct(EmployeeServices $empServices){
        $this->empServices = $empServices;
    }

    public function create($id)
    {
        $title = 'Add Nominee Information';
        $section = 'Employees Nominee Information';
        $sub_section = 'Add';
        $section_url = route('employees.index');
        $employee = $this->empServices->getEmployeeById($id);
        return view('employees.nominee_information.form', compact('employee', 'title', 'section', 'sub_section', 'section_url'));
    }


    public function store(Request $request){
        $validated = $this->empServices->employeeNomineeInfoValidation($request);
        try{
            $employee = $this->empServices->employeeNomineeInfoSave($request, $validated);
        }catch(\Exception $e){
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            ]);
        }

        /*$employeeEligiblePlan = EmployeeEligiblePlan::where('employee_id', $employee->employee_id)->first();

        if(empty($employeeEligiblePlan)){
            return redirect()->route('employees.eligible_plans.create', $employee->employee_id)->with([
                'message' => 'Nominee Info Added Successfully',
                'alert-type' => 'success'
            ]);
        }
        else{*/
            return redirect()->route('employees.profile.nominee_information', $employee->employee_id)->with([
                    'message' => 'Nominee Info Added Successfully',
                    'alert-type' => 'success'
                ]
            );
//        }

    }

    public function edit($id){
        $title = 'Edit Employee Nominee Information';
        $section = 'Employees';
        $section_url = route('employees.index');
        $sub_section = 'Nominee Edit';
        $employee_nominee_info = EmployeeNominee::where('employee_id', $id)->first();
//        dd($employee_nominee_info);
        if($employee_nominee_info){
            $employee = Employee::select('id', 'full_name')->where('id', $id)->first();
            return view('employees.nominee_information.form', compact('title', 'section',
                'sub_section', 'section_url', 'employee', 'employee_nominee_info'));
        }else{
            return redirect()->route('employees.index')->with([
                'message' => 'Employee Not Found',
                'alert-type' => 'error'
            ]);
        }

    }

    public function update(Request $request, $id){
        $validated = $this->empServices->employeeNomineeInfoValidation($request);
        $employeeNomineeData = EmployeeNominee::findOrFail($id);
        try {
            $employeeNomineeData = $this->empServices->employeeNomineeInfoSave($request, $validated, $employeeNomineeData);
            $employee = $employeeNomineeData->employee_id;
            return redirect()
                ->route('employees.profile.nominee_information', $employee)
                ->with(['message' => 'Employee nominee information updated successfully.',
                    'alert-type' => 'success']);
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with(['message' => 'Something went wrong. Please try again later.',
                    'alert-type' => 'error']);
        }
    }



    public function show($id){
        $title = 'Employee Profile';
        $section = 'Employees';
        $sub_section = 'Profile';
        $section_url = route('employees.index');
        $employee = $this->empServices->getEmployeeById($id);
        $employee_nominee_info = EmployeeNominee::where('employee_id', $id)->first();
//        dd($employee_nominee_info);
        return view('employees.profile', compact('title', 'section', 'sub_section', 'employee', 'employee_nominee_info', 'section_url'));
    }

    public function import(Request $request){
        $request->validate([
            'file' => 'required|mimes:text/csv,text/plain,application/csv,text/comma-separated-values,text/anytext,application/octet-stream,application/txt,xlsx,csv,txt',
        ]);
//    dd($request->all());
        try{
            Excel::import(new EmployeeNomineeImport(), $request->file('file'));
            return redirect()->route('employees.index')->with([
                'message' => 'Employee Nominee Info Imported Successfully',
                'alert-type' => 'success'
            ]);
        }catch (\Exception $e){
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => $e->getMessage(). 'Contact with your administrator',
                'alert-type' => 'error'
            ]);
        }

    }
}
