<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssessmentProcessDetail extends Model
{
    use HasFactory;

    protected $table = 'assessments_process_details';

    protected $fillable = [
        'assessments_pocess_id',
        'assessment_table',
    ];

    public function assessmentProcess(): BelongsTo
    {
        return $this->belongsTo(AssessmentProcess::class, 'assessments_pocess_id');
    }
}
