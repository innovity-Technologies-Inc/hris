<?php

namespace App\Http\Controllers;

use App\Imports\EmployeeGeneralInformationImport;
use App\Imports\EmployeeOfficeInformationImport;
use App\Models\Employee;
use App\Models\EmployeeEligiblePlan;
use App\Models\EmployeeOfficeInfo;
use App\Services\EmployeeServices;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;


class EmployeeProfileController extends Controller
{
    protected $empServices;
    public function __construct(EmployeeServices $empServices){
        $this->empServices = $empServices;
    }

    public function index(Request $request, FlexSearch $flexsearch){
        $title = 'Employees List';
        $section = 'Employees';
        $sub_section = 'Employees / List';

        $employees = $this->empServices->employeeSearchResult($request, $flexsearch);

        if ($request->ajax()) {
            return view('employees.partials.search_results', compact('employees'))->render();
        }
        return view('employees.index', compact('employees', 'title', 'section', 'sub_section'));

    }

    public function generalInfoCreate(){
        $title = 'Add Employee Information';
        $section = 'Employees';
        $sub_section = 'General Information / Create';
        $section_url = route('employees.index');
        return view('employees.general_informations.form', compact('title', 'section', 'sub_section', 'section_url'));
    }



    public function profileView($id){
        $title = 'Employee Profile';
        $section = 'Employees';
        $sub_section = 'Profile';
        $section_url = route('employees.index');
        $employee = $this->empServices->getEmployeeById($id);
        return view('employees.profile', compact('title', 'section', 'sub_section', 'employee', 'section_url'));
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
        return redirect()->route('employees.education_information.create', $employee->id)->with([
            'message' => 'Info Added Successfully',
            'alert-type' => 'success',
        ]);
    }

    public function generalInfoEdit($id){
        $title = 'Edit Employee Information';
        $section = 'Employees';
        $sub_section = 'General Information / Edit';
        $section_url = route('employees.index');
        $employee = $this->empServices->getEmployeeById($id);
        $employee_id = $employee->id;
        return view('employees.general_informations.form', compact('title', 'section', 'sub_section', 'employee', 'employee_id', 'section_url'));
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
            'file' => 'required|mimes:text/csv,text/plain,application/csv,text/comma-separated-values,text/anytext,application/octet-stream,application/txt,xlsx,csv,txt',
        ]);
//    dd($request->all());
        try{
            Excel::import(new EmployeeGeneralInformationImport(), $request->file('file'));
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

    public function officeInfoCreate($id){
        $title = 'Add Employee Office Information';
        $section = 'Employees';
        $sub_section = 'Office Information / Create';
        $section_url = route('employees.index');
        $employee = Employee::select('id', 'full_name')->where('id', $id)->first();
        $companies = $this->empServices->getCompanies();
        $acts = $this->empServices->getActs();
        $designations = $this->empServices->getDesignations();
        return view('employees.office_informations.form', compact('title', 'section',
            'sub_section', 'section_url', 'employee', 'companies', 'acts', 'designations'));
    }

    public function officeInfoStore(Request $request){
        $validated = $this->empServices->employeeOfficeInfoValidation($request);
        try{
            $employee = $this->empServices->employeeOfficeInfoSave($request, $validated);
        }catch(\Exception $e){
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            ]);
        }

        $employeeEligiblePlan = EmployeeEligiblePlan::where('employee_id', $employee->employee_id)->first();

        if(empty($employeeEligiblePlan)){
            return redirect()->route('employees.eligible_plans.create', $employee->employee_id)->with([
                'message' => 'Office Info Added Successfully',
                'alert-type' => 'success'
            ]);
        }
        else{
            return redirect()->route('employees.profile.office_informations', $employee->employee_id)->with([
                    'message' => 'Office Info Added Successfully',
                    'alert-type' => 'success'
                ]
            );
        }

    }

    public function officeInfoEdit($id){
        $title = 'Edit Employee Office Information';
        $section = 'Employees';
        $sub_section = 'Office Information / Edit';
        $section_url = route('employees.index');
        $employee_office_info = EmployeeOfficeInfo::where('employee_id', $id)->first();
        $designations = $this->empServices->getDesignations();
        if($employee_office_info){
            $employee = Employee::select('id', 'full_name')->where('id', $id)->first();
            $companies = $this->empServices->getCompanies();
            $acts = $this->empServices->getActs();
            return view('employees.office_informations.form', compact('title', 'section',
                'sub_section', 'section_url', 'employee', 'companies', 'acts', 'employee_office_info', 'designations'));
        }else{
            return redirect()->route('employees.index')->with([
                'message' => 'Employee Not Found',
                'alert-type' => 'error'
            ]);
        }

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
        return redirect()->route('employees.profile.office_informations', $id)->with([
                'message' => 'Office Info Updated Successfully',
                'alert-type' => 'success'
            ]);
    }

    public function showOfficeInfo($id){
        $title = 'Employee Office Profile';
        $section = 'Employees';
        $sub_section = 'Profile - Office Information';
        $section_url = route('employees.index');
        $employee = $this->empServices->getEmployeeById($id);
        $employee_office_info = EmployeeOfficeInfo::where('employee_id', $id)->first();
//        dd($employee_office_info);
        return view('employees.profile', compact('title', 'section', 'sub_section', 'employee', 'employee_office_info', 'section_url'));
    }

    public function officeInfoImport(Request $request){
        $request->validate([
            'file' => 'required|mimes:text/csv,text/plain,application/csv,text/comma-separated-values,text/anytext,application/octet-stream,application/txt,xlsx,csv,txt',
        ]);
//    dd($request->all());
        try{
            Excel::import(new EmployeeOfficeInformationImport(), $request->file('file'));
            return redirect()->route('employees.index')->with([
                'message' => 'Employee Office Info Imported Successfully',
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

    public function bulkEmployeeImportSections(){
        $title = 'Import Employee Information';
        $section = 'Employees';
        $sub_section = 'Import';
        $section_url = route('employees.index');
        return view('employees.bulk_uploads.form', compact('title', 'section', 'sub_section', 'section_url'));
    }

    /**
     * Toggle employee status between active and inactive
     */
    public function toggleStatus(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'status' => 'required|in:active,inactive'
            ]);

            $employee = $this->empServices->toggleEmployeeStatus($id, $validated['status']);

            return response()->json([
                'success' => true,
                'message' => 'Employee status updated successfully',
                'status' => $employee->status
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error: ' . implode(', ', $e->validator->errors()->all())
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error toggling employee status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update employee status: ' . $e->getMessage()
            ], 500);
        }
    }

}
