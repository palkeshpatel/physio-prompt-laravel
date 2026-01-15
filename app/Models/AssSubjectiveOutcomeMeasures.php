<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssSubjectiveOutcomeMeasures extends Model
{
    use HasFactory;

    protected $fillable = [
        'assessments_process_id',
        'outcome_measures',
        'scores',
        'additional_info',
        'completion_percentage',
    ];

    protected $casts = [
        'outcome_measures' => 'array',
        'scores' => 'array',
        'completion_percentage' => 'decimal:2',
    ];

    public function assessmentProcess(): BelongsTo
    {
        return $this->belongsTo(AssessmentProcess::class, 'assessments_process_id');
    }
}



