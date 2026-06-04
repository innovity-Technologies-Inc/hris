<?php

namespace App\Services\Company;

use App\Models\Company\PayGroup;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PayGroupServices
{
    /**
     * Get paginated and filtered pay groups.
     */
    public function getPayGroups(Request $request, FlexSearch $flexsearch)
    {
        $query = PayGroup::query()->with('company');
        $searchTerm = $request->get('keyword');
        $searchableFields = ['title', 'payroll_frequency', 'company.name'];
        
        return $flexsearch->apply($query, [], $searchTerm, $searchableFields)
            ->orderBy('id', 'desc')
            ->paginate(10);
    }

    /**
     * Store a new pay group.
     */
    public function storePayGroup(array $data)
    {
        try {
            return PayGroup::create($data);
        } catch (\Exception $e) {
            Log::error('Error storing PayGroup: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update an existing pay group.
     */
    public function updatePayGroup(PayGroup $payGroup, array $data)
    {
        try {
            $payGroup->update($data);
            return $payGroup;
        } catch (\Exception $e) {
            Log::error('Error updating PayGroup: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete a pay group.
     */
    public function deletePayGroup(PayGroup $payGroup)
    {
        try {
            return $payGroup->delete();
        } catch (\Exception $e) {
            Log::error('Error deleting PayGroup: ' . $e->getMessage());
            throw $e;
        }
    }
}
