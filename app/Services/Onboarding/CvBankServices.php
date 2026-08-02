<?php

namespace App\Services\Onboarding;

use App\Models\Onboarding\CvBank;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use App\HelperClass;
use Illuminate\Pagination\LengthAwarePaginator;

class CvBankServices
{
    /**
     * Get paginated CV list with filters and keyword search.
     */
    public function getCvsList(array $filters, ?string $keyword, FlexSearch $flexsearch): LengthAwarePaginator
    {
        $query = CvBank::latest();

        // Apply score range filter if set
        if (isset($filters['min_score']) && $filters['min_score'] !== '') {
            $query->where('cv_score', '>=', (int) $filters['min_score']);
        }
        if (isset($filters['max_score']) && $filters['max_score'] !== '') {
            $query->where('cv_score', '<=', (int) $filters['max_score']);
        }

        // Apply career level filter if set
        if (isset($filters['career_level']) && $filters['career_level'] !== '') {
            $query->where('career_level', $filters['career_level']);
        }

        // Apply company and designation filters if set
        if (isset($filters['company_name']) && $filters['company_name'] !== '') {
            $query->where('company_name', $filters['company_name']);
        }
        if (isset($filters['designation']) && $filters['designation'] !== '') {
            $query->where('designation', $filters['designation']);
        }

        $searchableColumns = ['company_name', 'designation', 'applicant_name', 'career_level', 'cv_score'];

        return $flexsearch
            ->apply($query, [], $keyword, $searchableColumns)
            ->paginate(10);
    }

    /**
     * Store multiple CVs.
     */
    public function storeCvs(array $entries): void
    {
        foreach ($entries as $entry) {
            $attachmentPath = null;
            if (isset($entry['attachment']) && $entry['attachment']->isValid()) {
                $attachmentPath = HelperClass::file_upload($entry['attachment'], 'cv_bank');
            }

            CvBank::create([
                'company_name' => $entry['company_name'],
                'designation' => $entry['designation'],
                'applicant_name' => $entry['applicant_name'],
                'career_level' => $entry['career_level'],
                'cv_score' => (int) $entry['cv_score'],
                'attachment_path' => $attachmentPath,
            ]);
        }
    }

    /**
     * Update a single CV entry.
     */
    public function updateCv(int $id, array $data): CvBank
    {
        $cv = CvBank::findOrFail($id);

        $updateData = [
            'company_name' => $data['company_name'],
            'designation' => $data['designation'],
            'applicant_name' => $data['applicant_name'],
            'career_level' => $data['career_level'],
            'cv_score' => (int) $data['cv_score'],
        ];

        if (isset($data['attachment']) && $data['attachment']->isValid()) {
            if ($cv->attachment_path) {
                HelperClass::file_delete($cv->attachment_path);
            }
            $updateData['attachment_path'] = HelperClass::file_upload($data['attachment'], 'cv_bank');
        }

        $cv->update($updateData);

        return $cv;
    }

    /**
     * Delete a CV entry.
     */
    public function deleteCv(int $id): void
    {
        $cv = CvBank::findOrFail($id);

        if ($cv->attachment_path) {
            HelperClass::file_delete($cv->attachment_path);
        }

        $cv->delete();
    }

    /**
     * Get analytics data for Chart.js.
     */
    public function getAnalyticsData(): array
    {
        $total = CvBank::count();
        $avgScore = CvBank::avg('cv_score') ?: 0;

        // Career Level Distribution
        $careerLevels = ['Entry', 'Mid', 'Senior', 'Executive'];
        $careerCounts = [];
        foreach ($careerLevels as $level) {
            $careerCounts[$level] = CvBank::where('career_level', $level)->count();
        }

        // CV Score Range Distribution
        $scoreRanges = [
            '0-50' => CvBank::whereBetween('cv_score', [0, 50])->count(),
            '51-70' => CvBank::whereBetween('cv_score', [51, 70])->count(),
            '71-90' => CvBank::whereBetween('cv_score', [71, 90])->count(),
            '91-100' => CvBank::whereBetween('cv_score', [91, 100])->count(),
        ];

        return [
            'total_cvs' => $total,
            'average_score' => round($avgScore, 1),
            'career_level' => [
                'labels' => array_keys($careerCounts),
                'values' => array_values($careerCounts),
            ],
            'score_ranges' => [
                'labels' => array_keys($scoreRanges),
                'values' => array_values($scoreRanges),
            ],
        ];
    }
}
