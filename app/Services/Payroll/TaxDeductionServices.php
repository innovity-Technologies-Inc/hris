<?php

namespace App\Services\Payroll;

use App\Models\Payroll\TaxDeductionHistory;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TaxDeductionServices
{
    /**
     * Search results using FlexSearch.
     */
    public function searchResult($request, FlexSearch $flexSearch, $paginate = true)
    {
        $query = TaxDeductionHistory::with(['employee.officeInfo.getCurrentCompany', 'employee.officeInfo.getCurrentBusinessUnit']);

        // Date Range Filters
        if ($request->filled('from')) {
            $query->where('deduction_date', '>=', Carbon::parse($request->input('from'))->startOfDay()->format('Y-m-d'));
        }

        if ($request->filled('to')) {
            $query->where('deduction_date', '<=', Carbon::parse($request->input('to'))->endOfDay()->format('Y-m-d'));
        }

        // Filter by keyword (employee name, applicant_id, system_id)
        if ($request->filled('keyword')) {
            $keyword = $request->input('keyword');
            $query->whereHas('employee', function ($q) use ($keyword) {
                $q->where('full_name', 'like', "%{$keyword}%")
                  ->orWhere('applicant_id', 'like', "%{$keyword}%")
                  ->orWhere('system_id', 'like', "%{$keyword}%");
            });
        }

        // Organizational Search Filters
        if ($request->filled('company')) {
            $query->whereHas('employee.officeInfo', function($q) use ($request) {
                $q->where('current_company_id', $request->input('company'));
            });
        }

        if ($request->filled('business_unit')) {
            $query->whereHas('employee.officeInfo', function($q) use ($request) {
                $q->where('current_business_unit_id', $request->input('business_unit'));
            });
        }

        if ($request->filled('division')) {
            $query->whereHas('employee.officeInfo', function($q) use ($request) {
                $q->where('current_division_id', $request->input('division'));
            });
        }

        if ($request->filled('department')) {
            $query->whereHas('employee.officeInfo', function($q) use ($request) {
                $q->where('current_department_id', $request->input('department'));
            });
        }

        if ($request->filled('section')) {
            $query->whereHas('employee.officeInfo', function($q) use ($request) {
                $q->where('current_section_id', $request->input('section'));
            });
        }

        $query->orderBy('deduction_date', 'desc')->orderBy('id', 'desc');

        if ($paginate) {
            return $query->paginate(15);
        }

        return $query->get();
    }
}
