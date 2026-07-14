<?php

namespace App\Http\Controllers\Announcement;

use App\Http\Controllers\Controller;
use App\Http\Requests\Announcement\AnnouncementRequest;
use App\Models\Announcement\Announcement;
use App\Models\Company\Company;
use App\Models\Company\CompanyLocation;
use App\Models\Company\Department;
use App\Services\Announcement\AnnouncementServices;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\Request;
use Exception;

class AnnouncementController extends Controller
{
    protected $announcementService;

    public function __construct(AnnouncementServices $announcementService)
    {
        $this->announcementService = $announcementService;
    }

    /**
     * Display a listing of announcements
     */
    public function index(Request $request, FlexSearch $flexsearch)
    {
        $keyword = $request->input('keyword');
        
        $filters = [];
        if ($request->filled('company_id')) {
            $filters['company_id'] = $request->input('company_id');
        }
        if ($request->filled('branch_id')) {
            $filters['branch_id'] = $request->input('branch_id');
        }
        if ($request->filled('department_id')) {
            $filters['department_id'] = $request->input('department_id');
        }

        $announcements = $this->announcementService->getAnnouncements($filters, $keyword, $flexsearch);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $announcements->items(),
                'total' => $announcements->total()
            ]);
        }

        $companies = Company::orderBy('name')->get();
        $branches = CompanyLocation::orderBy('name')->get();
        $departments = Department::orderBy('department_name')->get();

        return view('announcement.index', compact('announcements', 'companies', 'branches', 'departments'));
    }

    /**
     * Show the form for creating a new announcement
     */
    public function create()
    {
        $companies = Company::orderBy('name')->get();
        $branches = CompanyLocation::orderBy('name')->get();
        $departments = Department::orderBy('department_name')->get();

        return view('announcement.create', compact('companies', 'branches', 'departments'));
    }

    /**
     * Store a newly created announcement
     */
    public function store(AnnouncementRequest $request)
    {
        try {
            $data = $request->validated();
            $file = $request->file('attachment');
            
            // Remove attachment from data before passing to service
            unset($data['attachment']);

            $announcement = $this->announcementService->storeAnnouncement($data, $file);

            return response()->json([
                'success' => true,
                'message' => 'Announcement created successfully.',
                'redirect' => route('announcements.index')
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create announcement: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified announcement details
     */
    public function show($id)
    {
        $announcement = Announcement::with(['company', 'branch', 'department'])->findOrFail($id);
        return view('announcement.show', compact('announcement'));
    }

    /**
     * Show the form for editing the specified announcement
     */
    public function edit($id)
    {
        $announcement = Announcement::findOrFail($id);
        $companies = Company::orderBy('name')->get();
        $branches = CompanyLocation::orderBy('name')->get();
        $departments = Department::orderBy('department_name')->get();

        return view('announcement.edit', compact('announcement', 'companies', 'branches', 'departments'));
    }

    /**
     * Update the specified announcement
     */
    public function update(AnnouncementRequest $request, $id)
    {
        try {
            $announcement = Announcement::findOrFail($id);
            $data = $request->validated();
            $file = $request->file('attachment');

            unset($data['attachment']);

            $this->announcementService->updateAnnouncement($announcement, $data, $file);

            return response()->json([
                'success' => true,
                'message' => 'Announcement updated successfully.',
                'redirect' => route('announcements.index')
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update announcement: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified announcement
     */
    public function destroy($id)
    {
        try {
            $announcement = Announcement::findOrFail($id);
            $this->announcementService->deleteAnnouncement($announcement);

            return response()->json([
                'success' => true,
                'message' => 'Announcement deleted successfully.'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete announcement: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download the announcement as PDF
     */
    public function downloadPdf($id)
    {
        try {
            $announcement = Announcement::findOrFail($id);
            $pdfContent = $this->announcementService->generatePdf($announcement);

            return response($pdfContent)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="announcement-' . $announcement->id . '.pdf"');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to generate PDF: ' . $e->getMessage());
        }
    }
}
