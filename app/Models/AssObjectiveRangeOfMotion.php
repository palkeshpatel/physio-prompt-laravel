<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssObjectiveRangeOfMotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'assessments_process_id',
        'active_rom',
        'passive_rom',
        'pain_during_arom',
        'pain_location_arom',
        'end_feel',
        'comparison_other_side',
        'additional_info',
        'completion_percentage',
    ];

    protected $casts = [
        'active_rom' => 'array',
        'passive_rom' => 'array',
        'pain_during_arom' => 'boolean',
        'completion_percentage' => 'decimal:2',
    ];

    public function assessmentProcess(): BelongsTo
    {
        return $this->belongsTo(AssessmentProcess::class, 'assessments_process_id');
    }
}



