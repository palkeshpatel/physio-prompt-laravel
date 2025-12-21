<?php

namespace App\Http\Controllers\Api\Physio;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\AssessmentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssessmentController extends Controller
{
    public function index(Request $request)
    {
        $assessments = Assessment::where('user_id', $request->user()->id)
            ->with('assessmentType')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json($assessments);
    }

    public function store(Request $request)
    {
        $request->validate([
            'assessment_type_id' => 'required|exists:assessment_types,id',
        ]);

        // Check subscription limits
        $user = $request->user();
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

        $assessment = Assessment::create([
            'user_id' => $user->id,
            'assessment_type_id' => $request->assessment_type_id,
            'status' => 'draft',
            'completion_percentage' => 0,
        ]);

        // Update usage
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

        return response()->json([
            'message' => 'Assessment created successfully',
            'assessment' => $assessment->load('assessmentType'),
        ], 201);
    }

    public function show(Request $request, $id)
    {
        $assessment = Assessment::where('user_id', $request->user()->id)
            ->with([
                'assessmentType',
                'basicPatientDetails',
                'chiefComplaint',
                'painCharacteristics',
                'historyPresentCondition',
                'functionalLimitations',
                'redFlagScreening',
                'yellowFlags',
                'medicalHistory',
                'lifestyleSocialHistory',
                'iceAssessment',
                'regionSpecific',
                'subjectiveOutcomeMeasures',
                'observationsGeneralExamination',
                'palpation',
                'rangeOfMotion',
                'muscleStrength',
                'neurologicalExamination',
                'specialTests',
                'functionalAssessment',
                'jointMobility',
                'objectiveOutcomeMeasures',
                'objectiveRedFlags',
            ])
            ->findOrFail($id);

        return response()->json($assessment);
    }

    public function update(Request $request, $id)
    {
        $assessment = Assessment::where('user_id', $request->user()->id)
            ->findOrFail($id);

        $request->validate([
            'status' => 'sometimes|in:draft,in_progress,completed',
            'clinical_impression' => 'nullable|string',
            'rehab_program' => 'nullable|string',
        ]);

        $assessment->update($request->only([
            'status',
            'clinical_impression',
            'rehab_program',
        ]));

        if ($request->status === 'completed' && !$assessment->completed_at) {
            $assessment->update(['completed_at' => now()]);
        }

        return response()->json([
            'message' => 'Assessment updated successfully',
            'assessment' => $assessment->load('assessmentType'),
        ]);
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
}

