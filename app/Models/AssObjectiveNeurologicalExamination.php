<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssObjectiveNeurologicalExamination extends Model
{
    use HasFactory;

    protected $fillable = [
        'assessments_process_id',
        'sensation',
        'reflexes',
        'myotomes',
        'neural_tension',
        'additional_info',
        'completion_percentage',
    ];

    protected $casts = [
        'sensation' => 'array',
        'reflexes' => 'array',
        'myotomes' => 'array',
        'neural_tension' => 'array',
        'completion_percentage' => 'decimal:2',
    ];

    public function assessmentProcess(): BelongsTo
    {
        return $this->belongsTo(AssessmentProcess::class, 'assessments_process_id');
    }
}



