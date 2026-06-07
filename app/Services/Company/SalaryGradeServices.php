<?php

namespace App\Services\Company;

use App\Models\Company\SalaryGrade;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SalaryGradeServices
{
    /**
     * Get paginated and filtered salary grades.
     */
    public function getSalaryGrades(Request $request, FlexSearch $flexsearch)
    {
        $query = SalaryGrade::query();
        $searchTerm = $request->get('keyword');
        $searchableFields = ['grade_code', 'grade_name'];
        
        return $flexsearch->apply($query, [], $searchTerm, $searchableFields)
            ->orderBy('id', 'desc')
            ->paginate(10);
    }

    /**
     * Store a new salary grade.
     */
    public function storeSalaryGrade(array $data)
    {
        try {
            return SalaryGrade::create($data);
        } catch (\Exception $e) {
            Log::error('Error storing SalaryGrade: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update an existing salary grade.
     */
    public function updateSalaryGrade(SalaryGrade $salaryGrade, array $data)
    {
        try {
            $salaryGrade->update($data);
            return $salaryGrade;
        } catch (\Exception $e) {
            Log::error('Error updating SalaryGrade: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete a salary grade.
     */
    public function deleteSalaryGrade(SalaryGrade $salaryGrade)
    {
        try {
            return $salaryGrade->delete();
        } catch (\Exception $e) {
            Log::error('Error deleting SalaryGrade: ' . $e->getMessage());
            throw $e;
        }
    }
}
