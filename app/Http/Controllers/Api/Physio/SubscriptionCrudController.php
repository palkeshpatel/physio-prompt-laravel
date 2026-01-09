<?php

namespace App\Http\Controllers\Api\Physio;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubscriptionCrudController extends Controller
{
    /**
     * Display a listing of subscription plans.
     */
    public function indexPlans(Request $request)
    {
        $query = SubscriptionPlan::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        // Filter by active status
        if ($request->has('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $plans = $query->orderBy('price', 'asc')->paginate(15);

        return response()->json($plans);
    }

    /**
     * Store a newly created subscription plan.
     */
    public function storePlan(Request $request)
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

    /**
     * Display the specified subscription plan.
     */
    public function showPlan($id)
    {
        $plan = SubscriptionPlan::with('subscriptions.user')->findOrFail($id);

        return response()->json($plan);
    }

    /**
     * Update the specified subscription plan.
     */
    public function updatePlan(Request $request, $id)
    {
        $plan = SubscriptionPlan::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|required|string|max:100',
            'slug' => ['sometimes', 'required', 'string', 'max:50', Rule::unique('subscription_plans')->ignore($plan->id)],
            'price' => 'sometimes|required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'discounted_price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'features' => 'nullable|array',
            'free_assessments_limit' => 'sometimes|required|integer|min:0',
            'unlimited_assessments' => 'boolean',
            'ad_free' => 'boolean',
            'pdf_download' => 'boolean',
            'ai_impression' => 'boolean',
            'ai_rehab_program' => 'boolean',
            'reassessment_enabled' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $plan->update($request->all());

        return response()->json([
            'message' => 'Subscription plan updated successfully',
            'plan' => $plan->fresh(),
        ]);
    }

    /**
     * Remove the specified subscription plan.
     */
    public function destroyPlan($id)
    {
        $plan = SubscriptionPlan::findOrFail($id);
        $plan->delete();

        return response()->json([
            'message' => 'Subscription plan deleted successfully',
        ]);
    }

    /**
     * Display a listing of user subscriptions.
     */
    public function indexSubscriptions(Request $request)
    {
        $query = UserSubscription::with(['user', 'subscriptionPlan']);

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by user
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $subscriptions = $query->orderBy('created_at', 'desc')->paginate(15);

        return response()->json($subscriptions);
    }

    /**
     * Store a newly created user subscription.
     */
    public function storeSubscription(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'subscription_plan_id' => 'required|exists:subscription_plans,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:active,cancelled,expired',
            'assessment_of_month' => 'required|integer|min:0',
            'payment_id' => 'nullable|string|max:255',
            'amount_paid' => 'nullable|numeric|min:0',
        ]);

        // Cancel existing active subscription for this user
        if ($request->status === 'active') {
            UserSubscription::where('user_id', $request->user_id)
                ->where('status', 'active')
                ->update([
                    'status' => 'cancelled',
                    'cancelled_at' => now(),
                ]);
        }

        $subscription = UserSubscription::create($request->all());

        return response()->json([
            'message' => 'User subscription created successfully',
            'subscription' => $subscription->load(['user', 'subscriptionPlan']),
        ], 201);
    }

    /**
     * Display the specified user subscription.
     */
    public function showSubscription($id)
    {
        $subscription = UserSubscription::with(['user', 'subscriptionPlan'])->findOrFail($id);

        return response()->json($subscription);
    }

    /**
     * Update the specified user subscription.
     */
    public function updateSubscription(Request $request, $id)
    {
        $subscription = UserSubscription::findOrFail($id);

        $request->validate([
            'user_id' => 'sometimes|required|exists:users,id',
            'subscription_plan_id' => 'sometimes|required|exists:subscription_plans,id',
            'start_date' => 'sometimes|required|date',
            'end_date' => 'sometimes|required|date|after:start_date',
            'status' => 'sometimes|required|in:active,cancelled,expired',
            'assessment_of_month' => 'sometimes|required|integer|min:0',
            'payment_id' => 'nullable|string|max:255',
            'amount_paid' => 'nullable|numeric|min:0',
        ]);

        // If activating, cancel other active subscriptions for this user
        if ($request->has('status') && $request->status === 'active' && $subscription->status !== 'active') {
            UserSubscription::where('user_id', $subscription->user_id)
                ->where('id', '!=', $subscription->id)
                ->where('status', 'active')
                ->update([
                    'status' => 'cancelled',
                    'cancelled_at' => now(),
                ]);
        }

        $subscription->update($request->all());

        return response()->json([
            'message' => 'User subscription updated successfully',
            'subscription' => $subscription->fresh()->load(['user', 'subscriptionPlan']),
        ]);
    }

    /**
     * Remove the specified user subscription.
     */
    public function destroySubscription($id)
    {
        $subscription = UserSubscription::findOrFail($id);
        $subscription->delete();

        return response()->json([
            'message' => 'User subscription deleted successfully',
        ]);
    }
}

