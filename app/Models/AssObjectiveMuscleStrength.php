<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssObjectiveMuscleStrength extends Model
{
    use HasFactory;

    protected $fillable = [
        'assessments_process_id',
        'mmt_scores',
        'core_activation',
        'pain_on_resistance',
        'pain_movement',
        'functional_tests',
        'additional_info',
        'completion_percentage',
    ];

    protected $casts = [
        'mmt_scores' => 'array',
        'functional_tests' => 'array',
        'pain_on_resistance' => 'boolean',
        'completion_percentage' => 'decimal:2',
    ];

    public function assessmentProcess(): BelongsTo
    {
        return $this->belongsTo(AssessmentProcess::class, 'assessments_process_id');
    }
}



