<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssObjectiveSpecialTests extends Model
{
    use HasFactory;

    protected $fillable = [
        'assessment_id',
        'cervical_tests',
        'lumbar_tests',
        'shoulder_tests',
        'other_tests',
        'additional_info',
        'completion_percentage',
    ];

    protected $casts = [
        'cervical_tests' => 'array',
        'lumbar_tests' => 'array',
        'shoulder_tests' => 'array',
        'other_tests' => 'array',
        'completion_percentage' => 'decimal:2',
    ];

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }
}

