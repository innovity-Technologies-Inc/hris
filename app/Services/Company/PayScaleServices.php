<?php

namespace App\Services\Company;

use App\Models\Company\PayScale;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PayScaleServices
{
    /**
     * Get paginated and filtered pay scales.
     */
    public function getPayScales(Request $request, FlexSearch $flexsearch)
    {
        $query = PayScale::query()->with(['grade', 'payGroup']);
        $searchTerm = $request->get('keyword');
        $searchableFields = ['title', 'grade.grade_name', 'grade.grade_code', 'payGroup.title'];
        
        return $flexsearch->apply($query, [], $searchTerm, $searchableFields)
            ->orderBy('id', 'desc')
            ->paginate(10);
    }

    /**
     * Store a new pay scale.
     */
    public function storePayScale(array $data)
    {
        try {
            if (empty($data['title'])) {
                $grade = \App\Models\Company\SalaryGrade::find($data['grade_id']);
                $payGroup = \App\Models\Company\PayGroup::find($data['pay_group_id']);
                $data['title'] = ($payGroup?->title ?? 'N/A') . ' - ' . ($grade?->grade_code ?? 'N/A');
            }
            return PayScale::create($data);
        } catch (\Exception $e) {
            Log::error('Error storing PayScale: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update an existing pay scale.
     */
    public function updatePayScale(PayScale $payScale, array $data)
    {
        try {
            if (empty($data['title'])) {
                $grade = \App\Models\Company\SalaryGrade::find($data['grade_id']);
                $payGroup = \App\Models\Company\PayGroup::find($data['pay_group_id']);
                $data['title'] = ($payGroup?->title ?? 'N/A') . ' - ' . ($grade?->grade_code ?? 'N/A');
            }
            $payScale->update($data);
            return $payScale;
        } catch (\Exception $e) {
            Log::error('Error updating PayScale: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete a pay scale.
     */
    public function deletePayScale(PayScale $payScale)
    {
        try {
            return $payScale->delete();
        } catch (\Exception $e) {
            Log::error('Error deleting PayScale: ' . $e->getMessage());
            throw $e;
        }
    }
}
