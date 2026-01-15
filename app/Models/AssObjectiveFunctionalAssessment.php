<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssObjectiveFunctionalAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'assessments_process_id',
        'functional_data',
        'movement_quality',
        'functional_scores',
        'additional_info',
        'completion_percentage',
    ];

    protected $casts = [
        'functional_data' => 'array',
        'functional_scores' => 'array',
        'completion_percentage' => 'decimal:2',
    ];

    public function assessmentProcess(): BelongsTo
    {
        return $this->belongsTo(AssessmentProcess::class, 'assessments_process_id');
    }
}



