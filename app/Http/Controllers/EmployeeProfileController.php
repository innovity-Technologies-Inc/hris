<?php

namespace App\Http\Controllers;

use App\Imports\EmployeeGeneralInformationImport;
use App\Services\EmployeeServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;


class EmployeeProfileController extends Controller
{
    protected $empServices;
    public function __construct(EmployeeServices $empServices){
        $this->empServices = $empServices;
    }

    public function index(Request $request, FlexSearch $flexsearch){
        $title = 'Employees';
        $section = 'Employees';
        $sub_section = 'Index';

        $employees = $this->empServices->employeeSearchResult($request, $flexsearch);

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
        try{
            $this->empServices->employeeInfoSave($request);
        }catch(\Exception $e){
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            ]);
        }
        return redirect()->route('employees.index')->with([
            'message' => 'Info Added Successfully',
            'alert-type' => 'success'
        ]);
    }

    public function generalInfoEdit($id){
        $title = 'Edit Employee Information';
        $section = 'Employees';
        $sub_section = 'Edit';
        $employee = $this->empServices->getEmployeeById($id);
        return view('employees.general_informations.form', compact('title', 'section', 'sub_section', 'employee'));
    }

    public function generalInfoUpdate(Request $request, $id){
        try{
            $this->empServices->employeeInfoSave($request, $id);
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

    public function companyInfoCreate(){
        $title = 'Add Employee Information';
        $section = 'Employees';
        $section_url = route('employees.index');
        $sub_section = 'Create';
        return view('employees.office_informations.form', compact('title', 'section', 'sub_section', 'section_url'));;
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

    public function getGradeByAct($act_id){
        $grades = $this->empServices->getGradeByAct($act_id);
        return response()->json($grades);
    }


}
