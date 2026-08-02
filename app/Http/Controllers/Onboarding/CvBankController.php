<?php

namespace App\Http\Controllers\Onboarding;

use App\Http\Controllers\Controller;
use App\Http\Requests\Onboarding\StoreCvBankRequest;
use App\Http\Requests\Onboarding\UpdateCvBankRequest;
use App\Services\Onboarding\CvBankServices;
use App\Models\Onboarding\CvBank;
use Illuminate\Http\Request;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use App\Http\Responses\ApiResponse;

class CvBankController extends Controller
{
    protected CvBankServices $cvBankServices;

    public function __construct(CvBankServices $cvBankServices)
    {
        $this->cvBankServices = $cvBankServices;
    }

    /**
     * Display a listing of the CVs.
     */
    public function index(Request $request, FlexSearch $flexsearch)
    {
        $title = 'CV Bank';
        $section = 'Onboarding';
        $sub_section = 'CV Bank';

        $filters = [];
        if ($request->filled('career_level')) {
            $filters['career_level'] = $request->input('career_level');
        }
        if ($request->filled('min_score')) {
            $filters['min_score'] = $request->input('min_score');
        }
        if ($request->filled('max_score')) {
            $filters['max_score'] = $request->input('max_score');
        }

        $keyword = $request->input('keyword');

        $cvs = $this->cvBankServices->getCvsList($filters, $keyword, $flexsearch);

        if ($request->ajax() || $request->boolean('_ajax')) {
            return view('onboarding.cv_bank.partials.search_results', compact('cvs'))->render();
        }

        return view('onboarding.cv_bank.index', compact('title', 'section', 'sub_section', 'cvs'));
    }

    /**
     * Get CV bank analytics data for Chart.js.
     */
    public function analytics()
    {
        $analytics = $this->cvBankServices->getAnalyticsData();

        return ApiResponse::success('Analytics retrieved successfully.', $analytics);
    }

    /**
     * Show the form for creating new CVs.
     */
    public function create()
    {
        $title = 'Add CVs';
        $section = 'Onboarding';
        $sub_section = 'CV Bank';

        return view('onboarding.cv_bank.create', compact('title', 'section', 'sub_section'));
    }

    /**
     * Store newly created CVs in storage.
     */
    public function store(StoreCvBankRequest $request)
    {
        $validated = $request->validated();
        
        $this->cvBankServices->storeCvs($validated['cvs']);

        return ApiResponse::created('CVs created successfully.');
    }

    /**
     * Show the form for editing the specified CV.
     */
    public function edit($id)
    {
        $title = 'Edit CV';
        $section = 'Onboarding';
        $sub_section = 'CV Bank';

        $cv = CvBank::findOrFail($id);

        return view('onboarding.cv_bank.edit', compact('title', 'section', 'sub_section', 'cv'));
    }

    /**
     * Update the specified CV in storage.
     */
    public function update(UpdateCvBankRequest $request, $id)
    {
        $validated = $request->validated();

        $cv = $this->cvBankServices->updateCv((int) $id, $validated);

        return ApiResponse::success('CV updated successfully.', $cv);
    }

    /**
     * Remove the specified CV from storage.
     */
    public function destroy($id)
    {
        $this->cvBankServices->deleteCv((int) $id);

        return ApiResponse::deleted('CV deleted successfully.');
    }
}
