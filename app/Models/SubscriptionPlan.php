<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'price',
        'original_price',
        'discounted_price',
        'description',
        'features',
        'free_assessments_limit',
        'unlimited_assessments',
        'ad_free',
        'pdf_download',
        'ai_impression',
        'ai_rehab_program',
        'reassessment_enabled',
        'is_active',
    ];

    protected $casts = [
        'features' => 'array',
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'discounted_price' => 'decimal:2',
        'unlimited_assessments' => 'boolean',
        'ad_free' => 'boolean',
        'pdf_download' => 'boolean',
        'ai_impression' => 'boolean',
        'ai_rehab_program' => 'boolean',
        'reassessment_enabled' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function subscriptions(): HasMany
    {
        return $this->hasMany(UserSubscription::class);
    }
}



