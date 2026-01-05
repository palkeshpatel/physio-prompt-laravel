<?php

namespace App\Http\Controllers\Api\Physio;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use Illuminate\Http\Request;

class ObjectiveAssessmentController extends Controller
{
    private function getAssessment(Request $request, $assessmentId)
    {
        return Assessment::where('user_id', $request->user()->id)
            ->findOrFail($assessmentId);
    }

    public function observationsGeneralExamination(Request $request)
    {
        $request->validate([
            'assessment_id' => 'required|exists:assessments,id',
        ]);

        $assessment = $this->getAssessment($request, $request->assessment_id);
        
        $data = $request->except(['assessment_id']);
        $data['assessment_id'] = $assessment->id;
        
        $section = $assessment->observationsGeneralExamination()->updateOrCreate(
            ['assessment_id' => $assessment->id],
            $data
        );

        $this->updateCompletionPercentage($assessment, 'objective');

        return response()->json([
            'message' => 'Observations & general examination saved successfully',
            'data' => $section,
        ]);
    }

    public function palpation(Request $request)
    {
        $request->validate([
            'assessment_id' => 'required|exists:assessments,id',
        ]);

        $assessment = $this->getAssessment($request, $request->assessment_id);
        
        $data = $request->except(['assessment_id']);
        $data['assessment_id'] = $assessment->id;
        
        $section = $assessment->palpation()->updateOrCreate(
            ['assessment_id' => $assessment->id],
            $data
        );

        $this->updateCompletionPercentage($assessment, 'objective');

        return response()->json([
            'message' => 'Palpation saved successfully',
            'data' => $section,
        ]);
    }

    public function rangeOfMotion(Request $request)
    {
        $request->validate([
            'assessment_id' => 'required|exists:assessments,id',
        ]);

        $assessment = $this->getAssessment($request, $request->assessment_id);
        
        $data = $request->except(['assessment_id']);
        $data['assessment_id'] = $assessment->id;
        
        $section = $assessment->rangeOfMotion()->updateOrCreate(
            ['assessment_id' => $assessment->id],
            $data
        );

        $this->updateCompletionPercentage($assessment, 'objective');

        return response()->json([
            'message' => 'Range of motion saved successfully',
            'data' => $section,
        ]);
    }

    public function muscleStrength(Request $request)
    {
        $request->validate([
            'assessment_id' => 'required|exists:assessments,id',
        ]);

        $assessment = $this->getAssessment($request, $request->assessment_id);
        
        $data = $request->except(['assessment_id']);
        $data['assessment_id'] = $assessment->id;
        
        $section = $assessment->muscleStrength()->updateOrCreate(
            ['assessment_id' => $assessment->id],
            $data
        );

        $this->updateCompletionPercentage($assessment, 'objective');

        return response()->json([
            'message' => 'Muscle strength saved successfully',
            'data' => $section,
        ]);
    }

    public function neurologicalExamination(Request $request)
    {
        $request->validate([
            'assessment_id' => 'required|exists:assessments,id',
        ]);

        $assessment = $this->getAssessment($request, $request->assessment_id);
        
        $data = $request->except(['assessment_id']);
        $data['assessment_id'] = $assessment->id;
        
        $section = $assessment->neurologicalExamination()->updateOrCreate(
            ['assessment_id' => $assessment->id],
            $data
        );

        $this->updateCompletionPercentage($assessment, 'objective');

        return response()->json([
            'message' => 'Neurological examination saved successfully',
            'data' => $section,
        ]);
    }

    public function specialTests(Request $request)
    {
        $request->validate([
            'assessment_id' => 'required|exists:assessments,id',
        ]);

        $assessment = $this->getAssessment($request, $request->assessment_id);
        
        $data = $request->except(['assessment_id']);
        $data['assessment_id'] = $assessment->id;
        
        $section = $assessment->specialTests()->updateOrCreate(
            ['assessment_id' => $assessment->id],
            $data
        );

        $this->updateCompletionPercentage($assessment, 'objective');

        return response()->json([
            'message' => 'Special tests saved successfully',
            'data' => $section,
        ]);
    }

    public function functionalAssessment(Request $request)
    {
        $request->validate([
            'assessment_id' => 'required|exists:assessments,id',
        ]);

        $assessment = $this->getAssessment($request, $request->assessment_id);
        
        $data = $request->except(['assessment_id']);
        $data['assessment_id'] = $assessment->id;
        
        $section = $assessment->functionalAssessment()->updateOrCreate(
            ['assessment_id' => $assessment->id],
            $data
        );

        $this->updateCompletionPercentage($assessment, 'objective');

        return response()->json([
            'message' => 'Functional assessment saved successfully',
            'data' => $section,
        ]);
    }

    public function jointMobility(Request $request)
    {
        $request->validate([
            'assessment_id' => 'required|exists:assessments,id',
        ]);

        $assessment = $this->getAssessment($request, $request->assessment_id);
        
        $data = $request->except(['assessment_id']);
        $data['assessment_id'] = $assessment->id;
        
        $section = $assessment->jointMobility()->updateOrCreate(
            ['assessment_id' => $assessment->id],
            $data
        );

        $this->updateCompletionPercentage($assessment, 'objective');

        return response()->json([
            'message' => 'Joint mobility saved successfully',
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
        
        $section = $assessment->objectiveOutcomeMeasures()->updateOrCreate(
            ['assessment_id' => $assessment->id],
            $data
        );

        $this->updateCompletionPercentage($assessment, 'objective');

        return response()->json([
            'message' => 'Outcome measures saved successfully',
            'data' => $section,
        ]);
    }

    public function redFlags(Request $request)
    {
        $request->validate([
            'assessment_id' => 'required|exists:assessments,id',
        ]);

        $assessment = $this->getAssessment($request, $request->assessment_id);
        
        $data = $request->except(['assessment_id']);
        $data['assessment_id'] = $assessment->id;
        
        $section = $assessment->objectiveRedFlags()->updateOrCreate(
            ['assessment_id' => $assessment->id],
            $data
        );

        $this->updateCompletionPercentage($assessment, 'objective');

        return response()->json([
            'message' => 'Red flags saved successfully',
            'data' => $section,
        ]);
    }

    private function updateCompletionPercentage($assessment, $type)
    {
        if ($type === 'objective') {
            $sections = [
                $assessment->observationsGeneralExamination,
                $assessment->palpation,
                $assessment->rangeOfMotion,
                $assessment->muscleStrength,
                $assessment->neurologicalExamination,
                $assessment->specialTests,
                $assessment->functionalAssessment,
                $assessment->jointMobility,
                $assessment->objectiveOutcomeMeasures,
                $assessment->objectiveRedFlags,
            ];

            $totalPercentage = 0;
            $count = 0;
            foreach ($sections as $section) {
                if ($section) {
                    $totalPercentage += $section->completion_percentage ?? 0;
                    $count++;
                }
            }

            $average = $count > 0 ? $totalPercentage / 10 : 0;
            $finalPercentage = $average * 1.00; // 100% weight

            $assessment->update(['completion_percentage' => round($finalPercentage, 2)]);
        }
    }
}



