<?php

namespace App\Models\Onboarding;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Userstamps;

class CvBank extends Model
{
    use Userstamps;

    protected $table = 'cv_banks';

    protected $fillable = [
        'company_name',
        'designation',
        'applicant_name',
        'career_level',
        'cv_score',
        'attachment_path',
    ];
}
