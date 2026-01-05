<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAssessmentUsage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'month',
        'year',
        'assessments_used',
        'assessment_limit',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}



