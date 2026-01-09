<?php

namespace App\Http\Controllers\Api\Physio;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Get dashboard statistics
     */
    public function stats(Request $request)
    {
        try {
            $stats = [
                'admins' => Admin::count(),
                'users' => User::count(),
                'plans' => SubscriptionPlan::count(),
                'subscriptions' => UserSubscription::count(),
                'active_subscriptions' => UserSubscription::where('status', 'active')
                    ->where('end_date', '>=', now())
                    ->count(),
                'expired_subscriptions' => UserSubscription::where('status', 'expired')
                    ->orWhere(function ($query) {
                        $query->where('status', 'active')
                            ->where('end_date', '<', now());
                    })
                    ->count(),
            ];

            return response()->json([
                'success' => true,
                'data' => $stats,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch dashboard statistics',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

