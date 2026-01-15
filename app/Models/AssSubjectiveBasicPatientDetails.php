<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssSubjectiveBasicPatientDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'assessments_process_id',
        'full_name',
        'age',
        'gender',
        'height',
        'weight',
        'dominance',
        'occupation',
        'activity_level',
        'additional_info',
        'completion_percentage',
    ];

    protected $casts = [
        'height' => 'decimal:2',
        'weight' => 'decimal:2',
        'completion_percentage' => 'decimal:2',
    ];

    public function assessmentProcess(): BelongsTo
    {
        return $this->belongsTo(AssessmentProcess::class, 'assessments_process_id');
    }
}



