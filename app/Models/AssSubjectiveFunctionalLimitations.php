<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssSubjectiveFunctionalLimitations extends Model
{
    use HasFactory;

    protected $fillable = [
        'assessments_process_id',
        'daily_activities',
        'work_activities',
        'recreational_activities',
        'sleep_disturbance',
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



