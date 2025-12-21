<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssessmentType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'total_sections',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function assessments(): HasMany
    {
        return $this->hasMany(Assessment::class);
    }
}

