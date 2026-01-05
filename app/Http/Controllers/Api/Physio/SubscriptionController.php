<?php

namespace App\Http\Controllers\Api\Physio;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    public function plans()
    {
        $plans = SubscriptionPlan::where('is_active', true)
            ->orderBy('price', 'asc')
            ->get();

        return response()->json($plans);
    }

    public function current(Request $request)
    {
        $subscription = $request->user()->activeSubscription;

        if (!$subscription) {
            return response()->json([
                'message' => 'No active subscription found',
                'subscription' => null,
            ]);
        }

        return response()->json([
            'subscription' => $subscription->load('subscriptionPlan'),
        ]);
    }

    public function subscribe(Request $request)
    {
        $request->validate([
            'subscription_plan_id' => 'required|exists:subscription_plans,id',
            'payment_id' => 'nullable|string|max:255',
            'amount_paid' => 'nullable|numeric',
        ]);

        $plan = SubscriptionPlan::findOrFail($request->subscription_plan_id);

        if (!$plan->is_active) {
            return response()->json([
                'message' => 'This subscription plan is not available',
            ], 400);
        }

        // Cancel existing active subscription
        UserSubscription::where('user_id', $request->user()->id)
            ->where('status', 'active')
            ->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
            ]);

        // Create new subscription
        $subscription = UserSubscription::create([
            'user_id' => $request->user()->id,
            'subscription_plan_id' => $plan->id,
            'start_date' => now(),
            'end_date' => now()->addMonth(),
            'status' => 'active',
            'assessment_of_month' => $plan->unlimited_assessments ? 999999 : $plan->free_assessments_limit,
            'payment_id' => $request->payment_id,
            'amount_paid' => $request->amount_paid ?? $plan->price,
        ]);

        return response()->json([
            'message' => 'Subscription activated successfully',
            'subscription' => $subscription->load('subscriptionPlan'),
        ], 201);
    }

    public function usage(Request $request)
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $usage = DB::table('user_assessment_usage')
            ->where('user_id', $request->user()->id)
            ->where('month', $currentMonth)
            ->where('year', $currentYear)
            ->first();

        $activeSubscription = $request->user()->activeSubscription;

        return response()->json([
            'usage' => $usage,
            'subscription' => $activeSubscription ? $activeSubscription->load('subscriptionPlan') : null,
            'limit' => $activeSubscription ? $activeSubscription->assessment_of_month : 0,
            'used' => $usage ? $usage->assessments_used : 0,
            'remaining' => $activeSubscription && $usage 
                ? max(0, $activeSubscription->assessment_of_month - $usage->assessments_used)
                : 0,
        ]);
    }
}



