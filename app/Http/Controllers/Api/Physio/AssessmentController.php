<?php

namespace App\Http\Controllers\Api\Physio;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssessmentController extends Controller
{
    public function index(Request $request)
    {
        $assessments = Assessment::where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json($assessments);
    }

    /**
     * Check if user has any completed assessments
     * Returns completion status from assessments table
     */
    public function checkCompletionStatus(Request $request)
    {
        $userId = $request->user()->id;

        $hasCompleted = Assessment::where('user_id', $userId)
            ->where('status', 'completed')
            ->where('subjective_completion_percentage', '>=', 100)
            ->where('objective_completion_percentage', '>=', 100)
            ->exists();

        $hasSubjective = Assessment::where('user_id', $userId)
            ->where('subjective_completion_percentage', '>=', 100)
            ->exists();

        $hasObjective = Assessment::where('user_id', $userId)
            ->where('objective_completion_percentage', '>=', 100)
            ->exists();

        return response()->json([
            'has_completed_assessments' => $hasCompleted,
            'has_subjective' => $hasSubjective,
            'has_objective' => $hasObjective,
        ]);
    }

    /**
     * Quick check if user has any draft assessments
     * Returns: { "has_drafts": true/false, "count": 0 }
     */
    public function checkDrafts(Request $request)
    {
        $userId = $request->user()->id;

        $count = Assessment::where('user_id', $userId)
            ->whereIn('status', ['draft', 'in_progress'])
            ->count();

        return response()->json([
            'has_drafts' => $count > 0,
            'count' => $count,
        ]);
    }

    /**
     * Get all draft assessments (draft or in_progress)
     * Returns list with completion percentages
     */
    public function getDrafts(Request $request)
    {
        $userId = $request->user()->id;

        $drafts = Assessment::where('user_id', $userId)
            ->whereIn('status', ['draft', 'in_progress'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($assessment) {
                return [
                    'id' => $assessment->id,
                    'status' => $assessment->status,
                    'created_at' => $assessment->created_at,
                    'updated_at' => $assessment->updated_at,
                    'subjective_percentage' => round($assessment->subjective_completion_percentage ?? 0, 2),
                    'objective_percentage' => round($assessment->objective_completion_percentage ?? 0, 2),
                    'overall_progress' => round($assessment->completion_percentage ?? 0, 2),
                ];
            });

        return response()->json([
            'drafts' => $drafts,
            'count' => $drafts->count(),
        ]);
    }

    /**
     * Get all assessments grouped by status (drafts and completed)
     * Returns assessments with completion percentages
     */
    public function getAll(Request $request)
    {
        $userId = $request->user()->id;

        // Get draft assessments
        $drafts = Assessment::where('user_id', $userId)
            ->whereIn('status', ['draft', 'in_progress'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($assessment) {
                return [
                    'id' => $assessment->id,
                    'status' => $assessment->status,
                    'created_at' => $assessment->created_at,
                    'updated_at' => $assessment->updated_at,
                    'subjective_percentage' => round($assessment->subjective_completion_percentage ?? 0, 2),
                    'objective_percentage' => round($assessment->objective_completion_percentage ?? 0, 2),
                    'overall_progress' => round($assessment->completion_percentage ?? 0, 2),
                ];
            });

        // Get completed assessments
        $completed = Assessment::where('user_id', $userId)
            ->where('status', 'completed')
            ->orderBy('completed_at', 'desc')
            ->get()
            ->map(function ($assessment) {
                return [
                    'id' => $assessment->id,
                    'status' => $assessment->status,
                    'created_at' => $assessment->created_at,
                    'completed_at' => $assessment->completed_at,
                    'subjective_percentage' => round($assessment->subjective_completion_percentage ?? 0, 2),
                    'objective_percentage' => round($assessment->objective_completion_percentage ?? 0, 2),
                    'overall_progress' => round($assessment->completion_percentage ?? 0, 2),
                ];
            });

        return response()->json([
            'drafts' => $drafts,
            'completed' => $completed,
            'draft_count' => $drafts->count(),
            'completed_count' => $completed->count(),
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $forceNew = $request->input('force_new', false);

        // Check for existing draft or in_progress assessment (unless force_new is true)
        if (! $forceNew) {
            $existingAssessment = Assessment::where('user_id', $user->id)
                ->whereIn('status', ['draft', 'in_progress'])
                ->latest()
                ->first();

            if ($existingAssessment) {
                return response()->json([
                    'message' => 'Existing assessment found',
                    'assessment' => $existingAssessment,
                    'is_existing' => true,
                ], 200);
            }
        }

        // Check subscription limits (but don't increment yet - only when completed)
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $usage = DB::table('user_assessment_usage')
            ->where('user_id', $user->id)
            ->where('month', $currentMonth)
            ->where('year', $currentYear)
            ->first();

        $activeSubscription = $user->activeSubscription;

        if (! $activeSubscription) {
            return response()->json([
                'message' => 'No active subscription found',
            ], 403);
        }

        $limit = $activeSubscription->assessment_of_month;
        $used = $usage ? $usage->assessments_used : 0;

        if ($used >= $limit && ! $activeSubscription->subscriptionPlan->unlimited_assessments) {
            return response()->json([
                'message' => 'Monthly assessment limit reached',
                'limit' => $limit,
                'used' => $used,
            ], 403);
        }

        // Return success - assessment will be created when basic_details is submitted
        return response()->json([
            'message' => 'Ready to start assessment. Please fill basic details to begin.',
            'can_start' => true,
        ], 200);
    }

    public function show(Request $request, $id)
    {
        try {
            $assessment = Assessment::where('user_id', $request->user()->id)
                ->where('id', $id)
                ->firstOrFail();

            return response()->json($assessment);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Assessment not found. The assessment may not exist or may not belong to your account.',
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $assessment = Assessment::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->firstOrFail();

        $request->validate([
            'status' => 'sometimes|in:draft,in_progress,completed',
            'clinical_impression' => 'sometimes|string|nullable',
            'rehab_program' => 'sometimes|string|nullable',
        ]);

        $wasCompleted = $assessment->status === 'completed';
        $isBeingCompleted = $request->status === 'completed' && ! $wasCompleted;

        // Check if both subjective and objective are 100% complete before allowing overall completion
        if ($isBeingCompleted) {
            $subjectiveComplete = $assessment->subjective_completion_percentage >= 100;
            $objectiveComplete = $assessment->objective_completion_percentage >= 100;

            if (! $subjectiveComplete || ! $objectiveComplete) {
                return response()->json([
                    'message' => 'Cannot mark as completed. Both Subjective and Objective assessments must be 100% complete.',
                    'subjective_complete' => $subjectiveComplete,
                    'objective_complete' => $objectiveComplete,
                ], 400);
            }
        }

        $assessment->update($request->only(['status', 'clinical_impression', 'rehab_program']));

        // Only increment if being completed AND both are 100% complete
        if ($isBeingCompleted) {
            $assessment->update(['completed_at' => now()]);
            $this->incrementAssessmentUsage($request->user(), $assessment->id);
        }

        return response()->json([
            'message' => 'Assessment updated successfully',
            'assessment' => $assessment,
        ]);
    }

    /**
     * Increment assessment usage count for the current month
     * Only increments once per assessment completion
     */
    private function incrementAssessmentUsage($user, $assessmentId = null)
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $usage = DB::table('user_assessment_usage')
            ->where('user_id', $user->id)
            ->where('month', $currentMonth)
            ->where('year', $currentYear)
            ->first();

        $activeSubscription = $user->activeSubscription;

        if (! $activeSubscription) {
            return; // No subscription, can't increment
        }

        $limit = $activeSubscription->assessment_of_month;

        if ($usage) {
            DB::table('user_assessment_usage')
                ->where('id', $usage->id)
                ->increment('assessments_used');
        } else {
            DB::table('user_assessment_usage')->insert([
                'user_id' => $user->id,
                'month' => $currentMonth,
                'year' => $currentYear,
                'assessments_used' => 1,
                'assessment_limit' => $limit,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function destroy(Request $request, $id)
    {
        $assessment = Assessment::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->firstOrFail();

        $assessment->delete();

        return response()->json([
            'message' => 'Assessment deleted successfully',
        ]);
    }

    /**
     * Mark assessment as completed when all forms are submitted
     * This will increment the assessment usage count
     * Only increments if both Subjective and Objective are 100% complete and not already completed
     */
    public function complete(Request $request, $id)
    {
        $assessment = Assessment::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->firstOrFail();

        // Only allow completion if not already completed
        if ($assessment->status === 'completed') {
            return response()->json([
                'message' => 'Assessment is already completed',
                'assessment' => $assessment,
            ]);
        }

        // Verify both are 100% complete before marking as completed
        $subjectiveComplete = $assessment->subjective_completion_percentage >= 100;
        $objectiveComplete = $assessment->objective_completion_percentage >= 100;

        if (! $subjectiveComplete || ! $objectiveComplete) {
            return response()->json([
                'message' => 'Assessment is not 100% complete. Both Subjective and Objective assessments must be completed.',
                'subjective_complete' => $subjectiveComplete,
                'objective_complete' => $objectiveComplete,
                'subjective_percentage' => $assessment->subjective_completion_percentage ?? 0,
                'objective_percentage' => $assessment->objective_completion_percentage ?? 0,
            ], 400);
        }

        // Mark as completed
        $assessment->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        // Increment assessment usage count only once when completed
        $this->incrementAssessmentUsage($request->user(), $assessment->id);

        return response()->json([
            'message' => 'Assessment completed successfully',
            'assessment' => $assessment,
        ]);
    }
}
