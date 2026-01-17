<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public routes (no auth required)
Route::get('/settings', [App\Http\Controllers\Api\Physio\SettingsController::class, 'getPublic']);

// Physio Routes (User authentication)
Route::prefix('physio')->group(function () {
    // Public routes
    Route::post('/register', [App\Http\Controllers\Api\Physio\AuthController::class, 'register']);
    Route::post('/login', [App\Http\Controllers\Api\Physio\AuthController::class, 'login']);
    Route::post('/forgot-password', [App\Http\Controllers\Api\Physio\AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [App\Http\Controllers\Api\Physio\AuthController::class, 'resetPassword']);

    // Protected routes - require User authentication
    Route::middleware(['auth:sanctum', 'physio'])->group(function () {
        // Auth routes
        Route::post('/logout', [App\Http\Controllers\Api\Physio\AuthController::class, 'logout']);
        Route::get('/user', [App\Http\Controllers\Api\Physio\AuthController::class, 'user']);

        // Assessment routes
        Route::prefix('assessments')->group(function () {
            Route::get('/', [App\Http\Controllers\Api\Physio\AssessmentController::class, 'index']);
            Route::get('/completion-status', [App\Http\Controllers\Api\Physio\AssessmentController::class, 'checkCompletionStatus']);
            Route::get('/check-drafts', [App\Http\Controllers\Api\Physio\AssessmentController::class, 'checkDrafts']);
            Route::get('/drafts', [App\Http\Controllers\Api\Physio\AssessmentController::class, 'getDrafts']);
            Route::get('/all', [App\Http\Controllers\Api\Physio\AssessmentController::class, 'getAll']);
            Route::post('/', [App\Http\Controllers\Api\Physio\AssessmentController::class, 'store']);

            // Unified Subjective assessment endpoint (must be before /{id} route)
            Route::put('/subjective/section', [App\Http\Controllers\Api\Physio\SubjectiveAssessmentController::class, 'updateSection']);

            // Unified Objective assessment endpoint (must be before /{id} route)
            Route::put('/objective/section', [App\Http\Controllers\Api\Physio\ObjectiveAssessmentController::class, 'updateSection']);

            // Parameterized routes (must be after specific routes)
            Route::get('/{id}', [App\Http\Controllers\Api\Physio\AssessmentController::class, 'show']);
            Route::put('/{id}', [App\Http\Controllers\Api\Physio\AssessmentController::class, 'update']);
            Route::post('/{id}/complete', [App\Http\Controllers\Api\Physio\AssessmentController::class, 'complete']);
            Route::delete('/{id}', [App\Http\Controllers\Api\Physio\AssessmentController::class, 'destroy']);
        });

        // Subscription routes
        Route::prefix('subscriptions')->group(function () {
            Route::get('/plans', [App\Http\Controllers\Api\Physio\SubscriptionController::class, 'plans']);
            Route::get('/current', [App\Http\Controllers\Api\Physio\SubscriptionController::class, 'current']);
            Route::post('/subscribe', [App\Http\Controllers\Api\Physio\SubscriptionController::class, 'subscribe']);
            Route::get('/usage', [App\Http\Controllers\Api\Physio\SubscriptionController::class, 'usage']);
        });
    });
});

// Admin Routes (Admin authentication)
Route::prefix('admin')->group(function () {
    // Public routes
    Route::post('/login', [App\Http\Controllers\Api\Physio\AdminAuthController::class, 'login']);

    // Protected routes - require Admin authentication
    Route::middleware(['admin'])->group(function () {
        // Admin auth routes
        Route::post('/logout', [App\Http\Controllers\Api\Physio\AdminAuthController::class, 'logout']);
        Route::get('/me', [App\Http\Controllers\Api\Physio\AdminAuthController::class, 'admin']);
        Route::post('/change-password', [App\Http\Controllers\Api\Physio\AdminAuthController::class, 'changePassword']);

        // Dashboard stats
        Route::get('/dashboard/stats', [App\Http\Controllers\Api\Physio\DashboardController::class, 'stats']);

        // Admin CRUD
        Route::prefix('admins')->group(function () {
            Route::get('/', [App\Http\Controllers\Api\Physio\AdminCrudController::class, 'index']);
            Route::post('/', [App\Http\Controllers\Api\Physio\AdminCrudController::class, 'store']);
            Route::get('/{id}', [App\Http\Controllers\Api\Physio\AdminCrudController::class, 'show']);
            Route::put('/{id}', [App\Http\Controllers\Api\Physio\AdminCrudController::class, 'update']);
            Route::delete('/{id}', [App\Http\Controllers\Api\Physio\AdminCrudController::class, 'destroy']);
            Route::post('/{id}/reset-password', [App\Http\Controllers\Api\Physio\AdminCrudController::class, 'resetPassword']);
        });

        // User CRUD + Password Reset
        Route::prefix('users')->group(function () {
            Route::get('/', [App\Http\Controllers\Api\Physio\UserCrudController::class, 'index']);
            Route::post('/', [App\Http\Controllers\Api\Physio\UserCrudController::class, 'store']);
            Route::get('/{id}', [App\Http\Controllers\Api\Physio\UserCrudController::class, 'show']);
            Route::put('/{id}', [App\Http\Controllers\Api\Physio\UserCrudController::class, 'update']);
            Route::delete('/{id}', [App\Http\Controllers\Api\Physio\UserCrudController::class, 'destroy']);
            Route::post('/{id}/reset-password', [App\Http\Controllers\Api\Physio\UserCrudController::class, 'resetPassword']);
        });

        // Subscription Plans CRUD
        Route::prefix('subscription-plans')->group(function () {
            Route::get('/', [App\Http\Controllers\Api\Physio\SubscriptionCrudController::class, 'indexPlans']);
            Route::post('/', [App\Http\Controllers\Api\Physio\SubscriptionCrudController::class, 'storePlan']);
            Route::get('/{id}', [App\Http\Controllers\Api\Physio\SubscriptionCrudController::class, 'showPlan']);
            Route::put('/{id}', [App\Http\Controllers\Api\Physio\SubscriptionCrudController::class, 'updatePlan']);
            Route::delete('/{id}', [App\Http\Controllers\Api\Physio\SubscriptionCrudController::class, 'destroyPlan']);
        });

        // User Subscriptions CRUD
        Route::prefix('user-subscriptions')->group(function () {
            Route::get('/', [App\Http\Controllers\Api\Physio\SubscriptionCrudController::class, 'indexSubscriptions']);
            Route::post('/', [App\Http\Controllers\Api\Physio\SubscriptionCrudController::class, 'storeSubscription']);
            Route::get('/{id}', [App\Http\Controllers\Api\Physio\SubscriptionCrudController::class, 'showSubscription']);
            Route::put('/{id}', [App\Http\Controllers\Api\Physio\SubscriptionCrudController::class, 'updateSubscription']);
            Route::delete('/{id}', [App\Http\Controllers\Api\Physio\SubscriptionCrudController::class, 'destroySubscription']);
        });

        // Settings Management
        Route::prefix('settings')->group(function () {
            Route::get('/', [App\Http\Controllers\Api\Physio\SettingsController::class, 'getSettings']);
            Route::put('/', [App\Http\Controllers\Api\Physio\SettingsController::class, 'updateSettings']);

            Route::prefix('statistics')->group(function () {
                Route::get('/', [App\Http\Controllers\Api\Physio\SettingsController::class, 'getStatistics']);
                Route::put('/bulk', [App\Http\Controllers\Api\Physio\SettingsController::class, 'updateStatistics']);
                Route::put('/{id}', [App\Http\Controllers\Api\Physio\SettingsController::class, 'updateStatistic']);
            });
        });
    });
});