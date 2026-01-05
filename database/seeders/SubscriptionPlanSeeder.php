<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Free Plan',
                'slug' => 'free',
                'price' => 0.00,
                'original_price' => null,
                'discounted_price' => null,
                'description' => 'Basic free plan with limited assessments',
                'features' => [
                    '3 assessments per month',
                    'Basic features',
                ],
                'free_assessments_limit' => 3,
                'unlimited_assessments' => false,
                'ad_free' => false,
                'pdf_download' => false,
                'ai_impression' => false,
                'ai_rehab_program' => false,
                'reassessment_enabled' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Basic Plan',
                'slug' => 'basic',
                'price' => 999.00,
                'original_price' => 1299.00,
                'discounted_price' => 999.00,
                'description' => 'Basic plan with 100 assessments per month',
                'features' => [
                    '100 assessments per month',
                    'PDF download',
                    'AI impression',
                ],
                'free_assessments_limit' => 100,
                'unlimited_assessments' => false,
                'ad_free' => true,
                'pdf_download' => true,
                'ai_impression' => true,
                'ai_rehab_program' => false,
                'reassessment_enabled' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Premium Plan',
                'slug' => 'premium',
                'price' => 1299.00,
                'original_price' => 1599.00,
                'discounted_price' => 1299.00,
                'description' => 'Premium plan with unlimited assessments and all features',
                'features' => [
                    'Unlimited assessments',
                    'PDF download',
                    'AI impression',
                    'AI rehab program',
                    'Ad-free experience',
                    'Reassessment enabled',
                ],
                'free_assessments_limit' => 0,
                'unlimited_assessments' => true,
                'ad_free' => true,
                'pdf_download' => true,
                'ai_impression' => true,
                'ai_rehab_program' => true,
                'reassessment_enabled' => true,
                'is_active' => true,
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::create($plan);
        }
    }
}



