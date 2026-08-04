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

        // If a section id is provided (e.g. from section index page action), try to preload hierarchy from the Section
        $sectionId = $request->get('section_id') ?? $request->get('id');
        if ($sectionId) {
            $sectionRecord = \App\Models\Company\Section::find($sectionId);
            if ($sectionRecord) {
                $request->merge([
                    'company_id' => $request->get('company_id') ?? $sectionRecord->company_id,
                    'location_id' => $request->get('location_id') ?? $sectionRecord->location_id,
                    'branch_id' => $request->get('branch_id') ?? $sectionRecord->location_id,
                    'division_id' => $request->get('division_id') ?? $sectionRecord->division_id,
                    'department_id' => $request->get('department_id') ?? $sectionRecord->department_id,
                    'section_id' => $sectionId,
                ]);
            }
        }

        $masterData = $this->offboardingService->getFormMasterData();

        return view('offboarding.form', array_merge($masterData, compact('title', 'section', 'sub_section', 'section_url', 'type')));
    }

    /**
     * Store Offboarding Record
     */
    public function store(StoreOffboardingRequest $request): JsonResponse
    {
        try {
            $type = $request->input('offboarding_type');
            $permission = $type === 'termination' ? 'terminations.create' : 'resignations.create';
            if (!auth()->user()->can($permission)) {
                return $this->errorResponse('Unauthorized action.', 403);
            }

            $offboarding = $this->offboardingService->storeOffboarding($request->validated(), auth()->user());

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
        $permission = $type === 'termination' ? 'terminations.view' : 'resignations.view';
        if (!auth()->user()->can($permission)) {
            abort(403, 'Unauthorized action.');
        }

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
        $permission = $type === 'termination' ? 'terminations.edit' : 'resignations.edit';
        if (!auth()->user()->can($permission)) {
            abort(403, 'Unauthorized action.');
        }

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
            $offboarding = $this->offboardingService->getOffboardingById((int) $id);
            $type = $offboarding->offboarding_type;
            $permission = $type === 'termination' ? 'terminations.edit' : 'resignations.edit';
            if (!auth()->user()->can($permission)) {
                return $this->errorResponse('Unauthorized action.', 403);
            }

            $offboarding = $this->offboardingService->updateOffboarding((int) $id, $request->validated(), auth()->user());

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
            $offboarding = $this->offboardingService->getOffboardingById((int) $id);
            $type = $offboarding->offboarding_type;
            $permission = $type === 'termination' ? 'terminations.delete' : 'resignations.delete';
            if (!auth()->user()->can($permission)) {
                return $this->errorResponse('Unauthorized action.', 403);
            }

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

    /**
     * Export resignations to Excel.
     */
    public function exportResignationExcel(FlexSearch $flexsearch, Request $request)
    {
        $offboardings = $this->offboardingService->getOffboardingsAll($flexsearch, $request, 'resignation');
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\Offboarding\OffboardingExport($offboardings, 'resignation'), 'resignations.xlsx');
    }

    /**
     * Export terminations to Excel.
     */
    public function exportTerminationExcel(FlexSearch $flexsearch, Request $request)
    {
        $offboardings = $this->offboardingService->getOffboardingsAll($flexsearch, $request, 'termination');
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\Offboarding\OffboardingExport($offboardings, 'termination'), 'terminations.xlsx');
    }

    /**
     * Export resignations to PDF.
     */
    public function exportResignationPdf(FlexSearch $flexsearch, Request $request)
    {
        try {
            $offboardings = $this->offboardingService->getOffboardingsAll($flexsearch, $request, 'resignation');
            $pdfContent = $this->offboardingService->generateOffboardingPdf($offboardings, 'resignation');
            return response($pdfContent, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="resignations.pdf"',
            ]);
        } catch (\Exception $e) {
            Log::error('Resignation PDF generation failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to generate PDF: ' . $e->getMessage());
        }
    }

    /**
     * Export terminations to PDF.
     */
    public function exportTerminationPdf(FlexSearch $flexsearch, Request $request)
    {
        try {
            $offboardings = $this->offboardingService->getOffboardingsAll($flexsearch, $request, 'termination');
            $pdfContent = $this->offboardingService->generateOffboardingPdf($offboardings, 'termination');
            return response($pdfContent, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="terminations.pdf"',
            ]);
        } catch (\Exception $e) {
            Log::error('Termination PDF generation failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to generate PDF: ' . $e->getMessage());
        }
    }
}
