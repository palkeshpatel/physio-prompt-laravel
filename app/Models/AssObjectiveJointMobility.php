<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssObjectiveJointMobility extends Model
{
    use HasFactory;

    protected $fillable = [
        'assessment_id',
        'joint_data',
        'mobility_scores',
        'additional_info',
        'completion_percentage',
    ];

    protected $casts = [
        'joint_data' => 'array',
        'mobility_scores' => 'array',
        'completion_percentage' => 'decimal:2',
    ];

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }
}

