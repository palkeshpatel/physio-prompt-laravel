<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssSubjectiveYellowFlags extends Model
{
    use HasFactory;

    protected $fillable = [
        'assessments_process_id',
        'yellow_flags',
        'yellow_flag_present',
        'yellow_flag_details',
        'additional_info',
        'completion_percentage',
    ];

    protected $casts = [
        'yellow_flags' => 'array',
        'yellow_flag_present' => 'boolean',
        'completion_percentage' => 'decimal:2',
    ];

    public function assessmentProcess(): BelongsTo
    {
        return $this->belongsTo(AssessmentProcess::class, 'assessments_process_id');
    }
}



