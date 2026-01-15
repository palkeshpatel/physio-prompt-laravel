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

class SubjectiveAssessmentController extends Controller
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

            $process = $this->getSubjectiveProcess($request, $request->assessment_id);
            $data = $request->except(['assessment_id']);
            $data['assessments_process_id'] = $process->id;

            $section = $this->updateOrCreateSection($process, $relationshipName, $data);
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
        } catch (\Exception $e) {
            Log::error('Error saving section', [
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

    private function getSubjectiveProcess(Request $request, $assessmentId)
    {
        $assessment = Assessment::where('user_id', $request->user()->id)
            ->findOrFail($assessmentId);

        $subjectiveType = AssessmentType::where('name', 'Subjective')->firstOrFail();

        return AssessmentProcess::where('assessments_id', $assessment->id)
            ->where('assessment_type_id', $subjectiveType->id)
            ->firstOrFail();
    }

    /**
     * Common fast PUT method to update section fields
     * Creates entry if doesn't exist, updates if exists
     * Can update one field at a time or multiple fields
     */
    private function updateOrCreateSection($process, $relationshipName, $data)
    {
        try {
            if (!method_exists($process, $relationshipName)) {
                throw new \Exception("Relationship '{$relationshipName}' not found on AssessmentProcess model");
            }

            $relationship = $process->$relationshipName();

            // Use updateOrCreate - creates if doesn't exist, updates if exists
            $section = $relationship->updateOrCreate(
                ['assessments_process_id' => $process->id],
                $data
            );

            // Refresh to get updated data
            $section->refresh();

            return $section;
        } catch (\Exception $e) {
            Log::error('Error in updateOrCreateSection', [
                'process_id' => $process->id,
                'relationship' => $relationshipName,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Common PUT method for Basic Patient Details
     */
    public function basicPatientDetails(Request $request)
    {
        return $this->handleSectionUpdate($request, 'basicPatientDetails', 'Basic patient details saved successfully');
    }

    /**
     * Common PUT method for Chief Complaint
     */
    public function chiefComplaint(Request $request)
    {
        return $this->handleSectionUpdate($request, 'chiefComplaint', 'Chief complaint saved successfully');
    }

    /**
     * Common PUT method for Pain Characteristics
     */
    public function painCharacteristics(Request $request)
    {
        return $this->handleSectionUpdate($request, 'painCharacteristics', 'Pain characteristics saved successfully');
    }

    /**
     * Common PUT method for History of Present Condition
     */
    public function historyPresentCondition(Request $request)
    {
        return $this->handleSectionUpdate($request, 'historyPresentCondition', 'History of present condition saved successfully');
    }

    /**
     * Common PUT method for Functional Limitations
     */
    public function functionalLimitations(Request $request)
    {
        return $this->handleSectionUpdate($request, 'functionalLimitations', 'Functional limitations saved successfully');
    }

    /**
     * Common PUT method for Red Flag Screening
     */
    public function redFlagScreening(Request $request)
    {
        return $this->handleSectionUpdate($request, 'redFlagScreening', 'Red flag screening saved successfully');
    }

    /**
     * Common PUT method for Yellow Flags
     */
    public function yellowFlags(Request $request)
    {
        return $this->handleSectionUpdate($request, 'yellowFlags', 'Yellow flags saved successfully');
    }

    /**
     * Common PUT method for Medical History
     */
    public function medicalHistory(Request $request)
    {
        return $this->handleSectionUpdate($request, 'medicalHistory', 'Medical history saved successfully');
    }

    /**
     * Common PUT method for Lifestyle & Social History
     */
    public function lifestyleSocialHistory(Request $request)
    {
        return $this->handleSectionUpdate($request, 'lifestyleSocialHistory', 'Lifestyle & social history saved successfully');
    }

    /**
     * Common PUT method for ICE Assessment
     */
    public function iceAssessment(Request $request)
    {
        return $this->handleSectionUpdate($request, 'iceAssessment', 'ICE assessment saved successfully');
    }

    /**
     * Common PUT method for Region Specific
     */
    public function regionSpecific(Request $request)
    {
        return $this->handleSectionUpdate($request, 'regionSpecific', 'Region specific saved successfully');
    }

    /**
     * Common PUT method for Outcome Measures
     */
    public function outcomeMeasures(Request $request)
    {
        return $this->handleSectionUpdate($request, 'subjectiveOutcomeMeasures', 'Outcome measures saved successfully');
    }

    private function updateCompletionPercentage($process)
    {
        try {
            DB::beginTransaction();

            // Load all relationships
            $process->load([
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
                $process->basicPatientDetails,
                $process->chiefComplaint,
                $process->painCharacteristics,
                $process->historyPresentCondition,
                $process->functionalLimitations,
                $process->redFlagScreening,
                $process->yellowFlags,
                $process->medicalHistory,
                $process->lifestyleSocialHistory,
                $process->iceAssessment,
                $process->regionSpecific,
                $process->subjectiveOutcomeMeasures,
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
                } elseif (
                    $subjectiveComplete || $objectiveComplete ||
                    ($assessment->subjectiveProcess && $assessment->subjectiveProcess->completion_percentage > 0) ||
                    ($assessment->objectiveProcess && $assessment->objectiveProcess->completion_percentage > 0)
                ) {
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
