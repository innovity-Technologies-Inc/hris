<?php

namespace App\Http\Controllers\Resignation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Resignation\StoreResignationRequest;
use App\Http\Requests\Resignation\UpdateResignationRequest;
use App\Services\Resignation\ResignationServices;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ResignationController extends Controller
{
    protected ResignationServices $resignationService;

    public function __construct(ResignationServices $resignationService)
    {
        $this->resignationService = $resignationService;
    }

    public function index(FlexSearch $flexsearch, Request $request)
    {
        $title = 'Resignations';
        $section = 'Employee Management';
        $sub_section = 'Resignations';

        $resignations = $this->resignationService->getResignationsPaginated($flexsearch, $request);

        if ($request->ajax()) {
            return view('resignation.search_results', compact('resignations'))->render();
        }

        return view('resignation.index', compact('title', 'section', 'sub_section', 'resignations'));
    }

    public function create()
    {
        $title = 'Submit Resignation';
        $section = 'Resignations';
        $sub_section = 'Create';
        $section_url = route('resignation.index');

        $masterData = $this->resignationService->getFormMasterData();

        return view('resignation.create', array_merge($masterData, compact('title', 'section', 'sub_section', 'section_url')));
    }

    public function store(StoreResignationRequest $request): JsonResponse
    {
        try {
            $resignation = $this->resignationService->storeResignation($request->validated(), auth()->user());

            return $this->createdResponse('Resignation submitted successfully and sent for approval.', [
                'redirect' => route('resignation.index'),
                'resignation' => $resignation
            ]);
        } catch (\Exception $e) {
            Log::error('Resignation store error: ' . $e->getMessage());
            return $this->errorResponse('Failed to submit resignation: ' . $e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        $title = 'Resignation Details';
        $section = 'Resignations';
        $sub_section = 'Details';
        $section_url = route('resignation.index');

        $resignation = $this->resignationService->getResignationById((int) $id);

        return view('resignation.show', compact('title', 'section', 'sub_section', 'section_url', 'resignation'));
    }

    public function edit($id)
    {
        $title = 'Edit Resignation';
        $section = 'Resignations';
        $sub_section = 'Edit';
        $section_url = route('resignation.index');

        $resignation = $this->resignationService->getResignationById((int) $id);
        $masterData = $this->resignationService->getFormMasterData();

        return view('resignation.edit', array_merge($masterData, compact('title', 'section', 'sub_section', 'section_url', 'resignation')));
    }

    public function update(UpdateResignationRequest $request, $id): JsonResponse
    {
        try {
            $resignation = $this->resignationService->updateResignation((int) $id, $request->validated(), auth()->user());

            return $this->successResponse('Resignation updated successfully.', [
                'redirect' => route('resignation.index'),
                'resignation' => $resignation
            ]);
        } catch (\Exception $e) {
            Log::error('Resignation update error: ' . $e->getMessage());
            return $this->errorResponse('Failed to update resignation: ' . $e->getMessage(), 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $this->resignationService->deleteResignation((int) $id, auth()->user());

            return $this->deletedResponse('Resignation deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Resignation delete error: ' . $e->getMessage());
            return $this->errorResponse('Failed to delete resignation: ' . $e->getMessage(), 500);
        }
    }

    public function getEmployeesByHierarchy(Request $request): JsonResponse
    {
        $employees = $this->resignationService->getEmployeesByHierarchy($request->all());

        return $this->successResponse('Employees retrieved successfully.', $employees);
    }
}
