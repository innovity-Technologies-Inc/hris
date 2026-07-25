<?php

namespace App\Services\Payroll;

use App\Models\Payroll\TaxChallan;
use App\Models\Employee\Employee;
use App\HelperClass;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Support\Facades\Log;

class TaxChallanServices
{
    /**
     * Search results using FlexSearch and range filters.
     */
    public function searchResult($request, FlexSearch $flexSearch, $paginate = true)
    {
        $query = TaxChallan::with(['employee.officeInfo', 'company']);

        // Date range filters (overlaps logic: starts <= search_to && ends >= search_from)
        if ($request->filled('from')) {
            $query->where('tax_paid_to', '>=', $request->input('from'));
        }

        if ($request->filled('to')) {
            $query->where('tax_paid_from', '<=', $request->input('to'));
        }

        // Keyword filter: search employee name, system ID, applicant ID
        if ($request->filled('keyword')) {
            $keyword = $request->input('keyword');
            $query->whereHas('employee', function ($q) use ($keyword) {
                $q->where('full_name', 'like', "%{$keyword}%")
                  ->orWhere('applicant_id', 'like', "%{$keyword}%")
                  ->orWhere('system_id', 'like', "%{$keyword}%");
            });
        }

        // Company filter
        if ($request->filled('company')) {
            $query->where('company_id', $request->input('company'));
        }

        $query->orderBy('tax_paid_from', 'desc')->orderBy('id', 'desc');

        if ($paginate) {
            return $query->paginate(15);
        }

        return $query->get();
    }

    /**
     * Store a new tax challan.
     */
    public function storeChallan(array $data)
    {
        $filePaths = [];
        if (request()->hasFile('attachments')) {
            foreach (request()->file('attachments') as $file) {
                $filePaths[] = HelperClass::file_upload($file, 'tax_challans');
            }
        }
        $data['attachments'] = $filePaths;



        return TaxChallan::create($data);
    }

    /**
     * Update an existing tax challan.
     */
    public function updateChallan(int $id, array $data)
    {
        $challan = TaxChallan::findOrFail($id);
        $filePaths = $challan->attachments ?? [];

        // Append new files
        if (request()->hasFile('attachments')) {
            foreach (request()->file('attachments') as $file) {
                $filePaths[] = HelperClass::file_upload($file, 'tax_challans');
            }
        }
        
        // Remove specific files if requested
        if (request()->filled('removed_attachments')) {
            $removed = request()->input('removed_attachments');
            if (is_array($removed)) {
                foreach ($removed as $path) {
                    HelperClass::file_delete($path);
                    $filePaths = array_values(array_filter($filePaths, fn($p) => $p !== $path));
                }
            }
        }

        $data['attachments'] = $filePaths;



        $challan->update($data);
        return $challan;
    }

    /**
     * Delete an existing tax challan.
     */
    public function deleteChallan(int $id)
    {
        $challan = TaxChallan::findOrFail($id);
        if (!empty($challan->attachments)) {
            foreach ($challan->attachments as $path) {
                HelperClass::file_delete($path);
            }
        }
        $challan->delete();
    }
}
