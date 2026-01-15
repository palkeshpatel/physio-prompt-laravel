<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssSubjectiveChiefComplaint extends Model
{
    use HasFactory;

    protected $table = 'ass_subjective_chief_complaint';

    protected $fillable = [
        'assessments_process_id',
        'chief_complaint',
        'onset',
        'onset_date',
        'symptoms',
        'additional_info',
        'completion_percentage',
    ];

    protected $casts = [
        'symptoms' => 'array',
        'onset_date' => 'datetime',
        'completion_percentage' => 'decimal:2',
    ];

    public function assessmentProcess(): BelongsTo
    {
        return $this->belongsTo(AssessmentProcess::class, 'assessments_process_id');
    }
}



