<?php

namespace App\Http\Controllers\Api\Physio;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ObjectiveAssessmentController extends Controller
{
    /**
     * Unified endpoint to update any objective section
     * PUT /api/physio/assessments/objective/section
     * Body: { assessment_id, section_name, section_data }
     */
    public function updateSection(Request $request)
    {
        try {
            // Ensure user is authenticated
            $user = $request->user();
            if (!$user) {
                return response()->json([
                    'message' => 'Unauthenticated.',
                ], 401);
            }

            $request->validate([
                'assessment_id' => 'required|exists:assessments,id',
                'section_name' => 'required|string|in:observation_general,palpation,range_of_motion,muscle_strength,neurological_exam,special_tests,functional_assessment,joint_mobility,outcome_measures,red_flags',
                'section_data' => 'required|array',
            ]);

            $assessment = Assessment::where('user_id', $user->id)
                ->findOrFail($request->assessment_id);

            // Check if Subjective is 100% complete before allowing Objective updates
            if ($assessment->subjective_completion_percentage < 100) {
                return response()->json([
                    'message' => 'Subjective assessment must be 100% complete before starting Objective assessment.',
                    'subjective_percentage' => $assessment->subjective_completion_percentage ?? 0,
                ], 403);
            }

            $sectionName = $request->section_name;
            $sectionData = $request->section_data;

            // Normalize null values: empty string for strings, 0 for integers
            $sectionData = $this->normalizeNullValues($sectionData);

            // Map section name to column name
            $columnName = $this->getColumnNameForSection($sectionName);

            // Update the JSON column
            $assessment->$columnName = $sectionData;
            $assessment->status = 'in_progress';
            
            // Recalculate completion percentages
            $assessment->calculateCompletionPercentage();
            
            // Save the model with updated percentages
            $assessment->save();

            return response()->json([
                'message' => ucfirst(str_replace('_', ' ', $sectionName)) . ' saved successfully',
                'assessment' => $assessment->fresh(),
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $userId = $request->user() ? $request->user()->id : null;
            Log::error('Assessment not found', [
                'assessment_id' => $request->assessment_id ?? null,
                'user_id' => $userId,
            ]);
            return response()->json([
                'message' => 'Assessment not found',
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            $userId = $request->user() ? $request->user()->id : null;
            Log::error('Error saving objective section', [
                'assessment_id' => $request->assessment_id ?? null,
                'section_name' => $request->section_name ?? null,
                'user_id' => $userId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'message' => 'Failed to save section. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Map section name to column name
     */
    private function getColumnNameForSection(string $sectionName): string
    {
        $mapping = [
            'observation_general' => 'objective_observation_general',
            'palpation' => 'objective_palpation',
            'range_of_motion' => 'objective_range_of_motion',
            'muscle_strength' => 'objective_muscle_strength',
            'neurological_exam' => 'objective_neurological_exam',
            'special_tests' => 'objective_special_tests',
            'functional_assessment' => 'objective_functional_assessment',
            'joint_mobility' => 'objective_joint_mobility',
            'outcome_measures' => 'objective_outcome_measures',
            'red_flags' => 'objective_red_flags',
        ];

        return $mapping[$sectionName] ?? $sectionName;
    }

    /**
     * Normalize null values in section data
     * Converts null strings to empty strings and null integers to 0
     */
    private function normalizeNullValues(array $data): array
    {
        $normalized = [];
        
        // Fields that should be integers (0 if null)
        $integerFields = ['age', 'id', 'count', 'number', 'quantity', 'amount'];
        
        // Fields that should be floats/decimals (0 if null)
        $numericFields = ['height', 'weight', 'score', 'value', 'amount', 'price'];
        
        foreach ($data as $key => $value) {
            if ($value === null) {
                // Check if key suggests it's a numeric field
                if (in_array($key, $integerFields)) {
                    $normalized[$key] = 0;
                } elseif (in_array($key, $numericFields)) {
                    $normalized[$key] = 0.0;
                } else {
                    // Default to empty string for other fields
                    $normalized[$key] = '';
                }
            } elseif (is_array($value)) {
                // Recursively normalize nested arrays
                $normalized[$key] = $this->normalizeNullValues($value);
            } elseif (is_numeric($value)) {
                // Convert numeric strings to appropriate type
                if (in_array($key, $integerFields) || (is_numeric($value) && (int)$value == $value)) {
                    $normalized[$key] = (int)$value;
                } elseif (in_array($key, $numericFields)) {
                    $normalized[$key] = (float)$value;
                } else {
                    $normalized[$key] = $value;
                }
            } else {
                $normalized[$key] = $value;
            }
        }
        
        return $normalized;
    }
}
