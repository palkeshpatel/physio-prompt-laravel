<?php

namespace App\Http\Controllers\Api\Physio;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\AssessmentType;
use App\Models\AssessmentProcess;
use App\Models\AssessmentProcessDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssessmentController extends Controller
{
    public function index(Request $request)
    {
        $assessments = Assessment::where('user_id', $request->user()->id)
            ->with(['subjectiveProcess.assessmentType', 'objectiveProcess.assessmentType'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json($assessments);
    }

    /**
     * Check if user has any completed assessments
     * Returns completion status for both assessment types from database
     */
    public function checkCompletionStatus(Request $request)
    {
        $userId = $request->user()->id;

        // Get assessment types by name
        $subjectiveType = AssessmentType::where('name', 'Subjective')->first();
        $objectiveType = AssessmentType::where('name', 'Objective')->first();

        $hasSubjective = false;
        $hasObjective = false;

        if ($subjectiveType) {
            $hasSubjective = AssessmentProcess::whereHas('assessment', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
                ->where('assessment_type_id', $subjectiveType->id)
                ->where('status', 'completed')
                ->exists();
        }

        if ($objectiveType) {
            $hasObjective = AssessmentProcess::whereHas('assessment', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
                ->where('assessment_type_id', $objectiveType->id)
                ->where('status', 'completed')
                ->exists();
        }

        return response()->json([
            'has_completed_assessments' => $hasSubjective && $hasObjective, // Both must be completed
            'has_subjective' => $hasSubjective,
            'has_objective' => $hasObjective,
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        // Check for existing draft or in_progress assessment
        $existingAssessment = Assessment::where('user_id', $user->id)
            ->whereIn('status', ['draft', 'in_progress'])
            ->with(['subjectiveProcess', 'objectiveProcess'])
            ->latest()
            ->first();

        if ($existingAssessment) {
            return response()->json([
                'message' => 'Existing assessment found',
                'assessment' => $existingAssessment,
                'is_existing' => true,
            ], 200);
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

        if (!$activeSubscription) {
            return response()->json([
                'message' => 'No active subscription found',
            ], 403);
        }

        $limit = $activeSubscription->assessment_of_month;
        $used = $usage ? $usage->assessments_used : 0;

        if ($used >= $limit && !$activeSubscription->subscriptionPlan->unlimited_assessments) {
            return response()->json([
                'message' => 'Monthly assessment limit reached',
                'limit' => $limit,
                'used' => $used,
            ], 403);
        }

        // Get assessment type IDs
        $subjectiveType = AssessmentType::where('name', 'Subjective')->first();
        $objectiveType = AssessmentType::where('name', 'Objective')->first();

        if (!$subjectiveType || !$objectiveType) {
            return response()->json([
                'message' => 'Assessment types not found',
            ], 500);
        }

        // Create assessment (1 entry)
        $assessment = Assessment::create([
            'user_id' => $user->id,
            'status' => 'draft',
        ]);

        // Create 2 process entries (Subjective and Objective)
        $subjectiveProcess = AssessmentProcess::create([
            'assessments_id' => $assessment->id,
            'assessment_type_id' => $subjectiveType->id,
            'status' => 'draft',
            'completion_percentage' => 0,
        ]);

        $objectiveProcess = AssessmentProcess::create([
            'assessments_id' => $assessment->id,
            'assessment_type_id' => $objectiveType->id,
            'status' => 'draft',
            'completion_percentage' => 0,
        ]);

        // Create 22 process detail entries (12 Subjective + 10 Objective)
        $subjectiveDetails = [
            'subjective_basic_patient_details',
            'subjective_chief_complaint',
            'subjective_pain_characteristics',
            'subjective_history_present_condition',
            'subjective_functional_limitations',
            'subjective_red_flag_screening',
            'subjective_yellow_flags',
            'subjective_medical_history',
            'subjective_lifestyle_social_history',
            'subjective_ice_assessment',
            'subjective_region_specific',
            'subjective_outcome_measures',
        ];

        $objectiveDetails = [
            'objective_observations_general_examination',
            'objective_palpation',
            'objective_range_of_motion',
            'objective_muscle_strength',
            'objective_neurological_examination',
            'objective_special_tests',
            'objective_functional_assessment',
            'objective_joint_mobility',
            'objective_outcome_measures',
        ];

        foreach ($subjectiveDetails as $detail) {
            AssessmentProcessDetail::create([
                'assessments_pocess_id' => $subjectiveProcess->id,
                'assessment_table' => $detail,
            ]);
        }

        foreach ($objectiveDetails as $detail) {
            AssessmentProcessDetail::create([
                'assessments_pocess_id' => $objectiveProcess->id,
                'assessment_table' => $detail,
            ]);
        }

        return response()->json([
            'message' => 'Assessment created successfully',
            'assessment' => $assessment->load(['subjectiveProcess', 'objectiveProcess']),
            'is_existing' => false,
        ], 201);
    }

    public function show(Request $request, $id)
    {
        $assessment = Assessment::where('user_id', $request->user()->id)
            ->with([
                'subjectiveProcess.assessmentType',
                'subjectiveProcess.basicPatientDetails',
                'subjectiveProcess.chiefComplaint',
                'subjectiveProcess.painCharacteristics',
                'subjectiveProcess.historyPresentCondition',
                'subjectiveProcess.functionalLimitations',
                'subjectiveProcess.redFlagScreening',
                'subjectiveProcess.yellowFlags',
                'subjectiveProcess.medicalHistory',
                'subjectiveProcess.lifestyleSocialHistory',
                'subjectiveProcess.iceAssessment',
                'subjectiveProcess.regionSpecific',
                'subjectiveProcess.subjectiveOutcomeMeasures',
                'objectiveProcess.assessmentType',
                'objectiveProcess.observationsGeneralExamination',
                'objectiveProcess.palpation',
                'objectiveProcess.rangeOfMotion',
                'objectiveProcess.muscleStrength',
                'objectiveProcess.neurologicalExamination',
                'objectiveProcess.specialTests',
                'objectiveProcess.functionalAssessment',
                'objectiveProcess.jointMobility',
                'objectiveProcess.objectiveOutcomeMeasures',
            ])
            ->findOrFail($id);

        return response()->json($assessment);
    }

    public function update(Request $request, $id)
    {
        $assessment = Assessment::where('user_id', $request->user()->id)
            ->with(['subjectiveProcess', 'objectiveProcess'])
            ->findOrFail($id);

        $request->validate([
            'status' => 'sometimes|in:draft,in_progress,completed',
        ]);

        $wasCompleted = $assessment->status === 'completed';
        $isBeingCompleted = $request->status === 'completed' && !$wasCompleted;

        // Check if both processes are 100% complete before allowing overall completion
        if ($isBeingCompleted) {
            $subjectiveComplete = $assessment->subjectiveProcess &&
                $assessment->subjectiveProcess->completion_percentage >= 100 &&
                $assessment->subjectiveProcess->status === 'completed';

            $objectiveComplete = $assessment->objectiveProcess &&
                $assessment->objectiveProcess->completion_percentage >= 100 &&
                $assessment->objectiveProcess->status === 'completed';

            if (!$subjectiveComplete || !$objectiveComplete) {
                return response()->json([
                    'message' => 'Cannot mark as completed. Both Subjective and Objective assessments must be 100% complete.',
                    'subjective_complete' => $subjectiveComplete,
                    'objective_complete' => $objectiveComplete,
                ], 400);
            }
        }

        $assessment->update($request->only(['status']));

        // Only increment if being completed AND both processes are 100% complete
        if ($isBeingCompleted) {
            // Increment assessment usage count when assessment is completed
            $this->incrementAssessmentUsage($request->user(), $assessment->id);
        }

        return response()->json([
            'message' => 'Assessment updated successfully',
            'assessment' => $assessment->load(['subjectiveProcess.assessmentType', 'objectiveProcess.assessmentType']),
        ]);
    }

    /**
     * Increment assessment usage count for the current month
     * Only increments once per assessment completion
     * The complete() method ensures we only call this when status changes to 'completed'
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

        if (!$activeSubscription) {
            return; // No subscription, can't increment
        }

        $limit = $activeSubscription->assessment_of_month;

        // Increment usage count
        // Note: The complete() method already checks if assessment was already completed
        // So we can safely increment here without double counting
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
            ->findOrFail($id);

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
            ->with(['subjectiveProcess', 'objectiveProcess'])
            ->findOrFail($id);

        // Only allow completion if not already completed
        if ($assessment->status === 'completed') {
            return response()->json([
                'message' => 'Assessment is already completed',
                'assessment' => $assessment->load(['subjectiveProcess.assessmentType', 'objectiveProcess.assessmentType']),
            ]);
        }

        // Verify both processes are 100% complete before marking as completed
        $assessment->refresh();
        $assessment->load(['subjectiveProcess', 'objectiveProcess']);

        $subjectiveComplete = $assessment->subjectiveProcess &&
            $assessment->subjectiveProcess->completion_percentage >= 100 &&
            $assessment->subjectiveProcess->status === 'completed';

        $objectiveComplete = $assessment->objectiveProcess &&
            $assessment->objectiveProcess->completion_percentage >= 100 &&
            $assessment->objectiveProcess->status === 'completed';

        if (!$subjectiveComplete || !$objectiveComplete) {
            return response()->json([
                'message' => 'Assessment is not 100% complete. Both Subjective and Objective assessments must be completed.',
                'subjective_complete' => $subjectiveComplete,
                'objective_complete' => $objectiveComplete,
                'subjective_percentage' => $assessment->subjectiveProcess ? $assessment->subjectiveProcess->completion_percentage : 0,
                'objective_percentage' => $assessment->objectiveProcess ? $assessment->objectiveProcess->completion_percentage : 0,
            ], 400);
        }

        // Mark as completed
        $assessment->update([
            'status' => 'completed',
        ]);

        // Increment assessment usage count only once when completed
        // This will only increment if status was not already 'completed'
        $this->incrementAssessmentUsage($request->user(), $assessment->id);

        return response()->json([
            'message' => 'Assessment completed successfully',
            'assessment' => $assessment->load(['subjectiveProcess.assessmentType', 'objectiveProcess.assessmentType']),
        ]);
    }
}
