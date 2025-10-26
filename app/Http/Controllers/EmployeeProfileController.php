<?php

namespace App\Http\Controllers;

use App\Imports\EmployeeGeneralInformationImport;
use App\Models\Employee;
use App\Models\EmployeeOfficeInfo;
use App\Services\EmployeeServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

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
        $title = 'Add Employee Information';
        $section = 'Employees';
        $section_url = route('employees.index');
        $sub_section = 'Create';
        return view('employees.general_informations.form', compact('title', 'section', 'sub_section', 'section_url'));
    }



    public function profileView($id){
        $title = 'Employee Profile';
        $section = 'Employees';
        $sub_section = 'Profile';
        $employee = $this->empServices->getEmployeeById($id);
        return view('employees.profile', compact('title', 'section', 'sub_section', 'employee'));
    }

    public function generalInfoStore(Request $request){
        $validated = $this->empServices->employeeInfoValidation($request);
        try{
            $employee = $this->empServices->employeeInfoSave($request, $validated);
        }catch(\Exception $e){
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            ]);
        }
        return redirect()->route('employees.office_informations.create', $employee->id)->with([
            'message' => 'Info Added Successfully',
            'alert-type' => 'success',
        ]);
    }

    public function generalInfoEdit($id){
        $title = 'Edit Employee Information';
        $section = 'Employees';
        $sub_section = 'Edit';
        $employee = $this->empServices->getEmployeeById($id);
        $employee_id = $employee->id;
        return view('employees.general_informations.form', compact('title', 'section', 'sub_section', 'employee', 'employee_id'));
    }

    public function generalInfoUpdate(Request $request, $id){
        $validated = $this->empServices->employeeInfoValidation($request);
        try{
            $this->empServices->employeeInfoSave($request,$validated, $id);
        }catch(\Exception $e){
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            ]);
        }
        return redirect()->route('employees.index')->with([
            'message' => 'Info Updated Successfully',
            'alert-type' => 'success'
        ]);
    }

    public function generalInfoImport(Request $request){
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,text/plain,text/csv'
        ]);
//    dd($request->all());
        try{
            Excel::import(new EmployeeGeneralInformationImport, $request->file('file'));
            return redirect()->route('employees.index')->with([
                'message' => 'Employee Info Imported Successfully',
                'alert-type' => 'success'
            ]);
        }catch (\Exception $e){
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong',
                'alert-type' => 'error'
            ]);
        }

    }

    public function officeInfoCreate(){
        $title = 'Add Employee Information';
        $section = 'Employees';
        $section_url = route('employees.index');
        $sub_section = 'Create';
        //remove employee who already have office info
        $employees = Employee::whereNotIn('id', function ($query) {
            $query->select('employee_id')->from('employee_office_infos');
        })->get();
        $companies = $this->empServices->getCompanies();
        $acts = $this->empServices->getActs();
        return view('employees.office_informations.form', compact('title', 'section',
            'sub_section', 'section_url', 'employees', 'companies', 'acts'));
    }

    public function officeInfoStore(Request $request){
        $validated = $this->empServices->employeeOfficeInfoValidation($request);
        try{
            $this->empServices->employeeOfficeInfoSave($request, $validated);
        }catch(\Exception $e){
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            ]);
        }
        return redirect()->back()->with([
            'message' => 'Office Info Added Successfully',
                'alert-type' => 'success'
            ]
        );
    }

    public function officeInfoEdit($id){
        $title = 'Edit Employee Information';
        $section = 'Employees';
        $section_url = route('employees.index');
        $sub_section = 'Edit';
        $employees = $this->empServices->getEmployees();
        $companies = $this->empServices->getCompanies();
        $acts = $this->empServices->getActs();
        $employee_id = $id;
        $employee_office_info = EmployeeOfficeInfo::where('employee_id', $employee_id)->first();
        return view('employees.office_informations.form', compact('title', 'section',
            'sub_section', 'section_url', 'employees', 'companies', 'acts', 'employee_office_info', 'employee_id'));
    }

    public function officeInfoUpdate(Request $request, $id){
        $validated = $this->empServices->employeeOfficeInfoValidation($request);
        $employee_office_info = EmployeeOfficeInfo::where('employee_id', $id)->first();

        try{
            $this->empServices->employeeOfficeInfoSave($request, $validated, $employee_office_info);
        }catch(\Exception $e){
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            ]);
        }
        return redirect()->back()->with([
                'message' => 'Office Info Updated Successfully',
                'alert-type' => 'success'
            ]
        );
    }



    public function getUnitByCompany($company_id){
        $units = $this->empServices->getUnitByCompany($company_id);
        return response()->json($units);

    }

    public function getDivisionByUnit($location_id){
        $divisions = $this->empServices->getDivisionByUnit($location_id);
        return response()->json($divisions);
    }

    public function getDepartmentByDivision($division_id){
        $departments = $this->empServices->getDepartmentByDivision($division_id);
        return response()->json($departments);
    }

    public function getSectionByDepartment($department_id){
        $sections = $this->empServices->getSectionByDepartment($department_id);
        return response()->json($sections);
    }

    public function getGradeByAct($tofsil_id){
        $grades = $this->empServices->getGradeByAct($tofsil_id);
        return response()->json($grades);
    }

    public function getDesignationsByDivision($division_id){
        $designations= $this->empServices->getDesignationsByDivision($division_id);
        return response()->json($designations);
    }

}
