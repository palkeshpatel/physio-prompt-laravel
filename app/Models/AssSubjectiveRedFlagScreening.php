<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssSubjectiveRedFlagScreening extends Model
{
    use HasFactory;

    protected $fillable = [
        'assessments_process_id',
        'red_flags',
        'red_flag_present',
        'red_flag_details',
        'additional_info',
    ];

    protected $casts = [
        'red_flags' => 'array',
        'red_flag_present' => 'boolean',
    ];

    public function assessmentProcess(): BelongsTo
    {
        return $this->belongsTo(AssessmentProcess::class, 'assessments_process_id');
    }
}



