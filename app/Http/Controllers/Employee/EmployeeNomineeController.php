<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;

use App\Enums\UserType;
use App\Imports\Employee\EmployeeNomineeImport;
use App\Imports\EmployeeNomineeInformationImport;
use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeEligiblePlan;
use App\Models\Employee\EmployeeNominee;
use App\Models\Employee\EmployeeNomineeInfo;
use App\Models\Employee\EmployeeSalaryBreakdown;
use App\Services\Employee\EmployeeServices;
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
        $section = 'Employees';
        $sub_section = 'Nominee Information / Add';
        $section_url = route('employee.index');
        $employee = $this->empServices->getEmployeeById($id);

        if (!$employee) {
            abort(404, 'Employee not found');
        }

        // Security check: Employees can only view their own profile
        if (auth()->user()->user_type === UserType::Employee && auth()->user()->employee_id != $id) {
            abort(403, 'Unauthorized access to other profiles.');
        }

        return view('employee.nominee_information.form', compact('employee', 'title', 'section', 'sub_section', 'section_url'));
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

        return redirect()->route('employee.profile.nominee_information', $employee->employee_id)->with([
            'message' => 'Nominee Info Added Successfully',
            'alert-type' => 'success'
        ]);
    }

    public function edit($id){
        $title = 'Edit Employee Nominee Information';
        $section = 'Employees';
        $sub_section = 'Nominee Information / Edit';
        $section_url = route('employee.index');
        $employee = $this->empServices->getEmployeeById($id);

        if (!$employee) {
            abort(404, 'Employee not found');
        }

        // Security check: Employees can only view their own profile
        if (auth()->user()->user_type === UserType::Employee && auth()->user()->employee_id != $id) {
            abort(403, 'Unauthorized access to other profiles.');
        }

        $employee_nominee_info = EmployeeNominee::where('employee_id', $id)->first();
//        dd($employee_nominee_info);
        if($employee_nominee_info){
            return view('employee.nominee_information.form', compact('title', 'section',
                'sub_section', 'section_url', 'employee', 'employee_nominee_info'));
        }else{
            return redirect()->route('employee.index')->with([
                'message' => 'Nominee Information Not Found',
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
                ->route('employee.profile.nominee_information', $employee)
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
        $title = 'Employee Nominee Information';
        $section = 'Employees';
        $sub_section = 'Profile - Nominee Information';
        $section_url = route('employee.index');
        $employee = $this->empServices->getEmployeeById($id);

        if (!$employee) {
            abort(404, 'Employee not found');
        }

        // Security check: Employees can only view their own profile
        if (auth()->user()->user_type === UserType::Employee && auth()->user()->employee_id != $id) {
            abort(403, 'Unauthorized access to other profiles.');
        }

        $employee_nominee_info = EmployeeNominee::where('employee_id', $id)->first();
//        dd($employee_nominee_info);
        return view('employee.profile', compact('title', 'section', 'sub_section', 'employee', 'employee_nominee_info', 'section_url'));
    }

    public function import(Request $request){
        $request->validate([
            'file' => 'required|mimes:text/csv,text/plain,application/csv,text/comma-separated-values,text/anytext,application/octet-stream,application/txt,xlsx,csv,txt',
        ]);
//    dd($request->all());
        try{
            Excel::import(new EmployeeNomineeImport(), $request->file('file'));
            return redirect()->route('employee.index')->with([
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

