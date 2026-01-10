<?php

namespace App\Http\Controllers\Api\Physio;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use App\Models\AppStatistic;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Get public app settings and statistics (for home page).
     */
    public function getPublic()
    {
        // Force fresh query without cache
        $settings = AppSetting::first();
        if (!$settings) {
            $settings = AppSetting::getInstance();
        } else {
            $settings->refresh();
        }
        
        $statistics = AppStatistic::orderBy('sort_order')->get();

        // Ensure typing_texts is always an array, not null
        $typingTexts = [];
        if ($settings) {
            // Get raw attribute to check if it's JSON string or already decoded
            $rawTypingTexts = $settings->getAttributes()['typing_texts'] ?? null;
            
            if (!is_null($rawTypingTexts)) {
                // If it's a string, try to decode it
                if (is_string($rawTypingTexts)) {
                    try {
                        $decoded = json_decode($rawTypingTexts, true);
                        if (is_array($decoded)) {
                            $typingTexts = $decoded;
                        }
                    } catch (\Exception $e) {
                        $typingTexts = [];
                    }
                } elseif (is_array($settings->typing_texts)) {
                    // Already an array from the cast
                    $typingTexts = $settings->typing_texts;
                }
            }
        }

        // Add cache control headers and ensure JSON encoding
        return response()->json([
            'settings' => [
                'id' => $settings->id ?? null,
                'logo' => $settings->logo ?? null,
                'app_name' => $settings->app_name ?? 'PHYSIOPROMPT',
                'app_details' => $settings->app_details ?? null,
                'typing_texts' => $typingTexts,
            ],
            'statistics' => $statistics,
        ], 200, [
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
            'Content-Type' => 'application/json'
        ]);
    }

    /**
     * Get app settings (admin).
     */
    public function getSettings()
    {
        $settings = AppSetting::getInstance();
        return response()->json($settings);
    }

    /**
     * Update app settings (admin).
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'logo' => 'nullable|string|max:255',
            'app_name' => 'required|string|max:255',
            'app_details' => 'nullable|string',
            'typing_texts' => 'nullable|array',
            'typing_texts.*' => 'string|max:500',
        ]);

        $settings = AppSetting::first(); // Get existing record or create
        if (!$settings) {
            $settings = AppSetting::getInstance();
        }
        
        // Update fields
        if ($request->has('logo')) {
            $settings->logo = $request->logo;
        }
        if ($request->has('app_name')) {
            $settings->app_name = $request->app_name;
        }
        if ($request->has('app_details')) {
            $settings->app_details = $request->app_details;
        }
        if ($request->has('typing_texts')) {
            // Always set typing_texts if provided, even if empty array
            $settings->typing_texts = $request->typing_texts ?? [];
        }
        
        $settings->save();
        $settings->refresh(); // Refresh to get updated data with proper casts

        return response()->json([
            'message' => 'Settings updated successfully',
            'settings' => $settings,
        ]);
    }

    /**
     * Get all statistics (admin).
     */
    public function getStatistics()
    {
        $statistics = AppStatistic::orderBy('sort_order')->get();
        return response()->json($statistics);
    }

    /**
     * Update a statistic (admin).
     */
    public function updateStatistic(Request $request, $id)
    {
        $request->validate([
            'icon' => 'nullable|string|max:100',
            'title' => 'required|string|max:100',
            'count' => 'required|string|max:50',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $statistic = AppStatistic::findOrFail($id);
        $statistic->update($request->only(['icon', 'title', 'count', 'sort_order']));

        return response()->json([
            'message' => 'Statistic updated successfully',
            'statistic' => $statistic->fresh(),
        ]);
    }

    /**
     * Bulk update statistics (admin).
     */
    public function updateStatistics(Request $request)
    {
        $request->validate([
            'statistics' => 'required|array',
            'statistics.*.id' => 'required|exists:app_statistics,id',
            'statistics.*.icon' => 'nullable|string|max:100',
            'statistics.*.title' => 'required|string|max:100',
            'statistics.*.count' => 'required|string|max:50',
            'statistics.*.sort_order' => 'nullable|integer|min:0',
        ]);

        foreach ($request->statistics as $statData) {
            $statistic = AppStatistic::findOrFail($statData['id']);
            $statistic->update([
                'icon' => $statData['icon'] ?? $statistic->icon,
                'title' => $statData['title'],
                'count' => $statData['count'],
                'sort_order' => $statData['sort_order'] ?? $statistic->sort_order,
            ]);
        }

        $statistics = AppStatistic::orderBy('sort_order')->get();

        return response()->json([
            'message' => 'Statistics updated successfully',
            'statistics' => $statistics,
        ]);
    }
}
