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

    public function basicPatientDetails(Request $request)
    {
        $request->validate([
            'assessment_id' => 'required|exists:assessments,id',
        ]);

        $assessment = $this->getAssessment($request, $request->assessment_id);
        
        $data = $request->except(['assessment_id']);
        $data['assessment_id'] = $assessment->id;
        
        $section = $assessment->basicPatientDetails()->updateOrCreate(
            ['assessment_id' => $assessment->id],
            $data
        );

        $this->updateCompletionPercentage($assessment, 'subjective');

        return response()->json([
            'message' => 'Basic patient details saved successfully',
            'data' => $section,
        ]);
    }

    public function chiefComplaint(Request $request)
    {
        $request->validate([
            'assessment_id' => 'required|exists:assessments,id',
        ]);

        $assessment = $this->getAssessment($request, $request->assessment_id);
        
        $data = $request->except(['assessment_id']);
        $data['assessment_id'] = $assessment->id;
        
        $section = $assessment->chiefComplaint()->updateOrCreate(
            ['assessment_id' => $assessment->id],
            $data
        );

        $this->updateCompletionPercentage($assessment, 'subjective');

        return response()->json([
            'message' => 'Chief complaint saved successfully',
            'data' => $section,
        ]);
    }

    public function painCharacteristics(Request $request)
    {
        $request->validate([
            'assessment_id' => 'required|exists:assessments,id',
        ]);

        $assessment = $this->getAssessment($request, $request->assessment_id);
        
        $data = $request->except(['assessment_id']);
        $data['assessment_id'] = $assessment->id;
        
        $section = $assessment->painCharacteristics()->updateOrCreate(
            ['assessment_id' => $assessment->id],
            $data
        );

        $this->updateCompletionPercentage($assessment, 'subjective');

        return response()->json([
            'message' => 'Pain characteristics saved successfully',
            'data' => $section,
        ]);
    }

    public function historyPresentCondition(Request $request)
    {
        $request->validate([
            'assessment_id' => 'required|exists:assessments,id',
        ]);

        $assessment = $this->getAssessment($request, $request->assessment_id);
        
        $data = $request->except(['assessment_id']);
        $data['assessment_id'] = $assessment->id;
        
        $section = $assessment->historyPresentCondition()->updateOrCreate(
            ['assessment_id' => $assessment->id],
            $data
        );

        $this->updateCompletionPercentage($assessment, 'subjective');

        return response()->json([
            'message' => 'History of present condition saved successfully',
            'data' => $section,
        ]);
    }

    public function functionalLimitations(Request $request)
    {
        $request->validate([
            'assessment_id' => 'required|exists:assessments,id',
        ]);

        $assessment = $this->getAssessment($request, $request->assessment_id);
        
        $data = $request->except(['assessment_id']);
        $data['assessment_id'] = $assessment->id;
        
        $section = $assessment->functionalLimitations()->updateOrCreate(
            ['assessment_id' => $assessment->id],
            $data
        );

        $this->updateCompletionPercentage($assessment, 'subjective');

        return response()->json([
            'message' => 'Functional limitations saved successfully',
            'data' => $section,
        ]);
    }

    public function redFlagScreening(Request $request)
    {
        $request->validate([
            'assessment_id' => 'required|exists:assessments,id',
        ]);

        $assessment = $this->getAssessment($request, $request->assessment_id);
        
        $data = $request->except(['assessment_id']);
        $data['assessment_id'] = $assessment->id;
        
        $section = $assessment->redFlagScreening()->updateOrCreate(
            ['assessment_id' => $assessment->id],
            $data
        );

        $this->updateCompletionPercentage($assessment, 'subjective');

        return response()->json([
            'message' => 'Red flag screening saved successfully',
            'data' => $section,
        ]);
    }

    public function yellowFlags(Request $request)
    {
        $request->validate([
            'assessment_id' => 'required|exists:assessments,id',
        ]);

        $assessment = $this->getAssessment($request, $request->assessment_id);
        
        $data = $request->except(['assessment_id']);
        $data['assessment_id'] = $assessment->id;
        
        $section = $assessment->yellowFlags()->updateOrCreate(
            ['assessment_id' => $assessment->id],
            $data
        );

        $this->updateCompletionPercentage($assessment, 'subjective');

        return response()->json([
            'message' => 'Yellow flags saved successfully',
            'data' => $section,
        ]);
    }

    public function medicalHistory(Request $request)
    {
        $request->validate([
            'assessment_id' => 'required|exists:assessments,id',
        ]);

        $assessment = $this->getAssessment($request, $request->assessment_id);
        
        $data = $request->except(['assessment_id']);
        $data['assessment_id'] = $assessment->id;
        
        $section = $assessment->medicalHistory()->updateOrCreate(
            ['assessment_id' => $assessment->id],
            $data
        );

        $this->updateCompletionPercentage($assessment, 'subjective');

        return response()->json([
            'message' => 'Medical history saved successfully',
            'data' => $section,
        ]);
    }

    public function lifestyleSocialHistory(Request $request)
    {
        $request->validate([
            'assessment_id' => 'required|exists:assessments,id',
        ]);

        $assessment = $this->getAssessment($request, $request->assessment_id);
        
        $data = $request->except(['assessment_id']);
        $data['assessment_id'] = $assessment->id;
        
        $section = $assessment->lifestyleSocialHistory()->updateOrCreate(
            ['assessment_id' => $assessment->id],
            $data
        );

        $this->updateCompletionPercentage($assessment, 'subjective');

        return response()->json([
            'message' => 'Lifestyle & social history saved successfully',
            'data' => $section,
        ]);
    }

    public function iceAssessment(Request $request)
    {
        $request->validate([
            'assessment_id' => 'required|exists:assessments,id',
        ]);

        $assessment = $this->getAssessment($request, $request->assessment_id);
        
        $data = $request->except(['assessment_id']);
        $data['assessment_id'] = $assessment->id;
        
        $section = $assessment->iceAssessment()->updateOrCreate(
            ['assessment_id' => $assessment->id],
            $data
        );

        $this->updateCompletionPercentage($assessment, 'subjective');

        return response()->json([
            'message' => 'ICE assessment saved successfully',
            'data' => $section,
        ]);
    }

    public function regionSpecific(Request $request)
    {
        $request->validate([
            'assessment_id' => 'required|exists:assessments,id',
        ]);

        $assessment = $this->getAssessment($request, $request->assessment_id);
        
        $data = $request->except(['assessment_id']);
        $data['assessment_id'] = $assessment->id;
        
        $section = $assessment->regionSpecific()->updateOrCreate(
            ['assessment_id' => $assessment->id],
            $data
        );

        $this->updateCompletionPercentage($assessment, 'subjective');

        return response()->json([
            'message' => 'Region specific saved successfully',
            'data' => $section,
        ]);
    }

    public function outcomeMeasures(Request $request)
    {
        $request->validate([
            'assessment_id' => 'required|exists:assessments,id',
        ]);

        $assessment = $this->getAssessment($request, $request->assessment_id);
        
        $data = $request->except(['assessment_id']);
        $data['assessment_id'] = $assessment->id;
        
        $section = $assessment->subjectiveOutcomeMeasures()->updateOrCreate(
            ['assessment_id' => $assessment->id],
            $data
        );

        $this->updateCompletionPercentage($assessment, 'subjective');

        return response()->json([
            'message' => 'Outcome measures saved successfully',
            'data' => $section,
        ]);
    }

    private function updateCompletionPercentage($assessment, $type)
    {
        if ($type === 'subjective') {
            $sections = [
                $assessment->basicPatientDetails,
                $assessment->chiefComplaint,
                $assessment->painCharacteristics,
                $assessment->historyPresentCondition,
                $assessment->functionalLimitations,
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

            $average = $count > 0 ? $totalPercentage / 12 : 0;
            $finalPercentage = $average * 0.20; // 20% weight

            $assessment->update(['completion_percentage' => round($finalPercentage, 2)]);
        }
    }
}



