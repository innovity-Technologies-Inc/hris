<?php

namespace App\Http\Controllers;

use App\Imports\EmployeeEducationInfoImport;
use App\Models\EmployeeEducationExperienceTraining;
use App\Services\EmployeeServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeEducationExperienceTrainingController extends Controller
{
    protected $empServices;
    public function __construct(EmployeeServices $empServices){
        $this->empServices = $empServices;
    }

    public function create($id)
    {
        $title = 'Add Employees Information';
        $section = 'Employees Education, Experience, and Training';
        $sub_section = 'Add';
        $section_url = route('employees.index');
        $employee = $this->empServices->getEmployeeById($id);
        return view('employees.education_experience_trainings.form', compact('employee', 'title', 'section', 'sub_section', 'section_url'));
    }
    public function store(Request $request)
    {
        $validated = $this->empServices->employeeEducationInfoValidation($request);
        try{
            $employeeEduData = $this->empServices->employeeEducationInfoSave($validated);
        }catch(\Exception $e){
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong',
                'alert-type' => 'error'
            ]);
        }
        return redirect()->route('employees.profile.education_information', $employeeEduData->employee_id)->with([
            'message' => 'Information saved successfully.',
            'alert-type' => 'success'
        ]);
    }
    public function show($id)
    {
        $title = 'Employees';
        $section = 'Employees';
        $sub_section = 'Employees Education, Experience, and Training';
        $section_url = route('employees.index');
        $employee = $this->empServices->getEmployeeById($id);

        // Get the JSON record
        $employeeData = EmployeeEducationExperienceTraining::where('employee_id', $id)->first();


        // Extract arrays from JSON columns (will be empty arrays if null)
        $educations = $employeeData->educations ?? [];
        $experiences = $employeeData->experiences ?? [];
        $trainings = $employeeData->trainings ?? [];

        return view('employees.profile', compact('employee', 'educations', 'experiences', 'trainings', 'title', 'section', 'sub_section', 'section_url', 'employeeData'));
    }
    public function edit($id)
    {
        $employee = $this->empServices->getEmployeeById($id);
        $title = 'Add Employees Information';
        $section = 'Employees Education, Experience, and Training';
        $sub_section = 'Edit';
        $section_url = route('employees.index');
        $employeeData = EmployeeEducationExperienceTraining::where('employee_id', $id)->firstOrFail();

        return view('employees.education_experience_trainings.form', compact('employeeData', 'employee', 'title', 'section', 'sub_section', 'section_url'));
    }
    public function update(Request $request, $id)
    {
        $validated = $this->empServices->employeeEducationInfoValidation($request);
        $employeeEduData = EmployeeEducationExperienceTraining::where('employee_id', $id)->first();

        try{
            $employeeEduData = $this->empServices->employeeEducationInfoSave($validated, $employeeEduData);
            $employee = $employeeEduData->employee_id;
        }catch(\Exception $e){
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong',
                'alert-type' => 'error'
            ]);
        }
        return redirect()->route('employees.profile.education_information', $employee)->with([
            'message' => 'Updated successfully.',
            'alert-type' => 'success'
        ]);
    }

    public function import(Request $request){
        $request->validate([
            'file' => 'required|mimes:text/csv,text/plain,application/csv,text/comma-separated-values,text/anytext,application/octet-stream,application/txt,xlsx,csv,txt',
        ]);
//    dd($request->all());
        try{
            Excel::import(new EmployeeEducationInfoImport(), $request->file('file'));
            return redirect()->route('employees.index')->with([
                'message' => 'Imported Successfully',
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
