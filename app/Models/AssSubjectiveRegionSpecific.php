<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssSubjectiveRegionSpecific extends Model
{
    use HasFactory;

    protected $fillable = [
        'assessment_id',
        'region',
        'region_data',
        'additional_info',
        'completion_percentage',
    ];

    protected $casts = [
        'region_data' => 'array',
        'completion_percentage' => 'decimal:2',
    ];

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }
}

