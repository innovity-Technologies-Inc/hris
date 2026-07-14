<?php

namespace App\Services\Announcement;

use App\Models\Announcement\Announcement;
use App\Models\Setting\GeneralSetting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Spatie\Browsershot\Browsershot;
use Exception;

class AnnouncementServices
{
    public function getAnnouncements($filters = [], $keyword = null, $flexsearch = null)
    {
        $query = Announcement::with(['company', 'branch', 'division', 'department', 'section']);

        // Scope to visible announcements for the logged-in user
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->user_type !== \App\Enums\UserType::Group) {
                $employee = $user->employee()->with('officeInfo')->first();
                $office = $employee ? $employee->officeInfo : null;

                $userCompanyId = $office?->current_company_id;
                $userBranchId = $office?->current_business_unit_id;
                $userDivisionId = $office?->current_division_id;
                $userDepartmentId = $office?->current_department_id;
                $userSectionId = $office?->current_section_id;

                $query->where(function($q) use ($userCompanyId, $userBranchId, $userDivisionId, $userDepartmentId, $userSectionId) {
                    $q->where(function($sub) use ($userCompanyId) {
                        $sub->whereNull('company_id')->orWhere('company_id', $userCompanyId);
                    })
                    ->where(function($sub) use ($userBranchId) {
                        $sub->whereNull('branch_id')->orWhere('branch_id', $userBranchId);
                    })
                    ->where(function($sub) use ($userDivisionId) {
                        $sub->whereNull('division_id')->orWhere('division_id', $userDivisionId);
                    })
                    ->where(function($sub) use ($userDepartmentId) {
                        $sub->whereNull('department_id')->orWhere('department_id', $userDepartmentId);
                    })
                    ->where(function($sub) use ($userSectionId) {
                        $sub->whereNull('section_id')->orWhere('section_id', $userSectionId);
                    });
                });
            }
        }

        // Apply FlexSearch if provided, otherwise standard pagination
        if ($flexsearch) {
            $searchableColumns = ['title', 'content'];
            return $flexsearch->apply($query, $filters, $keyword, $searchableColumns)
                ->orderBy('id', 'desc')
                ->paginate(15);
        }

        return $query->orderBy('id', 'desc')->paginate(15);
    }

    /**
     * Store a new announcement
     */
    public function storeAnnouncement(array $data, $file = null): Announcement
    {
        try {
            if ($file) {
                $path = $file->store('announcements', 'public');
                $data['attachment_path'] = $path;
            }

            return Announcement::create($data);
        } catch (Exception $e) {
            Log::error("Failed to store announcement: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update an announcement
     */
    public function updateAnnouncement(Announcement $announcement, array $data, $file = null): Announcement
    {
        try {
            if ($file) {
                // Delete old file if exists
                if ($announcement->attachment_path) {
                    Storage::disk('public')->delete($announcement->attachment_path);
                }
                $path = $file->store('announcements', 'public');
                $data['attachment_path'] = $path;
            }

            $announcement->update($data);
            return $announcement;
        } catch (Exception $e) {
            Log::error("Failed to update announcement: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete an announcement
     */
    public function deleteAnnouncement(Announcement $announcement): bool
    {
        try {
            if ($announcement->attachment_path) {
                Storage::disk('public')->delete($announcement->attachment_path);
            }
            return $announcement->delete();
        } catch (Exception $e) {
            Log::error("Failed to delete announcement: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Generate PDF version of the announcement
     */
    public function generatePdf(Announcement $announcement)
    {
        $generalSettings = GeneralSetting::first();
        $company = $announcement->company ?? $generalSettings;

        $companyInfo = (object) [
            'name' => $company?->name ?? $generalSettings?->company_name ?? 'HRMS System',
            'logo' => $company?->logo ?? $generalSettings?->logo_light ?? null,
            'address' => $company?->address ?? ($generalSettings?->address ?? ''),
            'email' => $company?->email ?? ($generalSettings?->email ?? ''),
            'phone' => method_exists($company, 'telephone') ? ($company->telephone ?? $generalSettings?->contact_phone) : ($generalSettings?->contact_phone ?? ''),
        ];

        $html = View::make('announcement.pdf', compact('announcement', 'companyInfo'))->render();

        try {
            return Browsershot::html($html)
                ->setNodeBinary(config('browsershot.node_binary', 'node'))
                ->setNpmBinary(config('browsershot.npm_binary', 'npm'))
                ->setNodeModulePath(config('browsershot.node_modules_path', base_path('node_modules')))
                ->addChromiumArguments(config('browsershot.chrome_arguments', [
                    '--disable-gpu',
                    '--no-sandbox',
                    '--disable-dev-shm-usage',
                ]))
                ->setOption('landscape', false)
                ->paperSize(210, 297) // A4
                ->margins(15, 15, 15, 15)
                ->showBackground()
                ->waitUntilNetworkIdle()
                ->timeout(config('browsershot.timeout', 60))
                ->pdf();
        } catch (Exception $e) {
            Log::error('Error in AnnouncementServices@generatePdf: ' . $e->getMessage(), ['exception' => $e]);
            throw new Exception('PDF generation failed: ' . $e->getMessage());
        }
    }
}
