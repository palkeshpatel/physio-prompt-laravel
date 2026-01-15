<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssObjectivePalpation extends Model
{
    use HasFactory;

    protected $fillable = [
        'assessments_process_id',
        'tenderness',
        'temperature',
        'swelling',
        'tissue_texture',
        'crepitus',
        'additional_info',
        'completion_percentage',
    ];

    protected $casts = [
        'tenderness' => 'array',
        'tissue_texture' => 'array',
        'completion_percentage' => 'decimal:2',
    ];

    public function assessmentProcess(): BelongsTo
    {
        return $this->belongsTo(AssessmentProcess::class, 'assessments_process_id');
    }
}



