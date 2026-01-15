<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssSubjectiveHistoryPresentCondition extends Model
{
    use HasFactory;

    protected $fillable = [
        'assessments_process_id',
        'duration',
        'progression',
        'previous_episodes',
        'mechanism_injury',
        'initial_treatment',
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



