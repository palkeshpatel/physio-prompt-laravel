<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssSubjectiveIceAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'assessments_process_id',
        'ideas',
        'concerns',
        'expectations',
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



