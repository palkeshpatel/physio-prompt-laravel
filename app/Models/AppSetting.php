<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    protected $table = 'app_settings';

    protected $fillable = [
        'logo',
        'app_name',
        'app_details',
        'typing_texts',
    ];

    protected $casts = [
        'typing_texts' => 'array',
    ];

    /**
     * Get the single instance of app settings.
     * If no record exists, create one with default values.
     */
    public static function getInstance()
    {
        $settings = self::first();
        if (!$settings) {
            $settings = self::create([
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
        // Always return fresh data from database
        return $settings->fresh();
    }
}
