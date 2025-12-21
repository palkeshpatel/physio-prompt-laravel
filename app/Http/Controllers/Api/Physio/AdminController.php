<?php

namespace App\Http\Controllers\Api\Physio;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\UserSubscription;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function users(Request $request)
    {
        $users = User::with(['role', 'activeSubscription.subscriptionPlan'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json($users);
    }

    public function activeUsers(Request $request)
    {
        $users = User::whereHas('activeSubscription', function ($query) {
            $query->where('status', 'active')
                ->where('end_date', '>=', now());
        })
        ->with(['role', 'activeSubscription.subscriptionPlan'])
        ->orderBy('created_at', 'desc')
        ->paginate(15);

        return response()->json($users);
    }

    public function createPlan(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'required|string|max:50|unique:subscription_plans,slug',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'discounted_price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'features' => 'nullable|array',
            'free_assessments_limit' => 'required|integer|min:0',
            'unlimited_assessments' => 'boolean',
            'ad_free' => 'boolean',
            'pdf_download' => 'boolean',
            'ai_impression' => 'boolean',
            'ai_rehab_program' => 'boolean',
            'reassessment_enabled' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $plan = SubscriptionPlan::create($request->all());

        return response()->json([
            'message' => 'Subscription plan created successfully',
            'plan' => $plan,
        ], 201);
    }
}

