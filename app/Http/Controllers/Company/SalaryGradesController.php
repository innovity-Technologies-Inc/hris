<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;

use App\Models\Company\SalaryGrade;
use App\Models\Company\Tofsil;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SalaryGradesController extends Controller
{
    public function index(Request $request, FlexSearch $flexsearch)
    {
        $title = 'Salary Grade';
        $section = 'Company Setup';
        $sub_section = 'SalaryGrade';
        $query = SalaryGrade::query()->with(['getTofsil']);
        $searchTerm = $request->get('keyword');
        $searchableFields = ['name', 'getTofsil.name'];
        $salary_grades = $flexsearch->apply($query, [], $searchTerm, $searchableFields)->orderBy('id', 'desc')->paginate(10);
        if ($request->ajax()) {
            return view('company.salary_grade.search_results', compact('salary_grades'))->render();
        }
        return view('company.salary_grade.index', compact('title', 'section', 'sub_section', 'salary_grades'));
    }

    public function create()
    {
        $title = 'Add Salary Grade';
        $section = 'Salary Grade';
        $section_url = route('salary_grades.index');
        $sub_section = 'Add';
        $tofsils = Tofsil::all()->sortBy('name');
        return view('company.salary_grade.form', compact('title', 'section', 'sub_section', 'section_url', 'tofsils'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'tofsil_id' => 'required',
            'status' => 'required',
        ], [
            'name.required' => 'Please Enter Name',
            'name.max' => 'Name Must Be Less Than 255 Characters',
            'name.string' => 'Name Must Be String',
            'tofsil_id.required' => 'Please Select Tofsil Name',
        ]);

        try {
            Log::info('Creating Salary Grade');
            SalaryGrade::create($request->all());

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong',
                'alert-type' => 'error'
            ]);
        }

        Log::info('Salary Grade Created Successfully');;

        return redirect()->route('salary_grades.index')->with([
            'message' => 'Salary Grade Created Successfully',
            'alert-type' => 'success'
        ]);

    }

    public function edit($id)
    {
        $title = 'Edit Salary Grade';
        $section = 'Salary Grade';
        $section_url = route('salary_grades.index');
        $sub_section = 'Edit';
        $salary_grade = SalaryGrade::find($id);
        $tofsils = Tofsil::all()->sortBy('name');
        return view('company.salary_grade.form', compact('title', 'section', 'sub_section', 'section_url', 'salary_grade', 'tofsils'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'tofsil_id' => 'required',
            'status' => 'required',
        ], [
            'name.required' => 'Please Enter Name',
            'name.max' => 'Name Must Be Less Than 255 Characters',
            'name.string' => 'Name Must Be String',
            'tofsil_id.required' => 'Please Select Tofsil Name',
        ]);

        try {
            $salary_grade = SalaryGrade::find($id);

            Log::info('Creating Salary Grade');
            $salary_grade->update($request->all());

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong',
                'alert-type' => 'error'
            ]);
        }

        Log::info('Info Updated Successfully');;

        return redirect()->route('salary_grades.index')->with([
            'message' => 'Info Updated Successfully',
            'alert-type' => 'success'
        ]);

    }

    public function delete($id)
    {
        $salary_grade = SalaryGrade::find($id);
        $salary_grade->delete();
        return redirect()->back()->with([
            'message' => 'Salary Grade Deleted Successfully',
            'alert-type' => 'success'
        ]);
    }
}

