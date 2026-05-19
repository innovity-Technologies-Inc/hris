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
        $roles = $this->empServices->getRoles();

        if ($request->ajax()) {
            return view('employees.partials.search_results', compact('employees', 'roles'))->render();
        }
        return view('employees.index', compact('employees', 'title', 'section', 'sub_section', 'roles'));

    }

    public function generalInfoCreate(){
        $title = 'Add Employee Information';
        $section = 'Employees';
        $sub_section = 'General Information / Create';
        $section_url = route('employees.index');
        $roles = $this->empServices->getRoles();
        return view('employees.general_informations.form', compact('title', 'section', 'sub_section', 'section_url', 'roles'));
    }



    public function profileView($id){
        $title = 'Employee Profile';
        $section = 'Employees';
        $sub_section = 'Profile';
        $section_url = route('employees.index');
        $employee = $this->empServices->getEmployeeById($id);

        if (!$employee) {
            abort(404, 'Employee not found');
        }

        // Security check: Owner can view, or user with permission can view
        $isOwner = auth()->user()->employee_id == $id || auth()->user()->id == $employee->user_id;
        $canViewAny = auth()->user()->can('employee-management.view');

        if (!$isOwner && !$canViewAny) {
            abort(403, 'Unauthorized access.');
        }

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

        if (!$employee) {
            abort(404, 'Employee not found');
        }

        // Security check: Owner can edit, or user with permission can edit
        $isOwner = auth()->user()->id == $employee->user_id;
        $canEditAny = auth()->user()->can('employee-management.edit');

        if (!$isOwner && !$canEditAny) {
            abort(403, 'Unauthorized access.');
        }

        $employee_id = $employee->id;
        $roles = $this->empServices->getRoles();
        return view('employees.general_informations.form', compact('title', 'section', 'sub_section', 'employee', 'employee_id', 'section_url', 'roles'));
    }

    public function generalInfoUpdate(Request $request, $id){
        $employee = $this->empServices->getEmployeeById($id);
        if (!$employee) {
            abort(404, 'Employee not found');
        }

        // Security check: Owner can update, or user with permission can update
        $isOwner = auth()->user()->id == $employee->user_id;
        $canEditAny = auth()->user()->can('employee-management.edit');

        if (!$isOwner && !$canEditAny) {
            abort(403, 'Unauthorized access.');
        }

        $validated = $this->empServices->employeeInfoValidation($request);
        try{
            $this->empServices->employeeInfoSave($request,$validated, $id);
            
            // Status transition logic
            if ($employee->status === 'incomplete' || $employee->status === 'pending') {
                if (auth()->user()->user_type === 'Employee') {
                    $employee->update(['status' => 'pending']);
                } else {
                    $employee->update(['status' => 'active']);
                }
            }
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
        $employee = $this->empServices->getEmployeeById($id);

        if (!$employee) {
            abort(404, 'Employee not found');
        }

        // Security check
        if (auth()->user()->user_type === 'Employee' && auth()->user()->employee_id != $id) {
            abort(403, 'Unauthorized access to other profiles.');
        }

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
                'message' => 'Employee Office Information Not Found',
                'alert-type' => 'error'
            ]);
        }

    }

    public function officeInfoUpdate(Request $request, $id){
        $employee = $this->empServices->getEmployeeById($id);
        if (!$employee) {
            abort(404, 'Employee not found');
        }

        // Security check
        if (auth()->user()->user_type === 'Employee' && auth()->user()->employee_id != $id) {
            abort(403, 'Unauthorized access to other profiles.');
        }

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

        if (!$employee) {
            abort(404, 'Employee not found');
        }

        // Security check: Owner can view, or user with permission can view
        $isOwner = auth()->user()->employee_id == $id || auth()->user()->id == $employee->user_id;
        $canViewAny = auth()->user()->can('employee-management.view');

        if (!$isOwner && !$canViewAny) {
            abort(403, 'Unauthorized access.');
        }

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
                'status' => 'required|in:active,inactive,incomplete,pending'
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

    /**
     * Update employee login information
     */
    public function updateLoginInfo(Request $request, $id){
        try{
            $this->empServices->updateLoginInfo($request, $id);
            return redirect()->back()->with([
                'message' => 'Login Info Updated Successfully',
                'alert-type' => 'success'
            ]);
        }catch(\Exception $e){
            Log::error('Error updating login info: ' . $e->getMessage());
            return redirect()->back()->with([
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            ])->withInput();
        }
    }

    public function storeAccount(Request $request)
    {
        try {
            $this->empServices->createEmployeeAccount($request);
            return redirect()->route('employees.index')->with([
                'message' => 'Employee Account Created and Credentials Sent Successfully',
                'alert-type' => 'success'
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            ])->withInput();
        }
    }

}
