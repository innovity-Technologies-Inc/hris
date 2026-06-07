<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\SalaryGradeRequest;
use App\Models\Company\SalaryGrade;
use App\Services\Company\SalaryGradeServices;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\Request;

class SalaryGradesController extends Controller
{
    protected $salaryGradeService;

    public function __construct(SalaryGradeServices $salaryGradeService)
    {
        $this->salaryGradeService = $salaryGradeService;
    }

    public function index(Request $request, FlexSearch $flexsearch)
    {
        $title = 'Salary Grades';
        $section = 'Company Info';
        $sub_section = 'Salary Grades';

        if ($request->ajax()) {
            $salary_grades = $this->salaryGradeService->getSalaryGrades($request, $flexsearch);
            return view('company.salary_grade.search_results', compact('salary_grades'))->render();
        }

        return view('company.salary_grade.index', compact('title', 'section', 'sub_section'));
    }

    public function store(SalaryGradeRequest $request)
    {
        try {
            $salaryGrade = $this->salaryGradeService->storeSalaryGrade($request->validated());
            return response()->json([
                'success' => true,
                'message' => 'Salary Grade saved successfully.',
                'data' => $salaryGrade
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save Salary Grade.'
            ], 500);
        }
    }

    public function edit($id)
    {
        $salaryGrade = SalaryGrade::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $salaryGrade
        ]);
    }

    public function update(SalaryGradeRequest $request, $id)
    {
        try {
            $salaryGrade = SalaryGrade::findOrFail($id);
            $this->salaryGradeService->updateSalaryGrade($salaryGrade, $request->validated());
            return response()->json([
                'success' => true,
                'message' => 'Salary Grade updated successfully.',
                'data' => $salaryGrade
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update Salary Grade.'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $salaryGrade = SalaryGrade::findOrFail($id);
            $this->salaryGradeService->deleteSalaryGrade($salaryGrade);
            return response()->json([
                'success' => true,
                'message' => 'Salary Grade deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete Salary Grade.'
            ], 500);
        }
    }
}
