<?php

namespace App\Http\Controllers\Api\Physio;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use Illuminate\Http\Request;

class SubjectiveAssessmentController extends Controller
{
    private function getAssessment(Request $request, $assessmentId)
    {
        return Assessment::where('user_id', $request->user()->id)
            ->findOrFail($assessmentId);
    }

    /**
     * Common fast PUT method to update section fields
     * Creates entry if doesn't exist, updates if exists
     * Can update one field at a time or multiple fields
     */
    private function updateOrCreateSection($assessment, $relationshipName, $data)
    {
        $relationship = $assessment->$relationshipName();

        // Use updateOrCreate - creates if doesn't exist, updates if exists
        $section = $relationship->updateOrCreate(
            ['assessment_id' => $assessment->id],
            $data
        );

        // Refresh to get updated data
        $section->refresh();

        return $section;
    }

    /**
     * Common PUT method for Basic Patient Details
     */
    public function basicPatientDetails(Request $request)
    {
        $request->validate([
            'assessment_id' => 'required|exists:assessments,id',
        ]);

        $assessment = $this->getAssessment($request, $request->assessment_id);

        // Get all data except assessment_id
        $data = $request->except(['assessment_id']);
        $data['assessment_id'] = $assessment->id;

        // Update or create section
        $section = $this->updateOrCreateSection($assessment, 'basicPatientDetails', $data);

        // Update completion percentage
        $this->updateCompletionPercentage($assessment, 'subjective');

        return response()->json([
            'message' => 'Basic patient details saved successfully',
            'data' => $section,
        ]);
    }

    /**
     * Common PUT method for Chief Complaint
     */
    public function chiefComplaint(Request $request)
    {
        $request->validate([
            'assessment_id' => 'required|exists:assessments,id',
        ]);

        $assessment = $this->getAssessment($request, $request->assessment_id);
        $data = $request->except(['assessment_id']);
        $data['assessment_id'] = $assessment->id;

        $section = $this->updateOrCreateSection($assessment, 'chiefComplaint', $data);
        $this->updateCompletionPercentage($assessment, 'subjective');

        return response()->json([
            'message' => 'Chief complaint saved successfully',
            'data' => $section,
        ]);
    }

    /**
     * Common PUT method for Pain Characteristics
     */
    public function painCharacteristics(Request $request)
    {
        $request->validate([
            'assessment_id' => 'required|exists:assessments,id',
        ]);

        $assessment = $this->getAssessment($request, $request->assessment_id);
        $data = $request->except(['assessment_id']);
        $data['assessment_id'] = $assessment->id;

        $section = $this->updateOrCreateSection($assessment, 'painCharacteristics', $data);
        $this->updateCompletionPercentage($assessment, 'subjective');

        return response()->json([
            'message' => 'Pain characteristics saved successfully',
            'data' => $section,
        ]);
    }

    /**
     * Common PUT method for History of Present Condition
     */
    public function historyPresentCondition(Request $request)
    {
        $request->validate([
            'assessment_id' => 'required|exists:assessments,id',
        ]);

        $assessment = $this->getAssessment($request, $request->assessment_id);
        $data = $request->except(['assessment_id']);
        $data['assessment_id'] = $assessment->id;

        $section = $this->updateOrCreateSection($assessment, 'historyPresentCondition', $data);
        $this->updateCompletionPercentage($assessment, 'subjective');

        return response()->json([
            'message' => 'History of present condition saved successfully',
            'data' => $section,
        ]);
    }

    /**
     * Common PUT method for Functional Limitations
     */
    public function functionalLimitations(Request $request)
    {
        $request->validate([
            'assessment_id' => 'required|exists:assessments,id',
        ]);

        $assessment = $this->getAssessment($request, $request->assessment_id);
        $data = $request->except(['assessment_id']);
        $data['assessment_id'] = $assessment->id;

        $section = $this->updateOrCreateSection($assessment, 'functionalLimitations', $data);
        $this->updateCompletionPercentage($assessment, 'subjective');

        return response()->json([
            'message' => 'Functional limitations saved successfully',
            'data' => $section,
        ]);
    }

    /**
     * Common PUT method for Red Flag Screening
     */
    public function redFlagScreening(Request $request)
    {
        $request->validate([
            'assessment_id' => 'required|exists:assessments,id',
        ]);

        $assessment = $this->getAssessment($request, $request->assessment_id);
        $data = $request->except(['assessment_id']);
        $data['assessment_id'] = $assessment->id;

        $section = $this->updateOrCreateSection($assessment, 'redFlagScreening', $data);
        $this->updateCompletionPercentage($assessment, 'subjective');

        return response()->json([
            'message' => 'Red flag screening saved successfully',
            'data' => $section,
        ]);
    }

    /**
     * Common PUT method for Yellow Flags
     */
    public function yellowFlags(Request $request)
    {
        $request->validate([
            'assessment_id' => 'required|exists:assessments,id',
        ]);

        $assessment = $this->getAssessment($request, $request->assessment_id);
        $data = $request->except(['assessment_id']);
        $data['assessment_id'] = $assessment->id;

        $section = $this->updateOrCreateSection($assessment, 'yellowFlags', $data);
        $this->updateCompletionPercentage($assessment, 'subjective');

        return response()->json([
            'message' => 'Yellow flags saved successfully',
            'data' => $section,
        ]);
    }

    /**
     * Common PUT method for Medical History
     */
    public function medicalHistory(Request $request)
    {
        $request->validate([
            'assessment_id' => 'required|exists:assessments,id',
        ]);

        $assessment = $this->getAssessment($request, $request->assessment_id);
        $data = $request->except(['assessment_id']);
        $data['assessment_id'] = $assessment->id;

        $section = $this->updateOrCreateSection($assessment, 'medicalHistory', $data);
        $this->updateCompletionPercentage($assessment, 'subjective');

        return response()->json([
            'message' => 'Medical history saved successfully',
            'data' => $section,
        ]);
    }

    /**
     * Common PUT method for Lifestyle & Social History
     */
    public function lifestyleSocialHistory(Request $request)
    {
        $request->validate([
            'assessment_id' => 'required|exists:assessments,id',
        ]);

        $assessment = $this->getAssessment($request, $request->assessment_id);
        $data = $request->except(['assessment_id']);
        $data['assessment_id'] = $assessment->id;

        $section = $this->updateOrCreateSection($assessment, 'lifestyleSocialHistory', $data);
        $this->updateCompletionPercentage($assessment, 'subjective');

        return response()->json([
            'message' => 'Lifestyle & social history saved successfully',
            'data' => $section,
        ]);
    }

    /**
     * Common PUT method for ICE Assessment
     */
    public function iceAssessment(Request $request)
    {
        $request->validate([
            'assessment_id' => 'required|exists:assessments,id',
        ]);

        $assessment = $this->getAssessment($request, $request->assessment_id);
        $data = $request->except(['assessment_id']);
        $data['assessment_id'] = $assessment->id;

        $section = $this->updateOrCreateSection($assessment, 'iceAssessment', $data);
        $this->updateCompletionPercentage($assessment, 'subjective');

        return response()->json([
            'message' => 'ICE assessment saved successfully',
            'data' => $section,
        ]);
    }

    /**
     * Common PUT method for Region Specific
     */
    public function regionSpecific(Request $request)
    {
        $request->validate([
            'assessment_id' => 'required|exists:assessments,id',
        ]);

        $assessment = $this->getAssessment($request, $request->assessment_id);
        $data = $request->except(['assessment_id']);
        $data['assessment_id'] = $assessment->id;

        $section = $this->updateOrCreateSection($assessment, 'regionSpecific', $data);
        $this->updateCompletionPercentage($assessment, 'subjective');

        return response()->json([
            'message' => 'Region specific saved successfully',
            'data' => $section,
        ]);
    }

    /**
     * Common PUT method for Outcome Measures
     */
    public function outcomeMeasures(Request $request)
    {
        $request->validate([
            'assessment_id' => 'required|exists:assessments,id',
        ]);

        $assessment = $this->getAssessment($request, $request->assessment_id);
        $data = $request->except(['assessment_id']);
        $data['assessment_id'] = $assessment->id;

        $section = $this->updateOrCreateSection($assessment, 'subjectiveOutcomeMeasures', $data);
        $this->updateCompletionPercentage($assessment, 'subjective');

        return response()->json([
            'message' => 'Outcome measures saved successfully',
            'data' => $section,
        ]);
    }

    private function updateCompletionPercentage($assessment, $type)
    {
        if ($type === 'subjective') {
            // Load all relationships
            $assessment->load([
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
            ]);

            $sections = [
                $assessment->basicPatientDetails,
                $assessment->chiefComplaint,
                $assessment->painCharacteristics,
                $assessment->historyPresentCondition,
                $assessment->functionalLimitations,
                $assessment->redFlagScreening,
                $assessment->yellowFlags,
                $assessment->medicalHistory,
                $assessment->lifestyleSocialHistory,
                $assessment->iceAssessment,
                $assessment->regionSpecific,
                $assessment->subjectiveOutcomeMeasures,
            ];

            $totalPercentage = 0;
            $count = 0;
            foreach ($sections as $section) {
                if ($section) {
                    $totalPercentage += $section->completion_percentage ?? 0;
                    $count++;
                }
            }

            // There are 12 sections total for subjective assessment
            $average = $count > 0 ? $totalPercentage / 12 : 0;
            $finalPercentage = $average * 1.00; // 100% weight (each section contributes equally)

            $assessment->update(['completion_percentage' => round($finalPercentage, 2)]);
        }
    }
}
