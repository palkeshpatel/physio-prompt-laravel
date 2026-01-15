<?php

namespace App\Http\Controllers\Api\Physio;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\AssessmentProcess;
use App\Models\AssessmentType;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ObjectiveAssessmentController extends Controller
{
    /**
     * Helper method to handle section updates with error handling
     */
    private function handleSectionUpdate(Request $request, $relationshipName, $successMessage)
    {
        try {
            $request->validate([
                'assessment_id' => 'required|exists:assessments,id',
            ]);

            $process = $this->getObjectiveProcess($request, $request->assessment_id);
            
            $data = $request->except(['assessment_id']);
            $data['assessments_process_id'] = $process->id;
            
            $section = $process->$relationshipName()->updateOrCreate(
                ['assessments_process_id' => $process->id],
                $data
            );

            $this->updateCompletionPercentage($process);

            return response()->json([
                'message' => $successMessage,
                'data' => $section,
            ]);
        } catch (ModelNotFoundException $e) {
            Log::error('Assessment process not found', [
                'assessment_id' => $request->assessment_id,
                'user_id' => $request->user()->id,
                'relationship' => $relationshipName,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'message' => 'Assessment not found or process not initialized. Please create a new assessment.',
            ], 404);
        } catch (\Illuminate\Http\Exceptions\HttpResponseException $e) {
            // Re-throw HTTP exceptions (like abort(403))
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error saving objective section', [
                'assessment_id' => $request->assessment_id,
                'user_id' => $request->user()->id,
                'relationship' => $relationshipName,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'message' => 'An error occurred while saving. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], 500);
        }
    }

    private function getObjectiveProcess(Request $request, $assessmentId)
    {
        $assessment = Assessment::where('user_id', $request->user()->id)
            ->with(['subjectiveProcess', 'objectiveProcess'])
            ->findOrFail($assessmentId);

        // Check if Subjective is 100% complete before allowing Objective updates
        if (!$assessment->subjectiveProcess || 
            $assessment->subjectiveProcess->completion_percentage < 100 ||
            $assessment->subjectiveProcess->status !== 'completed') {
            abort(403, 'Subjective assessment must be 100% complete before starting Objective assessment.');
        }

        $objectiveType = AssessmentType::where('name', 'Objective')->firstOrFail();
        
        return AssessmentProcess::where('assessments_id', $assessment->id)
            ->where('assessment_type_id', $objectiveType->id)
            ->firstOrFail();
    }

    public function observationsGeneralExamination(Request $request)
    {
        return $this->handleSectionUpdate($request, 'observationsGeneralExamination', 'Observations & general examination saved successfully');
    }

    public function palpation(Request $request)
    {
        return $this->handleSectionUpdate($request, 'palpation', 'Palpation saved successfully');
    }

    public function rangeOfMotion(Request $request)
    {
        return $this->handleSectionUpdate($request, 'rangeOfMotion', 'Range of motion saved successfully');
    }

    public function muscleStrength(Request $request)
    {
        return $this->handleSectionUpdate($request, 'muscleStrength', 'Muscle strength saved successfully');
    }

    public function neurologicalExamination(Request $request)
    {
        return $this->handleSectionUpdate($request, 'neurologicalExamination', 'Neurological examination saved successfully');
    }

    public function specialTests(Request $request)
    {
        return $this->handleSectionUpdate($request, 'specialTests', 'Special tests saved successfully');
    }

    public function functionalAssessment(Request $request)
    {
        return $this->handleSectionUpdate($request, 'functionalAssessment', 'Functional assessment saved successfully');
    }

    public function jointMobility(Request $request)
    {
        return $this->handleSectionUpdate($request, 'jointMobility', 'Joint mobility saved successfully');
    }

    public function outcomeMeasures(Request $request)
    {
        return $this->handleSectionUpdate($request, 'objectiveOutcomeMeasures', 'Outcome measures saved successfully');
    }


    private function updateCompletionPercentage($process)
    {
        try {
            DB::beginTransaction();

            // Load all relationships
            $process->load([
                'observationsGeneralExamination',
                'palpation',
                'rangeOfMotion',
                'muscleStrength',
                'neurologicalExamination',
                'specialTests',
                'functionalAssessment',
                'jointMobility',
                'objectiveOutcomeMeasures',
            ]);

            $sections = [
                $process->observationsGeneralExamination,
                $process->palpation,
                $process->rangeOfMotion,
                $process->muscleStrength,
                $process->neurologicalExamination,
                $process->specialTests,
                $process->functionalAssessment,
                $process->jointMobility,
                $process->objectiveOutcomeMeasures,
            ];

            $totalPercentage = 0;
            $count = 0;
            foreach ($sections as $section) {
                if ($section) {
                    $totalPercentage += $section->completion_percentage ?? 0;
                    $count++;
                }
            }

            // There are 9 sections total for objective assessment
            $average = $count > 0 ? $totalPercentage / 9 : 0;
            $finalPercentage = round($average, 2);

            // Update process completion percentage and status
            $updateData = ['completion_percentage' => $finalPercentage];
            
            if ($finalPercentage >= 100) {
                $updateData['status'] = 'completed';
                $updateData['completed_at'] = now();
            } elseif ($finalPercentage > 0) {
                $updateData['status'] = 'in_progress';
            }

            $process->update($updateData);

            // Update main assessment status
            $assessment = $process->assessment;
            if ($assessment) {
                $assessment->load(['subjectiveProcess', 'objectiveProcess']);
                
                $subjectiveComplete = $assessment->subjectiveProcess && 
                    $assessment->subjectiveProcess->completion_percentage >= 100;
                $objectiveComplete = $assessment->objectiveProcess && 
                    $assessment->objectiveProcess->completion_percentage >= 100;

                if ($subjectiveComplete && $objectiveComplete) {
                    $assessment->update(['status' => 'completed']);
                } elseif ($subjectiveComplete || $objectiveComplete || 
                          ($assessment->subjectiveProcess && $assessment->subjectiveProcess->completion_percentage > 0) ||
                          ($assessment->objectiveProcess && $assessment->objectiveProcess->completion_percentage > 0)) {
                    $assessment->update(['status' => 'in_progress']);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating completion percentage', [
                'process_id' => $process->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            // Don't throw - just log the error so the main request can still succeed
        }
    }
}



