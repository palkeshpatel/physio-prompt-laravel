<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Assessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function processes(): HasMany
    {
        return $this->hasMany(AssessmentProcess::class, 'assessments_id');
    }

    public function subjectiveProcess(): HasOne
    {
        return $this->hasOne(AssessmentProcess::class, 'assessments_id')
            ->where('assessment_type_id', 1); // Subjective = 1
    }

    public function objectiveProcess(): HasOne
    {
        return $this->hasOne(AssessmentProcess::class, 'assessments_id')
            ->where('assessment_type_id', 2); // Objective = 2
    }

}



