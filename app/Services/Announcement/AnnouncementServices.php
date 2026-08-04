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
                $path = \App\HelperClass::file_upload($file, 'announcements', false);
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
                    \App\HelperClass::file_delete($announcement->attachment_path);
                }
                $path = \App\HelperClass::file_upload($file, 'announcements', false);
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
                \App\HelperClass::file_delete($announcement->attachment_path);
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

        if ($announcement->company_id && $announcement->company) {
            $company = $announcement->company;
            $companyName = $company->name;
            $address = $company->address;
            $email = $company->email;
            $phone = method_exists($company, 'telephone') ? $company->telephone : ($company->phone ?? '');
            $logo = $company->logo;
        } else {
            $group = \App\Models\Company\Group::first();
            $companyName = $group?->name ?? $generalSettings?->name ?? 'HRIS Group';
            $address = '';
            $email = '';
            $phone = '';
            $logo = $generalSettings?->logo_light;
        }

        $companyInfo = (object) [
            'name' => $companyName,
            'logo' => $logo,
            'address' => $address,
            'email' => $email,
            'phone' => $phone,
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
