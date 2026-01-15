<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssSubjectivePainCharacteristics extends Model
{
    use HasFactory;

    protected $fillable = [
        'assessments_process_id',
        'pain_location',
        'pain_type',
        'pain_scale',
        'pain_pattern',
        'aggravating_factors',
        'easing_factors',
        'additional_info',
        'completion_percentage',
    ];

    protected $casts = [
        'completion_percentage' => 'decimal:2',
    ];

    public function assessmentProcess(): BelongsTo
    {
        return $this->belongsTo(AssessmentProcess::class, 'assessments_process_id');
    }
}



