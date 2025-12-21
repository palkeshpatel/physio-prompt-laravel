<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssObjectiveObservationsGeneralExamination extends Model
{
    use HasFactory;

    protected $fillable = [
        'assessment_id',
        'posture_data',
        'gait_observation',
        'visual_inspection',
        'additional_info',
        'completion_percentage',
    ];

    protected $casts = [
        'posture_data' => 'array',
        'gait_observation' => 'array',
        'completion_percentage' => 'decimal:2',
    ];

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }
}

