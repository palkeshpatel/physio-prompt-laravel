<?php

namespace Database\Seeders;

use App\Models\AppSetting;
use Illuminate\Database\Seeder;

class AppSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if settings already exist
        if (!AppSetting::first()) {
            AppSetting::create([
                'logo' => '/images/logo.svg',
                'app_name' => 'PHYSIOPROMPT',
                'app_details' => 'Excellence Through Intelligence',
                'typing_texts' => [
                    'Welcome to PHYSIOPROMPT',
                    'Please check your first 3 assessments free with us',
                    'Start with Free Plan: 3 assessments with ads',
                    'Upgrade to Basic or Premium for unlimited assessments',
                    'Hurry!!! Grab our Special Launch Offer for first 100 subscribers at discounted prices!!',
                    'Experience AI-powered insights and comprehensive reporting'
                ],
            ]);
        }
    }
}
