<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('physio')->group(function () {
    // Public routes
    Route::post('/register', [App\Http\Controllers\Api\Physio\AuthController::class, 'register']);
    Route::post('/login', [App\Http\Controllers\Api\Physio\AuthController::class, 'login']);
    
    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        // Auth routes
        Route::post('/logout', [App\Http\Controllers\Api\Physio\AuthController::class, 'logout']);
        Route::get('/user', [App\Http\Controllers\Api\Physio\AuthController::class, 'user']);
        
        // Assessment routes
        Route::prefix('assessments')->group(function () {
            Route::get('/', [App\Http\Controllers\Api\Physio\AssessmentController::class, 'index']);
            Route::post('/', [App\Http\Controllers\Api\Physio\AssessmentController::class, 'store']);
            Route::get('/{id}', [App\Http\Controllers\Api\Physio\AssessmentController::class, 'show']);
            Route::put('/{id}', [App\Http\Controllers\Api\Physio\AssessmentController::class, 'update']);
            Route::delete('/{id}', [App\Http\Controllers\Api\Physio\AssessmentController::class, 'destroy']);
            
            // Subjective assessment sections
            Route::prefix('subjective')->group(function () {
                Route::post('/basic-patient-details', [App\Http\Controllers\Api\Physio\SubjectiveAssessmentController::class, 'basicPatientDetails']);
                Route::post('/chief-complaint', [App\Http\Controllers\Api\Physio\SubjectiveAssessmentController::class, 'chiefComplaint']);
                Route::post('/pain-characteristics', [App\Http\Controllers\Api\Physio\SubjectiveAssessmentController::class, 'painCharacteristics']);
                Route::post('/history-present-condition', [App\Http\Controllers\Api\Physio\SubjectiveAssessmentController::class, 'historyPresentCondition']);
                Route::post('/functional-limitations', [App\Http\Controllers\Api\Physio\SubjectiveAssessmentController::class, 'functionalLimitations']);
                Route::post('/red-flag-screening', [App\Http\Controllers\Api\Physio\SubjectiveAssessmentController::class, 'redFlagScreening']);
                Route::post('/yellow-flags', [App\Http\Controllers\Api\Physio\SubjectiveAssessmentController::class, 'yellowFlags']);
                Route::post('/medical-history', [App\Http\Controllers\Api\Physio\SubjectiveAssessmentController::class, 'medicalHistory']);
                Route::post('/lifestyle-social-history', [App\Http\Controllers\Api\Physio\SubjectiveAssessmentController::class, 'lifestyleSocialHistory']);
                Route::post('/ice-assessment', [App\Http\Controllers\Api\Physio\SubjectiveAssessmentController::class, 'iceAssessment']);
                Route::post('/region-specific', [App\Http\Controllers\Api\Physio\SubjectiveAssessmentController::class, 'regionSpecific']);
                Route::post('/outcome-measures', [App\Http\Controllers\Api\Physio\SubjectiveAssessmentController::class, 'outcomeMeasures']);
            });
            
            // Objective assessment sections
            Route::prefix('objective')->group(function () {
                Route::post('/observations-general-examination', [App\Http\Controllers\Api\Physio\ObjectiveAssessmentController::class, 'observationsGeneralExamination']);
                Route::post('/palpation', [App\Http\Controllers\Api\Physio\ObjectiveAssessmentController::class, 'palpation']);
                Route::post('/range-of-motion', [App\Http\Controllers\Api\Physio\ObjectiveAssessmentController::class, 'rangeOfMotion']);
                Route::post('/muscle-strength', [App\Http\Controllers\Api\Physio\ObjectiveAssessmentController::class, 'muscleStrength']);
                Route::post('/neurological-examination', [App\Http\Controllers\Api\Physio\ObjectiveAssessmentController::class, 'neurologicalExamination']);
                Route::post('/special-tests', [App\Http\Controllers\Api\Physio\ObjectiveAssessmentController::class, 'specialTests']);
                Route::post('/functional-assessment', [App\Http\Controllers\Api\Physio\ObjectiveAssessmentController::class, 'functionalAssessment']);
                Route::post('/joint-mobility', [App\Http\Controllers\Api\Physio\ObjectiveAssessmentController::class, 'jointMobility']);
                Route::post('/outcome-measures', [App\Http\Controllers\Api\Physio\ObjectiveAssessmentController::class, 'outcomeMeasures']);
                Route::post('/red-flags', [App\Http\Controllers\Api\Physio\ObjectiveAssessmentController::class, 'redFlags']);
            });
        });
        
        // Subscription routes
        Route::prefix('subscriptions')->group(function () {
            Route::get('/plans', [App\Http\Controllers\Api\Physio\SubscriptionController::class, 'plans']);
            Route::get('/current', [App\Http\Controllers\Api\Physio\SubscriptionController::class, 'current']);
            Route::post('/subscribe', [App\Http\Controllers\Api\Physio\SubscriptionController::class, 'subscribe']);
            Route::get('/usage', [App\Http\Controllers\Api\Physio\SubscriptionController::class, 'usage']);
        });
    });
    
    // Admin routes (not priority but included)
    Route::prefix('admin')->middleware('auth:sanctum')->group(function () {
        Route::get('/users', [App\Http\Controllers\Api\Physio\AdminController::class, 'users']);
        Route::get('/users/active', [App\Http\Controllers\Api\Physio\AdminController::class, 'activeUsers']);
        Route::post('/plans', [App\Http\Controllers\Api\Physio\AdminController::class, 'createPlan']);
    });
});



