<?php

namespace App\Http\Controllers\Offboarding;

use App\Http\Controllers\Controller;
use App\Http\Requests\Offboarding\StoreOffboardingRequest;
use App\Http\Requests\Offboarding\UpdateOffboardingRequest;
use App\Services\Offboarding\OffboardingServices;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OffboardingController extends Controller
{
    protected OffboardingServices $offboardingService;

    public function __construct(OffboardingServices $offboardingService)
    {
        $this->offboardingService = $offboardingService;
    }

    /**
     * Resignation Index Page
     */
    public function resignationIndex(FlexSearch $flexsearch, Request $request)
    {
        $title = 'Resignations';
        $section = 'Offboarding';
        $sub_section = 'Resignation';
        $type = 'resignation';

        $offboardings = $this->offboardingService->getOffboardingsPaginated($flexsearch, $request, 'resignation');
        $companies = \App\Models\Company\Company::orderBy('name')->get();
        $generalSettings = \App\HelperClass::getGeneralSetting();

        if ($request->ajax()) {
            return view('offboarding.search_results', compact('offboardings', 'type'))->render();
        }

        return view('offboarding.index', compact('title', 'section', 'sub_section', 'type', 'offboardings', 'companies', 'generalSettings'));
    }

    /**
     * Termination Index Page
     */
    public function terminationIndex(FlexSearch $flexsearch, Request $request)
    {
        $title = 'Terminations';
        $section = 'Offboarding';
        $sub_section = 'Termination';
        $type = 'termination';

        $offboardings = $this->offboardingService->getOffboardingsPaginated($flexsearch, $request, 'termination');
        $companies = \App\Models\Company\Company::orderBy('name')->get();
        $generalSettings = \App\HelperClass::getGeneralSetting();

        if ($request->ajax()) {
            return view('offboarding.search_results', compact('offboardings', 'type'))->render();
        }

        return view('offboarding.index', compact('title', 'section', 'sub_section', 'type', 'offboardings', 'companies', 'generalSettings'));
    }

    /**
     * Create Form (Shared for Resignation & Termination)
     */
    public function create(Request $request)
    {
        $type = $request->get('type', 'resignation');
        if (!in_array($type, ['resignation', 'termination'])) {
            $type = 'resignation';
        }

        $typeName = ucfirst($type);
        $title = "Offboarding - {$typeName}";
        $section = 'Offboarding';
        $sub_section = "Create {$typeName}";
        $section_url = route("offboarding.{$type}.index");

        $masterData = $this->offboardingService->getFormMasterData();

        return view('offboarding.form', array_merge($masterData, compact('title', 'section', 'sub_section', 'section_url', 'type')));
    }

    /**
     * Store Offboarding Record
     */
    public function store(StoreOffboardingRequest $request): JsonResponse
    {
        try {
            $offboarding = $this->offboardingService->storeOffboarding($request->validated(), auth()->user());
            $type = $offboarding->offboarding_type;

            return $this->createdResponse('Offboarding record created successfully.', [
                'redirect' => route("offboarding.{$type}.index"),
                'offboarding' => $offboarding
            ]);
        } catch (\Exception $e) {
            Log::error('Offboarding store error: ' . $e->getMessage());
            return $this->errorResponse('Failed to create offboarding record: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Display Offboarding Record Details
     */
    public function show($id)
    {
        $offboarding = $this->offboardingService->getOffboardingById((int) $id);
        $type = $offboarding->offboarding_type;
        $typeName = ucfirst($type);

        $title = "Offboarding Details - {$typeName}";
        $section = 'Offboarding';
        $sub_section = 'Details';
        $section_url = route("offboarding.{$type}.index");

        return view('offboarding.show', compact('title', 'section', 'sub_section', 'section_url', 'offboarding', 'type'));
    }

    /**
     * Edit Form
     */
    public function edit($id)
    {
        $offboarding = $this->offboardingService->getOffboardingById((int) $id);
        $type = $offboarding->offboarding_type;
        $typeName = ucfirst($type);

        $title = "Edit Offboarding - {$typeName}";
        $section = 'Offboarding';
        $sub_section = 'Edit';
        $section_url = route("offboarding.{$type}.index");

        $masterData = $this->offboardingService->getFormMasterData();

        return view('offboarding.form', array_merge($masterData, compact('title', 'section', 'sub_section', 'section_url', 'offboarding', 'type')));
    }

    /**
     * Update Offboarding Record
     */
    public function update(UpdateOffboardingRequest $request, $id): JsonResponse
    {
        try {
            $offboarding = $this->offboardingService->updateOffboarding((int) $id, $request->validated(), auth()->user());
            $type = $offboarding->offboarding_type;

            return $this->successResponse('Offboarding record updated successfully.', [
                'redirect' => route("offboarding.{$type}.index"),
                'offboarding' => $offboarding
            ]);
        } catch (\Exception $e) {
            Log::error('Offboarding update error: ' . $e->getMessage());
            return $this->errorResponse('Failed to update offboarding record: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Delete Offboarding Record
     */
    public function destroy($id): JsonResponse
    {
        try {
            $this->offboardingService->deleteOffboarding((int) $id, auth()->user());

            return $this->deletedResponse('Offboarding record deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Offboarding delete error: ' . $e->getMessage());
            return $this->errorResponse('Failed to delete offboarding record: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Single page portal view for offboarded employees
     */
    public function myOffboarding()
    {
        $user = auth()->user();
        $offboarding = $this->offboardingService->getEmployeeOffboardingDetails($user);

        $title = 'My Offboarding Status';
        $section = 'Portal';

        return view('offboarding.my_offboarding', compact('title', 'section', 'user', 'offboarding'));
    }

    /**
     * Helper route for cascading employee dropdown
     */
    public function getEmployeesByHierarchy(Request $request): JsonResponse
    {
        $employees = $this->offboardingService->getEmployeesByHierarchy($request->all());

        return $this->successResponse('Employees loaded successfully.', $employees);
    }
}
