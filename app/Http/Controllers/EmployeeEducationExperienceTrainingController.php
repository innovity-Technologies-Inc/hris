<?php

namespace App\Http\Controllers;

use App\Models\EmployeeEducationExperienceTraining;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeEducationExperienceTrainingController extends Controller
{
    public function create()
    {
        $employees = Employee::all();
        return view('employees.education_experience_trainings.form', compact('employees'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required',
            'educations' => 'nullable|array',
            'educations.*.education_title' => 'required_with:educations|string',
            'educations.*.institute' => 'required_with:educations|string',
            'educations.*.passing_year' => 'required_with:educations|string',
            'experiences' => 'nullable|array',
            'experiences.*.company' => 'required_with:experiences|string',
            'experiences.*.designation' => 'required_with:experiences|string',
            'experiences.*.date_from' => 'required_with:experiences|date',
            'experiences.*.date_to' => 'required_with:experiences|date',
            'trainings' => 'nullable|array',
            'trainings.*.training_title' => 'required_with:trainings|string',
            'trainings.*.from_date' => 'required_with:trainings|date',
            'trainings.*.to_date' => 'required_with:trainings|date',
        ]);

        EmployeeEducationExperienceTraining::updateOrCreate(
            ['employee_id' => $request->employee_id],
            [
                'employee_educations' => $request->educations ?? [],
                'employee_experiences' => $request->experiences ?? [],
                'employee_trainings' => $request->trainings ?? [],
            ]
        );

        return redirect()->back()->with([
            'message' => 'Employee Education, Experience, and Training information saved successfully.',
            'alert-type' => 'success'
        ]);
    }
    public function show($id)
    {
    $employee = Employee::findOrFail($id);

    // Get the JSON record
    $employeeData = EmployeeEducationExperienceTraining::where('employee_id', $id)->first();

    // Extract arrays from JSON columns (will be empty arrays if null)
    $educations = $employeeData->employee_educations ?? [];
    $experiences = $employeeData->employee_experiences ?? [];
    $trainings = $employeeData->employee_trainings ?? [];

    return view('employees.education_experience_trainings.info', compact('employee', 'educations', 'experiences', 'trainings'));
    }
    public function edit($id)
    {
        $employeeData = EmployeeEducationExperienceTraining::where('employee_id', $id)->firstOrFail();
        $employees = Employee::all();

        return view('employees.education_experience_trainings.form', compact('employeeData', 'employees'));
    }
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'employee_id' => 'required',
            'educations' => 'nullable|array',
            'educations.*.education_title' => 'required_with:educations|string',
            'educations.*.institute' => 'required_with:educations|string',
            'educations.*.passing_year' => 'required_with:educations|string',
            'experiences' => 'nullable|array',
            'experiences.*.company' => 'required_with:experiences|string',
            'experiences.*.designation' => 'required_with:experiences|string',
            'experiences.*.date_from' => 'required_with:experiences|date',
            'experiences.*.date_to' => 'required_with:experiences|date',
            'trainings' => 'nullable|array',
            'trainings.*.training_title' => 'required_with:trainings|string',
            'trainings.*.from_date' => 'required_with:trainings|date',
            'trainings.*.to_date' => 'required_with:trainings|date',
        ]);

        EmployeeEducationExperienceTraining::where('employee_id', $id)->update([
            'employee_educations' => $request->educations ?? [],
            'employee_experiences' => $request->experiences ?? [],
            'employee_trainings' => $request->trainings ?? [],
        ]);

        return redirect()->back()->with([
            'message' => 'Employee Education, Experience, and Training information updated successfully.',
            'alert-type' => 'success'
        ]);
    }


}
